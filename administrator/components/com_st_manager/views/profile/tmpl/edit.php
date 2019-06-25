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

?>
<form action="<?php echo JRoute::_('index.php?option=com_st_manager&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_ST_MANAGER_PROFILE_DETAILS'); ?></legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php foreach ($this->form->getFieldset() as $field): ?>
                        <div class="control-group">
                            <div class="control-label"><?php echo $field->label; ?></div>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="profile.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>