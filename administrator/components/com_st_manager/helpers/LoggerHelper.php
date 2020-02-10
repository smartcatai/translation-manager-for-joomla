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

class LoggerHelper
{
    private $isEventsEnabled;

    /**
     * LoggerHelper constructor.
     */
    public function __construct()
    {
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models', 'STMModel');
        $this->isEventsEnabled = JComponentHelper::getParams('com_st_manager')->get('enable_events_log');
    }

    /**
     * @param $type
     * @param $shortMessage
     * @param $message
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function addErrorRecord($type, $shortMessage, $message)
    {
        /** @var STMModelError $model */
        $model = JModelLegacy::getInstance('Error', 'STMModel', array('ignore_request' => true));
        $errorData = [
            'type' => $type,
            'short_message' => substr($shortMessage, 0, 254),
            'message' => $message
        ];

        return $model->save($errorData);
    }

    /**
     * @param $type
     * @param $message
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function event($type, $message)
    {
        if (!$this->isEventsEnabled) {
            return false;
        }

        /** @var STMModelEvent $model */
        $model = JModelLegacy::getInstance('Event', 'STMModel', array('ignore_request' => true));
        $eventData = [
            'type' => $type,
            'message' => $message
        ];

        return $model->save($eventData);
    }

    /**
     * @param $shortMessage
     * @param $message
     *
     *
     * @since 1.0.0
     */
    public function error($shortMessage, $message)
    {
        return $this->addErrorRecord('error', $shortMessage, $message);
    }

    /**
     * @param $shortMessage
     * @param $message
     *
     *
     * @since 1.0.0
     */
    public function warning($shortMessage, $message)
    {
        return $this->addErrorRecord('warning', $shortMessage, $message);
    }

    /**
     * @param $shortMessage
     * @param $message
     *
     *
     * @since 1.0.0
     */
    public function info($shortMessage, $message)
    {
        return $this->addErrorRecord('info', $shortMessage, $message);
    }

    /**
     * @param $shortMessage
     * @param $message
     *
     *
     * @since 1.0.0
     */
    public function debug($shortMessage, $message)
    {
        return $this->addErrorRecord('debug', $shortMessage, $message);
    }
}
