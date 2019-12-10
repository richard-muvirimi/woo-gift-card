<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/admin
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_card_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

	$this->plugin_name = $plugin_name;
	$this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
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
	switch (get_post_type()) {
	    case 'wgc-template':
		wp_enqueue_style($this->plugin_name . "template", plugin_dir_url(__FILE__) . 'css/woo-gift-card-template.css', array(), $this->version, 'all');
		break;
	    default:
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-gift-card-admin.css', array(), $this->version, 'all');
	}
    }

    /**
     * Register the JavaScript for the admin area.
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
	switch (get_post_type()) {
	    case "product":
		wp_enqueue_script($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'js/woo-gift-card-product.js', array('jquery'), $this->version, false);
		break;
	    default:
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-gift-card-admin.js', array('jquery'), $this->version, false);
	}
    }

    /**
     * Register our gate way to allow customers to purchase with their gift cards
     *
     * @param array $gateways
     * @return void
     */
    public function add_gateway($gateways) {

	/**
	 * The blueprint class of a woo gift card gateway
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'includes/payment/model/woo-gift-card-gateway-blueprint.php';

	$gateways[] = new WC_Woo_Gift_Card_Gateway($id);

	return $gateways;
    }

    /**
     * On initialise register our custom post type woo-gift-card
     *
     * @return void
     */
    public function onInitialise() {

	register_post_type('wgc-product', array(
	    'show_ui' => true,
	    'show_in_menu' => current_user_can('manage_woocommerce') ? 'woocommerce' : true,
	    'exclude_from_search' => true,
	    'hierarchical' => false,
	    'labels' => array(
		'name' => __('Gift Voucher Products', 'woo-gift-card'),
		'singular_name' => __('Gift Voucher Product', 'woo-gift-card'),
		'add_new' => __('Add New', 'woo-gift-card'),
		'add_new_item' => __('Add New Gift Voucher Product', 'woo-gift-card'),
		'edit_item' => __('Edit Gift Voucher Product', 'woo-gift-card'),
		'new_item' => __('New Gift Voucher Product', 'woo-gift-card'),
		'view_item' => __('View Gift Voucher Product', 'woo-gift-card'),
		'search_items' => __('Search Gift Voucher Products', 'woo-gift-card'),
		'not_found' => __('No Gift Voucher Products Found', 'woo-gift-card'),
		'not_found_in_trash' => __('No Gift Voucher Products found in trash', 'woo-gift-card'),
		'parent_item_colon' => __('Parent Gift Voucher Product:', 'woo-gift-card'),
		'all_items' => __('Gift Voucher Products', 'woo-gift-card'),
		'archives' => __('Gift Voucher Product archives', 'woo-gift-card'),
		'insert_into_item' => __('Insert into Gift Voucher Product profile', 'woo-gift-card'),
		'uploaded_to_this_item' => __('Uploaded to Gift Voucher Product profile', 'woo-gift-card'),
		'menu_name' => __('Gift Voucher Products', 'woo-gift-card'),
		'name_admin_bar' => __('Gift Voucher Products', 'woo-gift-card')
	    ),
	    'rewrite' => array('slug' => 'woo-gift-card', 'gift-card'),
	    'supports' => array('title', 'author'),
	    'delete_with_user' => false,
	    'register_meta_box_cb' => array($this, 'register_gift_card_meta_box'),
	    'description' => __('Gift card product type that can be bought by customers.', 'woo-gift-card'),
	));

	register_post_type('wgc-template', array(
	    'show_ui' => true,
	    'show_in_menu' => current_user_can('manage_woocommerce') ? 'wgc-template' : true,
	    'exclude_from_search' => true,
	    'hierarchical' => false,
	    'label' => __('Voucher Templates', 'woo-gift-card'),
	    'labels' => array(
		'name' => __('Gift Voucher Templates', 'woo-gift-card'),
		'singular_name' => __('Gift Voucher Template', 'woo-gift-card'),
		'add_new' => __('Add New', 'woo-gift-card'),
		'add_new_item' => __('Add New Gift Voucher Template', 'woo-gift-card'),
		'edit_item' => __('Edit Gift Voucher Template', 'woo-gift-card'),
		'new_item' => __('New Gift Voucher Template', 'woo-gift-card'),
		'view_item' => __('View Gift Voucher Template', 'woo-gift-card'),
		'search_items' => __('Search Gift Voucher Templates', 'woo-gift-card'),
		'not_found' => __('No Gift Voucher Templates Found', 'woo-gift-card'),
		'not_found_in_trash' => __('No Gift Voucher Templates found in trash', 'woo-gift-card'),
		'parent_item_colon' => __('Parent Gift Voucher Template:', 'woo-gift-card'),
		'all_items' => __('Gift Voucher Templates', 'woo-gift-card'),
		'archives' => __('Gift Voucher Template archives', 'woo-gift-card'),
		'insert_into_item' => __('Insert into Gift Voucher Template profile', 'woo-gift-card'),
		'uploaded_to_this_item' => __('Uploaded to Gift Voucher Template profile', 'woo-gift-card'),
		'menu_name' => __('Gift Voucher Templates', 'woo-gift-card'),
		'name_admin_bar' => __('Gift Voucher Templates', 'woo-gift-card')
	    ),
	    'rewrite' => array('slug' => 'wgc-template', 'gift-card-template'),
	    'supports' => array('title', 'author', 'editor', 'revisions', 'thumbnail'),
	    'delete_with_user' => false,
	    'description' => __('Templates for gift cards that will be sent to customers', 'woo-gift-card'),
	    'register_meta_box_cb' => array($this, 'register_template_meta_box')
	));
    }

    /**
     * On create the admin menu
     *
     * @return void
     */
    public function on_admin_menu() {
	add_menu_page(__('Woo Gift Voucher', 'woo-gift-card'), __('Woo Gift Voucher', 'woo-gift-card'), 'manage_options', 'wgc-template');

	include_once plugin_dir_path(__DIR__) . "/admin/partials/options/class-woo-gift-card-options.php";

	add_submenu_page('wgc-template', __('Options', 'woo-gift-card'), __('Options', 'woo-gift-card'), 'manage_options', 'wgc-options', array($this, 'render_options_page'));
    }

    public function render_options_page() {
	include_once plugin_dir_path(__DIR__) . "/admin/partials/options/woo-gift-card-options.php";
    }

    /**
     * Register our gift card meta box when registering post type to be shown when admin wants to edit a gift card
     *
     * @return void
     */
    public function register_gift_card_meta_box() {
	add_meta_box('wgc-customiser', __('Gift Voucher Options', 'woo-gift-card'), array($this, 'gift_card_meta_box'), 'woo-gift-card', 'normal', 'high');
    }

    /**
     * Register our template meta box when registering post type to be shown when admin wants to edit a gift card template
     *
     * @return void
     */
    public function register_template_meta_box() {
	add_meta_box('wgc-custom-css', __('Gift Voucher Custom Css', 'woo-gift-card'), array($this, 'gift_card_custom_css_meta_box'), 'wgc-template', 'normal', 'high');
    }

    /**
     * Add a meta box that allows the admin to manage a certain gift card
     *
     * @param \WP_Post $post
     * @return void
     */
    public function gift_card_meta_box($post) {

	woocommerce_wp_text_input(
		array(
		    'id' => 'wgc-value',
		    'value' => esc_html(get_post_meta($post->ID, 'wgc-value', true)),
		    'data_type' => 'price',
		    'label' => __('Gift Voucher Value', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
		    'description' => '<br>' . __('The monetary value of the gift voucher ', $this->plugin_name) . '(' . __('Will default to gift voucher value if not set', $this->plugin_name) . ')',
		)
	);

	woocommerce_wp_text_input(
		array(
		    'id' => 'wgc-balance',
		    'value' => esc_html(get_post_meta($post->ID, 'wgc-balance', true)),
		    'data_type' => 'price',
		    'label' => __('Gift Voucher Balance', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
		    'description' => '<br>' . __('The monetary balance of the gift voucher ', $this->plugin_name) . '(' . __('Will default to gift voucher value if not set', $this->plugin_name) . ')',
		)
	);

	woocommerce_wp_text_input(
		array(
		    'id' => 'wgc-key',
		    'value' => esc_html(get_post_meta($post->ID, 'wgc-key', true)),
		    'data_type' => 'text',
		    'label' => __('Gift Voucher Key', $this->plugin_name),
		    'description' => '<br>' . __('The gift voucher unique key ', $this->plugin_name) . '(' . __('A new key will be generated if not set', $this->plugin_name) . ')',
		)
	);
    }

    /**
     * Render template help files
     */
    public function gift_card_help_meta_box() {

	include_once plugin_dir_path(__DIR__) . "/admin/partials/woo-gift-card-admin-template-help.php";
    }

    /**
     * Register template help files
     */
    public function add_template_help() {
	$screen = WP_Screen::get("wgc-template");
	$screen->add_help_tab(array(
	    'title' => __('Gift Voucher Template Help'),
	    'id' => 'wgc-template-help',
	    'content' => '',
	    'callback' => array($this, "gift_card_help_meta_box"),
	    'priority' => 10,
	));
    }

    /**
     *
     * @param type $actions
     * @param \WP_Post $post
     * @return type
     */
    public function post_row_actions($actions, $post) {
	if ($post->post_type == 'wgc-template') {

// $actions["view"] = '<a href="#" class="template-preview">' . __("Preview") . "</a>";
	}

	return $actions;
    }

    /**
     * Add an editor to input template css
     */
    public function gift_card_custom_css_meta_box() {
	global $post_id;

	$content = get_post_meta($post_id, "wgc-template-css", true);

	wp_editor($content, "wgc-template-css", array(
	    'media_buttons' => false,
	    'teeny' => true,
	    'tinymce' => false,
	    'quicktags' => false,
	    'editor_height' => 100));
    }

    /**
     * Show a notice in the admin area
     *
     * @return void
     */
    public function show_admin_notice() {

	if (get_transient('wgc-notice')) {
	    include_once plugin_dir_path(__DIR__) . "/admin/partials/woo-gift-card-admin-notice.php";

	    delete_transient('wgc-notice');
	    delete_transient('wgc-notice-class');
	}
    }

    /**
     * Save our gift card custom fields, will default to product defaults if empty
     *
     * @param int $post_id
     */
    public function save_post($post_id) {

	$value = sanitize_text_field(filter_input(INPUT_POST, 'wgc-value'));
	$balance = sanitize_text_field(filter_input(INPUT_POST, 'wgc-balance'));
	$key = sanitize_text_field(filter_input(INPUT_POST, 'wgc-key'));

	if (empty($value) || empty($balance) || empty($key)) {

	    set_transient('wgc-notice', __('Empty gift voucher values set to defaults'));
	    set_transient('wgc-notice-class', 'notice-info');
	}

	if (empty($value)) {

//Here we get product and set $value to gift card value defaulting to product price if empty
	    $products = wc_get_products(array(
		'posts_per_page' => 1,
		'id' => get_post_meta($post_id, 'wgc-product', true)
	    ));

	    $price = get_post_meta($products[0]->id, 'wgc-value', true);

	    $value = $price ? $price : $products[0]->get_regular_price();
	}

	if (empty($balance)) {

	    $balance = $value;
	}

	if (empty($key)) {

	    $posts = get_posts(array(
		'posts_per_page' => 1,
		'post_type' => 'woo-gift-card',
		'id' => $post_id,
	    ));

//get a unique key for user
	    $key = Woo_gift_cards_utils::get_unique_key(get_user_by('id', $posts[0]['post_author']));
	}

	update_post_meta($post_id, 'wgc-value', sanitize_text_field($value));
	update_post_meta($post_id, 'wgc-balance', sanitize_text_field($balance));
	update_post_meta($post_id, 'wgc-key', sanitize_text_field($key));
    }

    /**
     * require custom product type
     *
     * @return void
     */
    public function woocommerce_loaded() {
	require_once plugin_dir_path(__DIR__) . "/includes/product/type/gift-card.php";
    }

    /**
     * insert custom product type
     *
     * @param array $types
     * @return array
     */
    public function add_product_type($types) {

	if (current_user_can('manage_woocommerce')) {
	    $types[$this->plugin_name] = __("Gift Voucher", $this->plugin_name);
	}

	return $types;
    }

    /**
     * Show and hide unneccessary product type tabs from woocommerce product customisation page
     *
     * @param array $product_data_tabs
     * @return array
     */
    public function setup_product_data_tabs($product_data_tabs) {

	$product_data_tabs['inventory']['class'][] = 'show_if_' . $this->plugin_name;

	$product_data_tabs["woo-gift-card"] = array(
	    'label' => __('Gift Voucher', 'woo-gift-card'),
	    'target' => 'wgc-general',
	    'class' => array('show_if_' . $this->plugin_name),
	    'priority' => 11,
	);

	return $product_data_tabs;
    }

    public function woocommerce_product_data_panels() {
	global $product_object;

	include_once plugin_dir_path(__DIR__) . "/admin/partials/product/product-data-gift-card-html.php";
    }

    /**
     * Add woo gift card value text input that an admin uses to enter gift card value
     *
     * @return void
     */
    public function setup_woo_gift_card_product() {
	global $product_object;
	if (get_option('wgc-thank-you', false)) {

	    include_once plugin_dir_path(__DIR__) . "/admin/partials/product/thank-you-gift-card-html.php";
	}

	include_once plugin_dir_path(__DIR__) . "/admin/partials/product/product-gift-card-html.php";
    }

    public function woocommerce_product_options_related() {
	global $product_object;

	include_once plugin_dir_path(__DIR__) . "/admin/partials/product/product-link-gift-card-html.php";
    }

    /**
     * Clean up if product is no longer our type
     * @param \WC_Product $product
     */
    public function save_product_object($product) {

	if ($product->is_type('woo-gift-card')) {
	    $product->delete_meta_data("wgc-discount");

	    $product->delete_meta_data("wgc-template");
	    $product->delete_meta_data("wgc-sale");
	    $product->delete_meta_data("wgc-multiple");
	    $product->delete_meta_data("wgc-expiry-days");
	    $product->delete_meta_data("wgc-cart-min");
	    $product->delete_meta_data("wgc-cart-max");
	    $product->delete_meta_data("wgc-individual");
	    $product->delete_meta_data("wgc-schedule");

	    $product->delete_meta_data("wgc-coupon-type");

	    //qrcode
	    $product->delete_meta_data('wgc-coupon-qrcode-ecc');
	    $product->delete_meta_data('wgc-coupon-qrcode-size');
	    $product->delete_meta_data('wgc-coupon-qrcode-margin');
	    $product->delete_meta_data('wgc-coupon-qrcode-code');

	    //barcode
	    $product->delete_meta_data('wgc-coupon-barcode-type');
	    $product->delete_meta_data('wgc-coupon-barcode-image-type');
	    $product->delete_meta_data('wgc-coupon-barcode-width');
	    $product->delete_meta_data('wgc-coupon-barcode-height');

	    $product->delete_meta_data("wgc-excluded-product-ids");
	    $product->delete_meta_data("wgc-excluded-product-categories");

//thank you
	    $product->delete_meta_data("_thankyouvoucher");
	}
    }

    /**
     * Save woo gift card custom fields after sanitizing
     *
     * @param int $post_id
     * @return void
     */
    public function save_woo_gift_card_product($post_id) {

	$product = new WC_Product_Woo_Gift_Card($post_id);

//discount
	$product->update_meta_data("wgc-discount", filter_input(INPUT_POST, 'wgc-discount'));

	switch ($product->get_meta('wgc-discount')) {
	    case 'fixed':
		$product->update_meta_data("wgc-discount-fixed", filter_input(INPUT_POST, 'wgc-discount-fixed'));
		$product->delete_meta_data('wgc-discount-percentage');
		break;
	    case 'percentage':
		$product->update_meta_data("wgc-discount-percentage", filter_input(INPUT_POST, 'wgc-discount-percentage'));
		$product->delete_meta_data('wgc-discount-fixed');
		break;
	    default :
		$product->delete_meta_data('wgc-discount-percentage');
		$product->delete_meta_data('wgc-discount-fixed');
	}

//custom options
	$product->update_meta_data("wgc-template", $_POST['wgc-template']);
	$product->update_meta_data("wgc-sale", filter_input(INPUT_POST, 'wgc-sale'));
	$product->update_meta_data("wgc-multiple", filter_input(INPUT_POST, 'wgc-multiple'));
	$product->update_meta_data("wgc-expiry-days", filter_input(INPUT_POST, 'wgc-expiry-days'));
	$product->update_meta_data("wgc-cart-min", filter_input(INPUT_POST, 'wgc-cart-min'));
	$product->update_meta_data("wgc-cart-max", filter_input(INPUT_POST, 'wgc-cart-max'));
	$product->update_meta_data("wgc-individual", filter_input(INPUT_POST, 'wgc-individual'));
	$product->update_meta_data("wgc-schedule", filter_input(INPUT_POST, 'wgc-schedule'));

	//qrcode
	$product->update_meta_data("wgc-coupon-type", filter_input(INPUT_POST, 'wgc-coupon-type'));

	switch ($product->get_meta('wgc-coupon-type')) {
	    case 'qrcode':

		//qrcode ecc level
		$product->update_meta_data("wgc-coupon-qrcode-ecc", filter_input(INPUT_POST, 'wgc-coupon-qrcode-ecc'));

		//qrcode size
		$product->update_meta_data("wgc-coupon-qrcode-size", filter_input(INPUT_POST, 'wgc-coupon-qrcode-size'));

		//qrcode margin
		$product->update_meta_data("wgc-coupon-qrcode-margin", filter_input(INPUT_POST, 'wgc-coupon-qrcode-margin'));

		//qrcode and code
		$product->update_meta_data("wgc-coupon-qrcode-code", filter_input(INPUT_POST, 'wgc-coupon-qrcode-code'));
		break;
	    case 'barcode':
		$product->update_meta_data("wgc-coupon-barcode-type", filter_input(INPUT_POST, 'wgc-coupon-barcode-type'));
		$product->update_meta_data("wgc-coupon-barcode-image-type", filter_input(INPUT_POST, 'wgc-coupon-barcode-image-type'));
		$product->update_meta_data("wgc-coupon-barcode-width", filter_input(INPUT_POST, 'wgc-coupon-barcode-width'));
		$product->update_meta_data("wgc-coupon-barcode-height", filter_input(INPUT_POST, 'wgc-coupon-barcode-height'));
		break;
	}

//linked products
	$product->update_meta_data("wgc-excluded-product-ids", $_POST['wgc-excluded-product-ids']);
	$product->update_meta_data("wgc-excluded-product-categories", $_POST['wgc-excluded-product-categories']);

//thank you
	$product->update_meta_data("_thankyouvoucher", filter_input(INPUT_POST, '_thankyouvoucher'));

//clear prices before setting
	$product->set_date_on_sale_from(null);
	$product->set_date_on_sale_to(null);
	$product->set_regular_price(null);
	$product->set_sale_price(null);

	$product->delete_meta_data('wgc-price-range-from');
	$product->delete_meta_data('wgc-price-range-to');
	$product->delete_meta_data('wgc-price-selected');
	$product->delete_meta_data('wgc-price-user');

	if ($product->is_thankyouvoucher()) {

//thank you validation order status
	    $product->update_meta_data("wgc-thankyou-order-status", filter_input(INPUT_POST, 'wgc-thankyou-order-status'));
	    $product->update_meta_data("wgc-thankyou-orders", filter_input(INPUT_POST, 'wgc-thankyou-orders'));
	    $product->update_meta_data("wgc-thankyou-min-cart", filter_input(INPUT_POST, 'wgc-thankyou-min-cart'));
	    $product->update_meta_data("wgc-thankyou-max-cart", filter_input(INPUT_POST, 'wgc-thankyou-max-cart'));

	    $product->delete_meta_data('wgc-pricing');
	} else {
	    $product->delete_meta_data('wgc-thankyou-order-status');
	    $product->delete_meta_data('wgc-thankyou-orders');
	    $product->delete_meta_data('wgc-thankyou-min-cart');
	    $product->delete_meta_data('wgc-thankyou-max-cart');

//pricing
	    $product->update_meta_data("wgc-pricing", filter_input(INPUT_POST, 'wgc-pricing'));

	    switch ($product->get_meta("wgc-pricing")) {
		case "selected":
		    $product->update_meta_data("wgc-price-selected", filter_input(INPUT_POST, 'wgc-price-selected'));
		    break;
		case "range":
		    $price1 = filter_input(INPUT_POST, 'wgc-price-range-from');
		    $price2 = filter_input(INPUT_POST, 'wgc-price-range-to');

		    $product->update_meta_data("wgc-price-range-from", min(array($price1, $price2)));
		    $product->update_meta_data("wgc-price-range-to", max(array($price1, $price2)));
		    break;
		case 'fixed':
		    $date_on_sale_from = wc_clean(wp_unslash(filter_input(INPUT_POST, 'wgc-sale-price-dates-from')));
		    $date_on_sale_to = wc_clean(wp_unslash(filter_input(INPUT_POST, 'wgc-sale-price-dates-to')));

// Force date from to beginning of day.
		    if ($date_on_sale_from) {
			$product->set_date_on_sale_from(date('Y-m-d 00:00:00', strtotime($date_on_sale_from)));
		    }

// Force date to to the end of the day.
		    if ($date_on_sale_to) {
			$product->set_date_on_sale_to(date('Y-m-d 23:59:59', strtotime($date_on_sale_to)));
		    }

		    $product->set_regular_price(wc_clean(wp_unslash(filter_input(INPUT_POST, 'wgc-price-regular'))));
		    $product->set_sale_price(wc_clean(wp_unslash(filter_input(INPUT_POST, 'wgc-price-sale'))));
		    break;
		case 'user':
		    $product->update_meta_data("wgc-price-user", filter_input(INPUT_POST, 'wgc-price-user'));
		    break;
		default:
	    }
	}

	$product->save();
	$product->save_meta_data();
    }

    /**
     * Save template post meta
     * @param int $post_id
     */
    public function save_wgc_template($post_id) {

	if (current_user_can('manage_woocommerce')) {
	    $css = isset($_POST['wgc-template-css']) ? wc_clean(wp_unslash($_POST['wgc-template-css'])) : "";

	    update_post_meta($post_id, 'wgc-template-css', $css);
	}
    }

    /**
     * out put our data for each custom column added to the woo gift card manage screen
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function add_column_data($column, $post_id) {

	switch ($column) {
	    case 'wgc-value':
	    case 'wgc-balance':
		echo get_woocommerce_currency_symbol() . get_post_meta($post_id, $column, true);
		break;
	}
    }

    public function product_type_options($product_types) {

	$product_types['virtual']['wrapper_class'] .= " show_if_" . $this->plugin_name;
	$product_types['downloadable']['wrapper_class'] .= " show_if_" . $this->plugin_name;

	if (get_option('wgc-thank-you', false)) {
//add custom product option
	    $product_types['thankyouvoucher'] = array(
		'id' => '_thankyouvoucher',
		'wrapper_class' => "show_if_" . $this->plugin_name,
		'label' => __('Thank You', 'woo-gift-card'),
		'description' => __('Thank you gift cards will be sent automatically if customer order qualifies.', 'woo-gift-card'),
		'default' => 'no',
	    );
	}

	return $product_types;
    }

    /**
     * Add our custom column title to the woo gift card manage screen
     *
     * @param array $columns
     * @return void
     */
    public function add_columns($columns) {

	$cols = array();

	foreach ($columns as $key => $item) {
	    $cols[$key] = $item;

	    if ($key === 'author') {
		$cols['wgc-value'] = __('Initial Value', 'woo-gift-card');
		$cols['wgc-balance'] = __('Balance', 'woo-gift-card');
	    }
	}

	$cols['title'] = __('Gift Voucher', 'woo-gift-card');
	$cols['author'] = __('Owner', 'woo-gift-card');

	return $cols;
    }

    /**
     * Filter gift cards if they can be displayed in store
     * @param array $tax_query
     * @param \WC_Query $query_object
     */
    public function woocommerce_product_query_tax_query($tax_query, $query_object) {

	if (get_option("wgc-list-shop") != "on") {
	    $tax_query[] = array(
		'taxonomy' => 'product_type',
		'field' => 'slug',
		'terms' => 'woo-gift-card',
		'operator' => 'NOT IN',
	    );
	}

	return $tax_query;
    }

}
