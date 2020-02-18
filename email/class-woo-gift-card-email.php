<?php

/**
 * The emailing functionality of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public
 */

/**
 * The emailing functionality of the plugin.
 *
 * @package Woo_gift_card
 * @subpackage Woo_gift_card/email
 * @author Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_card_Email {

    /**
     * The ID of this plugin.
     *
     * @since 1.0.0
     * @access private
     * @var string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since 1.0.0
     * @access private
     * @var string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
	$this->plugin_name = $plugin_name;
	$this->version = $version;
    }

    /**
     * Add our hooks for sending coupon status to customer
     *
     * @param array $email_actions
     * @return array
     */
    public function woocommerce_email_actions($email_actions) {
	if (wc_coupons_enabled()) {
	    $email_actions [] = 'wgc_coupon_published';
	    $email_actions [] = 'wgc_coupon_email';
	    $email_actions [] = 'wgc_coupon_applied';
	}

	return $email_actions;
    }

    /**
     * Add our custom email classes
     *
     * @param array $emails
     * @return array
     */
    public function woocommerce_email_classes($emails) {
	if (wc_coupons_enabled()) {
	    include_once plugin_dir_path(__FILE__) . 'models/class-wc-email-coupon.php';

	    $emails ["WGC_Email_Coupon_Received"] = include_once plugin_dir_path(__FILE__) . 'models/class-wc-email-coupon-received.php';
	    $emails ["WGC_Email_Coupon_Applied"] = include_once plugin_dir_path(__FILE__) . 'models/class-wc-email-coupon-applied.php';
	}

	return $emails;
    }

    /**
     * After a post has been saved
     *
     * @param int $post_ID
     * @param \WP_Post $post
     * @param bool $update
     */
    public function save_coupon_post(int $post_ID, WP_Post $post, bool $update) {
	if (wc_coupons_enabled()) {
	    if ($post->post_type == "shop_coupon" && $post->post_status == "publish" && !$update) {

		// send emails
		$coupon = new \WC_Coupon($post_ID);

		if ($coupon->meta_exists("wgc-order") && $coupon->meta_exists("wgc-order-item")) {

		    /**
		     * Action hook fired after a new coupon has been saved.
		     *
		     * @param \WC_Coupon $coupon The coupon just saved
		     */
		    do_action("wgc_coupon_published", $coupon);
		}
	    }
	}
    }

    /**
     * A coupon code has been applied
     * @param type $coupon_code
     */
    public function woocommerce_applied_coupon($coupon_code) {

	$coupon_id = wc_get_coupon_id_by_code($coupon_code);

	if ($coupon_id !== 0) {

	    $coupon = new \WC_Coupon($coupon_id);

	    if ($coupon->meta_exists("wgc-order") && $coupon->meta_exists("wgc-order-item")) {

		/**
		 * Action hook fired after a coupon has been applied.
		 *
		 * @param \WC_Coupon $coupon The coupon just applied
		 */
		do_action("wgc_coupon_applied", $coupon);
	    }
	}
    }

    /**
     * limit users who can use gift voucher
     * notify user in email about gift card
     * schedule gift card sending
     * resend gift card email
     * import coupon codes
     * add template as downloadable
     * notify of coupon balance etc
     * send email multiple support
     * thank you gift coupons
     * edit coupon meta data
     */
}
