<?php
/**
 * @copyright (C) 2014 Albert Peschar
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

class PlgSystemEValor extends JPlugin {
    private $config;

    public function onBeforeCompileHead() {
        $app = JFactory::getApplication();
        if($app->isSite())
            $this->addScript();
    }

    public function onAfterInitialise() {
        $app = JFactory::getApplication();
        if(!$app->isSite())
            $this->sendInvites();
    }

    private function getConfig() {
        if(!isset($this->config)) {
            $db = JFactory::getDBO();
            $db->setQuery("SELECT `value` FROM `#__evalor_config` WHERE `id` = 1");
            $result = $db->loadResult();
            if($result) {
                $this->config = @json_decode($result, true);
            } else {
                $this->config = array();
            }
        }
        return $this->config;
    }

    private function addScript() {
        $config = $this->getConfig();

        if(empty($config['wwk_shop_id']))
            return;

        if(empty($config['sidebar'])
           && empty($config['tooltip'])
           && empty($config['javascript'])
        )
            return;

        $settings = array(
            '_webwinkelkeur_id' => (int) $config['wwk_shop_id'],
            '_webwinkelkeur_sidebar' => !empty($config['sidebar']),
            '_webwinkelkeur_tooltip' => !empty($config['tooltip']),
        );

        if($sidebar_position = @$config['sidebar_position'])
            $settings['_webwinkelkeur_sidebar_position'] = $sidebar_position;

        $sidebar_top = @$config['sidebar_top'];
        if(is_string($sidebar_top) && $sidebar_top != '')
            $settings['_webwinkelkeur_sidebar_top'] = $sidebar_top;

        ob_start();
        require dirname(__FILE__) . '/sidebar.php';
        $script = ob_get_clean();

        JFactory::getDocument()->addCustomTag($script);
    }

    private function sendInvites() {
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $config = $this->getConfig();

        // invites enabled?
        if(empty($config['invite'])
           || empty($config['wwk_shop_id'])
           || empty($config['wwk_api_key'])
        )
            return;

        $delay = (int) @$config['invite_delay'];
        $noremail = @$config['invite'] == 2;

        // find orders
        $db->setQuery("
            SELECT
                vo.virtuemart_order_id,
                vo.order_number,
                vou.email
            FROM `#__virtuemart_orders` vo
            INNER JOIN `#__virtuemart_order_userinfos` vou ON
                vou.virtuemart_order_id = vo.virtuemart_order_id
            LEFT JOIN `#__evalor_virtuemart_order` wvo ON
                wvo.virtuemart_order_id = vo.virtuemart_order_id
            WHERE
                (
                    wvo.virtuemart_order_id IS NULL
                    OR (
                        wvo.success = 0
                        AND wvo.tries <= 5
                        AND wvo.time < " . (time() - 1800) . "
                    )
                )
                AND vou.email LIKE '%@%'
                AND vo.order_status = 'S'
        ");
        $orders = $db->loadAssocList();
        if(!$orders)
            return;

        // process
        require_once dirname(__FILE__) . '/api.php';
        foreach($orders as $order) {
            $api = new WebwinkelKeurAPI($config['wwk_shop_id'], $config['wwk_api_key']);
            $error = null;
            $url = null;
            try {
                $api->invite($order['order_number'], $order['email'], $delay, $noremail);
            } catch(WebwinkelKeurAPIAlreadySentError $e) {
            } catch(WebwinkelKeurAPIError $e) {
                $error = $e->getMessage();
                $url = $e->getURL();
            }

            $now = time();

            $db->setQuery("
                INSERT INTO `#__evalor_virtuemart_order` SET
                    `virtuemart_order_id` = " . (int) $order['virtuemart_order_id'] . ",
                    `success` = " . ($error ? '0' : '1') . ",
                    `tries` = 1,
                    `time` = " . $now . "
                ON DUPLICATE KEY UPDATE
                    `success` = IF(`success` = 1, 1, " . ($error ? '0' : '1') . "),
                    `tries` = `tries` + 1,
                    `time` = " . $now . "
            ");
            $db->query();

            if($error) {
                $db->setQuery("
                    INSERT INTO `#__evalor_invite_error` SET
                        `url` = " . $db->quote($url) . ",
                        `response` = " . $db->quote($error) . ",
                        `time` = " . $now . ",
                        `reported` = 0
                ");
                $db->query();
                $app->enqueueMessage("Ha habido un error al enviar la invitación de eValor. -- $error", 'error');
            }
        }
    }
}
