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
class WGC_Product extends WC_Product_Simple
{

    /**
     * Return the product type
     *
     * @return string
     */
    public function get_type()
    {
        return "woo-gift-card";
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
     * Set the discounting system for gift voucher
     *
     * @since 1.0.0
     * @param  string $value Coupon Discount Type.
     */
    public function set_coupon_discount($value = '')
    {
        $this->update_meta_data("wgc-discount", $value);
    }

    /**
     * Set discount amount for gift voucher
     *
     * @since 1.0.0
     * @param  string $value Coupon Discount Amount.
     */
    public function set_coupon_discount_amount($value = '')
    {
        $this->update_meta_data("wgc-discount-amount", $value);
    }

    /**
     * Set coupon cart minimum amount
     *
     * @since 1.0.0
     * @param  string $value Coupon Cart Min.
     */
    public function set_coupon_cart_min($value = '')
    {
        $this->update_meta_data("wgc-cart-min", $value);
    }

    /**
     * Set coupon cart max amount
     *
     * @since 1.0.0
     * @param  string $value Coupon Cart Max.
     */
    public function set_coupon_cart_max($value = '')
    {
        $this->update_meta_data("wgc-cart-max", $value);
    }

    /**
     * Set coupon applicable emails
     *
     * @since 1.0.0
     * @param  string $value Coupon Emails.
     */
    public function set_coupon_emails($value = '')
    {
        $this->update_meta_data("wgc-emails", $value);
    }

    /**
     * set coupon individual use only
     *
     * @since 1.0.0
     * @param  string $value Coupon Individual Use.
     */
    public function set_coupon_individual($value = '')
    {
        $this->update_meta_data("wgc-individual", $value);
    }

    /**
     * Set coupon not applicable on sale items
     *
     * @since 1.0.0
     * @param  string $value Coupon Sale Items.
     */
    public function set_coupon_sale($value = '')
    {
        $this->update_meta_data("wgc-sale", $value);
    }

    /**
     * Set coupon allows free shipping
     *
     * @since 1.0.0
     * @param  string $value Coupon Free Shipping.
     */
    public function set_coupon_free_shipping($value = '')
    {
        $this->update_meta_data("wgc-free-shipping", $value);
    }

    /**
     * Set coupon per item usability limit
     *
     * @since 1.0.0
     * @param  string $value BCoupon Usability.
     */
    public function set_coupon_limit_usage_to_x_items($value = '')
    {
        $this->update_meta_data("wgc-limit-usage-to-x-items", $value);
    }

    /**
     * Set coupon usage limit
     *
     * @since 1.0.0
     * @param  string $value Coupon Multiple.
     */
    public function set_coupon_usage_limit($value = '')
    {
        $this->update_meta_data("wgc-usage-limit", $value);
    }

    /**
     * Set Usage limit per user
     *
     * @since 1.0.0
     * @param  string $value Coupon Usage limit per user.
     */
    public function set_coupon_usage_limit_per_user($value = '')
    {
        $this->update_meta_data("wgc-usage-limit-per-user", $value);
    }

    /**
     * Set coupon schedule
     *
     * @since 1.0.0
     * @param  string $value Coupon Schedule.
     */
    public function set_coupon_schedule($value = '')
    {
        $this->update_meta_data("wgc-schedule", $value);
    }

    /**
     * Set coupon expiry days from purchase
     *
     * @since 1.0.0
     * @param  string $value Coupon Expiry Days.
     */
    public function set_coupon_expiry_days($value = '')
    {
        $this->update_meta_data("wgc-expiry-days", $value);
    }

    /**
     * set coupon usable products
     *
     * @since 1.0.0
     * @param  string $value Coupon Products.
     */
    public function set_coupon_products($value = '')
    {
        $this->update_meta_data("wgc-products", $value);
    }

    /**
     * Set coupon excluded products
     *
     * @since 1.0.0
     * @param  string $value Coupon Excluded Products.
     */
    public function set_coupon_excluded_products($value = '')
    {
        $this->update_meta_data("wgc-excluded-products", $value);
    }

    /**
     * Set coupon usable product categories
     *
     * @since 1.0.0
     * @param  string $value Coupon Product Categories.
     */
    public function set_coupon_product_categories($value = '')
    {
        $this->update_meta_data("wgc-product-categories", $value);
    }

    /**
     * Set coupon excluded product categories
     *
     * @since 1.0.0
     * @param  string $value Coupon Excluded Product Categories.
     */
    public function set_coupon_excluded_product_categories($value = '')
    {
        $this->update_meta_data("wgc-excluded-product-categories", $value);
    }

    /*
      |--------------------------------------------------------------------------
      | Getters
      |--------------------------------------------------------------------------
      |
      | Methods for getting data from the coupon object.
      |
     */

    /**
     * Get the discounting system for gift voucher
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_discount($context = 'view')
    {
        return $this->get_meta("wgc-discount", true, $context);
    }

    /**
     * Get discount amount for gift voucher
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_discount_amount($context = 'view')
    {
        return $this->get_meta("wgc-discount-amount", true, $context);
    }

    /**
     * Get coupon cart minimum amount
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_cart_min($context = 'view')
    {
        return $this->get_meta("wgc-cart-min", true, $context);
    }

    /**
     * Get coupon cart max amount
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_cart_max($context = 'view')
    {
        return $this->get_meta("wgc-cart-max", true, $context);
    }

    /**
     * Get coupon applicable emails
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_emails($context = 'view')
    {
        return $this->get_meta("wgc-emails", true, $context);
    }

    /**
     * Get coupon individual use only
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_individual($context = 'view')
    {
        return $this->get_meta("wgc-individual", true, $context);
    }

    /**
     * Get coupon not applicable on sale items
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_sale($context = 'view')
    {
        return $this->get_meta("wgc-sale", true, $context);
    }

    /**
     * Get coupon allows free shipping
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_free_shipping($context = 'view')
    {
        return $this->get_meta("wgc-free-shipping", true, $context);
    }

    /**
     * Get coupon per item usability limit
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_limit_usage_to_x_items($context = 'view')
    {
        return $this->get_meta("wgc-limit-usage-to-x-items", true, $context);
    }

    /**
     * Get coupon usage limit
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_usage_limit($context = 'view')
    {
        return $this->get_meta("wgc-usage-limit", true, $context);
    }

    /**
     * Get Coupon Usage limit per user
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_usage_limit_per_user($context = 'view')
    {
        return $this->get_meta("wgc-usage-limit-per-user", true, $context);
    }

    /**
     * Get coupon schedule
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_schedule($context = 'view')
    {
        return $this->get_meta("wgc-schedule", true, $context);
    }

    /**
     * Get coupon expiry days from purchase
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_expiry_days($context = 'view')
    {
        return $this->get_meta("wgc-expiry-days", true, $context);
    }

    /**
     * Get coupon usable products
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_products($context = 'view')
    {
        return $this->get_meta("wgc-products", true, $context);
    }

    /**
     * Get coupon excluded products
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_excluded_products($context = 'view')
    {
        return $this->get_meta("wgc-excluded-products", true, $context);
    }

    /**
     * Get coupon usable product categories
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_product_categories($context = 'view')
    {
        return $this->get_meta("wgc-product-categories", true, $context);
    }

    /**
     * Get coupon excluded product categories
     *
     * @since 1.0.0
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_coupon_excluded_product_categories($context = 'view')
    {
        return $this->get_meta("wgc-excluded-product-categories", true, $context);
    }

    /**
     * Returns whether or not the product has additional options that need
     * selecting before adding to cart.
     *
     * @since  3.0.0
     * @return boolean
     */
    public function has_options()
    {
        return true;
    }
}
