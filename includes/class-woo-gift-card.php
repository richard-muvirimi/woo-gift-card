<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_card {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woo_gift_card_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
	if (defined('WOO_GIFT_CARD_VERSION')) {
	    $this->version = WOO_GIFT_CARD_VERSION;
	} else {
	    $this->version = '1.0.0';
	}
	$this->plugin_name = 'woo-gift-card';

	$this->load_dependencies();
	$this->set_locale();
	$this->define_admin_hooks();
	$this->define_public_hooks();
	$this->define_rest_hooks();
	$this->define_email_hooks();
	$this->define_ajax_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woo_gift_card_Loader. Orchestrates the hooks of the plugin.
     * - Woo_gift_card_i18n. Defines internationalization functionality.
     * - Woo_gift_card_Admin. Defines all hooks for the admin area.
     * - Woo_gift_card_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

	/**
	 * The class responsible for orchestrating the actions and filters of the
	 * core plugin.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-gift-card-loader.php';

	/**
	 * The class responsible for defining internationalization functionality
	 * of the plugin.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-gift-card-i18n.php';

	/**
	 * The class responsible for defining all actions that occur in the admin area.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-gift-card-admin.php';

	/**
	 * The class responsible for defining all actions that occur in the public-facing
	 * side of the site.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woo-gift-card-public.php';

	/**
	 * The class responsible for defining all actions that occur in the rest api
	 * side of the site.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'rest/class-woo-gift-card-rest.php';

	/**
	 * The class responsible for defining all actions that occur in the email
	 * side of the plugin.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'email/class-woo-gift-card-email.php';

	/**
	 * The class responsible for defining all actions that occur in the ajax
	 * side of the plugin.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'ajax/class-woo-gift-card-ajax.php';

	/**
	 * The file responsible for all plugin utility functions.
	 */
	require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utils/functions.php';

	/**
	 * The bar code generating classes
	 */
	if (!class_exists("Picqer\Barcode\BarcodeGenerator")) {
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/php-barcode-generator-master/src/BarcodeGenerator.php';
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/php-barcode-generator-master/src/BarcodeGeneratorSVG.php';
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/php-barcode-generator-master/src/BarcodeGeneratorHTML.php';

	    if (wgc_supports_image_barcode()) {
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/php-barcode-generator-master/src/BarcodeGeneratorPNG.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/php-barcode-generator-master/src/BarcodeGeneratorJPG.php';
	    }
	}

	/**
	 * Qrcode generating classes
	 */
	if (wgc_supports_qrcode() && !class_exists("QRtools")) {
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/phpqrcode/phpqrcode.php';
	}

	/**
	 * Pdf libraries
	 * @filesource https://github.com/dompdf/dompdf/releases
	 */
	if (wgc_supports_pdf_generation() && !class_exists("Dompdf")) {
	    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/dompdf/autoload.inc.php';
	}

	$this->loader = new Woo_gift_card_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woo_gift_card_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

	$plugin_i18n = new Woo_gift_card_i18n();

	$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

	$plugin_admin = new Woo_gift_card_Admin($this->get_plugin_name(), $this->get_version());

	$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
	$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

	//on order completed
	$this->loader->add_action('woocommerce_order_status_completed', $plugin_admin, 'woocommerce_order_status_completed');

	//save cart item data to order
	$this->loader->add_filter('woocommerce_checkout_create_order_line_item', $plugin_admin, 'woocommerce_checkout_create_order_line_item', 10, 4);

	//product type
	$this->loader->add_action('woocommerce_loaded', $plugin_admin, 'woocommerce_loaded');

	//add product type
	$this->loader->add_filter('product_type_selector', $plugin_admin, 'add_product_type');

	//product type tabs
	$this->loader->add_filter('woocommerce_product_data_tabs', $plugin_admin, 'setup_product_data_tabs');

	//woo gift card thank you options
	$this->loader->add_action('woocommerce_product_options_general_product_data', $plugin_admin, 'setup_woo_gift_card_product');

	//product inventory tab
	$this->loader->add_action('woocommerce_product_options_stock', $plugin_admin, 'woocommerce_product_options_stock');

	//save product type
	$this->loader->add_filter('woocommerce_admin_process_product_object', $plugin_admin, 'save_product_object');
	$this->loader->add_filter('woocommerce_process_product_meta_' . $this->plugin_name, $plugin_admin, 'save_woo_gift_card_product');

	//on init
	$this->loader->add_action('init', $plugin_admin, 'init');

	//on save post
	$this->loader->add_action('save_post_woo-gift-card', $plugin_admin, 'save_post');

	//admin notices
	$this->loader->add_action('admin_notices', $plugin_admin, 'show_admin_notice');

	// admin gateways list
	$this->loader->add_filter('manage_woo-gift-card_posts_columns', $plugin_admin, 'add_columns');
	$this->loader->add_action('manage_woo-gift-card_posts_custom_column', $plugin_admin, 'add_column_data', 10, 2);

	//admin menu initialising
	$this->loader->add_action('admin_menu', $plugin_admin, 'on_admin_menu');

	//product type options
	$this->loader->add_filter("product_type_options", $plugin_admin, "product_type_options");

	//excluded products
	$this->loader->add_action("woocommerce_product_options_related", $plugin_admin, "woocommerce_product_options_related");

	//add gift card panel
	$this->loader->add_action("woocommerce_product_data_panels", $plugin_admin, "woocommerce_product_data_panels");

	//template help
	$this->loader->add_action('load-post.php', $plugin_admin, "add_template_help");
	$this->loader->add_action('load-post-new.php', $plugin_admin, "add_template_help");

	//on save gift card template
	$this->loader->add_action('save_post_wgc-template', $plugin_admin, 'save_wgc_template');

	//edit the quick links
	$this->loader->add_filter('post_row_actions', $plugin_admin, 'post_row_actions', 10, 2);

	//filter product meta and tax query
	$this->loader->add_filter('woocommerce_product_query_tax_query', $plugin_admin, 'woocommerce_product_query_tax_query', 10, 2);

	//filter template background image properties
	$this->loader->add_filter('admin_post_thumbnail_html', $plugin_admin, 'admin_post_thumbnail_html', 10, 2);

	//on preview post
	$this->loader->add_filter('the_preview', $plugin_admin, 'preview_post', 10, 2);
    }

    /**
     * Register all of the hooks related to the rest api functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_rest_hooks() {

	$plugin_rest = new Woo_gift_card_Rest($this->get_plugin_name(), $this->get_version());

	//on init the rest api
	$this->loader->add_action('rest_api_init', $plugin_rest, 'register_routes');

	//short codes
	$shortCodes = array_keys(wgc_supported_shortcodes());
	foreach ($shortCodes as $shortCode) {
	    $this->loader->add_shortcode('wgc-' . $shortCode, $plugin_rest, "template_shortcode");
	}

	//filter requested file content
	$this->loader->add_filter("wgc_ajax_template_file", $plugin_rest, "wgc_ajax_template_file", 10, 3);

	//filter for post content images
	$this->loader->add_filter("the_content", $plugin_rest, "the_content");
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

	$plugin_public = new Woo_gift_card_Public($this->get_plugin_name(), $this->get_version());

	$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
	$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

	//on init
	$this->loader->add_action('init', $plugin_public, 'init');

	//my account page items
	$this->loader->add_filter('woocommerce_account_menu_items', $plugin_public, 'filter_account_menu_items');

	//display gift cards
	$this->loader->add_action('woocommerce_account_wgc-vouchers_endpoint', $plugin_public, 'woocommerce_account_endpoint');

	//display before the add to cart button
	$this->loader->add_action('woocommerce_before_add_to_cart_button', $plugin_public, 'woocommerce_before_add_to_cart_button');
	$this->loader->add_action('woocommerce_after_add_to_cart_button', $plugin_public, 'woocommerce_after_add_to_cart_button');

	//if item can be bought
	$this->loader->add_filter('woocommerce_is_purchasable', $plugin_public, 'woocommerce_is_purchasable', 10, 2);

	//filter the product price do display correct values
	$this->loader->add_filter('woocommerce_get_price_html', $plugin_public, 'woocommerce_get_price_html', 10, 2);

	//filter whether product can be displayed in store
	$this->loader->add_filter('woocommerce_product_is_visible', $plugin_public, 'woocommerce_product_is_visible', 10, 2);

	//add customise gift card form
	$this->loader->add_action('woocommerce_' . $this->get_plugin_name() . '_add_to_cart', $plugin_public, 'woocommerce_add_to_cart_html');

	//on calculate cart totals
	$this->loader->add_action('woocommerce_before_calculate_totals', $plugin_public, 'woocommerce_before_calculate_totals');

	//add cart meta data
	$this->loader->add_filter('woocommerce_add_cart_item_data', $plugin_public, 'woocommerce_add_cart_item_data', 10, 4);

	//cart item thumbnail
	$this->loader->add_filter('woocommerce_cart_item_thumbnail', $plugin_public, 'woocommerce_cart_item_thumbnail', 10, 3);

	//cart item name
	$this->loader->add_filter('woocommerce_cart_item_name', $plugin_public, 'woocommerce_cart_item_name', 10, 3);

	//cart item data
	$this->loader->add_filter('woocommerce_get_item_data', $plugin_public, 'woocommerce_get_item_data', 10, 2);

	//customise order meta data
	$this->loader->add_filter('woocommerce_order_item_display_meta_key', $plugin_public, 'woocommerce_order_item_display_meta_key', 10, 3);
	$this->loader->add_filter('woocommerce_order_item_display_meta_value', $plugin_public, 'woocommerce_order_item_display_meta_value', 10, 3);
	$this->loader->add_filter('woocommerce_order_item_get_formatted_meta_data', $plugin_public, 'woocommerce_order_item_get_formatted_meta_data', 10, 2);
    }

    /**
     * Register all of the hooks related to the email functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_email_hooks() {

	$plugin_email = new Woo_gift_card_Email($this->get_plugin_name(), $this->get_version());

	//add our gift voucher balance hook
	$this->loader->add_filter('woocommerce_email_actions', $plugin_email, 'woocommerce_email_actions');

	//register our email class
	$this->loader->add_filter('wgc_coupon_state_notification', $plugin_email, 'wgc_coupon_state_notification');
	$this->loader->add_filter('woocommerce_email_classes', $plugin_email, 'woocommerce_email_classes');
    }

    /**
     * Register all of the hooks related to the ajax functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_ajax_hooks() {

	$plugin_ajax = new Woo_gift_card_Ajax($this->get_plugin_name(), $this->get_version());

	//delete voucher ajax
	$this->loader->add_action('wp_ajax_wgc_send_mail', $plugin_ajax, 'send_mail');

	//send mail ajax
	$this->loader->add_action('wp_ajax_wgc_delete_voucher', $plugin_ajax, 'delete_voucher');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
	$this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
	return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woo_gift_card_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
	return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
	return $this->version;
    }

}
