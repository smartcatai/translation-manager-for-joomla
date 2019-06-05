<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

/**
 * Foo view.
 *
 * @package  [PACKAGE_NAME]
 * @since    1.0
 */
class STMViewDashboard extends HtmlView
{
    protected $helper;

    /**
     * The sidebar to show
     *
     * @var    string
     * @since  1.0
     */
    protected $sidebar = '';

    protected $items;
    protected $pagination;
    protected $server;

    /**
     * STMViewProfiles constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->helper = new STMHelper();

        parent::__construct($config);
    }

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a JError object.
     *
     * @see     fetch()
     * @since   1.0
     */
    public function display($tpl = null)
    {
        $this->server = SCHelper::getInstance()->getServer();

        $items = $this->get('Items');
        $pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }
        // Show the toolbar
        $this->toolbar();

        $this->helper->addSubmenus('dashboard');
        $this->sidebar = JHtmlSidebar::render();

        $this->items = $items;
        $this->pagination = $pagination;

        // Display it all
        return parent::display($tpl);
    }

    /**
     * Displays a toolbar for a specific page.
     *
     * @return  void.
     *
     * @since   1.0
     */
    private function toolbar()
    {
        JToolBarHelper::title(Text::_('COM_ST_MANAGER_DASHBOARD'), '');

        // Options button.
        if (Factory::getUser()->authorise('core.admin', 'com_st_manager')) {
            //JToolbarHelper::custom('cron.manual', 'checkin', 'checkin', 'Manual Cron Run', false);
            JToolBarHelper::preferences('com_st_manager');
        }
    }
}
