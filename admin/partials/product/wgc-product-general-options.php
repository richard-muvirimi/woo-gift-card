<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @package WooCommerce/Admin
 * @global \WGC_Product $product
 */

defined('ABSPATH') || exit;
?>

<div class="options_group show_if_woo-gift-card">

	<?php
	woocommerce_wp_select(array(
		'id' => 'wgc-discount',
		'label' => __('Discount Type', $plugin_name),
		'description' => __('The discounting system for gift voucher', $plugin_name),
		'options' => wc_get_coupon_types(),
		'value' => $product->get_coupon_discount('edit'),
		'desc_tip' => true
	));

	woocommerce_wp_text_input(array(
		'wrapper_class' => 'wgc-discount-amount',
		'id' => 'wgc-discount-amount',
		'value' => $product->get_coupon_discount_amount('edit') ?: 0,
		'data_type' => 'price',
		'label' => __('Discount Amount', $plugin_name),
		'description' => __('The amount to discount. Leave blank to default to this product\'s price.', $plugin_name),
		'desc_tip' => true
	));
	?>
</div>