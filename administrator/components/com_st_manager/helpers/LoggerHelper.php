<?php
/**
 * @package    src
 *
 * @author     medic84 <medic84@example.com>
 * @copyright  (c) 2019 medic84. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://medic84.example.com
 */

class LoggerHelper
{
    public function __construct()
    {
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models', 'STMModel');
    }

    private function addErrorRecord($type, $shortMessage, $message) {
        /** @var STMModelError $model */
        $model = JModelLegacy::getInstance('Error', 'STMModel', array('ignore_request' => true));
        $errorData = [
            'type' => $type,
            'short_message' => substr($shortMessage, 0, 254),
            'message' => $message
        ];

        $model->save($errorData);
    }

    public function event($type, $message) {
        /** @var STMModelEvent $model */
        $model = JModelLegacy::getInstance('Event', 'STMModel', array('ignore_request' => true));
        $eventData = [
            'type' => $type,
            'message' => $message
        ];

        $model->save($eventData);
    }

    public function error($shortMessage, $message) {
        $this->addErrorRecord('error', $shortMessage, $message);
    }

    public function warning($shortMessage, $message) {
        $this->addErrorRecord('warning', $shortMessage, $message);
    }

    public function info($shortMessage, $message) {
        $this->addErrorRecord('info', $shortMessage, $message);
    }

    public function debug($shortMessage, $message) {
        $this->addErrorRecord('debug', $shortMessage, $message);
    }
}
