<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\MVC\Controller\AdminController;

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Smartcat Translation Manager Admin Controller.
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMControllerProfiles extends AdminController
{
    protected $option = 'com_st_manager';

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   1.6
     */
    public function getModel($name = 'Profile', $prefix = 'STMModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    public function delete()
    {
        $cid = $this->input->get('cid', array(), 'array');

        /** @var STMModelProjects $model */
        $model = self::getModel('Projects');

        foreach ($cid as $id) {
            $projects = $model->getByProfile(intval($id));

            $pks = [];

            foreach ($projects as $project) {
                $pks[] = $project->id;
            }

            if (!empty($pks)) {
                /** @var STMModelProject $projectModel */
                $projectModel = self::getModel('Project');
                $projectModel->delete($pks);
            }
        }

        parent::delete();
    }
}
