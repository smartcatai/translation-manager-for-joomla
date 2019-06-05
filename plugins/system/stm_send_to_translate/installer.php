<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

class PlgSystemSTM_Send_To_TranslateInstallerScript
{
    /**
     * @param $parent
     */
    public function install($parent)
    {
        $db  = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->update('#__extensions')
            ->set($db->quoteName('enabled') . ' = 1')
            ->where($db->quoteName('element') . ' = ' . $db->quote('stm_send_to_translate'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));

        $db->setQuery($query);
        $db->execute();
    }
}