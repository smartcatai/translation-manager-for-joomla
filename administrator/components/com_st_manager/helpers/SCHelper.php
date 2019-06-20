<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use SmartCat\Client\SmartCat;
use SmartCat\Client\Model\CallbackPropertyModel;

require_once JPATH_LIBRARIES . '/smartcat_api/autoload.php';

class SCHelper extends SmartCat
{
    const CIPHER = "AES-128-CBC";

    /** @var SCHelper|null */
    private static $instance = null;

    protected $server = SmartCat::SC_EUROPE;

    /**
     * SCHelper constructor.
     */
    public function __construct()
    {
        $params = JComponentHelper::getParams('com_st_manager');
        $applicationId = $params->get('application_id');
        $apiToken = self::decryptToken($params->get('api_token'));

        switch ($params->get('server')) {
            case 'europe':
                $this->server = SmartCat::SC_EUROPE;
                break;
            case 'usa':
                $this->server = SmartCat::SC_USA;
                break;
            case 'asia':
                $this->server = SmartCat::SC_ASIA;
                break;
            default:
                $this->server = SmartCat::SC_EUROPE;
                break;
        }

        parent::__construct($applicationId, $apiToken, $this->server);
    }

    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return bool
     */
    public function checkAccess()
    {
        try {
            $this->getAccountManager()->accountGetAccountInfo();
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }

    /**
     * @return SCHelper
     */
    public static function getInstance()
    {
        if (is_null(self::$instance) || !self::$instance->checkAccess()) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $token_hash
     * @return string
     */
    public static function decryptToken($token_hash)
    {
        $key = JFactory::getConfig()->get('secret');
        try {
            $c = base64_decode($token_hash);
            $iv_len = openssl_cipher_iv_length(self::CIPHER);
            $cipher_text_raw = substr($c, $iv_len + 32);
            $decrypted = openssl_decrypt(
                $cipher_text_raw,
                self::CIPHER,
                $key,
                OPENSSL_RAW_DATA,
                substr($c, 0, $iv_len)
            );
        } catch (\Throwable $e) {
            return $token_hash;
        }
        return $decrypted;
    }
    /**
     * @param $token
     * @return string
     */
    public static function encryptToken($token)
    {
        $key = JFactory::getConfig()->get('secret');
        try {
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER));
            $cipher_text_raw = openssl_encrypt($token, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
            $hmac = hash_hmac('sha256', $cipher_text_raw, $key, true);
        } catch (\Throwable $e) {
            return $token;
        }
        return base64_encode($iv . $hmac . $cipher_text_raw);
    }
}
