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
 * @subpackage Woo_gift_card/includes/product/type
 */

/**
 * The file that defines the gift card product type
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/product/type
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

}
