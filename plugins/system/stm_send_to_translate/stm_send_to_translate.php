<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\CMS\Plugin\CMSPlugin;

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * An example custom profile plugin.
 *
 * @since  1.6
 */
class PlgSystemstm_send_to_translate extends CMSPlugin
{
    const RELATED_COMPONENT_NAME = 'com_st_manager';

    protected $autoloadLanguage = true;

    public function onBeforeRender()
    {
        // Get the application object
        $app = JFactory::getApplication();

        // Run in backend
        if ($app->isAdmin() === true)
        {
            // Get the input object
            $input = $app->input;

            // Append button just on Articles
            if ($input->getCmd('option') === 'com_content' && $input->getCmd('view', 'articles') === 'articles')
            {
                // Get an instance of the Toolbar
                $toolbar = JToolbar::getInstance('toolbar');

                $layout = new JLayoutFile('joomla.toolbar.popup');

                $dhtml = $layout->render(array(
                    'name' => 'stm-profile-form',
                    'text' => JText::_('PLG_STM_SEND_TO_TRANSLATE_BTN_LABEL'),
                    'class' => 'icon-edit',
                    'doTask' => ''
                ));

                $toolbar->appendButton('Custom', $dhtml);

                $jsCode = file_get_contents(__DIR__ . '/modal_script.js');
                $jsCode = str_replace(
                    "{{ERROR_MESSAGE}}",
                    JText::_('PLG_STM_SEND_TO_TRANSLATE_MODAL_ERROR_MESSAGE'),
                    $jsCode
                );

                JFactory::getDocument()->addScriptDeclaration($jsCode);
            }
        }
    }


    public function onAfterRender()
    {
        // Get the application object
        $app = JFactory::getApplication();

        // Run in backend
        if ($app->isAdmin() === true) {
            // Get the input object
            $input = $app->input;

            // Append button just on Articles
            if ($input->getCmd('option') === 'com_content' && $input->getCmd('view', 'articles') === 'articles') {
                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_st_manager/models');

                /** @var STMModelProfiles $modelInstance */
                $modelInstance = JModelLegacy::getInstance('Profiles', 'STMModel');
                $profiles = $modelInstance->getItems();

                $options = [];

                foreach ($profiles as $profile) {
                    $options[] = "<option value='{$profile->id}'>{$profile->name}</option>";
                }

                $replacer = [
                    '{{TITLE}}' => JText::_('PLG_STM_SEND_TO_TRANSLATE_MODAL_TITLE'),
                    '{{SELECT_PROFILE}}' => JText::_('PLG_STM_SEND_TO_TRANSLATE_MODAL_SELECT_PROFILE_LABEL'),
                    '{{OPTIONS}}' => implode('<br />', $options),
                    '{{SUBMIT}}' => JText::_('PLG_STM_SEND_TO_TRANSLATE_MODAL_SUBMIT_BUTTON'),
                ];

                $html = file_get_contents(__DIR__ . '/modal.html');
                $html = str_replace(array_keys($replacer), array_values($replacer), $html);

                $app->appendBody($html);
            }
        }
    }
}
