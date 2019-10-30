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
class Woo_gift_card
{

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
		$this->define_public_hooks();
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
	private function load_dependencies()
	{

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
		 * The class responsible for all plugin utility methods.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utils/Utils.php';

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
	private function set_locale()
	{

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
	private function define_admin_hooks()
	{

		$plugin_admin = new Woo_gift_card_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		//product type
		$this->loader->add_action('woocommerce_loaded', $plugin_admin, 'woocommerce_loaded');

		//add product type
		$this->loader->add_filter('product_type_selector', $plugin_admin, 'add_product_type');

		//product type tabs
		$this->loader->add_filter('woocommerce_product_data_tabs', $plugin_admin, 'setup_product_data_tabs');

		//woo gift card value
		$this->loader->add_action('woocommerce_product_options_pricing', $plugin_admin, 'setup_woo_gift_card_value');

		//save product type
		$this->loader->add_action('woocommerce_process_product_meta_' . $this->plugin_name, $plugin_admin, 'save_woo_gift_card_value');

		//on init
		$this->loader->add_action('init', $plugin_admin, 'onInitialise');

		//add payment gateways
		$this->loader->add_filter('woocommerce_payment_gateways', $plugin_admin, 'add_gateway');

		//on save post
		$this->loader->add_filter('save_post_woo-gift-card', $plugin_admin, 'save_post');

		//admin notices
		$this->loader->add_action('admin_notices', $plugin_admin, 'show_admin_notice');

		// admin gateways list
		$this->loader->add_filter('manage_woo-gift-card_posts_columns', $plugin_admin, 'add_columns');
		$this->loader->add_action('manage_woo-gift-card_posts_custom_column', $plugin_admin, 'add_column_data', 10, 2);
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

		$plugin_public = new Woo_gift_card_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		//on order completed
		$this->loader->add_action('woocommerce_order_status_completed', $plugin_public, 'payment_complete');

		//my account page items
		$this->loader->add_filter('woocommerce_account_menu_items', $plugin_public, 'filter_account_menu_items');

		//on init
		$this->loader->add_action('init', $plugin_public, 'onInitialise');

		//display gift cards
		$this->loader->add_action('woocommerce_account_woo-gift-card_endpoint', $plugin_public, 'show_gift_cards');
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