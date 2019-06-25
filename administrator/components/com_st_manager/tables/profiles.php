<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class STMTableProfiles extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__st_manager_profiles', 'id', $db);
    }
}
