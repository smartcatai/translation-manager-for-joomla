<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\MVC\Controller\BaseController;

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Smartcat Translation Manager Controller.
 *
 * @package  Smartcat Translation Manager
 * @since    1.0
 */
class STMController extends BaseController
{
    /**
     * @var		string	The default view.
     * @since   1.6
     */
    protected $default_view = 'dashboard';
  
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
    public function getModel($name = '', $prefix = 'STMModel', $config = array('ignore_request' => true))
    {
        if ($name === 'dashboard') {
            $name = 'projects';
        }
        return parent::getModel($name, $prefix, $config);
    }
}
