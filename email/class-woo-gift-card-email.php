<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_card_Email {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

	$this->plugin_name = $plugin_name;
	$this->version = $version;
    }

    /**
     * A new Coupon has been generated
     * @param int $coupon
     */
    public function wgc_coupon_state_notification($coupon) {
	if ($order_id && !is_a($order, 'WC_Order')) {
	    $order = wc_get_order($order_id);
	}

	foreach ($order->get_items() as $item) {

	    $product = $item->get_product();
	    if ($product->is_type('woo-gift-card')) {

		//order includes gift vouchers and we intend to send an email for each
	    }
	}
    }

    /**
     * Add hook for sending coupon balance
     *
     * @param array $email_actions
     */
    public function woocommerce_email_actions($email_actions) {

	if (wc_coupons_enabled()) {
	    $email_actions[] = 'wgc_coupon_state';
	}

	return $email_actions;
    }

    public function woocommerce_email_classes($emails) {

	if (wc_coupons_enabled()) {
	    $emails["WC_Email_Coupon_Status"] = include_once plugin_dir_path(__FILE__) . 'models/class-wc-email-coupon-status.php';
	}

	return $emails;
    }

    /**
     * limit users who can use gift voucher
     * notify user in email about gift card
     * schedule gift card sending
     * resend gift card email
     *
     * import coupon codes
     * add template as downloadable
     *
     * notify of coupon balance etc
     * send email multiple support
     *
     * thank you gift coupons
     * edit coupon meta data
     *
     */
}
