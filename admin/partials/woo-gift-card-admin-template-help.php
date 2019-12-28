<?php
/**
 * Provide a gift card template shortcode 101
 *
 * This file is used to markup the admin-facing gift card template aspects of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/admin/partials
 */
defined('ABSPATH') || exit;
?>
<p><?php _e("Gift Voucher Templates can be setup to have dynamic optional attributes that are filled in on demand. Below is the list of all available shortcodes to customise a template.", 'woo-gift-card') ?>
</p>

<table class="wgc-template-help">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th><?php _e("Attribute", 'woo-gift-card') ?></th>
            <th><?php _e("Description", 'woo-gift-card') ?></th>
        </tr>
    </thead>
    <tbody>

	<?php foreach (WooGiftCardsUtils::getSupportedShortCodes() as $key => $value) : ?>
    	<tr>
    	    <td>&diamondsuit;</td>
    	    <td><code><?php esc_html_e(WooGiftCardsUtils::getShortCodePrefix() . $key) ?></code></td>
    	    <td><?php esc_html_e($value) ?></td>
    	</tr>
	<?php endforeach; ?>
    </tbody>
</table>