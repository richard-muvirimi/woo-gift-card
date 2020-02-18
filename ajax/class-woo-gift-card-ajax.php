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
class Woo_gift_card_Ajax {

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

    public function send_mail() {
	check_ajax_referer('wgc-send-mail');

	$coupon_id = wgc_get_post_var("which");

	$coupon = new \WC_Coupon($coupon_id);

	$data = array();
	if (wc_coupons_enabled() && $coupon->meta_exists("wgc-order") && $coupon->meta_exists("wgc-order-item")) {

	    /**
	     * Action hook fired after a coupon has been applied.
	     *
	     * @param \WC_Coupon $coupon The coupon just applied
	     */
	    do_action("wgc_coupon_email", $coupon);

	    $data["message"] = __("Gift Voucher recipients have been notified successfully.", $this->plugin_name);
	    $data["status"] = "message";
	} else {
	    $data["message"] = __("Failed to resend gift voucher notification email.", $this->plugin_name);
	    $data["status"] = "error";
	}

	$template = wc_get_template_html("wgc-ajax-status.php", $data, "", plugin_dir_path(__FILE__) . "partials/");

	if ($data["status"] === "message") {
	    wp_send_json_success($template);
	} else {
	    wp_send_json_error($template);
	}
    }

    public function delete_voucher() {
	check_ajax_referer('wgc-delete-voucher');

	$coupon_id = wgc_get_post_var("which");

	$success = is_a(wp_delete_post($coupon_id), "\WP_Post");

	$data = array();
	if ($success) {
	    $data["message"] = __("Gift Voucher Deleted Successfully.", $this->plugin_name);
	    $data["status"] = "message";
	} else {
	    $data["message"] = __("Failed to delete gift voucher.", $this->plugin_name);
	    $data["status"] = "error";
	}

	$template = wc_get_template_html("wgc-ajax-status.php", $data, "", plugin_dir_path(__FILE__) . "partials/");

	if ($success) {
	    wp_send_json_success($template);
	} else {
	    wp_send_json_error($template);
	}
    }

}
