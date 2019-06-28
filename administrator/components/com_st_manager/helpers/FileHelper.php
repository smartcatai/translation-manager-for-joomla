<?php

/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 * @since 1.0.0
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class FileHelper
{
    const FIELD_TAG = '<field id="%s">%s</field>';

    /**
     * current instance
     *
     * @var FileHelper
     */
    private static $instance = null;

    /**
     * return current instance
     *
     * @return FileHelper
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $fields
     *
     * @return bool|resource
     *
     * @since version
     */
    public function createFile($fields)
    {
        $content = $this->generateHtmlMarkup($fields);

        $file = fopen("php://temp", "r+");
        fputs($file, $content);
        rewind($file);

        return $file;
    }

    /**
     * @param array $fields
     * @return string
     */
    protected function generateHtmlMarkup($fields)
    {
        $data = [];

        foreach ($fields as $field => $value) {
            $data[] = sprintf(self::FIELD_TAG, $field, $value);
        }

        return '<html><head></head><body>' . implode('', $data) . '</body></html>';
    }

    /**
     * @param string $content
     * @return array
     */
    public function parseHtmlMarkup($content)
    {
        $fieldPattern = str_replace('/', '\/', sprintf(self::FIELD_TAG, '(.+?)', '(.*?)'));

        $matches = [];

        preg_match_all('/' . $fieldPattern . '/is', $content, $matches);

        $fields = [];

        foreach ($matches[1] as $i => $field) {
            $value = $matches[2][$i];

            $fields[$field] = $this->specialcharsDecode($value);
        }

        return $fields;
    }

    /**
     * Decode unicode special char
     * @param string $str
     * @return string
     */
    public function specialcharsDecode($str)
    {
        $str = html_entity_decode($str);
        $str = str_replace('&#39;', "'", $str);
        return $str;
    }
}
