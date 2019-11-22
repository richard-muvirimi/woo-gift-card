<?php

if (!defined('WPINC')) {
    die(); // Exit if accessed directly.
}

/**
 * Cassava Remit Payment Gateway
 * Provides a blue print class for a Payment Gateway.
 *
 * @class 		Woo_Custom_Gateway
 * @extends		WC_Payment_Gateway
 * @package Woo_Custom_Gateway
 * @subpackage Woo_Custom_Gateway/public
 * @author Tyganeutronics <tygalive@gmail.com>
 */
class WC_Woo_Gift_Card_Gateway extends WC_Payment_Gateway {

    /**
     *
     * Initialis our custom payment gateway
     *
     * @param int $id
     */
    public function __construct($id) {

	$this->id = 'woo-gift-card-gateway';
	$this->icon = apply_filters('woo-gift-card-icon', $this->plugin_dir_url("public/img/wallet-giftcard.svg"));
	$this->has_fields = true;
	$this->method_title = __('Gift Voucher', 'woo-gift-card');
	$this->method_description = __('Allow customers to pay for their orders using Gift Vouchers', 'woo-gift-card');

	$this->init_form_fields();
	$this->init_settings();

	// Define user set variables
	$this->title = __('Gift Voucher', 'woo-gift-card');
	$this->description = __('Pay for this order using a Gift Voucher', 'woo-gift-card');
	$this->instructions = $this->get_option('instructions');
	$this->order_stat = $this->get_option('order_stat');

	add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
	add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));

	// Customer Emails
	add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);
    }

    /**
     * Output for the order received page.
     *
     * @param int $orderId
     */
    public function thankyou_page($orderId) {

	if ($this->instructions) {
	    echo wpautop(wptexturize($this->instructions));
	}
    }

    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
    public function email_instructions($order, $sent_to_admin, $plain_text = false) {

	if ($this->instructions && !$sent_to_admin && $this->id === $order->get_payment_method()) {

	    // Go ahead only if the order has one of our statusses.
	    if ($order->has_status('on-hold') || $order->has_status('processing')) {

		echo wpautop(wptexturize($this->instructions)) . PHP_EOL;
	    }
	}
    }

    /**
     * Initialise Gateway Settings Form Fields
     */
    public function init_form_fields() {

	$this->form_fields = array(
	    'enabled' => array('title' => __('Enable/Disable', 'woocommerce'), 'type' => 'checkbox', 'label' => __(sprintf('Enable %s?', $this->method_title), 'woocommerce'), 'default' => 'yes'),
	    'order_stat' => array(
		'title' => __('Order status', 'woocommerce'),
		'type' => 'select',
		'description' => __('The order status after customer has paid with a Gift Voucher.', 'woo-gift-card'),
		'default' => 'on-hold',
		'desc_tip' => false,
		'options' => array('on-hold' => __('On Hold', 'woocommerce'), 'completed' => __('Completed', 'woocommerce'))
	    ),
	    'instructions' => array('title' => __('Instructions', 'woocommerce'), 'type' => 'textarea', 'description' => __('General instructions that will be added to the thank you page and emails.', 'woo-gift-card'), 'default' => '', 'desc_tip' => false)
	);
    }

    /**
     * Helper function to get plugin directory url
     *
     * @param string $url
     * @return void
     */
    private function plugin_dir_url($url = '') {
	return plugin_dir_url(dirname(dirname(dirname(__FILE__)))) . $url;
    }

    /**
     * Helper function to get plugin directory
     *
     * @param string $url
     * @return void
     */
    private function plugin_dir_path($path = '') {
	return plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . $path;
    }

    /**
     * Show our payment gateway data entry fields
     *
     * @return void
     */
    public function payment_fields() {
	include_once apply_filters('woo-gift-card-payment-display', $this->plugin_dir_path("public/partials/woo-gift-card-payment.php"));
    }

    /**
     * Validate customer entered data
     * First take out our hand wash, and sanitize customer input. i know for sure they are trying to inject something, just not sure what
     *
     *
     * @return void
     */
    public function validate_fields() {

	$email = sanitize_email(filter_input(INPUT_POST, 'woo-gift-card-email'));
	$voucher = sanitize_text_field(filter_input(INPUT_POST, 'woo-gift-card-key'));

	return email_exists($email) && $voucher;
    }

    /**
     * Process the payment and return the result
     *
     * @param int $order_id
     * @return array
     */
    public function process_payment($order_id) {

	$order = wc_get_order($order_id);

	//A little sanitisation never kills
	$email = sanitize_email(filter_input(INPUT_POST, 'woo-gift-card-email'));
	$voucher = sanitize_text_field(filter_input(INPUT_POST, 'woo-gift-card-key'));

	//if key exists
	$posts = get_posts(array(
	    'posts_per_page' => 1,
	    'post_type' => 'woo-gift-card',
	    'author' => $email,
	    'meta_key' => 'woo-gift-card-key',
	    'meta_value' => $voucher,
	    'fields' => 'ids'
	));

	if (count($posts) > 0) {

	    $gift_card = get_post($posts[0]);

	    //check if voucher has enough balance
	    $balance = get_post_meta($gift_card->ID, 'woo-gift-card-balance', true);
	    if ($order->get_total() <= $balance) {

		if ($this->order_stat == 'on-hold') {
		    $order->update_status($this->order_stat, __('Awaiting manual confirmation.', 'woo-gift-card'));
		} else {
		    //set order as complete
		    $order->payment_complete();
		}

		//reduce gift card balance
		update_post_meta($gift_card->ID, 'woo-gift-card-balance', $balance - $order->get_total(), $balance);

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array('result' => 'success', 'redirect' => $this->get_return_url($order));
	    } else {
		//gift card balance not enough

		wc_add_notice(__('Payment Error: ', 'woothemes') . sprintf(__('The Gift Voucher balance %s is lower than the cart total.', 'woo-gift-card'), get_woocommerce_currency_symbol() . $balance), 'error');
	    }
	} else {

	    wc_add_notice(__('Payment Error: ', 'woothemes') . sprintf(__('The Gift Voucher (%s) was not found.', 'woo-gift-card'), $voucher), 'error');
	}
    }

}
