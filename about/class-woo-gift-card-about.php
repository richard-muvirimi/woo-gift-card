<?php

/**
 * The about-specific functionality of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/about
 */

/**
 * The about-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the about-specific stylesheet and JavaScript.
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/about
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class WGC_About
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the about area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		//if list wgc-templates screen
		switch (get_current_screen()->id) {
			case 'toplevel_page_wgc-dashboard':
			case 'woo-gift-voucher_page_wgc-about':
				wp_enqueue_style($this->plugin_name . "-about", plugin_dir_url(__FILE__) . 'css/wgc-about.css', array(), $this->version, 'all');
				break;
		}
	}

	/**
	 * Register the JavaScript for the about area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		//if list wgc-templates screen
		switch (get_current_screen()->id) {
			case 'toplevel_page_wgc-dashboard':
			case 'woo-gift-voucher_page_wgc-about':
				wp_enqueue_script($this->plugin_name . "-about", plugin_dir_url(__FILE__) . 'js/wgc-about.js', array('jquery'), $this->version, false);
				break;
		}
	}

	/**
	 * On create the about menu
	 *
	 * @return void
	 */
	public function on_admin_menu()
	{
		add_menu_page(__('Woo Gift Voucher', $this->plugin_name), __('Woo Gift Voucher', $this->plugin_name), 'manage_woocommerce', 'wgc-dashboard',  array($this, 'render_plugin_page'), 'dashicons-businessman');

		if (wc_coupons_enabled()) {
			include_once plugin_dir_path(__DIR__) . "/about/partials/options/class-wgc-options.php";

			//options
			add_submenu_page('wgc-dashboard', __('Options', $this->plugin_name), __('Options', $this->plugin_name), 'manage_options', 'wgc-options', array($this, 'render_options_page'));
		}
	}

	public function render_options_page()
	{
		include_once plugin_dir_path(__DIR__) . "/about/partials/options/wgc-options.php";
	}

	public function render_plugin_page()
	{
		$plugin = get_plugin_data(plugin_dir_path(__DIR__) . $this->plugin_name . ".php");

		$data = array(
			"plugin_official_name" => $plugin["Name"],
			"plugin_name" => $this->plugin_name,
			"plugin_description" => $plugin["Description"],
			"plugin_version" => $plugin["Version"],
		);

		wc_get_template("wgc-about.php", $data, "", plugin_dir_path(__DIR__) . "/about/partials/admin/");
	}

	/**
	 * Render the plugins status tab, where users can view if their environment supports the plugin
	 */
	public function	render_status_tab()
	{
		$plugin = get_plugin_data(plugin_dir_path(__DIR__) . $this->plugin_name . ".php");

		$data = array(
			"plugin_name" => $this->plugin_name,
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

		wc_get_template("wgc-about-status.php", $data, "", plugin_dir_path(__DIR__) . "about/partials/admin/");
	}

	/**
	 * Render the plugins home tab, which summaries the purpose of the plugin
	 */
	public function render_home_tab()
	{

		wc_get_template("wgc-about-home.php", array(), "", plugin_dir_path(__DIR__) . "/about/partials/admin/");
	}

	/**
	 * Render the plugins help tab, which shows help info for using the plugin
	 */
	public function render_help_tab()
	{

		wc_get_template("wgc-about-help.php", array(), "", plugin_dir_path(__DIR__) . "/about/partials/admin/");
	}

	/**
	 * Render the plugins about tab, which sshows mainly contact info
	 */
	public function render_about_tab()
	{

		wc_get_template("wgc-about-about.php", array(), "", plugin_dir_path(__DIR__) . "/about/partials/admin/");
	}
}
