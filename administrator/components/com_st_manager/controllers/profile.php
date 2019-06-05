<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\MVC\Controller\FormController;

defined('_JEXEC') or die;

/**
 * Smartcat Translation Manager Admin Controller.
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMControllerProfile extends FormController
{
    protected $option = 'com_st_manager';

    private $smartcat;

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->smartcat = SCHelper::getInstance();
    }

    public function add()
    {
        if (!$this->checkAccess()) {
            return false;
        }

        return parent::add();
    }

    public function edit($key = null, $urlVar = null)
    {
        if (!$this->checkAccess()) {
            return false;
        }

        return parent::edit($key, $urlVar);
    }

    /**
     * Check access to Smartcat and redirect on error
     *
     * @since 1.0.0
     */
    private function checkAccess()
    {
        if (!$this->smartcat->checkAccess()) {
            $this->setRedirect(
                JRoute::_('index.php?option=com_st_manager&view=profiles', false),
                JText::_('COM_STM_INCORRECT_CREDENTIALS'),
                'error'
            );

            $this->redirect();

            return false;
        }

        return true;
    }
}
