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
class STMViewProfiles extends HtmlView
{
    protected $helper;
    protected $items;
    protected $pagination;
    protected $sidebar = '';

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
        $items = $this->get('Items');
        $pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Show the toolbar
        $this->toolbar();

        // Show the sidebar
        $this->helper->addSubmenus('profiles');
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
        JToolBarHelper::title(Text::_('COM_ST_MANAGER_PROFILES'), '');

        // Options button.
        if (Factory::getUser()->authorise('core.admin', 'com_st_manager'))
        {
            JToolBarHelper::preferences('com_st_manager');
            JToolbarHelper::addNew('profile.add');
            JToolbarHelper::editList('profile.edit');
            JToolbarHelper::deleteList(Text::_('COM_STM_DELETE_PROFILES_MSG'), 'profiles.delete');
        }
    }

    /**
     * @param stdClass $item
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function getTargetLanguages($item)
    {
        $items = array_map(function ($value) {
            return LanguageDictionary::getNameByCode($value);
        }, explode(',', $item->target_lang));

        return implode(', ', $items);
    }

    /**
     * @param stdClass $item
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function getWorkflowStages($item)
    {
        $items = array_map(function ($value) {
            return ucfirst($value);
        }, explode(',', $item->stages));

        return implode(', ', $items);
    }
}
