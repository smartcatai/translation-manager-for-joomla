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

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Profiles view.
 *
 * @package   Smartcat Translation Manager
 * @since    1.0
 */
class STMViewEvents extends HtmlView
{
    protected $helper;
    protected $items;
    protected $pagination;
    protected $state;
    protected $sidebar = '';
    public $filterForm;

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
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful.
     *
     * @throws Exception
     *
     * @see     fetch()
     * @since   1.0
     */
    public function display($tpl = null)
    {
        // Kostyli what i like
        $input = JFactory::getApplication()->input->post;
        $this->getModel()->setState('list.limit', intval($input->get('list')[0]) ?? 20);
        $this->getModel()->setState('list.start', $input->getInt('limitstart') ?? 0);

        $this->items = $this->get('Items');
        $this->state         = $this->get('State');
        $this->total         = $this->get('Total');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Show the toolbar
        $this->toolbar();

        // Show the sidebar
        $this->helper->addSubmenus('events');
        $this->sidebar = JHtmlSidebar::render();

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
        JToolBarHelper::title(Text::_('COM_ST_MANAGER_EVENTS'), '');

        // Options button.
        if (Factory::getUser()->authorise('core.admin', 'com_st_manager')) {
            JToolBarHelper::preferences('com_st_manager');
            JToolbarHelper::deleteList(Text::_('COM_STM_DELETE_EVENTS_MSG'), 'events.delete');
        }
    }
}
