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
 * Dashboard view.
 *
 * @package  Smartcat Translation Manager
 * @since    1.0.0
 */
class STMViewDashboard extends HtmlView
{
    protected $stmHelper;
    protected $scHelper;

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
        $this->stmHelper = new STMHelper();
        $this->scHelper = SCHelper::getInstance();
        $this->server = $this->scHelper->getServer();

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
        // Kostyli what i like
        $input = JFactory::getApplication()->input->post;
        $this->getModel()->setState('list.limit', $input->getInt('limit') ?? 20);
        $this->getModel()->setState('list.start', $input->getInt('limitstart') ?? 0);

        $items = $this->get('Items');
        $pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }

        if (!$this->scHelper->checkAccess()) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_STM_INCORRECT_CREDENTIALS'), 'error');
        }

        // Show the toolbar
        $this->toolbar();

        $this->stmHelper->addSubmenus('dashboard');
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
            JToolbarHelper::deleteList(Text::_('COM_STM_DELETE_PROJECTS_MSG'), 'dashboard.delete');
            JToolBarHelper::preferences('com_st_manager');
        }
    }

    protected function getDocumentUrl($item)
    {
        if (!empty($item->document_id)) {
            $ids = explode('_', $item->document_id);

            $link = "https://{$this->server}/editor?DocumentId={$ids[0]}&LanguageId={$ids[1]}";
            $text = JText::_('COM_STM_GO_TO_SMARTCAT_LINK_TEXT');

            return "<a href='{$link}' target='_blank'>{$text}</a>";
        }

        return "";
    }
}
