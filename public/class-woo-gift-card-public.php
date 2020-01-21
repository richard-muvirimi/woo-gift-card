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
	wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-gift-card-public.css', array(), $this->version);

	if (is_product()) {

	    global $post;

	    $product = wc_get_product($post);

	    if ($product->is_type('woo-gift-card')) {
		wp_enqueue_style($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'css/wgc-product.css', array(), $this->version);
		wp_enqueue_style($this->plugin_name . "-preview", plugin_dir_url(__FILE__) . 'css/wgc-pdf-preview.css', array(), $this->version);
	    }
	}
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
		wp_enqueue_script($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'js/wgc-product.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name . "-product", 'wgc_product', array(
		    "maxlength" => get_option('wgc-message-length'),
		    "pdf_template_url" => get_rest_url(null, "woo-gift-card/v1/template/")));
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
				'woo-gift-card-key' => WooGiftCardsUtils::get_unique_key($order->get_user())
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

	    if ($product->get_meta("wgc-pricing") != 'fixed' && $product->is_virtual() && !empty($product->get_meta('wgc-template'))) {
		include_once plugin_dir_path(__DIR__) . "/public/partials/preview/preview-woo-gift-card-html.php";
		include_once plugin_dir_path(__DIR__) . "/public/partials/preview-woo-gift-card-button.php";
	    }
	}
    }

    /**
     * Set totals as appropriate
     *
     * @param \WC_Cart $cart
     */
    public function woocommerce_before_calculate_totals($cart) {

	foreach ($cart->cart_contents as $cart_item) {
	    $cart_item["data"]->set_price($cart_item['wgc-receiver-price']);
	}
    }

    /**
     * If product can be purchased depending on the pricing model used
     * @param bool $purchasable
     * @param \WC_Product $product
     * @return bool
     */
    public function woocommerce_is_purchasable($purchasable, $product) {

	if ($product->is_type('woo-gift-card')) {

	    //if we are not on the shop page to allow customisation first
	    if (!is_shop()) {
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

    public function woocommerce_add_to_cart_html() {
	wc_get_template('single-product/add-to-cart/simple.php');
    }

    public function woocommerce_add_cart_item_data($cart_item_data, $product_id, $variation_id, $quantity) {

	$product = wc_get_product($product_id);
	if ($product->is_type('woo-gift-card')) {

	    //pricing
	    $price = wgc_get_post_var('wgc-receiver-price');

	    switch ($product->get_meta("wgc-pricing")) {
		case "range":
		    //normalise price into range
		    $price = min(array($product->get_meta('wgc-price-range-to'), $price));
		    $price = max(array($product->get_meta('wgc-price-range-from'), $price));
		    break;
		case "selected":
		    //use price from customer
		    if (!in_array($price, explode("|", $product->get_meta('wgc-price-selected')))) {
			//get nearest price from prices array

			$prices = array_merge($product->get_meta('wgc-price-selected'), array($price));

			sort($prices, SORT_NUMERIC);

			$prices = array_slice($prices, array_search($price, $prices), 3);

			$max = max(array($prices, $price));
			$min = min(array($prices, $price));

			$price = $max != $price && $max - $price <= $price - $min ? $max : $min;
		    }
		    break;
		case 'user':
		    $price = $price ?: $product->get_meta('wgc-price-user');
		    break;
		case 'fixed':
		default:
		//use price from admin
	    }

	    $cart_item_data['wgc-receiver-price'] = $price;

	    if ($product->is_virtual() && !empty($product->get_meta('wgc-template'))) {
		//receiver name
		$cart_item_data['wgc-receiver-name'] = wgc_get_post_var('wgc-receiver-name') ?: "";

		//receiver email
		$email = wgc_get_post_var('wgc-receiver-email');
		if ($email && $email !== false) {
		    if (is_email($email)) {
			$cart_item_data['wgc-receiver-email'] = $email;
		    } else {
			throw new Exception(__("Please enter a valid recipient email to proceed."));
		    }
		} else {
		    $cart_item_data['wgc-receiver-email'] = get_user_option("user_email");
		}

		//message
		$cart_item_data['wgc-receiver-message'] = substr(wgc_get_post_var('wgc-receiver-message'), 0, get_option("wgc-message-length")) ?: "";

		if (!empty($product->get_meta('wgc-template'))) {
		    //template
		    $template = get_post(wgc_get_post_var('wgc-receiver-template'));
		    if (is_object($template)) {
			$cart_item_data['wgc-receiver-template'] = $template->ID;
		    } else {
			throw new Exception(__("Please enter valid details to proceed."));
		    }

		    //image
		    if (isset($_FILES['wgc-receiver-image']) && $_FILES['wgc-receiver-image']['size']) {
			$file = $_FILES['wgc-receiver-image'];
			$path = $file['tmp_name'];

			$cart_item_data['wgc-receiver-image'] = wgc_path_to_base64($path);
		    } else {
			if (has_post_thumbnail($template)) {
			    $thumbnail_id = get_post_thumbnail_id($template);
			    $url = wp_get_attachment_image_url($thumbnail_id);

			    $cart_item_data['wgc-receiver-image'] = wgc_path_to_base64($url);
			}
		    }

		    //event
		    $cart_item_data['wgc-event'] = wgc_get_post_var("wgc-event") ?: $template->post_title;
		}

		//schedule
		if (!empty($product->get_meta('wgc-schedule'))) {
		    $schedule = wgc_get_post_var('wgc-receiver-schedule');
		    if ($schedule) {
			$cart_item_data['wgc-receiver-schedule'] = $schedule;
		    } else {
			throw new Exception(__("Please enter valid details to proceed."));
		    }
		}
	    }
	}

	return $cart_item_data;
    }

    /**
     * Set cart item image to template image if available
     *
     * @param string $image
     * @param \WC_Cart $cart_item
     * @param string $cart_item_key
     * @return string
     */
    public function woocommerce_cart_item_thumbnail($image, $cart_item, $cart_item_key) {

	if ($cart_item["data"]->is_type('woo-gift-card')) {
	    if (isset($cart_item['wgc-receiver-image'])) {
		$image = wgc_image_html($cart_item['wgc-receiver-image']);
	    }
	}

	return $image;
    }

    /**
     * Set cart item image to template image if available
     *
     * @param string $name
     * @param \WC_Cart $cart_item
     * @param string $cart_item_key
     * @return string
     */
    public function woocommerce_cart_item_name($name, $cart_item, $cart_item_key) {

	$product = $cart_item["data"];
	if ($product->is_type('woo-gift-card') && is_cart() && !empty($product->get_meta('wgc-template'))) {

	    $name .= " (" . get_post_field("post_title", $cart_item["wgc-receiver-template"]) . ")";
	}

	return $name;
    }

    /**
     *
     * @param array $item_data
     * @param \WC_Cart $cart_item
     */
    public function woocommerce_get_item_data($item_data, $cart_item) {

	$product = $cart_item["data"];
	if ($product->is_type('woo-gift-card') && is_cart() && !empty($product->get_meta('wgc-template'))) {
	    $item_data[] = array(
		"key" => __("To", "woo-gift-card"),
		"value" => $cart_item["wgc-receiver-email"]
	    );
	}

	return $item_data;
    }

    /**
     *
     */
    public function woocommerce_after_cart_contents() {

	if (!get_posts(array("post_type" => "wgc-voucher", "post_status" => "published"))) {
	    include_once plugin_dir_path(__DIR__) . "/public/partials/cart/wgc-cart-voucher.php";
	}
    }

}
