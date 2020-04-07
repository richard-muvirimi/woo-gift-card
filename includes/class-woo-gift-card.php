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
 * @package    WGC_Main
 * @subpackage WGC_Main/includes
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
 * @package    WGC_Main
 * @subpackage WGC_Main/includes
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_card
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WGC_Main_Loader    $loader    Maintains and registers all hooks for the plugin.
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
    public function __construct()
    {
        if (defined('WOO_GIFT_CARD_VERSION')) {
            $this->version = WOO_GIFT_CARD_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'woo-gift-card';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_about_hooks();
        $this->define_public_hooks();
        $this->define_email_hooks();
        $this->define_ajax_hooks();

        //get main plugin name
        $this->loader->add_filter('wgc-plugin-name', $this, 'get_plugin_name');
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - WGC_Main_Loader. Orchestrates the hooks of the plugin.
     * - WGC_Main_i18n. Defines internationalization functionality.
     * - WGC_Main_Admin. Defines all hooks for the admin area.
     * - WGC_Main_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-woo-gift-card-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-woo-gift-card-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(__DIR__) . 'admin/class-woo-gift-card-admin.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(__DIR__) . 'about/class-woo-gift-card-about.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(__DIR__) . 'public/class-woo-gift-card-public.php';

        /**
         * The class responsible for defining all actions that occur in the email
         * side of the plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'email/class-woo-gift-card-email.php';

        /**
         * The class responsible for defining all actions that occur in the ajax
         * side of the plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'ajax/class-woo-gift-card-ajax.php';

        /**
         * The file responsible for all plugin utility functions.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/utils/functions.php';

        $this->loader = new WGC_Loader();
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
    private function set_locale()
    {

        $plugin_i18n = new WGC_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the about area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_about_hooks()
    {

        $plugin_about = new WGC_About($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_about, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_about, 'enqueue_scripts');

        //admin menu initialising
        $this->loader->add_action('admin_menu', $plugin_about, 'on_admin_menu');

        //admin status tab
        $this->loader->add_action('wgc-tabcontent-system-status', $plugin_about, 'render_status_tab');

        //admin home tab
        $this->loader->add_action('wgc-tabcontent-home', $plugin_about, 'render_home_tab');

        //admin help tab
        $this->loader->add_action('wgc-tabcontent-help', $plugin_about, 'render_help_tab');

        //admin about tab
        $this->loader->add_action('wgc-tabcontent-about', $plugin_about, 'render_about_tab');
    }
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new WGC_Admin($this->get_plugin_name(), $this->get_version());

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

        //save product type
        $this->loader->add_action('woocommerce_admin_process_product_object', $plugin_admin, 'save_product_object');
        $this->loader->add_action('woocommerce_process_product_meta_' . $this->get_plugin_name(), $plugin_admin, 'save_woo_gift_card_product');

        //on init
        $this->loader->add_action('init', $plugin_admin, 'init');

        //on save post
        $this->loader->add_action('save_post_' . $this->get_plugin_name(), $plugin_admin, 'save_post');

        //admin menu initialising
        $this->loader->add_action('admin_menu', $plugin_admin, 'on_admin_menu');

        //product type options
        $this->loader->add_filter("product_type_options", $plugin_admin, "product_type_options");

        //excluded products
        $this->loader->add_action("woocommerce_product_options_related", $plugin_admin, "woocommerce_product_options_related");

        //add gift card panel
        $this->loader->add_action("woocommerce_product_data_panels", $plugin_admin, "woocommerce_product_data_panels");

        //on save gift card template
        $this->loader->add_action('save_post_wgc-template', $plugin_admin, 'save_wgc_template');

        //filter product meta and tax query
        $this->loader->add_filter('woocommerce_product_query_tax_query', $plugin_admin, 'woocommerce_product_query_tax_query', 10, 2);

        //filter product coupon code
        $this->loader->add_filter('wgc-coupon-code', $plugin_admin, 'get_inventory_coupon_code', 1000);

        //Overide product type class
        $this->loader->add_filter('woocommerce_product_class', $plugin_admin, 'woocommerce_product_class', 10, 4);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new WGC_Public($this->get_plugin_name(), $this->get_version());

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

        //if item can be bought
        $this->loader->add_filter('woocommerce_is_purchasable', $plugin_public, 'woocommerce_is_purchasable', 10, 2);

        //add customise gift card form
        $this->loader->add_action('woocommerce_' . $this->get_plugin_name() . '_add_to_cart', $plugin_public, 'woocommerce_add_to_cart_html');

        //add cart meta data
        $this->loader->add_filter('woocommerce_add_cart_item_data', $plugin_public, 'woocommerce_add_cart_item_data', 10, 4);

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
    private function define_email_hooks()
    {

        $plugin_email = new WGC_Email($this->get_plugin_name(), $this->get_version());

        //add custom email trigger hooks
        $this->loader->add_filter('woocommerce_email_actions', $plugin_email, 'woocommerce_email_actions');

        //add custom email classes
        $this->loader->add_filter('woocommerce_email_classes', $plugin_email, 'woocommerce_email_classes');

        //on publish post type
        $this->loader->add_filter('save_post', $plugin_email, 'save_coupon_post', 10, 3);

        //coupon code has been applied
        $this->loader->add_filter('woocommerce_applied_coupon', $plugin_email, 'woocommerce_applied_coupon');
    }

    /**
     * Register all of the hooks related to the ajax functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_ajax_hooks()
    {

        $plugin_ajax = new WGC_Ajax($this->get_plugin_name(), $this->get_version());

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
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woo_gift_card_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
