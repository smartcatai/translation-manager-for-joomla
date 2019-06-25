<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\MVC\Model\ListModel;

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Profiles Model
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMModelProfiles extends ListModel
{
    /**
     * @return JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = JFactory::getDBO();

        $query = $db
            ->getQuery(true)
            ->select('id, vendor_name, stages, source_lang, target_lang, name, project_guid, vendor')
            ->from('#__st_manager_profiles');

        return $query;
    }
}
