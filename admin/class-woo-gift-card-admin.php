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

	/**
	 * Register our gate way to allow customers to purchase with their gift cards
	 *
	 * @param array $gateways
	 * @return void
	 */
	public function add_gateway($gateways)
	{

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
	public function onInitialise()
	{
		register_post_type('woo-gift-card', array(
			'show_ui' => true,
			'show_in_menu' => current_user_can('manage_woocommerce') ? 'woocommerce' : true,
			'exclude_from_search' => true,
			// 'map_meta_cap' => true,
			'hierarchical' => false,
			'labels' => array(
				'name' => __('Gift Vouchers', 'woo-gift-card'),
				'singular_name' => __('Gift Voucher', 'woo-gift-card'),
				'add_new' => __('Add New', 'woo-gift-card'),
				'add_new_item' => __('Add New Gift Voucher', 'woo-gift-card'),
				'edit_item' => __('Edit Gift Voucher', 'woo-gift-card'),
				'new_item' => __('New Gift Voucher', 'woo-gift-card'),
				'view_item' => __('View Gift Voucher', 'woo-gift-card'),
				'search_items' => __('Search Gift Vouchers', 'woo-gift-card'),
				'not_found' => __('No Gift Vouchers Found', 'woo-gift-card'),
				'not_found_in_trash' => __('No Gift Vouchers found in trash', 'woo-gift-card'),
				'parent_item_colon' => __('Parent Gift Voucher:', 'woo-gift-card'),
				'all_items' => __('Gift Vouchers', 'woo-gift-card'),
				'archives' => __('Gift Voucher archives', 'woo-gift-card'),
				'insert_into_item' => __('Insert into Gift Voucher profile', 'woo-gift-card'),
				'uploaded_to_this_item' => __('Uploaded to Gift Voucher profile', 'woo-gift-card'),
				'menu_name' => __('Gift Vouchers', 'woo-gift-card'),
				'name_admin_bar' => __('Gift Vouchers', 'woo-gift-card')
			),
			'rewrite' => array('slug' => 'woo-gift-card', 'gift-card'),
			'supports' => array('title', 'author'),
			'delete_with_user' => false,
			'register_meta_box_cb' => array($this, 'register_meta_box')
		));
	}

	/**
	 * Register our meta box when registering post type to be shown when admin wants to edit a gift card
	 *
	 * @return void
	 */
	public function register_meta_box()
	{
		add_meta_box('woo-gift-card-customiser', __('Gift Voucher Options', 'woo-gift-card'), array($this, 'gift_card_meta_box'), 'woo-gift-card', 'normal', 'high');
	}

	/**
	 * Add a meta box that allows the admin to manage a certain gift card
	 *
	 * @param \WP_Post $post
	 * @return void
	 */
	public function gift_card_meta_box($post)
	{

		woocommerce_wp_text_input(
			array(
				'id'          => 'woo-gift-card-value',
				'value'       => esc_html(get_post_meta($post->ID, 'woo-gift-card-value', true)),
				'data_type'   => 'price',
				'label'       => __('Gift Voucher Value', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
				'description' =>  '<br>' . __('The monetary value of the gift voucher ', $this->plugin_name) . '(' . __('Will default to gift voucher value if not set', $this->plugin_name) . ')',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => 'woo-gift-card-balance',
				'value'       => esc_html(get_post_meta($post->ID, 'woo-gift-card-balance', true)),
				'data_type'   => 'price',
				'label'       => __('Gift Voucher Balance', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
				'description' =>  '<br>' . __('The monetary balance of the gift voucher ', $this->plugin_name) . '(' . __('Will default to gift voucher value if not set', $this->plugin_name) . ')',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => 'woo-gift-card-key',
				'value'       => esc_html(get_post_meta($post->ID, 'woo-gift-card-key', true)),
				'data_type'   => 'text',
				'label'       => __('Gift Voucher Key', $this->plugin_name),
				'description' =>  '<br>' . __('The gift voucher unique key ', $this->plugin_name) . '(' . __('A new key will be generated if not set', $this->plugin_name) . ')',
			)
		);
	}

	/**
	 * Show a notice in the admin area
	 *
	 * @return void
	 */
	public function show_admin_notice()
	{

		if (get_transient('woo-gift-card-notice')) {
			include_once plugin_dir_path(dirname(__FILE__)) . "/admin/partials/woo-gift-card-admin-notice.php";

			delete_transient('woo-gift-card-notice');
			delete_transient('woo-gift-card-notice-class');
		}
	}

	/**
	 * Save our gift card custom fields, will default to product defaults if empty
	 * 
	 * @param int $post_id
	 */
	public function save_post($post_id)
	{

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

			$price = get_post_meta($products[0]->id, '_gift_card_value', true);

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

		$types[$this->plugin_name] = __("Gift Voucher", $this->plugin_name);
		return $types;
	}

	/**
	 * Show and hide unneccessary product type tabs from woocommerce product customisation page
	 *
	 * @param array $product_data_tabs
	 * @return array
	 */
	public function setup_product_data_tabs($product_data_tabs)
	{

		foreach ($product_data_tabs as $key => $value) {

			if ($key === 'shipping') {
				$product_data_tabs[$key]['class'][] = 'hide_if_' . $this->plugin_name;
			}

			if ($key === 'inventory') {
				$product_data_tabs[$key]['class'][] = 'show_if_' . $this->plugin_name;
			}
		}

		return $product_data_tabs;
	}

	/**
	 * Add woo gift card value text input that an admin uses to enter gift card value
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
				'value'       => esc_html(get_post_meta($thepostid, '_gift_card_value', true)),
				'data_type'   => 'price',
				'label'       => __('Gift Voucher Value', $this->plugin_name) . ' (' . get_woocommerce_currency_symbol() . ')',
				'description' =>  __('The monetary value of the gift card', $this->plugin_name),
			)
		);
	}

	/**
	 * Save woo gift card custom fields
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

	/**
	 * out put our data for each custom culumn added to the woo gift card manage screen
	 *
	 * @param string $column
	 * @param int $post_id
	 * @return void
	 */
	public function add_column_data($column, $post_id)
	{

		switch ($column) {
			case 'woo-gift-card-value':
			case 'woo-gift-card-balance':
				echo get_woocommerce_currency_symbol() .  get_post_meta($post_id, $column, true);
				break;
		}
	}

	/**
	 * Add our custom column title to the woo gift card manage screen
	 *
	 * @param array $columns
	 * @return void
	 */
	public function add_columns($columns)
	{

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