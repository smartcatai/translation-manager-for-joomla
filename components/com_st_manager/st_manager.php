<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\Language\Text;

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!is_file(JPATH_LIBRARIES . '/smartcat_api/autoload.php')) {
    throw new RuntimeException(Text::_('COM_STM_LIBRARY_NOT_FOUND'));
}

// Require the libraries
include_once JPATH_LIBRARIES . '/smartcat_api/autoload.php';
include_once JPATH_COMPONENT_ADMINISTRATOR . '/loader.php';

// Get an instance of the controller prefixed by HelloWorld
$controller = JControllerLegacy::getInstance('STM');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();