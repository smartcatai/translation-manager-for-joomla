<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/LanguageDictionary.php';

defined('_JEXEC') or die;

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
            <?php
            $items = array_map(function ($value) {
                return LanguageDictionary::getNameByCode($value);
            }, explode(',', $item->target_lang));

            echo implode(', ', $items);
            ?>
        </td>
        <td class="center">
            <?php
            $items = array_map(function ($value) {
                return ucfirst($value);
            }, explode(',', $item->stages));

            echo implode(', ', $items);
            ?>
        </td>
    </tr>
<?php endforeach; ?>
