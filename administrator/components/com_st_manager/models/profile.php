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
 * Profile Model
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMModelProfile extends AdminModel
{
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
    public function getTable($type = 'Profiles', $prefix = 'STMTable', $config = array())
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
            'com_st_manager.profile',
            'profile',
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
     * @param null $pk
     * @return bool|JObject
     * @throws Exception
     */
    public function getItem($pk = null)
    {
        if (!$pk) {
            $pk = intval(JFactory::getApplication()->input->get('id'));
        }

        return parent::getItem($pk);
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
            'com_st_manager.edit.profile.data',
            array()
        );

        if (empty($data))
        {
            $data = $this->getItem();
            if ($data->id) {
                $data->target_lang = explode(',', $data->target_lang);
                $data->stages = explode(',', $data->stages);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save($data)
    {
        if (in_array($data['source_lang'], $data['target_lang'])) {
            $this->setError('Source Language and Target Language are identical');
            return false;
        }

        if (is_array($data['target_lang'])) {
            $data['target_lang'] = implode(',', $data['target_lang']);
        }

        if (is_array($data['stages'])) {
            $data['stages'] = implode(',', $data['stages']);
        }

        if (empty($data['name'])) {
            $data['name'] = 'Translate ' . $data['source_lang'] . ' -> ' . $data['target_lang'];
        }

        if (!empty($data['vendor']) && $data['vendor'] != 0) {
            try {
                $vendorsList = SCHelper::getInstance()->getDirectoriesManager()
                    ->directoriesGet(['type' => 'vendor'])
                    ->getItems();

                foreach ($vendorsList as $vendor) {
                    if ($vendor->getId() == $data['vendor']) {
                        $data['vendor_name'] = $vendor->getName();
                        break;
                    }
                }
            } catch (\Throwable $e) {
                $data['vendor_name'] = null;
            }
        }
        $data['component'] = 'com_content';

        return parent::save($data);
    }
}
