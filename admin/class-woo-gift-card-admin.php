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
class Woo_gift_card_Admin
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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-gift-card-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-gift-card-admin.js', array('jquery'), $this->version, false);

		if (get_post_type() == 'product') {
			wp_enqueue_script($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'js/woo-gift-card-product.js', array('jquery'), $this->version, false);
		}
	}

	public function add_admin_menu()
	{
		// $function = array($this, 'students_dash_board');
		// $image = \CollegePort\Core::getAssetsUrl('images') . 'favicon.png';
		// $image = 'dashicons-businessman';
		//add_menu_page(__('Woo Gift Card'), __('Woo Gift Card'), "manage_options", $this->plugin_name . "-dashboard", '', "dashicons-money", '100');

		// add_submenu_page(DashBoardSlug, __('About'), __('About'), 'manage_options', 'about', $function);
	}

	/**
	 * require custom product type
	 *
	 * @return void
	 */
	public function woocommerce_loaded()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . "/includes/product/type/gift-card.php";
	}

	/**
	 * insert custom product type
	 *
	 * @param array $types
	 * @return array
	 */
	public function add_product_type($types)
	{

		$types[$this->plugin_name] = __("Woo Gift Card", $this->plugin_name);
		return $types;
	}

	/**
	 * Undocumented function
	 *
	 * @param array $product_data_tabs
	 * @return array
	 */
	public function setup_product_data_tabs($product_data_tabs)
	{

		foreach ($product_data_tabs as $key => $value) {

			if ($key == 'shipping') {
				$product_data_tabs[$key]['class'][] = 'hide_if_' . $this->plugin_name;
			}

			if ($key == 'inventory') {
				$product_data_tabs[$key]['class'][] = 'show_if_' . $this->plugin_name;
			}
		}

		return $product_data_tabs;
	}

	/**
	 * Add woo gift card value text input
	 *
	 * @return void
	 */
	public function setup_woo_gift_card_value()
	{
		global $thepostid;

		woocommerce_wp_text_input(
			array(
				'id'          => '_gift_card_value',
				'wrapper_class' => 'show_if_' . $this->plugin_name,
				'value'       => get_post_meta($thepostid, '_gift_card_value', true),
				'data_type'   => 'price',
				'label'       => __('Gift Card Value', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
				'description' =>  __('The monetary value of the gift card', $this->plugin_name),
			)
		);
	}

	/**
	 * Save woo gift card fields
	 *
	 * @param int $post_id
	 * @return void
	 */
	public function save_woo_gift_card($post_id)
	{

		if (isset($_POST['_gift_card_value'])) {
			update_post_meta($post_id, '_gift_card_value', wc_clean(wp_unslash($_POST['_gift_card_value'])));
		}
	}
}