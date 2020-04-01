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
 * @subpackage Woo_gift_card/includes/modal/coupon
 */

/**
 * The file that defines the gift card product type
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/modal/coupon
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class WGC_Coupon extends WC_Coupon
{

    /*
      |--------------------------------------------------------------------------
      | Getters
      |--------------------------------------------------------------------------
      |
      | Methods for getting data from the coupon object.
      |
     */

    /**
     * Get order id.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return integer
     */
    public function get_order_id($context = 'view')
    {
        $this->get_meta("wgc-order", true, $context);
    }

    /**
     * Get order item Id.
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return integer
     */
    public function get_order_item_id($context = 'view')
    {
        $this->get_meta("wgc-order-item", true, $context);
    }

    /**
     * Get Order Item Index
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return integer
     */
    public function get_order_item_index($context = 'view')
    {
        $this->get_meta("wgc-order-item-index", true, $context);
    }

    /*
      |--------------------------------------------------------------------------
      | Setters
      |--------------------------------------------------------------------------
      |
      | Methods for setting data to the coupon object.
      |
     */

    /**
     * Set order id.
     *
     * @param integer $order_id Order Id.
     */
    public function set_order_id($order_id)
    {
        $this->update_meta_data("wgc-order", $order_id);
    }

    /**
     * Set order item Id.
     *
     * @param integer $order_item_id
     */
    public function set_order_item_id($order_item_id)
    {
        $this->update_meta_data("wgc-order-item", $order_item_id);
    }

    /**
     * Set Order Item Index
     *
     * @param integer $order_item_index
     */
    public function set_order_item_index($order_item_index)
    {
        $this->update_meta_data("wgc-order-item-index", $order_item_index);
    }
}
