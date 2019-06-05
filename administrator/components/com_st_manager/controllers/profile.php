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

    /**
     * @return bool
     */
    public function add()
    {
        $smartcat = SCHelper::getInstance();

        if (!$smartcat->checkAccess()) {
            $this->setRedirect(
                JRoute::_('index.php?option=com_st_manager&view=profiles', false),
                JText::_('COM_STM_INCORRECT_CREDENTIALS'),
                'error'
            );

            $this->redirect();

            return false;
        }

        return parent::add();
    }
}
