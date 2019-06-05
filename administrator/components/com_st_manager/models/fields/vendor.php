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

JFormHelper::loadFieldClass('list');

/**
 * Vendor field.
 *
 * @since  3.5
 */
class JFormFieldSTM_Vendor extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var	   string
     * @since  3.5
     */
    protected $type = 'STM_Vendor';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   1.6
     */
    public function getOptions()
    {
        $vendors = ['0' => 'Translate internally'];

        try {
            $vendorsList = SCHelper::getInstance()->getDirectoriesManager()
                ->directoriesGet(['type' => 'vendor'])
                ->getItems();

            foreach ($vendorsList as $vendor) {
                $vendors = array_merge($vendors, [$vendor->getId() => $vendor->getName()]);
            }
        } catch (\Throwable $e) {
        }

        return array_merge(parent::getOptions(), $vendors);
    }
}