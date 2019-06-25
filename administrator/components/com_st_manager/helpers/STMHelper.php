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

/**
 * Smartcat Translation Manager helper.
 *
 * @package     Smartcat Translation Manager
 * @since       1.0
 */
class STMHelper
{
	/**
	 * Render submenu.
	 *
	 * @param   string  $vName  The name of the current view.
	 *
	 * @return  void.
	 *
	 * @since   1.0
	 */
	public function addSubmenus($vName)
	{
        JHtmlSidebar::addEntry(Text::_('COM_ST_MANAGER_DASHBOARD'), 'index.php?option=com_st_manager&view=dashboard', $vName == 'dashboard');
	    JHtmlSidebar::addEntry(Text::_('COM_ST_MANAGER_PROFILES'), 'index.php?option=com_st_manager&view=profiles', $vName == 'profiles');
	}
}
