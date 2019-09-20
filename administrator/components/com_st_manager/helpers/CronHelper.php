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

use Http\Client\Common\FlexibleHttpClient;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Socket\Client as SocketHttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;

/**
 * Class CronHelper
 *
 * @package SmartCAT\WP\Helpers
 */
class CronHelper implements PluginInterface {
    /**
     * @var string
     */
    private $host = 'smartcat-cron-app.azurewebsites.net';
    /**
     * @var FlexibleHttpClient
     */
    private $http_client;
    /**
     * @var GuzzleMessageFactory
     */
    private $message_factory;

    /**
     * CronHelper constructor.
     */
    public function __construct()
    {
        $this->message_factory = new GuzzleMessageFactory();
        $options               = [
            'remote_socket' => "tcp://{$this->host}:443",
            'ssl'           => true,
        ];

        $socket_client = new SocketHttpClient($this->message_factory, $options);
        $client = new PluginClient($socket_client, [new ErrorPlugin(), new ContentLengthPlugin()]);

        $this->http_client = new FlexibleHttpClient($client);
    }

    /**
     * @param $account
     * @param $token
     * @param $url
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function subscribe( $account, $token, $url )
    {
        $data = array(
            'account' => $account,
            'token'   => $token,
            'url'     => $url,
        );

        $request  = $this->createRequest("https://{$this->host}/api/subscription", $data);
        $response = $this->http_client->sendRequest($request);

        return $response;
    }

    /**
     * @param $account
     * @param $url
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function unsubscribe($account, $url)
    {
        $data = array(
            'account' => $account,
            'url'     => $url,
        );

        $request  = $this->createRequest("https://{$this->host}/api/subscription", $data, 'DELETE');
        $response = $this->http_client->sendRequest($request);

        return $response;
    }

    /**
     * @param $url
     * @param $data
     * @param string $method
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    private function createRequest($url, $data, $method = 'POST')
    {
        $headers = array_merge(array('Accept' => array('application/json')));
        $body = json_encode($data);

        return $this->message_factory->createRequest($method, $url, $headers, $body);
    }
}
