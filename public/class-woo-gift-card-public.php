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
class Woo_gift_card_Public {

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
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Woo_gift_card_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Woo_gift_card_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-gift-card-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Woo_gift_card_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Woo_gift_card_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-gift-card-public.js', array('jquery'), $this->version, false);

	if (is_product()) {

	    global $post;

	    $product = wc_get_product($post);

	    if ($product->is_type('woo-gift-card')) {
		wp_enqueue_script($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'js/woo-gift-card-product.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name . "-product", 'wgc_product', array("maxlength" => get_option('wgc-message-length')));
	    }
	}
    }

    /**
     * Add gift card link to my account items
     *
     * @param array $items
     * @return void
     */
    public function filter_account_menu_items($items) {
	$links = array();

	foreach ($items as $key => $item) {
	    $links[$key] = $item;

	    if ($key === 'downloads') {
		$links['woo-gift-card'] = __('Gift Cards', 'woo-gift-card');
	    }
	}

	return $links;
    }

    /**
     * On initialise
     *
     * @return void
     */
    public function onInitialise() {

	add_rewrite_endpoint('woo-gift-card', EP_PAGES);
    }

    /**
     * Display user gift cards on the front end
     *
     * @return void
     */
    public function show_gift_cards() {

	include_once plugin_dir_path(dirname(__FILE__)) . "public/partials/woo-gift-card-public-display.php";
    }

    /**
     * Called when the customer order has been paid. creates the gift card if it does not already exists.
     *
     * @param int $order_id
     * @return void
     */
    public function payment_complete($order_id) {
	$order = wc_get_order($order_id);

//if already processed skip
	$posts = get_posts(array(
	    'posts_per_page' => 1,
	    'post_type' => 'woo-gift-card',
	    'meta_key' => 'woo-gift-card-order',
	    'meta_value' => $order->id,
	    'fields' => 'ids'
	));

	if (empty($posts)) {

	    foreach ($order->get_items() as $item) {

		$product = $item->get_product();
		if ($product->get_type() == 'woo-gift-card') {

//if gift card create for tracking
		    for ($i = 0; $i < $item->get_quantity(); $i++) {
			$value = get_post_meta($product->id, '_gift_card_value', true);

			$gift_card_value = $value ? $value : $product->get_regular_price();

			wp_insert_post(array(
			    'post_type' => 'woo-gift-card',
			    'post_title' => $product->get_name(),
			    'post_status' => 'publish',
			    'meta_input' => array(
				'woo-gift-card-order' => $order->id,
				'woo-gift-card-product' => $product->id,
				'woo-gift-card-balance' => $gift_card_value,
				'woo-gift-card-value' => $gift_card_value,
				'woo-gift-card-key' => Woo_gift_cards_utils::get_unique_key($order->get_user())
			    )
			));
		    }
		}
	    }
	}
    }

    /**
     * This function outputs the content displayed before the add to cart button
     * @global type $product
     */
    public function woocommerce_before_add_to_cart_button() {
	global $product;

	if ($product->is_type('woo-gift-card')) {
	    include_once plugin_dir_path(__DIR__) . "/public/partials/add-cart-woo-gift-card.php";
	}
    }

    /**
     * This function outputs the content displayed after the add to cart button
     * @global type $product
     */
    public function woocommerce_after_add_to_cart_button() {
	global $product;

	if ($product->is_type('woo-gift-card')) {
	    include_once plugin_dir_path(__DIR__) . "/public/partials/preview-woo-gift-card-button.php";
	}
    }

    /**
     *
     * @param \WC_Cart $cart
     */
    public function woocommerce_before_calculate_totals($cart) {

    }

    /**
     * If product can be purchased depending on the pricing model used
     * @param bool $purchasable
     * @param \WC_Product $product
     * @return bool
     */
    public function woocommerce_is_purchasable($purchasable, $product) {

	if ($product->is_type('woo-gift-card')) {

	    if (is_product()) {
		$purchasable = true;
	    }
	}

	return $purchasable;
    }

    public function woocommerce_get_price_html($display_price, $product) {

	if ($product->is_type('woo-gift-card')) {
	    switch ($product->get_meta("wgc-pricing")) {
		case "range":
		    $from = $product->get_meta('wgc-price-range-from');
		    $to = $product->get_meta('wgc-price-range-to');

		    //if values are the same treat as one value
		    if ($from !== $to) {

			//if not single product page
			if (!is_product()) {
			    $display_price = wc_format_price_range(wc_get_price_to_display($product, array('price' => $from)) . $product->get_price_suffix($from), wc_get_price_to_display($product, array('price' => $to)) . $product->get_price_suffix($to));
			}
			break;
		    }
		//fall through if the prices are the same
		case 'user':

		    //if not single product page
		    if (!is_product()) {

			if (!isset($from)) {
			    $from = $product->get_meta('wgc-price-user');
			}

			$display_price = wc_price(wc_get_price_to_display($product, array('price' => $from))) . $product->get_price_suffix($from);
		    }
		    break;
		case "selected":
		    $display_price = __("Select Price", 'woo-gift-card');
		    break;
		case 'fixed':
		default:
	    }
	}

	return $display_price;
    }

    /**
     * Filter whether product can be displayed in store
     * @param boolean $visible
     * @param int $product_id
     * @return boolean
     */
    public function woocommerce_product_is_visible($visible, $product_id) {
	$product = wc_get_product($product_id);

	if ($product->is_type('woo-gift-card')) {
	    $visible = $product->is_thankyouvoucher() === false;
	}

	return $visible;
    }

    public function woocommerce_add_to_cart() {
	wc_get_template('single-product/add-to-cart/simple.php');
    }

}
