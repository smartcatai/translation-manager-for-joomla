<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

/**
 * Profiles view.
 *
 * @package   Smartcat Translation Manager
 * @since    1.0
 */
class STMViewProfile extends HtmlView
{
    protected $form = null;
    protected $item = null;

    /**
     * Display the Hello World view
     *
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @throws Exception

     * @return  void
     */
    public function display($tpl = null)
    {
        // Get the Data
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolBar()
    {
        $input = Factory::getApplication()->input;

        // Hide Joomla Administrator Main menu
        $input->set('hidemainmenu', true);

        $isNew = ($this->item->id == 0);

        if ($isNew)
        {
            $title = Text::_('COM_ST_MANAGER_PROFILE_NEW');
        }
        else
        {
            $title = Text::_('COM_ST_MANAGER_PROFILE_EDIT') . ": " . $this->item->name;
        }

        JToolbarHelper::title($title, 'STM');
        JToolbarHelper::save('profile.save');
        JToolbarHelper::cancel(
            'profile.cancel',
            $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
        );
    }
}