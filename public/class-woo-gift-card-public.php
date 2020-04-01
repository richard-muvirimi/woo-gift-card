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
class WGC_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		//wgc-my-account

		if (is_account_page()) {
			wp_enqueue_style($this->plugin_name . "-account", plugin_dir_url(__FILE__) . 'css/wgc-my-account.css', array(), $this->version);
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		if (is_product()) {

			global $post;

			$product = wc_get_product($post);

			if ($product->is_type($this->plugin_name)) {
				wp_enqueue_script($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'js/wgc-product.js', array('jquery'), $this->version, false);
				wp_localize_script($this->plugin_name . "-product", 'wgc_product', array(
					"maxlength" => get_option('wgc-message-length')
				));
			}
		}

		if (is_account_page()) {
			wp_enqueue_script($this->plugin_name . "-account", plugin_dir_url(__FILE__) . 'js/wgc-my-account.js', array('jquery'), $this->version, false);
			wp_localize_script($this->plugin_name . "-account", 'wgc_account', array(
				"ajax_url" => admin_url('admin-ajax.php'),
			));
		}
	}

	/**
	 * On initialise register our custom post type woo-gift-card
	 *
	 * @return void
	 */
	public function init()
	{

		add_rewrite_endpoint('wgc-vouchers', EP_PAGES);
	}

	/**
	 * Add gift card link to my account items
	 *
	 * @param array $items
	 * @return void
	 */
	public function filter_account_menu_items($items)
	{
		$links = array();

		foreach ($items as $key => $item) {
			$links[$key] = $item;

			if ($key === 'downloads') {

				$links['wgc-vouchers'] = __('Gift Vouchers', $this->plugin_name);
			}
		}

		return $links;
	}

	/**
	 * Display user gift cards on the front end
	 *
	 * @return void
	 */
	public function woocommerce_account_endpoint()
	{

		wc_get_template("wgc-my-account.php", array("plugin_name" => $this->plugin_name), "", plugin_dir_path(__DIR__) . "public/partials/");
	}

	/**
	 * This function outputs the content displayed before the add to cart button
	 * @global WGC_Product $product
	 */
	public function woocommerce_before_add_to_cart_button()
	{
		global $product;

		if ($product->is_type($this->plugin_name)) {
			wc_get_template("wgc-add-to-cart.php", array_merge(compact("product"), array("plugin_name" => $this->plugin_name)), "", plugin_dir_path(__DIR__) . "public/partials/");
		}
	}

	/**
	 * If product can be purchased depending on the pricing model used
	 * @param bool $purchasable
	 * @param \WGC_Product $product
	 * @return bool
	 */
	public function woocommerce_is_purchasable($purchasable, $product)
	{

		if ($product->is_type($this->plugin_name)) {

			//if we are not on the shop page to allow customisation first
			if (!is_shop()) {
				$purchasable = true;
			}
		}

		return $purchasable;
	}

	/**
	 * Output the simple product add to cart area.
	 */
	public function woocommerce_add_to_cart_html()
	{
		woocommerce_simple_add_to_cart();
	}

	/**
	 * Save coupon product custom user options
	 *
	 * @param type $cart_item_data
	 * @param type $product_id
	 * @param type $variation_id
	 * @param type $quantity
	 * @return type
	 * @throws Exception
	 */
	public function woocommerce_add_cart_item_data($cart_item_data, $product_id, $variation_id, $quantity)
	{

		$product = new WGC_Product($product_id);
		if ($product->is_type($this->plugin_name)) {

			$options = array();

			//receiver email
			$emails = wgc_get_post_var('wgc-receiver-email');
			if ($emails && $emails !== false) {

				//split emails by space or comma
				$emails = array_unique(wp_parse_list($emails));

				//verify emails are valid
				if (!empty($emails)) {
					foreach ($emails as $email) {
						if (!is_email($email)) {
							throw new Exception(sprintf(__("(%s) is not a valid email address."), $email));
						}
					}
				} else {
					throw new Exception(__("Please enter a valid email address to proceed."));
				}

				$options['wgc-receiver-email'] = $emails;
			} else {
				$options['wgc-receiver-email'] = array(get_user_option("user_email"));
			}

			//message
			$options['wgc-receiver-message'] = substr(wgc_get_post_var('wgc-receiver-message'), 0, get_option("wgc-message-length")) ?: "";

			//schedule
			if ($product->get_coupon_schedule() == "yes") {
				$schedule = wgc_get_post_var('wgc-receiver-schedule');
				if ($schedule  && $schedule !== false) {
					$options['wgc-receiver-schedule'] = $schedule;
				}
			}

			$cart_item_data["wgc-options"] = array_merge($options, isset($cart_item_data["wgc-options"]) ? $cart_item_data["wgc-options"] : array());
		}

		return $cart_item_data;
	}

	/**
	 * Format coupon user options
	 *
	 * @param array $item_data
	 * @param \WC_Cart $cart_item
	 */
	public function woocommerce_get_item_data($item_data, $cart_item)
	{
		$product = $cart_item["data"];
		if ($product->is_type($this->plugin_name) && is_cart()) {

			$emails = $cart_item["wgc-options"]["wgc-receiver-email"];

			$item_data[] = array(
				"key" => __("Send To", $this->plugin_name),
				"value" => $emails ? implode(", ", $emails) : get_user_option("user_email")
			);
		}

		return $item_data;
	}

	/**
	 * Filter order meta titles
	 *
	 * @param string $display_key
	 * @param \WC_Order_Item_Meta $meta
	 * @param \WC_Order_Item_Product $order_item
	 * @return string
	 */
	public function woocommerce_order_item_display_meta_key($display_key, $meta, WC_Order_Item_Product $order_item)
	{

		$product = $order_item->get_product();
		if ($product->is_type($this->plugin_name)) {

			switch ($meta->key) {
				case "wgc-receiver-email":
					$display_key = __("Receiver Email", $this->plugin_name);
					break;
				case "wgc-receiver-schedule":
					$display_key = __("Scheduled for", $this->plugin_name);
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
	 * @param \WC_Order_Item_Product $order_item
	 * @return string
	 */
	public function woocommerce_order_item_display_meta_value($display_value, $meta, WC_Order_Item_Product $order_item)
	{

		$product = $order_item->get_product();
		if ($product->is_type($this->plugin_name)) {

			switch ($meta->key) {
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
	 * @param \WC_Order_Item_Product $order_item
	 * @return array
	 */
	public function woocommerce_order_item_get_formatted_meta_data($formatted_meta, WC_Order_Item_Product $order_item)
	{

		$product = $order_item->get_product();
		if ($product->is_type($this->plugin_name)) {

			$formatted_meta = array_filter($formatted_meta, function ($meta) {
				switch ($meta->key) {
					case 'wgc-receiver-email':
						//hide if same with logged in user
						if ($meta->value == get_user_option("user_email")) {
							return false;
						}
						break;
					case "wgc-receiver-schedule":
						//if less than a day or past do not show scheduled
						return strtotime($meta->value) - time() > 60 * 60 * 24;
				}
				return true;
			});
		}

		return $formatted_meta;
	}
}
