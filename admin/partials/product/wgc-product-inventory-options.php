<?php
/**
 * Product general data panel.
 *
 * @package WooCommerce/Admin
 */
defined('ABSPATH') || exit;
?>

<div class="stock_fields show_if_woo-gift-card">

    <?php
    woocommerce_wp_textarea_input(array(
	'id' => 'wgc-coupon-codes',
	'value' => $product_object->get_meta('wgc-coupon-codes'),
	'label' => __('Coupon Codes', 'woo-gift-card'),
	'description' => __('Enter codes here (separated by spaces and/or commas) if you do not want them generated automatically.', 'woo-gift-card'),
	'desc_tip' => true,
    ));
    ?>

</div>

