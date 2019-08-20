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
<?php foreach ($this->items as $i => $item) :?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="center">
            <?php echo $item->id; ?>
        </td>
        <td class="center">
            <?php echo $item->created_at; ?>
        </td>
        <td class="center">
            <?php echo ucfirst($item->type); ?>
        </td>
        <td class="center">
            <?php echo $item->short_message; ?>
        </td>
        <td class="center">
            <?php echo $item->message; ?>
        </td>
    </tr>
<?php endforeach; ?>
