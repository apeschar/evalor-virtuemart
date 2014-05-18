<?php
/**
 * @copyright (C) 2014 Albert Peschar
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addStyleDeclaration('.icon-48-webwinkelkeur { background-image: url(../media/com_evalor/images/logo48.png); }');
$doc->addStyleSheet('components/com_evalor/evalor.css');
 
jimport('joomla.application.component.controller');
 
$controller = JControllerLegacy::getInstance('eValor');
 
$jinput = JFactory::getApplication()->input;
$task = $jinput->get('task', "", 'STR' );
 
$controller->execute($task);
 
$controller->redirect();
