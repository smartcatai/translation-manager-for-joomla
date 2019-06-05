<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

include_once JPATH_COMPONENT_ADMINISTRATOR . '/models/project.php';

defined('_JEXEC') or die;

?>
<?php foreach ($this->items as $i => $item) :?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
            <?php
                $item_link = JRoute::_('index.php?option=com_content&task=article.edit&id=' . $item->entity_id, false);
            ?>
            <a href="<?php echo $item_link; ?>" target="_blank"><?php echo $item->entity_name; ?></a>
        </td>
        <td class="center">
            <?php
                $profile_link = JRoute::_('index.php?option=com_st_manager&view=profile&layout=edit&id=' . $item->profile_id, false);
            ?>
            <a href="<?php echo $profile_link; ?>" target="_blank"><?php echo $item->profile_name; ?></a>
        </td>
        <td class="center">
            <?php echo LanguageDictionary::getNameByCode($item->source_lang); ?>
        </td>
        <td class="center">
            <?php echo LanguageDictionary::getNameByCode($item->target_lang); ?>
        </td>
        <td class="center">
            <?php echo STMModelProject::getStatusText($item->status) ?>
        </td>
        <td class="center">
            
            <?php if (!empty($item->document_id)) : ?>
            <?php 
                $ids = explode('_', $item->document_id);
                $link = "https://{$this->server}/editor?DocumentId={$ids[0]}&LanguageId={$ids[1]}";
            ?>
            <a href="<?php echo $link; ?>" target="_blank"><?php echo JText::_('COM_STM_GO_TO_SMARTCAT_LINK_TEXT'); ?></a>
            <?php endif ?>
        </td>
    </tr>
<?php endforeach; ?>
