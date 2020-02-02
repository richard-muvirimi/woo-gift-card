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
	if (is_product()) {

	    global $post;

	    $product = wc_get_product($post);

	    if ($product->is_type('woo-gift-card')) {
		wp_enqueue_style($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'css/wgc-product.css', array(), $this->version);
		wp_enqueue_style($this->plugin_name . "-preview", plugin_dir_url(__FILE__) . 'css/wgc-pdf-preview.css', array(), $this->version);
	    }
	}

	//wgc-my-account

	if (is_account_page()) {
	    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wgc-my-account.css', array(), $this->version);
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
	if (is_product()) {

	    global $post;

	    $product = wc_get_product($post);

	    if ($product->is_type('woo-gift-card')) {
		wp_enqueue_script($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'js/wgc-product.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name . "-product", 'wgc_product', array(
		    "maxlength" => get_option('wgc-message-length'),
		    "pdf_template_url" => get_rest_url(null, $this->plugin_name . "/v1/template/preview/")));
	    }
	}

	if (is_account_page()) {
	    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wgc-my-account.js', array('jquery'), $this->version, false);
	}
    }

    /**
     * On initialise register our custom post type woo-gift-card
     *
     * @return void
     */
    public function init() {

	add_rewrite_endpoint('wgc-vouchers', EP_PAGES);
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

		$links['wgc-vouchers'] = __('Gift Vouchers', 'woo-gift-card');
	    }
	}

	return $links;
    }

    /**
     * Display user gift cards on the front end
     *
     * @return void
     */
    public function woocommerce_account_endpoint() {

	wc_get_template("wgc-my-account.php", array("plugin_name" => $this->plugin_name), "", plugin_dir_path(__DIR__) . "public/partials/");
    }

    /**
     * This function outputs the content displayed before the add to cart button
     * @global WC_Product_Woo_Gift_Card $product
     */
    public function woocommerce_before_add_to_cart_button() {
	global $product;

	if ($product->is_type('woo-gift-card')) {
	    wc_get_template("wgc-add-to-cart.php", array(), "", plugin_dir_path(dirname(__FILE__)) . "public/partials/");
	}
    }

    /**
     * This function outputs the content displayed after the add to cart button
     * @global WC_Product_Woo_Gift_Card $product
     */
    public function woocommerce_after_add_to_cart_button() {
	global $product;

	if ($product->is_type('woo-gift-card')) {

	    if ($product->get_meta("wgc-pricing") != 'fixed' && $product->is_virtual() && !empty($product->get_meta('wgc-template'))) {
		wc_get_template("wgc-preview-html.php", array(), "", plugin_dir_path(dirname(__FILE__)) . "public/partials/preview/");
		wc_get_template("wgc-preview-button.php", compact("product"), "", plugin_dir_path(dirname(__FILE__)) . "public/partials/preview/");
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
	    $product = $cart_item["data"];
	    if ($product->is_type('woo-gift-card') && isset($cart_item['wgc-receiver-price'])) {
		$product->set_price($cart_item['wgc-receiver-price']);
	    }
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

	    //schedule
	    if ($product->get_meta('wgc-schedule') == "yes") {
		$schedule = wgc_get_post_var('wgc-receiver-schedule');
		if ($schedule) {
		    $cart_item_data['wgc-receiver-schedule'] = $schedule;
		} else {
		    throw new Exception(__("Please enter valid details to proceed."));
		}
	    }

	    if ($product->is_virtual() && !empty($product->get_meta('wgc-template'))) {
		//receiver name
		$cart_item_data['wgc-receiver-name'] = wgc_get_post_var('wgc-receiver-name') ?: "";

		//template
		$template = get_post(wgc_get_post_var('wgc-receiver-template'));
		if (is_object($template)) {
		    $cart_item_data['wgc-receiver-template'] = $template->ID;
		} else {
		    throw new Exception(__("Please enter valid details to proceed."));
		}

		//event
		$cart_item_data['wgc-event'] = wgc_get_post_var("wgc-event") ?: $template->post_title;

		//image
		if (isset($_FILES['wgc-receiver-image']) && $_FILES['wgc-receiver-image']['size']) {
		    $file = $_FILES['wgc-receiver-image'];
		    $path = $file['tmp_name'];

		    $cart_item_data['wgc-receiver-image'] = wgc_path_to_base64($path);
		} else {
		    if (has_post_thumbnail($template)) {
			$thumbnail_id = get_post_thumbnail_id($template);
			$url = wp_get_attachment_image_url($thumbnail_id, "full");

			//todo maybe disable this to allow customised admin images to reflect to customer
			$cart_item_data['wgc-receiver-image'] = wgc_path_to_base64($url);
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
     * Set cart item name to template name if available
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
     * Filter order meta titles
     *
     * @param string $display_key
     * @param \WC_Order_Item_Meta $meta
     * @param \WC_Order_Item $order_item
     * @return string
     */
    public function woocommerce_order_item_display_meta_key($display_key, $meta, $order_item) {

	$product = $order_item->get_product();
	if ($product->is_type('woo-gift-card')) {

	    switch ($meta->key) {
		case "wgc-receiver-price":
		    $display_key = __("Pricing", "woo-gift-card");
		    break;
		case "wgc-receiver-name":
		    $display_key = __("Receiver Name", "woo-gift-card");
		    break;
		case "wgc-receiver-email":
		    $display_key = __("Receiver Email", "woo-gift-card");
		    break;
		case "wgc-event":
		    $display_key = __("Event", "woo-gift-card");
		    break;
		case "wgc-receiver-schedule":
		    $display_key = __("Scheduled", "woo-gift-card");
		    break;
	    }
	}

	return $display_key;
    }

    /**
     * Filter order meta values
     *
     * @param string $display_value
     * @param \WC_Order_Item_Meta $meta
     * @param \WC_Order_Item $order_item
     * @return string
     */
    public function woocommerce_order_item_display_meta_value($display_value, $meta, $order_item) {

	$product = $order_item->get_product();
	if ($product->is_type('woo-gift-card')) {

	    switch ($meta->key) {
		case "wgc-receiver-price":
		    $display_value = wgc_get_pricing_types()[$product->get_meta('wgc-pricing')];
		    break;
		case "wgc-receiver-schedule":
		    $date = new WC_DateTime();
		    $date->setTimestamp(strtotime($display_value));

		    $display_value = wc_format_datetime($date);
		    break;
	    }
	}

	return $display_value;
    }

    /**
     * Filter the items displayed as order meta data
     *
     * @param array|\WC_Order_Item_Meta $formatted_meta
     * @param \WC_Order_Item $order_item
     * @return array
     */
    public function woocommerce_order_item_get_formatted_meta_data($formatted_meta, $order_item) {

	$product = $order_item->get_product();
	if ($product->is_type('woo-gift-card')) {

	    $formatted_meta = array_filter($formatted_meta, function ($meta) {
		switch ($meta->key) {
		    case 'wgc-receiver-template':
		    case 'wgc-receiver-image':
			return false;
		    case 'wgc-receiver-name':
			//hide if same with logged in user
			if ($meta->value == get_user_option("display_name")) {
			    return false;
			}
			break;
		    case 'wgc-receiver-email':
			//hide if same with logged in user
			if ($meta->value == get_user_option("user_email")) {
			    return false;
			}
			break;
		    case "wgc-receiver-schedule":
			//if less than a day do not show scheduled
			return abs(strtotime($meta->value) - time()) > 60 * 60 * 24;
		}
		return true;
	    });
	}

	return $formatted_meta;
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
