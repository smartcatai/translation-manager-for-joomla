<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('password');

/**
 * Language field.
 *
 * @since  3.5
 */
class JFormFieldSTM_Token extends JFormFieldPassword
{
    protected $type = 'STM_Token';

    /**
     * @param SimpleXMLElement $element
     * @param mixed $value
     * @param null $group
     * @return bool
     */
    public function setup(SimpleXMLElement $element, $value, $group = null)
    {
        $value = SCHelper::decryptToken($value);

        return parent::setup($element, $value, $group);
    }
}