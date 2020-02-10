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

class PdoStore extends \Symfony\Component\Lock\Store\PdoStore
{
    public function __construct()
    {
        $config = JFactory::getConfig();

        parent::__construct(
            "mysql:host={$config->get('host')};dbname={$config->get('db')}",
            [
                'db_username' => $config->get('user'),
                'db_password' => $config->get('password'),
                'db_table' => $config->get('dbprefix') . 'st_manager_lock_keys'
            ]);
    }
}
