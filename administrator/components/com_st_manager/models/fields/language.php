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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/LanguageDictionary.php';

JFormHelper::loadFieldClass('list');

/**
 * Language field.
 *
 * @since  3.5
 */
class JFormFieldSTM_Language extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var	   string
     * @since  3.5
     */
    protected $type = 'STM_Language';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   1.6
     */
    public function getOptions()
    {
        $languages = [];

        try {
            $languageList = SCHelper::getInstance()->getDirectoriesManager()
                ->directoriesGet(['type' => 'language'])
                ->getItems();

            $languages = JLanguage::getKnownLanguages();
            $joomlaCodes = array_keys($languages);

            if (!empty($languageList) && !empty($joomlaCodes)) {
                foreach ($joomlaCodes as $language) {
                    $name = LanguageDictionary::getNameByCode($language);

                    if ($name) {
                        $languages = array_merge(
                            $languages,
                            [$language => $name . ' - ' . $language]
                        );
                    }
                }
            }

            asort($languages);
        } catch (\Throwable $e) {
        }

        return array_merge(parent::getOptions(), $languages);
    }
}