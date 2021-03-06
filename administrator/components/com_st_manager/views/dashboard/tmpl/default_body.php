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

include_once JPATH_COMPONENT_ADMINISTRATOR . '/models/project.php';
?>

<?php foreach ($this->items as $i => $item) :?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
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
            <?php echo $this->getDocumentUrl($item) ?>
        </td>
    </tr>
<?php endforeach; ?>
