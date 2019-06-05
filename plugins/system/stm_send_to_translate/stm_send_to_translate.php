<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
/**
 * An example custom profile plugin.
 *
 * @since  1.6
 */
class PlgSystemstm_send_to_translate extends CMSPlugin
{
    const RELATED_COMPONENT_NAME = 'com_st_manager';

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

                // Add your custom button here
                $url = JRoute::_('index.php?option='.self::RELATED_COMPONENT_NAME.'&task=dashboard.add');
                $profile_url = JRoute::_('index.php?option='.self::RELATED_COMPONENT_NAME.'&view=profile&layout=edit');

                $layout = new JLayoutFile('joomla.toolbar.popup');
                //$toolbar->appendButton('Standard', 'edit', 'Submit for translation', 'dashboard.add');
                $dhtml = $layout->render(array('name' => 'stm-profile-form', 'text' => JText::_('Submit for translation'), 'class' => 'icon-edit', 'doTask' => ''));
                $toolbar->appendButton('Custom', $dhtml);


                JFactory::getDocument()->addScriptDeclaration('function stmSubmit() {
	    $form = document.getElementById("adminForm");
	    $profile_id = document.getElementById("profile_id").value;
	    if ($profile_id) {
	        $form.setAttribute("action", "'.$url.'&profile_id=" + $profile_id)
	        Joomla.submitform("dashboard.add", $form);
	    } else {
	        Joomla.renderMessages({"error":["No translation profiles found. Please create a profile <a href=\"'.$profile_url.'\">here</a>."]});
	        document.getElementById("modal-close-btn").click();
	    }
    };');
            }
        }
    }


    public function onAfterRender()
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
                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_st_manager/models');

                /** @var STMModelProfiles $modelInstance */
                $modelInstance = JModelLegacy::getInstance('Profiles', 'STMModel');
                $profiles = $modelInstance->getItems();

                $options = [];

                foreach ($profiles as $profile) {
                    $options[] = "<option value='{$profile->id}'>{$profile->name}</option>";
                }

                $options = implode('<br />', $options);

                $html =
                    <<<HTML
<div id="modal-stm-profile-form" class="modal hide fade">
    <div class="modal-header">
        <button id="modal-close-btn" type="button" role="presentation" class="close" data-dismiss="modal">x</button>
        <h3>Select Profile</h3>
    </div>
    <div class="modal-body raw" style="padding: 20px 0 0 20px">
        <div class="control-group container">
            <form id="form-stm" class="form-horizontal">
            <div class="control-label">
			    <label for="profile_id">Select profile:</label>
			</div>
			<div class="controls">
			    <select class="form-control" id="profile_id" name="profile_id">
                    {$options}
                </select>
			</div>
            </form> 
        </div> 
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success" onclick="stmSubmit();">Submit</button>
    </div>    
</div>
HTML;

                $app->appendBody($html);
            }
        }
    }
}