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

use \SmartCat\Client\SmartCat;

/**
 * An example custom profile plugin.
 *
 * @since  1.6
 */
class PlgExtensionStm_Check_Login extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * This event is triggered before form has been saved
     *
     * @param string $context Component which trigger event
     * @param Joomla\CMS\Table\Extension $table
     * @param boolean $bool
     * @return  boolean
     *
     * @since   1.6
     */
    public function onExtensionBeforeSave($context, &$table, $bool)
    {
        if ($context !== 'com_config.component' || $table->element !== 'com_st_manager') {
            return true;
        }

        if (!is_file(JPATH_LIBRARIES . '/smartcat_api/autoload.php')) {
            throw new RuntimeException(JText::_('PLG_STM_LIBRARY_NOT_FOUND'));
        }

        require_once JPATH_LIBRARIES . '/smartcat_api/autoload.php';
        require_once JPATH_ADMINISTRATOR . '/components/com_st_manager/helpers/SCHelper.php';

        $params = json_decode($table->params, true);

        switch ($params['server']) {
            case 'europe':
                $server = SmartCat::SC_EUROPE;
                break;
            case 'usa':
                $server = SmartCat::SC_USA;
                break;
            case 'asia':
                $server = SmartCat::SC_ASIA;
                break;
            default:
                $server = SmartCat::SC_EUROPE;
                break;
        }

        $sc = new SmartCat($params['application_id'], $params['api_token'], $server);

        try {
            JFactory::getApplication()->setUserState('com_st_manager.smartcat.access', false);
            $sc->getAccountManager()->accountGetAccountInfo();
            $params['api_token'] = SCHelper::encryptToken($params['api_token']);
            $table->params = json_encode($params);
        } catch (\Throwable $e) {
            throw new RuntimeException(JText::_('PLG_STM_INCORRECT_CREDENTIALS'));
        }

        return true;
    }
}
