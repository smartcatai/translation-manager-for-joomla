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

class LanguageDictionary
{
    /**
     * @return array
     */
    private static function getLanguages()
    {
        return [
            ['name' => 'Afrikaans', 'code' => 'af-ZA', 'sc_code' => 'af'],
            ['name' => 'Arabic Unitag', 'code' => 'ar-AA', 'sc_code' => 'ar'],
            ['name' => 'Belarusian', 'code' => 'be-BY', 'sc_code' => 'be'],
            ['name' => 'Bulgarian', 'code' => 'bg-BG', 'sc_code' => 'bg'],
            ['name' => 'Bengali', 'code' => 'bn-BD', 'sc_code' => 'bn'],
            ['name' => 'Bosnian', 'code' => 'bs-BA', 'sc_code' => 'bs'],
            ['name' => 'Catalan', 'code' => 'ca-ES', 'sc_code' => 'ca'],
            ['name' => 'Czech', 'code' => 'cs-CZ', 'sc_code' => 'cs'],
            ['name' => 'Welsh', 'code' => 'cy-GB', 'sc_code' => 'cy'],
            ['name' => 'Danish', 'code' => 'da-DK', 'sc_code' => 'da'],
            ['name' => 'German AT', 'code' => 'de-AT', 'sc_code' => 'de-AT'],
            ['name' => 'German CH', 'code' => 'de-CH', 'sc_code' => 'de-CH'],
            ['name' => 'German DE', 'code' => 'de-DE', 'sc_code' => 'de-DE'],
            ['name' => 'Greek', 'code' => 'el-GR', 'sc_code' => 'el'],
            ['name' => 'English AU', 'code' => 'en-AU', 'sc_code' => 'en-AU'],
            ['name' => 'English CA', 'code' => 'en-CA', 'sc_code' => 'en-CA'],
            ['name' => 'English (United Kingdom)', 'code' => 'en-GB', 'sc_code' => 'en-GB'],
            ['name' => 'English US', 'code' => 'en-US', 'sc_code' => 'en-US'],
            ['name' => 'Esperanto', 'code' => 'eo-XX', 'sc_code' => 'eo'],
            ['name' => 'Spanish', 'code' => 'es-ES', 'sc_code' => 'es-ES'],
            ['name' => 'Estonian', 'code' => 'et-EE', 'sc_code' => 'et'],
            ['name' => 'Basque', 'code' => 'eu-ES', 'sc_code' => 'eu'],
            ['name' => 'Persian', 'code' => 'fa-IR', 'sc_code' => 'fa-IR'],
            ['name' => 'Finnish', 'code' => 'fi-FI', 'sc_code' => 'fi'],
            ['name' => 'French CA', 'code' => 'fr-CA', 'sc_code' => 'fr-CA'],
            ['name' => 'French', 'code' => 'fr-FR', 'sc_code' => 'fr-FR'],
            ['name' => 'Irish', 'code' => 'ga-IE', 'sc_code' => 'ga'],
            ['name' => 'Galician', 'code' => 'gl-ES', 'sc_code' => 'gl'],
            ['name' => 'Hebrew', 'code' => 'he-IL', 'sc_code' => 'he'],
            ['name' => 'Hindi', 'code' => 'hi-IN', 'sc_code' => 'hi'],
            ['name' => 'Croatian', 'code' => 'hr-HR', 'sc_code' => 'hr'],
            ['name' => 'Hungarian', 'code' => 'hu-HU', 'sc_code' => 'hu'],
            ['name' => 'Armenian', 'code' => 'hy-AM', 'sc_code' => 'hy'],
            ['name' => 'Bahasa Indonesia', 'code' => 'id-ID', 'sc_code' => 'id'],
            ['name' => 'Italian', 'code' => 'it-IT', 'sc_code' => 'it-IT'],
            ['name' => 'Japanese', 'code' => 'ja-JP', 'sc_code' => 'ja'],
            ['name' => 'Georgian', 'code' => 'ka-GE', 'sc_code' => 'ka'],
            ['name' => 'Kazakh', 'code' => 'kk-KZ', 'sc_code' => 'kk'],
            ['name' => 'Khmer', 'code' => 'km-KH', 'sc_code' => 'km'],
            ['name' => 'Korean', 'code' => 'ko-KR', 'sc_code' => 'ko'],
            ['name' => 'Lithuanian', 'code' => 'lt-LT', 'sc_code' => 'lt'],
            ['name' => 'Latvian', 'code' => 'lv-LV', 'sc_code' => 'lv'],
            ['name' => 'Macedonian', 'code' => 'mk-MK', 'sc_code' => 'mk'],
            ['name' => 'Malay', 'code' => 'ms-MY', 'sc_code' => 'ms-MY'],
            ['name' => 'Norwegian Bokmal', 'code' => 'nb-NO', 'sc_code' => 'nb'],
            ['name' => 'Flemish', 'code' => 'nl-BE', 'sc_code' => 'nl-BE'],
            ['name' => 'Dutch', 'code' => 'nl-NL', 'sc_code' => 'nl-NL'],
            ['name' => 'Norwegian Nynorsk', 'code' => 'nn-NO', 'sc_code' => 'nn'],
            ['name' => 'Polish', 'code' => 'pl-PL', 'sc_code' => 'pl'],
            ['name' => 'Portuguese Brazil', 'code' => 'pt-BR', 'sc_code' => 'pt-BR'],
            ['name' => 'Portuguese', 'code' => 'pt-PT', 'sc_code' => 'pt-PT'],
            ['name' => 'Romanian', 'code' => 'ro-RO', 'sc_code' => 'ro-RO'],
            ['name' => 'Russian', 'code' => 'ru-RU', 'sc_code' => 'ru'],
            ['name' => 'Sinhala', 'code' => 'si-LK', 'sc_code' => 'si'],
            ['name' => 'Slovak', 'code' => 'sk-SK', 'sc_code' => 'sk'],
            ['name' => 'Slovenian', 'code' => 'sl-SI', 'sc_code' => 'sl'],
            ['name' => 'Albanian', 'code' => 'sq-AL', 'sc_code' => 'sq'],
            ['name' => 'Serbian Cyrillic', 'code' => 'sr-RS', 'sc_code' => 'sr-Cyrl'],
            ['name' => 'Serbian Latin', 'code' => 'sr-YU', 'sc_code' => 'sr-Latn'],
            ['name' => 'Swedish', 'code' => 'sv-SE', 'sc_code' => 'sv'],
            ['name' => 'Swahili', 'code' => 'sw-KE', 'sc_code' => 'sw'],
            ['name' => 'Tamil', 'code' => 'ta-IN', 'sc_code' => 'ta'],
            ['name' => 'Thai', 'code' => 'th-TH', 'sc_code' => 'th'],
            ['name' => 'Turkmen', 'code' => 'tk-TM', 'sc_code' => 'tk'],
            ['name' => 'Turkish', 'code' => 'tr-TR', 'sc_code' => 'tr'],
            ['name' => 'Uyghur', 'code' => 'ug-CN', 'sc_code' => 'ug'],
            ['name' => 'Ukrainian', 'code' => 'uk-UA', 'sc_code' => 'uk'],
            ['name' => 'Vietnamese', 'code' => 'vi-VN', 'sc_code' => 'vi'],
            ['name' => 'Chinese Simplified', 'code' => 'zh-CN', 'sc_code' => 'zh-Hans'],
            ['name' => 'Chinese Traditional', 'code' => 'zh-TW', 'sc_code' => 'zh-Hant'],
        ];
    }

    /**
     * @param $code
     * @return string
     */
    public static function getNameByCode($code)
    {
        $index = array_search($code, array_column(self::getLanguages(), 'code'));

        if ($index === false) {
            return false;
        }

        return self::getLanguages()[$index]['name'];
    }

    /**
     * @param $code
     * @return string
     */
    public static function getScCodeByCode($code)
    {
        $index = array_search($code, array_column(self::getLanguages(), 'code'));

        if ($index === false) {
            return false;
        }

        return self::getLanguages()[$index]['sc_code'];
    }

    public static function codeConvertToJoomla($code)
    {
        $index = array_search($code, array_column(self::getLanguages(), 'sc_code'));

        if ($index === false) {
            return false;
        }

        return self::getLanguages()[$index]['code'];
    }
}
