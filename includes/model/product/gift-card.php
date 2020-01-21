<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The file that defines the gift card product type
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/modal/product
 */

/**
 * The file that defines the gift card product type
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/modal/product
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class WC_Product_Woo_Gift_Card extends WC_Product_Simple {

    /**
     * Return the product type
     *
     * @return string
     */
    public function get_type() {
	return "woo-gift-card";
    }

    /**
     * Is a thank you gift voucher
     *
     * @return boolean
     */
    public function is_thankyouvoucher() {
	return $this->get_meta("_thankyouvoucher") === "on";
    }

    /**
     * Update meta data by key or ID, if provided.
     *
     * @since  2.6.0
     *
     * @param  string       $key Meta key.
     * @param  string|array $value Meta value.
     * @param  int          $meta_id Meta ID.
     */
    public function update_meta_data($key, $value, $meta_id = 0) {
	parent::update_meta_data($key, wc_clean(wp_unslash($value)), $meta_id);
    }

    public function get_non_null_meta($key = '', $default = null, $single = true, $context = 'view') {
	$meta = parent::get_meta($key, $single, $context);

	if ($meta === null) {
	    $meta = $default;
	}

	return $meta;
    }

    /**
     * Returns whether or not the product has additional options that need
     * selecting before adding to cart.
     *
     * @since  3.0.0
     * @return boolean
     */
    public function has_options() {
	return true;
    }

}
