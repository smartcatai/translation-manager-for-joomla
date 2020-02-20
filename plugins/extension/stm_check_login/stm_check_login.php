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
    public function onExtensionBeforeSave($context, $table, $bool)
    {
        if ($context !== 'com_config.component' || $table->get('element') !== 'com_st_manager') {
            return true;
        }

        if (!is_file(JPATH_LIBRARIES . '/smartcat_api/autoload.php')) {
            throw new RuntimeException(JText::_('PLG_STM_LIBRARY_NOT_FOUND'));
        }

        require_once JPATH_LIBRARIES . '/smartcat_api/autoload.php';
        require_once JPATH_ADMINISTRATOR . '/components/com_st_manager/helpers/SCHelper.php';

        $params = json_decode($table->get('params'), true);

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

        $smartcat = new SmartCat($params['application_id'], $params['api_token'], $server);

        try {
            $smartcat->getAccountManager()->accountGetAccountInfo();
            $params['api_token'] = SCHelper::encryptToken($params['api_token']);
            $table->bind(['params' => $params]);
        } catch (\Throwable $e) {
            throw new RuntimeException(JText::_('PLG_STM_INCORRECT_CREDENTIALS'));
        }

        try {
            $this->cronHanler($params);
        } catch (\Throwable $e) {
            throw new RuntimeException($e->getMessage());
        }

        if (!$table->check() || !$table->store()) {
            throw new RuntimeException(JText::_('PLG_STM_CONFIG_SAVE_ERROR'));
        }

        return true;
    }

    private function cronHanler($params)
    {
        require_once JPATH_ADMINISTRATOR . '/components/com_st_manager/helpers/CronHelper.php';

        $cronState = CronHelper::process(
            $params['enable_external_cron'],
            JComponentHelper::getParams('com_st_manager')->get('enable_external_cron'),
            $params['application_id'],
            JComponentHelper::getParams('com_st_manager')->get('application_id'),
            JRoute::_(JURI::root() . 'index.php?option=com_st_manager&task=cron')
        );
    }

    private function logEvent($type, $message)
    {
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_st_manager' . DIRECTORY_SEPARATOR . 'models', 'STMModel');
        /** @var STMModelEvent $model */
        $model = JModelLegacy::getInstance('Event', 'STMModel', array('ignore_request' => false));

        $eventData = [
            'type' => $type,
            'message' => $message
        ];

        return $model->save($eventData);
    }
}
