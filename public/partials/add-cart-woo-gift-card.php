<?php
/**
 * Provide a payment view for the plugin
 *
 * This file is used to markup the payment aspects of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public/partials
 */
global $product;
?>

<p> <label for="'wgc-price'">Price</label>
    <?php
    switch ($product->get_meta("wgc-pricing")) {
	case "range":
	    woocommerce_quantity_input(array(
		'input_id' => 'wgc-price',
		'input_name' => 'wgc-price',
		'min_value' => $product->get_meta('wgc-price-range-from'),
		'max_value' => $product->get_meta('wgc-price-range-to'),
		'input_value' => ceil($product->get_meta('wgc-price-range-from') + $product->get_meta('wgc-price-range-to')) / 2, // WPCS: CSRF ok, input var ok.
		    ), $product);
	    break;
	case 'user':

	    if (is_null($from)) {
		$from = $product->get_meta('wgc-price-user');
	    }

	    $display_price = wc_price(wc_get_price_to_display($product, array('price' => $from))) . $product->get_price_suffix($from);
	    break;
	case "selected":
	    $display_price = __("Select Price", 'woo-gift-card');
	    break;
	case 'fixed':
	default:
    }
    ?>
</p>
<?php
/**
 * todo: add default price for user price
 *
 * send gift card
 * emails, from name, gift message
 *
 * schedule gift card
 * image for gift card
 *
 * pricing
 * range, selected, user
 */
?>

<div class="wgc-add-to-cart">
    <input type="text">
</div>

