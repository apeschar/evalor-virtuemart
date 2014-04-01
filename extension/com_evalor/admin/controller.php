<?php

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');
 
class eValorController extends JController {
    private $wwk_fields = array(
        'wwk_shop_id',
        'wwk_api_key',
        'sidebar',
        'sidebar_position',
        'sidebar_top',
        'invite',
        'invite_delay',
        'tooltip',
        'javascript',
    );
    
    function display($cachable = false, $urlparams = false) {
        // set default view if not set
        $input = JFactory::getApplication()->input;
        $input->set('view', $input->getCmd('view', 'Config'));

        // add toolbar
        JToolBarHelper::title('eValor', 'webwinkelkeur');
        JToolBarHelper::apply();
        JToolBarHelper::save();
        JToolBarHelper::cancel('cancel', 'JTOOLBAR_CLOSE');

        // set document title
        $doc = JFactory::getDocument();
        $doc->setTitle('eValor');

        // call parent behavior
        parent::display($cachable);
    }

    private function doApply() {
        $config = $this->get('Config');

        foreach($this->wwk_fields as $field_name) {
            $value = @$_POST['webwinkelkeur_' . $field_name];
            if(is_string($value)) {
                $value = str_replace('\\', '', $value);
                $value = trim($value);
                $config[$field_name] = $value;
            } elseif(!isset($config[$field_name])) {
                $config[$field_name] = '';
            }
        }

        if(empty($config['wwk_shop_id']))
            $errors[] = 'Uw webwinkel ID is verplicht.';
        elseif(!ctype_digit($config['wwk_shop_id']))
            $errors[] = 'Uw webwinkel ID kan alleen cijfers bevatten.';

        if($config['invite'] && !$config['wwk_api_key'])
            $errors[] = 'Om uitnodigingen te versturen is uw API key verplicht.';

        $application = JFactory::getApplication();

        foreach($errors as $error)
            $application->enqueueMessage($error, 'error');

        if(!$errors) {
            if($this->getModel('config')->setConfig($config))
                $application->enqueueMessage('Uw wijzigingen zijn opgeslagen.');
            else
                $application->enqueueMessage('Uw wijzigingen konden niet worden opgeslagen.', 'error');
        }

        return !$errors;
    }

    function apply() {
        $this->doApply();
        $this->display();
    }

    function save() {
        if($this->doApply()) {
            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php'));
        } else {
            $this->display();
        }
    }

    function cancel() {
        $app = JFactory::getApplication();
        $app->redirect(JRoute::_('index.php'));
    }
}
