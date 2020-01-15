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
<h2 style="text-align: center;">
    <?php _e("Gift Voucher Template Help", 'woo-gift-card') ?>
</h2>

<p>
    <?php _e("Gift Voucher Templates can be setup to have dynamic optional attributes that are filled in on demand. Below is the list of all available shortcodes to customise a template.", 'woo-gift-card') ?>
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
    <tfoot>
	<tr>
	    <td>
		<strong>
		    <?php esc_html_e("*", 'woo-gift-card') ?>
		</strong>
	    </td>
	    <td>
		<?php _e("These options can be customised on the front end by the customer.", 'woo-gift-card') ?>
	    </td>
	</tr>
    </tfoot>
</table>

<h3 style="text-align: center;"> <?php _e("Customisation", 'woo-gift-card') ?></h3>
<p>
    <?php _e("Furthermore the background image of the template can be customised by the css selector <code>body.preview-body</code>", 'woo-gift-card') ?>
</p>
<p>
    <?php _e("For the <code>wgc-code</code> shortcode, the qrcode and barcode can be customised by the css selectors <strong>(listed respectively)</strong>:", 'woo-gift-card') ?>
</p>
<ul>
    <li>
	<?php _e("<code>div.qrcode-container</code> and <code>div.barcode-container</code> for the container", "woo-gift-card") ?>
    </li>
    <li>
	<?php _e("<code>div.qrcode-img</code> and <code>div.barcode-img</code> for the html <code>img</code> tag parent container", "woo-gift-card") ?>
    </li>
    <li>
	<?php _e("<code>div.qrcode-img > img</code> and <code>div.barcode-img > img</code> for the actual html <code>img</code> tag", "woo-gift-card") ?>
    </li>
    <li>
	<?php _e("<code>div.qrcode</code> and <code>div.barcode</code> for the QR or Barcode text", "woo-gift-card") ?>
    </li>
</ul>


