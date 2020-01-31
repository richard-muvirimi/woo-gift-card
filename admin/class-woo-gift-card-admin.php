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
		wp_enqueue_style($this->plugin_name . "-template", plugin_dir_url(__FILE__) . 'css/woo-gift-card-template.css', array(), $this->version, 'all');
		break;
	    default:
	}

	//if list wgc-templates screen
	switch (get_current_screen()->id) {
	    case "edit-wgc-template":
		wp_enqueue_style($this->plugin_name . "-template-preview", plugin_dir_url(__DIR__) . 'public/css/wgc-pdf-preview.css', array(), $this->version);
		break;
	    case 'toplevel_page_wgc-dashboard':
	    case 'woo-gift-voucher_page_wgc-about':
		wp_enqueue_style($this->plugin_name . "-about", plugin_dir_url(__FILE__) . 'css/woo-gift-card-admin.css', array(), $this->version, 'all');
		break;
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
	    case "wgc-template":
		wp_enqueue_script($this->plugin_name . "-template", plugin_dir_url(__FILE__) . 'js/woo-gift-card-template.js', array('jquery'), $this->version, false);
		break;
	    default:
	}

	//if list wgc-templates screen
	switch (get_current_screen()->id) {
	    case "edit-wgc-template":
		wp_enqueue_script($this->plugin_name . "-template-preview", plugin_dir_url(__FILE__) . 'js/wgc-preview.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name . "-template-preview", 'wgc_product', array(
		    "pdf_template_url" => wgc_preview_link()
		));
		break;
	    case 'toplevel_page_wgc-dashboard':
	    case 'woo-gift-voucher_page_wgc-about':
		wp_enqueue_script($this->plugin_name . "-about", plugin_dir_url(__FILE__) . 'js/woo-gift-card-admin.js', array('jquery'), $this->version, false);
		break;
	}
    }

    /**
     * On initialise register our custom post type woo-gift-card
     *
     * @return void
     */
    public function init() {

	if (wc_coupons_enabled()) {

	    register_post_type('wgc-template', array(
		'show_ui' => true,
		'show_in_menu' => 'wgc-dashboard',
		'exclude_from_search' => true,
		'hierarchical' => false,
		"description" => __("Woo Gift Card Template post type", 'woo-gift-card'),
		'label' => __('Templates', 'woo-gift-card'),
		'labels' => array(
		    'name' => __('Templates', 'woo-gift-card'),
		    'singular_name' => __('Template', 'woo-gift-card'),
		    'add_new' => __('Add New', 'woo-gift-card'),
		    'add_new_item' => __('Add New Template', 'woo-gift-card'),
		    'edit_item' => __('Edit Template', 'woo-gift-card'),
		    'new_item' => __('New Template', 'woo-gift-card'),
		    'view_item' => __('View Template', 'woo-gift-card'),
		    'search_items' => __('Search Templates', 'woo-gift-card'),
		    'not_found' => __('No Templates Found', 'woo-gift-card'),
		    'not_found_in_trash' => __('No Templates found in trash', 'woo-gift-card'),
		    'parent_item_colon' => __('Parent Template:', 'woo-gift-card'),
		    'all_items' => __('Templates', 'woo-gift-card'),
		    'archives' => __('Template archives', 'woo-gift-card'),
		    'insert_into_item' => __('Insert into Template profile', 'woo-gift-card'),
		    'uploaded_to_this_item' => __('Uploaded to Template profile', 'woo-gift-card'),
		    'menu_name' => __('Templates', 'woo-gift-card'),
		    'name_admin_bar' => __('Templates', 'woo-gift-card'),
		    'featured_image' => __('Template Background Image', 'woo-gift-card'),
		    'set_featured_image' => __('Set template background image', 'woo-gift-card'),
		    'remove_featured_image' => __('Remove template background image', 'woo-gift-card'),
		    'use_featured_image' => __('Use as template background image', 'woo-gift-card'),
		),
		'rewrite' => array('slug' => 'wgc-template', 'gift-card-template'),
		'supports' => array('title', 'author', 'editor', 'revisions', 'thumbnail'),
		'delete_with_user' => false,
		'publicly_queryable' => true,
		'description' => __('Templates for gift cards that will be sent to customers', 'woo-gift-card'),
		'register_meta_box_cb' => array($this, 'register_template_meta_box')
	    ));

	    register_taxonomy("wgc-template-dimension", "wgc-template", array(
		'labels' => array(
		    'name' => _x('Template Sizes', 'woo-gift-card'),
		    'singular_name' => __('Template Size', 'woo-gift-card'),
		    'search_items' => __('Search Template Sizes', 'woo-gift-card'),
		    'popular_items' => __('Popular Template Sizes', 'woo-gift-card'),
		    'all_items' => __('All Template Sizes', 'woo-gift-card'),
		    'parent_item' => null,
		    'parent_item_colon' => null,
		    'edit_item' => __('Edit Template Size', 'woo-gift-card'),
		    'view_item' => __('View Template Size', 'woo-gift-card'),
		    'update_item' => __('Update Template Size', 'woo-gift-card'),
		    'add_new_item' => __('Add New Template Size', 'woo-gift-card'),
		    'new_item_name' => __('New Template Size Name', 'woo-gift-card'),
		    'separate_items_with_commas' => null,
		    'add_or_remove_items' => __('Add or remove template sizes', 'woo-gift-card'),
		    'choose_from_most_used' => null,
		    'not_found' => __('No Template Sizes found.', 'woo-gift-card'),
		    'no_terms' => __('No Template Sizes', 'woo-gift-card'),
		    'items_list_navigation' => __('Template Sizes list navigation', 'woo-gift-card'),
		    'items_list' => __('Template Sizes list', 'woo-gift-card'),
		    'most_used' => __('Most Used', 'woo-gift-card'),
		    'back_to_items' => __('&larr; Back to Template Sizes', 'woo-gift-card')
		),
		"description" => __("Woo Gift Card Template sizes", 'woo-gift-card'),
		"public" => false,
		"show_admin_column" => true,
	    ));

	    $sizes = get_terms(array(
		"taxonomy" => "wgc-template-dimension"
	    ));

	    if (empty($sizes)) {
		require_once plugin_dir_path(__DIR__) . 'includes/install/Dimensions.php';
		DimensionsInstaller::Install();
	    }
	}
    }

    /**
     * On create the admin menu
     *
     * @return void
     */
    public function on_admin_menu() {
	add_menu_page(__('Woo Gift Voucher', 'woo-gift-card'), __('Woo Gift Voucher', 'woo-gift-card'), 'manage_woocommerce', 'wgc-dashboard', wc_coupons_enabled() ? "" : array($this, 'render_about_page'), 'dashicons-businessman');

	if (wc_coupons_enabled()) {
	    include_once plugin_dir_path(__DIR__) . "/admin/partials/options/class-wgc-options.php";

	    //options
	    add_submenu_page('wgc-dashboard', __('Options', 'woo-gift-card'), __('Options', 'woo-gift-card'), 'manage_options', 'wgc-options', array($this, 'render_options_page'));

	    //about
	    add_submenu_page('wgc-dashboard', __('About', 'woo-gift-card'), __('About', 'woo-gift-card'), 'manage_options', 'wgc-dashboard', array($this, 'render_about_page'));
	}
    }

    public function render_options_page() {
	include_once plugin_dir_path(__DIR__) . "/admin/partials/options/wgc-options.php";
    }

    public function render_about_page() {
	$plugin = get_plugin_data(plugin_dir_path(__DIR__) . "woo-gift-card.php");

	$data = array(
	    "plugin_name" => $plugin["Name"],
	    "plugin_description" => $plugin["Description"],
	    "plugin_version" => $plugin["Version"],
	    "wp_version" => get_bloginfo("version"),
	    "wp_required_version" => $plugin["RequiresWP"],
	    "wc_version" => function_exists("WC") ? WC()->version : "0",
	    "wc_required_version" => $plugin['WC requires at least'],
	    "php_version" => phpversion(),
	    "php_required_version" => $plugin["RequiresPHP"],
	    "MBString" => extension_loaded("MBString"),
	    "DOM" => extension_loaded("DOM"),
	    "GD" => function_exists('imagecreate'),
	    "Imagick" => extension_loaded('imagick'),
	);

	wc_get_template("wgc-admin-about.php", $data, "", plugin_dir_path(__DIR__) . "/admin/partials/about/");
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
	add_meta_box('wgc-code-options', __('Gift Voucher Code Options', 'woo-gift-card'), array($this, 'gift_card_code_options_meta_box'), 'wgc-template', 'side');
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

	wc_get_template("wgc-template-help.php", array(), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/template/");
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
     * Filter the post row actions and add our own
     *
     * @param type $actions
     * @param \WP_Post $post
     * @return type
     */
    public function post_row_actions($actions, $post) {
	if ($post->post_type == 'wgc-template') {

	    $actions["view"] = '<a href="JavaScript:void()" class="wgc-template-preview" data-template="' . esc_attr($post->ID) . '">' . __("View", "woo-gift-card") . "</a>";
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

    public function gift_card_code_options_meta_box() {
	global $post_id;

	wc_get_template("wgc-code-options.php", compact("post_id"), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/template/");
    }

    /**
     * Show a notice in the admin area
     *
     * @return void
     */
    public function show_admin_notice() {

	//if page is list wgc-templates page
	if (get_current_screen()->id == "edit-wgc-template") {
	    wc_get_template("wgc-admin-preview-html.php", array(), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/preview/");
	}
    }

    /**
     *
     * @param \WP_Post $post
     * @param \WP_Query $query
     */
    public function preview_post($post, $query) {

	if ($query->is_preview) {
	    if (get_post_type($post) == "wgc-template") {

		$query->is_singular = false;
		ob_start();

		wc_get_template("wgc-preview-admin-html.php", compact("post"), "", plugin_dir_path(dirname(__FILE__)) . "public/partials/preview/");

		ob_flush();
		exit();
	    }
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
	    $key = WooGiftCardsUtils::get_unique_key(get_user_by('id', $posts[0]['post_author']));
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
	require_once plugin_dir_path(__DIR__) . "/includes/model/product/gift-card.php";
    }

    /**
     * insert custom product type
     *
     * @param array $types
     * @return array
     */
    public function add_product_type($types) {

	if (wc_coupons_enabled() && current_user_can('manage_woocommerce')) {
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

	if (wc_coupons_enabled()) {
	    $product_data_tabs['inventory']['class'][] = 'show_if_' . $this->plugin_name;

	    $product_data_tabs["woo-gift-card"] = array(
		'label' => __('Gift Voucher', 'woo-gift-card'),
		'target' => 'wgc-general',
		'class' => array('show_if_' . $this->plugin_name),
		'priority' => 11,
	    );
	}

	return $product_data_tabs;
    }

    public function woocommerce_product_data_panels() {

	if (wc_coupons_enabled()) {
	    global $product_object;

	    wc_get_template("wgc-product-coupon-options.php", compact("product_object"), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/product/");
	}
    }

    public function woocommerce_product_options_stock() {
	if (wc_coupons_enabled()) {
	    global $product_object;

	    wc_get_template("wgc-product-inventory-options.php", compact("product_object"), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/product/");
	}
    }

    /**
     * Add woo gift card value text input that an admin uses to enter gift card value
     *
     * @return void
     */
    public function setup_woo_gift_card_product() {

	if (wc_coupons_enabled()) {
	    global $product_object;
	    if (get_option('wgc-thank-you', false)) {

		wc_get_template("wgc-product-thank-you-options.php", compact("product_object"), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/product/");
	    }

	    wc_get_template("wgc-product-general-options.php", compact("product_object"), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/product/");
	}
    }

    public function woocommerce_product_options_related() {
	if (wc_coupons_enabled()) {
	    global $product_object;

	    wc_get_template("wgc-product-linked-options.php", compact("product_object"), "", plugin_dir_path(dirname(__FILE__)) . "admin/partials/product/");
	}
    }

    /**
     * Clean up if product is no longer our type
     * @param \WC_Product $product
     */
    public function save_product_object($product) {

	if (!$product->is_type('woo-gift-card')) {
	    //pricing
	    $product->delete_meta_data("wgc-pricing");
	    $product->delete_meta_data("wgc-price-selected");
	    $product->delete_meta_data("wgc-price-range-from");
	    $product->delete_meta_data("wgc-price-range-to");
	    $product->delete_meta_data("wgc-price-user");

	    //discount
	    $product->delete_meta_data("wgc-discount");
	    $product->delete_meta_data("wgc-discount-fixed");
	    $product->delete_meta_data("wgc-discount-percentage");

	    //templates
	    $product->delete_meta_data("wgc-template");

	    //restrictions
	    $product->delete_meta_data("wgc-cart-min");
	    $product->delete_meta_data("wgc-cart-max");
	    $product->delete_meta_data("wgc-emails");

	    //options
	    $product->delete_meta_data("wgc-individual");
	    $product->delete_meta_data("wgc-sale");

	    //limits
	    $product->delete_meta_data("wgc-usability");
	    $product->delete_meta_data("wgc-multiple");

	    //misc
	    $product->delete_meta_data("wgc-schedule");
	    $product->delete_meta_data("wgc-expiry-days");

	    //linked products
	    $product->delete_meta_data("wgc-product-ids");
	    $product->delete_meta_data("wgc-excluded-product-ids");
	    $product->delete_meta_data("wgc-product-categories");
	    $product->delete_meta_data("wgc-excluded-product-categories");

	    //thank you
	    $product->delete_meta_data("_thankyouvoucher");
	    $product->delete_meta_data("wgc-thankyou-order-status");
	    $product->delete_meta_data("wgc-thankyou-orders");
	    $product->delete_meta_data("wgc-thankyou-min-cart");
	    $product->delete_meta_data("wgc-thankyou-max-cart");
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

	//thank you
	$product->update_meta_data("_thankyouvoucher", wgc_get_post_var('_thankyouvoucher'));

	//clear prices before setting
	$product->set_date_on_sale_from(null);
	$product->set_date_on_sale_to(null);
	$product->set_regular_price(null);
	$product->set_sale_price(null);

	$delete_meta = array();

	if ($product->is_thankyouvoucher()) {

	    //thank you validation order status
	    $product->update_meta_data("wgc-thankyou-order-status", wgc_get_post_var('wgc-thankyou-order-status'));
	    $product->update_meta_data("wgc-thankyou-orders", wgc_get_post_var('wgc-thankyou-orders'));
	    $product->update_meta_data("wgc-thankyou-min-cart", wgc_get_post_var('wgc-thankyou-min-cart'));
	    $product->update_meta_data("wgc-thankyou-max-cart", wgc_get_post_var('wgc-thankyou-max-cart'));

	    $delete_meta = array("wgc-pricing", 'wgc-price-range-from', 'wgc-price-range-to', 'wgc-price-selected', 'wgc-price-user');
	} else {
	    $delete_meta = array('wgc-thankyou-order-status', 'wgc-thankyou-orders', 'wgc-thankyou-min-cart', 'wgc-thankyou-max-cart');

	    //pricing
	    $product->update_meta_data("wgc-pricing", wgc_get_post_var('wgc-pricing'));

	    switch ($product->get_meta("wgc-pricing")) {
		case "selected":
		    $product->update_meta_data("wgc-price-selected", wgc_get_post_var('wgc-price-selected'));
		    break;
		case "range":
		    $price1 = wgc_get_post_var('wgc-price-range-from');
		    $price2 = wgc_get_post_var('wgc-price-range-to');

		    $product->update_meta_data("wgc-price-range-from", min(array($price1, $price2)));
		    $product->update_meta_data("wgc-price-range-to", max(array($price1, $price2)));
		    break;
		case 'fixed':
		    $date_on_sale_from = wgc_get_post_var('wgc-sale-price-dates-from');
		    $date_on_sale_to = wgc_get_post_var('wgc-sale-price-dates-to');

		    // Force date from to beginning of day.
		    if ($date_on_sale_from) {
			$product->set_date_on_sale_from(date('Y-m-d 00:00:00', strtotime($date_on_sale_from)));
		    }

		    // Force date to to the end of the day.
		    if ($date_on_sale_to) {
			$product->set_date_on_sale_to(date('Y-m-d 23:59:59', strtotime($date_on_sale_to)));
		    }

		    $product->set_regular_price(wgc_get_post_var('wgc-price-regular'));
		    $product->set_sale_price(wgc_get_post_var('wgc-price-sale'));
		    break;
		case 'user':
		    $product->update_meta_data("wgc-price-user", wgc_get_post_var('wgc-price-user'));
		    break;
		default:
	    }
	}

	//delete unnecessary meta data
	array_walk($delete_meta, array($product, "delete_meta_data"));

	//discount
	$product->update_meta_data("wgc-discount", wgc_get_post_var('wgc-discount'));

	if (strpos($product->get_meta('wgc-discount'), "fixed") !== false) {
	    $product->update_meta_data("wgc-discount-fixed", wgc_get_post_var('wgc-discount-fixed'));
	    $product->delete_meta_data('wgc-discount-percentage');
	} else {
	    $product->update_meta_data("wgc-discount-percentage", wgc_get_post_var('wgc-discount-percentage'));
	    $product->delete_meta_data('wgc-discount-fixed');
	}

	//templates
	$product->update_meta_data("wgc-template", wgc_get_post_var('wgc-template'));

	//restrictions
	$product->update_meta_data("wgc-cart-min", wgc_get_post_var('wgc-cart-min'));
	$product->update_meta_data("wgc-cart-max", wgc_get_post_var('wgc-cart-max'));
	$product->update_meta_data("wgc-emails", wgc_get_post_var('wgc-emails'));

	//options
	$product->update_meta_data("wgc-individual", wgc_get_post_var('wgc-individual'));
	$product->update_meta_data("wgc-sale", wgc_get_post_var('wgc-sale'));

	//limits
	$product->update_meta_data("wgc-usability", wgc_get_post_var('wgc-usability'));
	$product->update_meta_data("wgc-multiple", wgc_get_post_var('wgc-multiple'));

	//misc
	$product->update_meta_data("wgc-schedule", wgc_get_post_var('wgc-schedule'));
	$product->update_meta_data("wgc-expiry-days", wgc_get_post_var('wgc-expiry-days'));

	//linked products
	$product->update_meta_data("wgc-products", wgc_get_post_var('wgc-products'));
	$product->update_meta_data("wgc-excluded-products", wgc_get_post_var('wgc-excluded-products'));
	$product->update_meta_data("wgc-product-categories", wgc_get_post_var('wgc-product-categories'));
	$product->update_meta_data("wgc-excluded-product-categories", wgc_get_post_var('wgc-excluded-product-categories'));

	$product->save();
	$product->save_meta_data();
    }

    /**
     * Save template post meta
     * @param int $post_id
     */
    public function save_wgc_template($post_id) {

	if (current_user_can('manage_woocommerce')) {

	    update_post_meta($post_id, "wgc-template-css", wgc_get_post_var("wgc-template-css") ?: "");
	    update_post_meta($post_id, "wgc-template-orientation", wgc_get_post_var("wgc-template-orientation") ?: "landscape");

	    $term = get_term_by("slug", wgc_get_post_var("wgc-template-dimension") ?: "a4", "wgc-template-dimension")->slug;

	    wp_set_post_terms($post_id, $term, "wgc-template-dimension");

	    //gift voucher code meta
	    update_post_meta($post_id, "wgc-coupon-type", wgc_get_post_var('wgc-coupon-type'));

	    switch (get_post_meta($post_id, 'wgc-coupon-type', true)) {
		case 'qrcode':
		    $this->deleteTemplateBarCodeMeta($post_id);

		    //qrcode ecc level
		    update_post_meta($post_id, "wgc-coupon-qrcode-ecc", wgc_get_post_var('wgc-coupon-qrcode-ecc'));

		    //qrcode size
		    update_post_meta($post_id, "wgc-coupon-qrcode-size", wgc_get_post_var('wgc-coupon-qrcode-size'));

		    //qrcode margin
		    update_post_meta($post_id, "wgc-coupon-qrcode-margin", wgc_get_post_var('wgc-coupon-qrcode-margin'));

		    //qrcode and code
		    update_post_meta($post_id, "wgc-coupon-qrcode-code", wgc_get_post_var('wgc-coupon-qrcode-code'));
		    break;
		case 'barcode':
		    $this->deleteTemplateQrCodeMeta($post_id);

		    update_post_meta($post_id, "wgc-coupon-barcode-type", wgc_get_post_var('wgc-coupon-barcode-type'));
		    update_post_meta($post_id, "wgc-coupon-barcode-image-type", wgc_get_post_var('wgc-coupon-barcode-image-type'));
		    update_post_meta($post_id, "wgc-coupon-barcode-width", wgc_get_post_var('wgc-coupon-barcode-width'));
		    update_post_meta($post_id, "wgc-coupon-barcode-height", wgc_get_post_var('wgc-coupon-barcode-height'));
		    update_post_meta($post_id, "wgc-coupon-barcode-color", wgc_get_post_var('wgc-coupon-barcode-color'));
		    break;
		default :
		    $this->deleteTemplateBarCodeMeta($post_id);
		    $this->deleteTemplateQrCodeMeta($post_id);
	    }
	}
    }

    /**
     * Delete template post meta for qrcode
     * @param int $post_id
     */
    private function deleteTemplateQrCodeMeta($post_id) {
	delete_post_meta($post_id, 'wgc-coupon-qrcode-ecc');
	delete_post_meta($post_id, 'wgc-coupon-qrcode-size');
	delete_post_meta($post_id, 'wgc-coupon-qrcode-margin');
	delete_post_meta($post_id, 'wgc-coupon-qrcode-code');
    }

    /**
     * Delete template post meta for barcode
     * @param int $post_id
     */
    private function deleteTemplateBarCodeMeta($post_id) {
	delete_post_meta($post_id, 'wgc-coupon-barcode-type');
	delete_post_meta($post_id, 'wgc-coupon-barcode-image-type');
	delete_post_meta($post_id, 'wgc-coupon-barcode-width');
	delete_post_meta($post_id, 'wgc-coupon-barcode-height');
	delete_post_meta($post_id, 'wgc-coupon-barcode-color');
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

    /**
     * Add is thank you gift voucher product type to woocommerce product edit screen
     *
     * @param array $product_types
     * @return array
     */
    public function product_type_options($product_types) {
	if (wc_coupons_enabled()) {

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

	if (get_option("wgc-list-shop") != "on" || !wc_coupons_enabled()) {
	    //hide product if not enabled or cannot be listed in shop
	    $tax_query[] = array(
		'taxonomy' => 'product_type',
		'field' => 'slug',
		'terms' => 'woo-gift-card',
		'operator' => 'NOT IN',
	    );
	}

	return $tax_query;
    }

    /**
     * Add page sizes content at the end of template background metabox
     *
     * @param string $content
     * @param int $post_id
     */
    public function admin_post_thumbnail_html($content, $post_id) {
	if (get_post_type($post_id) == "wgc-template") {

	    //template sizes
	    $content .= "<p>";
	    $content .= '<label for="wgc-template-dimension">' . __("Template Size", "woo-gift-card") . '</label>';
	    $content .= '<select size="5" name="wgc-template-dimension" id="wgc-template-dimension">';

	    //get all terms for templates
	    $dimensions = get_terms(array(
		"taxonomy" => "wgc-template-dimension",
		"hide_empty" => false,
		"orderby" => "term_id"
	    ));

	    //get selected term for post
	    $post_dimension = wp_get_post_terms($post_id, "wgc-template-dimension");

	    $orientation = get_post_meta($post_id, "wgc-template-orientation", true);

	    //get most popular term to set as default
	    $terms = get_terms(array(
		"taxonomy" => "wgc-template-dimension",
		"orderby" => "count",
		"include" => get_term_by("slug", "a4", "wgc-template-dimension")->term_id,
		"number" => 1,
		"hide_empty" => false
	    ));

	    foreach ($dimensions as $dimension) {
		$title = $dimension->name . " (";
		$meta = get_term_meta($dimension->term_id);

		$val1 = $meta["wgc-dimension-value1"][0];
		$val2 = $meta["wgc-dimension-value2"][0];

		if ($val1 && $val2) {
		    if ($orientation === "landscape") {
			$title .= max(array($val1, $val2)) . " * " . min(array($val1, $val2));
		    } else {
			$title .= min(array($val1, $val2)) . " * " . max(array($val1, $val2));
		    }
		    $title .= " " . $meta["wgc-dimension-unit"][0];
		} else {
		    $title .= __("From Image", "woo-gift-card");
		}
		$title .= ")";

		$content .= '<option value="' . esc_attr($dimension->slug) . '" ' . selected($dimension->slug, $post_dimension[0]->slug ?: $terms[0]->slug, false) . '>' . esc_html($title) . '</option>';
	    }
	    $content .= '</select></p>';

	    //image orientation
	    $content .= "<p>";
	    $content .= '<select name="wgc-template-orientation" id="wgc-template-orientation">';

	    $orientations = array("landscape", "potrait");
	    foreach ($orientations as $orientation) {
		$content .= '<option value="' . esc_attr($orientation) . '" ' . selected($orientation, get_post_meta($post_id, "wgc-template-orientation", true) ?: $orientation[0], false) . '>' . esc_html(ucfirst($orientation)) . '</option>';
	    }
	    $content .= '</select></p>';
	}
	return $content;
    }

}
