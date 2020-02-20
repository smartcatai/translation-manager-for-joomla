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

class Pkg_St_ManagerInstallerScript
{
    const MIN_PHP_COM_VERSION = '7.0.0';

    /**
     * @param $type
     * @param $parent
     *
     *
     * @throws Exception
     * @since 1.0.0
     */
    public function preflight($type, $parent)
    {
        if (version_compare(PHP_VERSION, self::MIN_PHP_COM_VERSION) < 0) {
            JFactory::getApplication()->enqueueMessage(
                'This PHP version is unsupported by component. Minimal version is ' . self::MIN_PHP_COM_VERSION,
                'error'
            );

            return false;
        }

        if (!extension_loaded('json')) {
            JFactory::getApplication()->enqueueMessage('PHP extension JSON is not loaded', 'error');

            return false;
        }

        if (!extension_loaded('openssl')) {
            JFactory::getApplication()->enqueueMessage('PHP extension OpenSSL is not loaded', 'error');

            return false;
        }

        return true;
    }

    public function postflight($type, $parent)
    {
        if ($type !== 'install') {
            return;
        }

        JHtml::_('jquery.framework');
        JHTML::_('bootstrap.framework');
        JHTML::_('behavior.modal');

        $modal_params = array();
        $modal_params['title'] = "Information for contact";
        $modal_params['backdrop'] = "true";
        $modal_params['height'] = "500px";
        $modal_params['width'] = "400px";

        $body = file_get_contents(__DIR__ . '/modal.html');

        echo JHTML::_('bootstrap.renderModal', 'hbsptModal', $modal_params, $body);
        echo '<script>setTimeout(function(){jQuery("#hbsptModal").modal("show");}, 500)</script>';
        echo '<style>#hbsptModal .modal-body{ max-height: 600px; box-sizing: border-box; padding: 10px 20px;}</style>';
    }
}
