<?php
/**
 * @copyright (C) 2014 Albert Peschar
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class eValorViewConfig extends JViewLegacy {
    function display($tpl = null) {
        $this->config = $this->get('Config');
        $this->virtuemart = $this->get('VirtueMart');

        if(!$this->virtuemart) {
            JFactory::getApplication()->enqueueMessage('Instale y active VirtuaMart para poder enviar invitaciones.', 'notice');
        }

        parent::display($tpl);
    }
}
