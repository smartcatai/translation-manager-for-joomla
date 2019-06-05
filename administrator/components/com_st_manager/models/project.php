<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\MVC\Model\AdminModel;

defined('_JEXEC') or die;

/**
 * Project Model
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMModelProject extends AdminModel
{
    const STATUS_WAITING = 'waiting';
    const STATUS_CREATED = "created";
    const STATUS_IN_PROGRESS = "inprogress";
    const STATUS_COMPLETED = "completed";
    const STATUS_ON_EXPORT = "onexport";
    const STATUS_CANCELED = "canceled";
    const STATUS_FAILED = "failed";
    const STATUS_DOWNLOADED = "downloaded";

    /**
     * @param $status
     * @return string
     */
    public static function getStatusText($status)
    {
        return self::getStatuses()[$status];
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_WAITING => JText::_('COM_STM_PROJECT_STATUS_WAITING'),
            self::STATUS_CREATED => JText::_('COM_STM_PROJECT_STATUS_CREATED'),
            self::STATUS_IN_PROGRESS => JText::_('COM_STM_PROJECT_STATUS_IN_PROGRESS'),
            self::STATUS_COMPLETED => JText::_('COM_STM_PROJECT_STATUS_COMPLETED'),
            self::STATUS_ON_EXPORT => JText::_('COM_STM_PROJECT_STATUS_ON_EXPORT'),
            self::STATUS_DOWNLOADED => JText::_('COM_STM_PROJECT_STATUS_DOWNLOADED'),
            self::STATUS_CANCELED => JText::_('COM_STM_PROJECT_STATUS_CANCELED'),
            self::STATUS_FAILED => JText::_('COM_STM_PROJECT_STATUS_FAILED'),
        ];
    }
    
    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Projects', $prefix = 'STMTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm(
            'com_st_manager.project',
            'project',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if (empty($form)) {
                return false;
            }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @throws Exception
     *
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState(
            'com_st_manager.edit.project.data',
            array()
        );

        if (empty($data)) {
                $data = $this->getItem();
        }
        return $data;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save($data)
    {
        $data['status'] = strtolower($data['status']);

        return parent::save($data);
    }
}
