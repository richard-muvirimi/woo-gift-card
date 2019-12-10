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
<p><?php _e("The default short code is <code>[woogiftcard]</code> which will default to <code>code</code>. Various optional attributes can be added to a gift voucher template using the format <code>[woogiftcard attr=\"logo\"]</code> where <code>attr</code> is the voucher attribute and <code>\"logo\"</code> representing the attibute you want to insert.", 'woo-gift-card') ?>
</p>

<?php
$attr = array(
    "amount" => __("The monetary value of the gift voucher.", 'woo-gift-card'),
    "code" => __("The code to uniquely identify the gift voucher.", 'woo-gift-card'),
    "disclaimer" => __("Disclaimer message to show to the receipent of the gift voucher.", 'woo-gift-card'),
    "event" => __("What event is this gift voucher for.", 'woo-gift-card'),
    "expiry-date" => __("The expiry date of the gift voucher", 'woo-gift-card'),
    "featured-image" => __("An actual image to place on the gift voucher", 'woo-gift-card'),
    "from" => __("The sender of the gift voucher", 'woo-gift-card'),
    "logo" => __("This companies logo", 'woo-gift-card'),
    "message" => __("A message sent by customer to the recipient of the gift voucher", 'woo-gift-card'),
    "order-id" => __("The order id to use as reference of the gift voucher", 'woo-gift-card'),
    "product-name" => __("The product name of this gift voucher", 'woo-gift-card'),
    "to" => __("The recipient of the gift voucher", 'woo-gift-card')
);
?>

<table class="wgc-template-help">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th><?php _e("Attribute", 'woo-gift-card') ?></th>
            <th><?php _e("Description", 'woo-gift-card') ?></th>
        </tr>
    </thead>
    <tbody>

	<?php foreach ($attr as $key => $value) : ?>
    	<tr>
    	    <td>&diamondsuit;</td>
    	    <td><code><?php esc_html_e($key) ?></code></td>
    	    <td><?php esc_html_e($value) ?></td>
    	</tr>
	<?php endforeach; ?>
    </tbody>
</table>