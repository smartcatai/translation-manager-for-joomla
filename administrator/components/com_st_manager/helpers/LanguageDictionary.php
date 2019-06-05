<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

class LanguageDictionary
{
    /**
     * @return array
     */
    private static function getLanguages()
    {
        return [
            ['name' => 'Abkhaz', 'code' => 'ab'],
            ['name' => 'Afar', 'code' => 'aa'],
            ['name' => 'Afrikaans', 'code' => 'af'],
            ['name' => 'Akan', 'code' => 'ak'],
            ['name' => 'Albanian', 'code' => 'sq'],
            ['name' => 'Amharic', 'code' => 'am'],
            ['name' => 'Arabic', 'code' => 'ar'],
            ['name' => 'Armenian (Eastern)', 'code' => 'hy-arevela'],
            ['name' => 'Armenian (Western)', 'code' => 'hy-arevmda'],
            ['name' => 'Armenian', 'code' => 'hy'],
            ['name' => 'Assamese', 'code' => 'as'],
            ['name' => 'Avar', 'code' => 'av'],
            ['name' => 'Azeri (Cyrillic)', 'code' => 'az-Cyrl'],
            ['name' => 'Azeri (Latin)', 'code' => 'az-Latn'],
            ['name' => 'Balochi (southern)', 'code' => 'bcc'],
            ['name' => 'Bambara', 'code' => 'bm'],
            ['name' => 'Bashkir', 'code' => 'ba'],
            ['name' => 'Basque', 'code' => 'eu'],
            ['name' => 'Belarusian', 'code' => 'be'],
            ['name' => 'Bengali', 'code' => 'bn'],
            ['name' => 'Bihari', 'code' => 'bh'],
            ['name' => 'Bosnian', 'code' => 'bs'],
            ['name' => 'Bulgarian', 'code' => 'bg'],
            ['name' => 'Burmese', 'code' => 'my'],
            ['name' => 'Catalan', 'code' => 'ca'],
            ['name' => 'Chechen', 'code' => 'ce'],
            ['name' => 'Chinese (Cantonese)', 'code' => 'yue'],
            ['name' => 'Chinese (Hong Kong SAR)', 'code' => 'zh-Hant-HK'],
            ['name' => 'Chinese (Macau SAR)', 'code' => 'zh-Hant-MO'],
            ['name' => 'Chinese (Malaysia)', 'code' => 'zh-Hans-MY'],
            ['name' => 'Chinese (PRC)', 'code' => 'zh-Hans'],
            ['name' => 'Chinese (Singapore)', 'code' => 'zh-Hans-SG'],
            ['name' => 'Chinese (Taiwan)', 'code' => 'zh-Hant-TW'],
            ['name' => 'Chuvash', 'code' => 'cv'],
            ['name' => 'Cornish', 'code' => 'kw'],
            ['name' => 'Croatian', 'code' => 'hr'],
            ['name' => 'Czech', 'code' => 'cs'],
            ['name' => 'Danish', 'code' => 'da'],
            ['name' => 'Dutch', 'code' => 'nl'],
            ['name' => 'English (Australia)', 'code' => 'en-AU'],
            ['name' => 'English (USA)', 'code' => 'en-US'],
            ['name' => 'English (United Kindom)', 'code' => 'en-GB'],
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Esperanto', 'code' => 'eo'],
            ['name' => 'Estonian', 'code' => 'et'],
            ['name' => 'Farsi', 'code' => 'fa'],
            ['name' => 'Filipino', 'code' => 'fil'],
            ['name' => 'Finnish', 'code' => 'fi'],
            ['name' => 'French (Canada)', 'code' => 'fr-CA'],
            ['name' => 'French (France)', 'code' => 'fr-FR'],
            ['name' => 'French', 'code' => 'fr'],
            ['name' => 'Fulah', 'code' => 'ff'],
            ['name' => 'Galician', 'code' => 'gl'],
            ['name' => 'Georgian', 'code' => 'ka'],
            ['name' => 'German (Austria)', 'code' => 'de-AT'],
            ['name' => 'German (Germany)', 'code' => 'de-DE'],
            ['name' => 'German (Switzerland)', 'code' => 'de-CH'],
            ['name' => 'German', 'code' => 'de'],
            ['name' => 'Greek', 'code' => 'el'],
            ['name' => 'Guarani', 'code' => 'gn'],
            ['name' => 'Gujarati', 'code' => 'gu'],
            ['name' => 'Haitian Creole', 'code' => 'ht'],
            ['name' => 'Hausa (Latin)', 'code' => 'ha-Latn'],
            ['name' => 'Hazaragi', 'code' => 'haz'],
            ['name' => 'Hebrew', 'code' => 'he'],
            ['name' => 'Hindi', 'code' => 'hi'],
            ['name' => 'Hmong', 'code' => 'hmn'],
            ['name' => 'Hungarian', 'code' => 'hu'],
            ['name' => 'Icelandic', 'code' => 'is'],
            ['name' => 'Indonesian', 'code' => 'id'],
            ['name' => 'Irish', 'code' => 'ga'],
            ['name' => 'Italian', 'code' => 'it'],
            ['name' => 'Japanese', 'code' => 'ja'],
            ['name' => 'Javanese', 'code' => 'jv'],
            ['name' => 'Kabyle', 'code' => 'kab'],
            ['name' => 'Kannada', 'code' => 'kn'],
            ['name' => 'Kazakh', 'code' => 'kk'],
            ['name' => 'Khmer', 'code' => 'km'],
            ['name' => 'Kinyarwanda', 'code' => 'rw'],
            ['name' => 'Komi', 'code' => 'kv'],
            ['name' => 'Korean', 'code' => 'ko'],
            ['name' => 'Kurdish (Kurmandji)', 'code' => 'kmr-Latn'],
            ['name' => 'Kurdish (Palewani)', 'code' => 'sdh-Arab'],
            ['name' => 'Kurdish (Sorani)', 'code' => 'ckb-Arab'],
            ['name' => 'Kyrgyz', 'code' => 'ky'],
            ['name' => 'Lao', 'code' => 'lo'],
            ['name' => 'Latin', 'code' => 'la'],
            ['name' => 'Latvian', 'code' => 'lv'],
            ['name' => 'Limburgish', 'code' => 'li'],
            ['name' => 'Lingala', 'code' => 'ln'],
            ['name' => 'Lithuanian', 'code' => 'lt'],
            ['name' => 'Luxembourgish', 'code' => 'lb'],
            ['name' => 'Macedonian', 'code' => 'mk'],
            ['name' => 'Malagasy', 'code' => 'mg'],
            ['name' => 'Malay (Malaysia)', 'code' => 'ms-MY'],
            ['name' => 'Malay (Singapore)', 'code' => 'ms-SG'],
            ['name' => 'Malay', 'code' => 'ms'],
            ['name' => 'Malayalam', 'code' => 'ml'],
            ['name' => 'Marathi', 'code' => 'mr'],
            ['name' => 'Mari', 'code' => 'chm'],
            ['name' => 'Mongolian', 'code' => 'mn'],
            ['name' => 'Nepali', 'code' => 'ne'],
            ['name' => 'Norwegian (Bokmål)', 'code' => 'nb'],
            ['name' => 'Norwegian (Nynorsk)', 'code' => 'nn'],
            ['name' => 'Norwegian', 'code' => 'no'],
            ['name' => 'Occitan', 'code' => 'oc'],
            ['name' => 'Oria', 'code' => 'or'],
            ['name' => 'Ossetian', 'code' => 'os'],
            ['name' => 'Pashto', 'code' => 'ps'],
            ['name' => 'Polish', 'code' => 'pl'],
            ['name' => 'Portuguese (Brazil)', 'code' => 'pt-BR'],
            ['name' => 'Portuguese (Portugal)', 'code' => 'pt-PT'],
            ['name' => 'Portuguese', 'code' => 'pt'],
            ['name' => 'Punjabi', 'code' => 'pa'],
            ['name' => 'Rohingya (Latin)', 'code' => 'rhg-Latn'],
            ['name' => 'Romanian (Moldova)', 'code' => 'ro-MD'],
            ['name' => 'Romanian (Romania)', 'code' => 'ro-RO'],
            ['name' => 'Romanian', 'code' => 'ro'],
            ['name' => 'Rundi', 'code' => 'rn'],
            ['name' => 'Russian', 'code' => 'ru'],
            ['name' => 'Sakha', 'code' => 'sah'],
            ['name' => 'Samoan', 'code' => 'sm'],
            ['name' => 'Sango', 'code' => 'sg'],
            ['name' => 'Sanskrit', 'code' => 'sa'],
            ['name' => 'Sardinian', 'code' => 'sc'],
            ['name' => 'Serbian (Cyrillic)', 'code' => 'sr-Cyrl'],
            ['name' => 'Serbian (Latin)', 'code' => 'sr-Latn'],
            ['name' => 'Shona', 'code' => 'sn'],
            ['name' => 'Sindhi', 'code' => 'sd'],
            ['name' => 'Sinhalese', 'code' => 'si'],
            ['name' => 'Slovak', 'code' => 'sk'],
            ['name' => 'Slovenian', 'code' => 'sl'],
            ['name' => 'Somali', 'code' => 'so'],
            ['name' => 'Spanish (Argentina)', 'code' => 'es-AR'],
            ['name' => 'Spanish (Mexico)', 'code' => 'es-MX'],
            ['name' => 'Spanish (Spain)', 'code' => 'es-ES'],
            ['name' => 'Spanish', 'code' => 'es'],
            ['name' => 'Sundanese', 'code' => 'su'],
            ['name' => 'Swahili', 'code' => 'sw'],
            ['name' => 'Swedish', 'code' => 'sv'],
            ['name' => 'Tagalog', 'code' => 'tl'],
            ['name' => 'Tajik', 'code' => 'tg'],
            ['name' => 'Tamil', 'code' => 'ta'],
            ['name' => 'Tatar', 'code' => 'tt'],
            ['name' => 'Telugu', 'code' => 'te'],
            ['name' => 'Thai', 'code' => 'th'],
            ['name' => 'Tibetan', 'code' => 'bo'],
            ['name' => 'Tigrinya', 'code' => 'ti'],
            ['name' => 'Tongan', 'code' => 'to'],
            ['name' => 'Tswana', 'code' => 'tn'],
            ['name' => 'Turkish', 'code' => 'tr'],
            ['name' => 'Turkmen', 'code' => 'tk'],
            ['name' => 'Udmurt', 'code' => 'udm'],
            ['name' => 'Uigur', 'code' => 'ug'],
            ['name' => 'Ukrainian', 'code' => 'uk'],
            ['name' => 'Undefined Language', 'code' => ''],
            ['name' => 'Urdu', 'code' => 'ur'],
            ['name' => 'Uzbek', 'code' => 'uz-Latn'],
            ['name' => 'Vietnamese', 'code' => 'vi'],
            ['name' => 'Wolof', 'code' => 'wo'],
            ['name' => 'Yiddish', 'code' => 'yi'],
            ['name' => 'Yoruba', 'code' => 'yo'],
            ['name' => 'Zulu', 'code' => 'zu'],
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

    public static function codeConvertToSmartcat($code)
    {
        $languageList = SCHelper::getInstance()->getDirectoriesManager()
                ->directoriesGet(['type' => 'language'])
                ->getItems();

        foreach ($languageList as $scLang) {
            if ($code === $scLang->getId()) {
                break;
            }
            if (strpos($code, $scLang->getId())===0) {
                $code = $scLang->getId();
                break;
            }
        }

        return $code;
    }

    public static function codeConvertToJoomla($code)
    {
        $languages = JLanguage::getKnownLanguages();
        $joomlaCodes = array_keys($languages);

        if (!in_array($code, $joomlaCodes)) {
            foreach ($joomlaCodes as $jCode) {
                if (strpos($jCode, $code)===0) {
                    return $jCode;
                }
            }
        }
 
        return $code;
    }
}
