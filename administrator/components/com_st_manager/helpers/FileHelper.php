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

class FileHelper
{

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
        $content = json_encode($fields);

        $file = fopen("php://temp", "r+");
        fputs($file, $content);
        rewind($file);

        return $file;
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
