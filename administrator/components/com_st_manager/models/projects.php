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

defined('_JEXEC') or die;

/**
 * Projects Model
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMModelProjects extends ListModel
{
    /**
     * @return JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = JFactory::getDBO();

        $query = $db
            ->getQuery(true)
            ->select('a.*, c.title as entity_name, b.name as profile_name, b.source_lang')
            ->from($db->quoteName('#__st_manager_projects', 'a'))
            ->join('LEFT', $db->quoteName('#__st_manager_profiles', 'b') . ' ON (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('a.profile_id') . ')')
            ->join('LEFT', $db->quoteName('#__content', 'c') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.entity_id') . ')')
            ->order('a.id ASC');
            // #__content

        return $query;
    }

    /**
     * @param $data
     * @param array $conditionData
     * @return mixed
     */
    public function bulkUpdate($data, $conditionData = [])
    {
        $db = JFactory::getDbo();
        // Fields to update.
        $fields = [];
        foreach ($data as $field => $value) {
            $fields[] = $db->quoteName($field) . ' = ' . $db->quote($value);
        }

        // Conditions for which records should be updated.
        $conditions = [];

        foreach ($conditionData as $field => $condition) {
            if (is_scalar($condition)) {
                $condition = ' = ' . $db->quote('1'.$condition);
            }
            if (is_array($condition)) {
                $values = '';
                foreach ($condition as $value) {
                    $values .= (is_int($value) ? $value : $db->quote($value)) . ',';
                }
                if (count($condition) == 1) {
                    $condition = ' = ' . rtrim($values, ',') . '';
                } else {
                    $condition = ' IN(' . rtrim($values, ',') . ')';
                }
            }

            $conditions[] = $db->quoteName($field) . ' ' . $condition;
        }


        $query = $db->getQuery(true)
            ->update($db->quoteName('#__st_manager_projects'))
            ->set($fields)
            ->where($conditions);

        $db->setQuery($query);

        return $db->execute();
    }

    /**
     * @param string|array $status
     * @return mixed
     */
    public function getByStatus($status)
    {
        $db = JFactory::getDbo();

        if (is_array($status)) {
            foreach ($status as &$stat) {
                $stat = $db->quote($stat);
            }

            $status = implode(" OR " . $db->quoteName('status') . " = ", $status);
        } else {
            $status = $db->quote($status);
        }

        $query = $this->getListQuery()
            ->where($db->quoteName('status') . " = " . $status)
            ->order('id DESC')
            ->setLimit(100);

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}
