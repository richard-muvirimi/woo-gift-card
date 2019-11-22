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

	add_submenu_page('wgc-template', __('Options', 'woo-gift-card'), __('Options', 'woo-gift-card'), 'manage_options', 'woo-gift-card-options', array($this, 'render_options_page'));
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
	add_meta_box('woo-gift-card-customiser', __('Gift Voucher Options', 'woo-gift-card'), array($this, 'gift_card_meta_box'), 'woo-gift-card', 'normal', 'high');
    }

    /**
     * Register our template meta box when registering post type to be shown when admin wants to edit a gift card template
     *
     * @return void
     */
    public function register_template_meta_box() {
	add_meta_box('woo-gift-card-custom-css', __('Gift Voucher Custom Css', 'woo-gift-card'), array($this, 'gift_card_custom_css_meta_box'), 'wgc-template', 'normal', 'high');
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
		    'id' => 'woo-gift-card-value',
		    'value' => esc_html(get_post_meta($post->ID, 'woo-gift-card-value', true)),
		    'data_type' => 'price',
		    'label' => __('Gift Voucher Value', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
		    'description' => '<br>' . __('The monetary value of the gift voucher ', $this->plugin_name) . '(' . __('Will default to gift voucher value if not set', $this->plugin_name) . ')',
		)
	);

	woocommerce_wp_text_input(
		array(
		    'id' => 'woo-gift-card-balance',
		    'value' => esc_html(get_post_meta($post->ID, 'woo-gift-card-balance', true)),
		    'data_type' => 'price',
		    'label' => __('Gift Voucher Balance', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
		    'description' => '<br>' . __('The monetary balance of the gift voucher ', $this->plugin_name) . '(' . __('Will default to gift voucher value if not set', $this->plugin_name) . ')',
		)
	);

	woocommerce_wp_text_input(
		array(
		    'id' => 'woo-gift-card-key',
		    'value' => esc_html(get_post_meta($post->ID, 'woo-gift-card-key', true)),
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

	if (get_transient('woo-gift-card-notice')) {
	    include_once plugin_dir_path(__DIR__) . "/admin/partials/woo-gift-card-admin-notice.php";

	    delete_transient('woo-gift-card-notice');
	    delete_transient('woo-gift-card-notice-class');
	}
    }

    /**
     * Save our gift card custom fields, will default to product defaults if empty
     *
     * @param int $post_id
     */
    public function save_post($post_id) {

	$value = sanitize_text_field(filter_input(INPUT_POST, 'woo-gift-card-value'));
	$balance = sanitize_text_field(filter_input(INPUT_POST, 'woo-gift-card-balance'));
	$key = sanitize_text_field(filter_input(INPUT_POST, 'woo-gift-card-key'));

	if (empty($value) || empty($balance) || empty($key)) {

	    set_transient('woo-gift-card-notice', __('Empty gift voucher values set to defaults'));
	    set_transient('woo-gift-card-notice-class', 'notice-info');
	}

	if (empty($value)) {

//Here we get product and set $value to gift card value defaulting to product price if empty
	    $products = wc_get_products(array(
		'posts_per_page' => 1,
		'id' => get_post_meta($post_id, 'woo-gift-card-product', true)
	    ));

	    $price = get_post_meta($products[0]->id, 'woo-gift-card-value', true);

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

	update_post_meta($post_id, 'woo-gift-card-value', sanitize_text_field($value));
	update_post_meta($post_id, 'woo-gift-card-balance', sanitize_text_field($balance));
	update_post_meta($post_id, 'woo-gift-card-key', sanitize_text_field($key));
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

	foreach ($product_data_tabs as $key => $product_data_tab) {

	    if ($key === 'inventory') {
		$product_data_tabs[$key]['class'][] = 'show_if_' . $this->plugin_name;
	    }
	}

	$product_data_tabs["woo-gift-card"] = array(
	    'label' => __('Gift Voucher', 'woocommerce'),
	    'target' => 'woo-gift-card',
	    'class' => array('show_if_' . $this->plugin_name),
	    'priority' => 11,
	);

	return $product_data_tabs;
    }

    public function woocommerce_product_data_panels() {
	global $thepostid, $product_object;

	include_once plugin_dir_path(__DIR__) . "/admin/partials/html-product-data-gift-card.php";
    }

    /**
     * Add woo gift card value text input that an admin uses to enter gift card value
     *
     * @return void
     */
    public function setup_woo_gift_card_product() {
	global $thepostid, $product_object;

	include_once plugin_dir_path(__DIR__) . "/admin/partials/woo-gift-card-admin-product.php";
    }

    public function woocommerce_product_options_related() {
	global $thepostid;

	include_once plugin_dir_path(__DIR__) . "/admin/partials/woo-gift-card-admin-product-link.php";
    }

    /**
     * Save woo gift card custom fields after sanitizing
     *
     * @param int $post_id
     * @return void
     */
    public function save_woo_gift_card_product($post_id) {

//pricing
	$type = isset($_POST['woo-gift-card-pricing']) ? wc_clean(wp_unslash($_POST['woo-gift-card-pricing'])) : "fixed";

	switch ($type) {
	    case "selected":
		$selected_price = isset($_POST['woo-gift-card-selected']) ? wc_clean(wp_unslash($_POST['woo-gift-card-selected'])) : null;
		update_post_meta($post_id, 'woo-gift-card-selected', $selected_price);
		update_post_meta($post_id, 'woo-gift-card-range-from', null);
		update_post_meta($post_id, 'woo-gift-card-range-to', null);
		break;
	    case "range":
		$from_price = isset($_POST['woo-gift-card-range-from']) ? wc_clean(wp_unslash($_POST['woo-gift-card-range-from'])) : null;
		$to_price = isset($_POST['woo-gift-card-range-to']) ? wc_clean(wp_unslash($_POST['woo-gift-card-range-to'])) : null;

		update_post_meta($post_id, 'woo-gift-card-range-from', $from_price);
		update_post_meta($post_id, 'woo-gift-card-range-to', $to_price);
		update_post_meta($post_id, 'woo-gift-card-selected', null);
		break;
	    default:
		update_post_meta($post_id, 'woo-gift-card-selected', null);
		update_post_meta($post_id, 'woo-gift-card-range-from', null);
		update_post_meta($post_id, 'woo-gift-card-range-to', null);
	}

	update_post_meta($post_id, 'woo-gift-card-pricing', $type);

//discount
	$discount_min = isset($_POST['woo-gift-card-discount-min']) ? wc_clean(wp_unslash($_POST['woo-gift-card-discount-min'])) : null;
	update_post_meta($post_id, 'woo-gift-card-discount-min', $discount_min);

	$discount_max = isset($_POST['woo-gift-card-discount-max']) ? wc_clean(wp_unslash($_POST['woo-gift-card-discount-max'])) : null;
	update_post_meta($post_id, 'woo-gift-card-discount-max', $discount_max);

	$discount_type = isset($_POST['woo-gift-card-discount']) ? wc_clean(wp_unslash($_POST['woo-gift-card-discount'])) : 'fixed';

	switch ($discount_type) {
	    case 'fixed':
		$discount_fixed = isset($_POST['woo-gift-card-discount-fixed']) ? wc_clean(wp_unslash($_POST['woo-gift-card-discount-fixed'])) : null;
		update_post_meta($post_id, 'woo-gift-card-discount-fixed', $discount_fixed);
		update_post_meta($post_id, 'woo-gift-card-discount-percentage', null);
		break;
	    case 'percentage':
		$discount_percentage = isset($_POST['woo-gift-card-discount-percentage']) ? wc_clean(wp_unslash($_POST['woo-gift-card-discount-percentage'])) : null;
		update_post_meta($post_id, 'woo-gift-card-discount-percentage', $discount_percentage);
		update_post_meta($post_id, 'woo-gift-card-discount-fixed', null);
		break;
	    default :
		update_post_meta($post_id, 'woo-gift-card-discount-percentage', null);
		update_post_meta($post_id, 'woo-gift-card-discount-fixed', null);
	}

	update_post_meta($post_id, 'woo-gift-card-discount', $discount_type);

//custom options
	$template = isset($_POST['woo-gift-card-template']) ? wc_clean(wp_unslash($_POST['woo-gift-card-template'])) : null;
	update_post_meta($post_id, 'woo-gift-card-template', $template);

	$sale = isset($_POST['woo-gift-card-sale']) ? wc_clean(wp_unslash($_POST['woo-gift-card-sale'])) : "no";
	update_post_meta($post_id, 'woo-gift-card-sale', $sale);

	$multiple = isset($_POST['woo-gift-card-multiple']) ? wc_clean(wp_unslash($_POST['woo-gift-card-multiple'])) : 'no';
	update_post_meta($post_id, 'woo-gift-card-multiple', $multiple);

	$days = isset($_POST['woo-gift-card-expiry-days']) ? wc_clean(wp_unslash($_POST['woo-gift-card-expiry-days'])) : null;
	update_post_meta($post_id, 'woo-gift-card-expiry-days', $days);

	$cart_min = isset($_POST['woo-gift-card-cart-min']) ? wc_clean(wp_unslash($_POST['woo-gift-card-cart-min'])) : null;
	update_post_meta($post_id, 'woo-gift-card-cart-min', $cart_min);

	$cart_max = isset($_POST['woo-gift-card-cart-max']) ? wc_clean(wp_unslash($_POST['woo-gift-card-cart-max'])) : null;
	update_post_meta($post_id, 'woo-gift-card-cart-max', $cart_max);

	$individual = isset($_POST['woo-gift-card-individual']) ? wc_clean(wp_unslash($_POST['woo-gift-card-individual'])) : 'yes';
	update_post_meta($post_id, 'woo-gift-card-individual', $individual);

	$schedule = isset($_POST['woo-gift-card-schedule']) ? wc_clean(wp_unslash($_POST['woo-gift-card-schedule'])) : 'no';
	update_post_meta($post_id, 'woo-gift-card-schedule', $schedule);

//linked products
	$excluded_products = isset($_POST['excluded_product_ids']) ? wc_clean(wp_unslash($_POST['excluded_product_ids'])) : array();
	update_post_meta($post_id, 'excluded_product_ids', $excluded_products);

	$excluded_categories = isset($_POST['excluded_product_categories']) ? wc_clean(wp_unslash($_POST['excluded_product_categories'])) : array();
	update_post_meta($post_id, 'excluded_product_categories', $excluded_categories);
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
     *
     * @param WC_Product_Woo_Gift_Card $product
     */
    public function save_woo_gift_card_product_object($product) {

	if ($product->is_type('woo-gift-card')) {

	    $type = isset($_POST['woo-gift-card-pricing']) ? wc_clean(wp_unslash($_POST['woo-gift-card-pricing'])) : "fixed";

	    if ($type == "fixed") {
// Handle dates.
		$date_on_sale_from = '';
		$date_on_sale_to = '';

// Force date from to beginning of day.
		if (isset($_POST['_woo-gift-card_sale_price_dates_from'])) {
		    $date_on_sale_from = wc_clean(wp_unslash($_POST['_woo-gift-card_sale_price_dates_from']));

		    if (!empty($date_on_sale_from)) {
			$date_on_sale_from = date('Y-m-d 00:00:00', strtotime($date_on_sale_from));
		    }
		}

// Force date to to the end of the day.
		if (isset($_POST['_woo-gift-card_sale_price_dates_to'])) {
		    $date_on_sale_to = wc_clean(wp_unslash($_POST['_woo-gift-card_sale_price_dates_to']));

		    if (!empty($date_on_sale_to)) {
			$date_on_sale_to = date('Y-m-d 23:59:59', strtotime($date_on_sale_to));
		    }
		}

		$product->set_date_on_sale_from($date_on_sale_from);
		$product->set_date_on_sale_to($date_on_sale_to);

		$regular_price = isset($_POST['_woo-gift-card-regular-price']) ? wc_clean(wp_unslash($_POST['_woo-gift-card-regular-price'])) : null;
		$sale_price = isset($_POST['_woo-gift-card-sale-price']) ? wc_clean(wp_unslash($_POST['_woo-gift-card-sale-price'])) : null;

		$product->set_regular_price($regular_price);
		$product->set_sale_price($sale_price);
	    } else {
		$product->set_date_on_sale_from(null);
		$product->set_date_on_sale_to(null);

		$product->set_regular_price(null);
		$product->set_sale_price(null);
	    }
	}
	return $product;
    }

    /**
     * out put our data for each custom culumn added to the woo gift card manage screen
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function add_column_data($column, $post_id) {

	switch ($column) {
	    case 'woo-gift-card-value':
	    case 'woo-gift-card-balance':
		echo get_woocommerce_currency_symbol() . get_post_meta($post_id, $column, true);
		break;
	}
    }

    public function product_type_options($product_types) {

	foreach ($product_types as $key => $item) {
	    $product_types[$key]['wrapper_class'] .= " show_if_" . $this->plugin_name;
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
		$cols['woo-gift-card-value'] = __('Initial Value', 'woo-gift-card');
		$cols['woo-gift-card-balance'] = __('Balance', 'woo-gift-card');
	    }
	}

	$cols['title'] = __('Gift Voucher', 'woo-gift-card');
	$cols['author'] = __('Owner', 'woo-gift-card');

	return $cols;
    }

}
