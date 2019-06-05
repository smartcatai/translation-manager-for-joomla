<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_st_manager'))
{
	throw new InvalidArgumentException(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

if (!is_file(JPATH_LIBRARIES . '/smartcat_api/autoload.php')) {
    throw new RuntimeException(Text::_('COM_STM_LIBRARY_NOT_FOUND'));
}

// Require the libraries
include_once JPATH_LIBRARIES . '/smartcat_api/autoload.php';
include_once JPATH_COMPONENT_ADMINISTRATOR . '/loader.php';

// Execute the task
$controller = BaseController::getInstance('STM');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
