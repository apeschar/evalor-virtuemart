<?php

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
