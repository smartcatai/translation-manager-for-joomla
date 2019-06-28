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
 * Smartcat Translation Manager Da Controller.
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMControllerDashboard extends AdminController
{
    protected $option = 'com_st_manager';

    /** @var ContentModelArticle  */
    private $articleModel;
    /** @var STMModelProfile */
    private $profileModel;

    public function __construct($config = array())
    {
        parent::__construct($config);

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');

        $this->articleModel = parent::getModel('Article', 'ContentModel', array('ignore_request' => true));
        $this->profileModel = parent::getModel('Profile', 'STMModel', array('ignore_request' => true));
    }

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
    public function getModel($name = 'Projects', $prefix = 'STMModel', $config = array('ignore_request' => true))
    {
        if ($name === 'dashboard') {
            $name = 'projects';
        }
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    /**
     * @return bool
     * @throws Exception
     *
     * @since 1.0.0
     */
    public function add()
    {
        $redirect = $_SERVER['HTTP_REFERER'];
        $input = JFactory::getApplication()->input;
        $cid = $input->post->get('cid');
        $profileId = $input->get->get('profile_id');

        if (empty($cid)) {
            $this->setRedirect($redirect, JText::_('COM_STM_ARTICLES_NOT_SELECTED_ERROR'), 'error');
            return false;
        }

        if (!SCHelper::getInstance()->checkAccess(true)) {
            $this->setRedirect($redirect, JText::_('COM_STM_INCORRECT_CREDENTIALS'), 'error');
            return false;
        }

        $profile = $this->profileModel->getItem($profileId);

        if (!property_exists($profile, 'id') || !$profile->id) {
            $this->setRedirect($redirect, JText::_('COM_STM_PROFILE_DOES_NOT_EXISTS_ERROR'), 'error');
            return false;
        }

        $targetLangs = explode(',', $profile->target_lang);

        foreach ($cid as $id) {
            $article = $this->articleModel->getItem($id);

            if (!property_exists($article, 'id') || !$article->id) {
                continue;
            }

            foreach ($targetLangs as $targetLang) {
                /** @var STMModelProject $model */
                $model = parent::getModel('Project', 'STMModel', array('ignore_request' => true));

                $data = [
                    'entity_id' => $article->id,
                    'profile_id' => $profile->id,
                    'status' => STMModelProject::STATUS_WAITING,
                    'document_id' => null,
                    'task_id' => null,
                    'target_lang' => $targetLang,
                ];

                $model->save($data);
            }
        }

        $this->setRedirect($redirect, JText::_('COM_STM_PROJECTS_SUCCESSFULLY_CREATED'));
        return true;
    }
}
