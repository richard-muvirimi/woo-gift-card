<?php
/**
 * Product general data panel.
 *
 * @package WooCommerce/Admin
 */
defined('ABSPATH') || exit;
?>
<div class="options_group hidden show_if_thankyouvoucher">
    <?php
    woocommerce_wp_select(array(
	'id' => 'wgc-thankyou-order-status',
	'label' => __('Order Status', 'woo-gift-card'),
	'description' => __('The order status to send thank you gift voucher on', 'woo-gift-card'),
	'options' => wc_get_order_statuses(),
	'value' => $product_object->get_meta('wgc-thankyou-order-status') ?: wc_get_is_paid_statuses()[0],
	'desc_tip' => true
    ));

    woocommerce_wp_text_input(array(
	'id' => 'wgc-thankyou-orders',
	'value' => $product_object->get_meta('wgc-thankyou-orders') ?: 5,
	'label' => __('Order Count', 'woo-gift-card'),
	'description' => __('The number of orders to wait before sending thank you gift voucher.', 'woo-gift-card'),
	'desc_tip' => true,
	'custom_attributes' => array(
	    "min" => 1
	),
	'type' => "number",
    ));

    woocommerce_wp_text_input(array(
	'id' => 'wgc-thankyou-min-cart',
	'value' => $product_object->get_meta('wgc-thankyou-min-cart'),
	'data_type' => 'price',
	'label' => __('Minimum Cart Value', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
	'description' => __('The minimum monetary value this thank you gift voucher will be sent at', 'woo-gift-card'),
	'desc_tip' => true
    ));

    woocommerce_wp_text_input(array(
	'id' => 'wgc-thankyou-max-cart',
	'value' => $product_object->get_meta('wgc-thankyou-max-cart'),
	'data_type' => 'price',
	'label' => __('Maximum Cart Value', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
	'description' => __('The maximum monetary value this thank you gift voucher will be sent at', 'woo-gift-card'),
	'desc_tip' => true
    ));
    ?>
</div>