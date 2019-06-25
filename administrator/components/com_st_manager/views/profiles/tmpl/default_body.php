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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/LanguageDictionary.php';

?>
<?php foreach ($this->items as $i => $item) :?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="center">
            <?php echo $item->id; ?>
        </td>
        <td class="center">
            <?php echo $item->name; ?>
        </td>
        <td class="center">
            <?php echo $item->vendor_name ? $item->vendor_name : 'Translate internally'; ?>
        </td>
        <td class="center">
            <?php echo LanguageDictionary::getNameByCode($item->source_lang); ?>
        </td>
        <td class="center">
            <?php echo $this->getTargetLanguages($item); ?>
        </td>
        <td class="center">
            <?php echo $this->getWorkflowStages($item); ?>
        </td>
    </tr>
<?php endforeach; ?>
