<?php
/**
 * Product general data panel.
 *
 * @package WooCommerce/Admin
 */
defined('ABSPATH') || exit;
?>
<div id="wgc-general" class="panel woocommerce_options_panel">
    <div class="options_group show_if_woo-gift-card">

	<?php
	woocommerce_wp_text_input(array(
	    'id' => 'wgc-cart-min',
	    'value' => $product_object->get_meta('wgc-cart-min') ?: "",
	    'data_type' => 'price',
	    'label' => __('Cart Total Minimum', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	    'description' => __('The minimum monetary value of customer\'s cart before gift voucher can be applied', 'woo-gift-card'),
	    'desc_tip' => true
	));

	woocommerce_wp_text_input(array(
	    'id' => 'wgc-cart-max',
	    'value' => $product_object->get_meta('wgc-cart-max') ?: "",
	    'data_type' => 'price',
	    'label' => __('Cart Total Maximum', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	    'description' => __('The maximum monetary value of customer\'s cart the gift voucher can be applied to', 'woo-gift-card'),
	    'desc_tip' => true
	));

	woocommerce_wp_text_input(array(
	    'id' => 'wgc-emails',
	    'value' => $product_object->get_meta('wgc-emails') ?: "",
	    'label' => __('Allowed emails', 'woo-gift-card'),
	    'description' => __('Whitelist of billing emails to check against when an order is placed. Separate email addresses with commas. You can also use an asterisk (*) to match parts of an email. For example "*@gmail.com" would match all gmail addresses.', 'woo-gift-card'),
	    'desc_tip' => true,
	));
	?>
    </div>
    <div class="options_group show_if_woo-gift-card">

	<?php
	woocommerce_wp_checkbox(array(
	    'id' => 'wgc-individual',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Individual use only', 'woo-gift-card'),
	    'description' => __('Check this box if the coupon cannot be used in conjunction with other coupons.', 'woo-gift-card'),
	    'value' => $product_object->get_meta("wgc-individual") ?: "no"
	));

	woocommerce_wp_checkbox(array(
	    'id' => 'wgc-sale',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Exclude sale items', 'woo-gift-card'),
	    'description' => __('Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'woo-gift-card'),
	    'value' => $product_object->get_meta("wgc-sale") ?: "no"
	));
	?>
    </div>
    <div class="options_group show_if_woo-gift-card">
	<?php
	woocommerce_wp_text_input(array(
	    'id' => 'wgc-usability',
	    'value' => $product_object->get_meta('wgc-usability') ?: "",
	    'label' => __('Limit usage to X items', 'woo-gift-card'),
	    'description' => __('The maximum number of individual items this coupon can apply to when using product discounts. Leave blank to apply to all qualifying items in cart.', 'woo-gift-card'),
	    'desc_tip' => true,
	    'custom_attributes' => array(
		"min" => 0
	    ),
	    'type' => "number",
	));

	woocommerce_wp_text_input(array(
	    'id' => 'wgc-multiple',
	    'value' => $product_object->get_meta('wgc-multiple') ?: "",
	    'label' => __('Usage limit', 'woo-gift-card'),
	    'description' => __('How many times a coupon can be used before its void.', 'woo-gift-card'),
	    'desc_tip' => true,
	    'custom_attributes' => array(
		"min" => 0
	    ),
	    'type' => "number",
	));
	?>
    </div>
    <div class="options_group show_if_woo-gift-card">
	<?php
	woocommerce_wp_checkbox(array(
	    'id' => 'wgc-schedule',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Gift Voucher Scheduling', 'woo-gift-card'),
	    'description' => __('A customer can set a date to send coupon on front end during purchase.', 'woo-gift-card'),
	    'value' => $product_object->get_meta('wgc-schedule') ?: ""
	));

	woocommerce_wp_text_input(array(
	    'id' => 'wgc-expiry-days',
	    'value' => $product_object->get_meta('wgc-expiry-days') ?: 5,
	    'label' => __('Expiry Days', 'woo-gift-card'),
	    'description' => __('The number of days after purchase that a gift voucher will become invalid. Expiring at 00:00:00 of the last day.', 'woo-gift-card'),
	    'desc_tip' => true,
	    'custom_attributes' => array(
		"min" => 1
	    ),
	    'type' => "number",
	));
	?>
    </div>


</div>
