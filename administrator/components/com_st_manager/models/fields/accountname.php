<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

require_once JPATH_ADMINISTRATOR . '/components/com_st_manager/helpers/SCHelper.php';

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('note');

/**
 * Language field.
 *
 * @since  3.5
 */
class JFormFieldSTM_AccountName extends JFormFieldNote
{
    /**
     * The form field type.
     *
     * @var	   string
     * @since  3.5
     */
    protected $type = 'STM_AccountName';

    /**
     * @return string
     */
    protected function getLabel()
    {
        $accountInfo = null;

        try {
            $accountInfo = SCHelper::getInstance()->getAccountManager()->accountGetAccountInfo();
            $this->element['description'] = $accountInfo->getName();
        } catch (\Throwable $e) {
            return '</div>';
        }

        $title = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : 'Logged as: ');
        $heading = $this->element['heading'] ? (string) $this->element['heading'] : 'b';
        $description = (string) $this->element['description'];
        $class = !empty($this->class) ? ' class="' . $this->class . '"' : '';

        $html = array();

        $html[] = !empty($title) ? '<' . $heading . '>' . JText::_($title) . '</' . $heading . '>' : '';
        $html[] = !empty($description) ? JText::_($description) : '';

        return '</div><br/><div ' . $class . '>' . implode('', $html);
    }
}
