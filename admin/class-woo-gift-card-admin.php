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
class WGC_Admin
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


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{


		switch (get_post_type()) {
			case "product":
				wp_enqueue_script($this->plugin_name . "-product", plugin_dir_url(__FILE__) . 'js/wgc-product.js', array('jquery'), $this->version, false);
				break;
			default:
		}

	}

	/**
	 * On initialise register our custom post type woo-gift-card
	 *
	 * @return void
	 */
	public function init()
	{

		if (wc_coupons_enabled()) {

			do_action("wgc-init");
		}
	}

	/**
	 * On create the admin menu
	 *
	 * @return void
	 */
	public function on_admin_menu()
	{
	}



	/**
	 * require custom product type
	 *
	 * @return void
	 */
	public function woocommerce_loaded()
	{

		require_once apply_filters("wgc-model-data", plugin_dir_path(__DIR__) . "/includes/model/wgc-data.php");
		require_once apply_filters("wgc-model-product", plugin_dir_path(__DIR__) . "/includes/model/product.php");
		require_once apply_filters("wgc-model-coupon", plugin_dir_path(__DIR__) . "/includes/model/coupon.php");

		do_action("wgc-loaded");
	}

	/**
	 * insert custom product type
	 *
	 * @param array $types
	 * @return array
	 */
	public function add_product_type($types)
	{

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
	public function setup_product_data_tabs($product_data_tabs)
	{

		if (wc_coupons_enabled()) {

			$product_data_tabs[$this->plugin_name] = array(
				'label' => __('Gift Voucher', $this->plugin_name),
				'target' => 'wgc-general',
				'class' => array('show_if_' . $this->plugin_name),
				'priority' => 11,
			);
		}

		return $product_data_tabs;
	}

	/**
	 * Load coupon product options template
	 *
	 * @global type $product_object
	 */
	public function woocommerce_product_data_panels()
	{

		if (wc_coupons_enabled()) {

			global $product_object;
			$product = new WGC_Product($product_object);

			wc_get_template("wgc-product-coupon-options.php", array_merge(compact("product"), array("plugin_name" => $this->plugin_name)), "", plugin_dir_path(__DIR__) . "admin/partials/product/");
		}
	}

	/**
	 * Load coupon product general template
	 *
	 * @global type $product_object
	 */
	public function setup_woo_gift_card_product()
	{

		if (wc_coupons_enabled()) {

			global $product_object;
			$product = new WGC_Product($product_object);

			wc_get_template("wgc-product-general-options.php", array_merge(compact("product"), array("plugin_name" => $this->plugin_name)), "", plugin_dir_path(__DIR__) . "admin/partials/product/");
		}
	}

	/**
	 * Load coupon product restriction options
	 *
	 * @global type $product_object
	 */
	public function woocommerce_product_options_related()
	{
		if (wc_coupons_enabled()) {

			global $product_object;
			$product = new WGC_Product($product_object);

			wc_get_template("wgc-product-linked-options.php", array_merge(compact("product"), array("plugin_name" => $this->plugin_name)), "", plugin_dir_path(__DIR__) . "admin/partials/product/");
		}
	}

	/**
	 * Clean up if product is no longer our type
	 * @param \WGC_Product $product
	 */
	public function save_product_object($product)
	{

		if (!$product->is_type($this->plugin_name)) {

			//discount
			$product->delete_meta_data("wgc-discount");
			$product->delete_meta_data("wgc-discount-amount");

			//restrictions
			$product->delete_meta_data("wgc-cart-min");
			$product->delete_meta_data("wgc-cart-max");

			//options
			$product->delete_meta_data("wgc-individual");
			$product->delete_meta_data("wgc-sale");
			$product->delete_meta_data("wgc-free-shipping");

			//limits
			$product->delete_meta_data("wgc-limit-usage-to-x-items");
			$product->delete_meta_data("wgc-usage-limit");
			$product->delete_meta_data("wgc-usage-limit-per-user");

			//misc
			$product->delete_meta_data("wgc-schedule");
			$product->delete_meta_data("wgc-expiry-days");

			//linked products
			$product->delete_meta_data("wgc-products");
			$product->delete_meta_data("wgc-excluded-products");
			$product->delete_meta_data("wgc-product-categories");
			$product->delete_meta_data("wgc-excluded-product-categories");
		} else {

			//stock management
			$product->set_manage_stock(false);
			$product->set_stock_status();
		}
	}

	/**
	 * Save woo gift card custom fields after sanitizing
	 *
	 * @param int $post_id
	 * @return void
	 */
	public function save_woo_gift_card_product($post_id)
	{

		$product = new WGC_Product($post_id);

		//discount
		$product->set_coupon_discount(wgc_get_post_var('wgc-discount'));
		$product->set_coupon_discount_amount(wgc_get_post_var('wgc-discount-amount'));

		//restrictions
		$product->set_coupon_cart_min(wgc_get_post_var('wgc-cart-min'));
		$product->set_coupon_cart_max(wgc_get_post_var('wgc-cart-max'));

		//options
		$product->set_coupon_individual(wgc_get_post_var('wgc-individual'));
		$product->set_coupon_sale(wgc_get_post_var('wgc-sale'));
		$product->set_coupon_free_shipping(wgc_get_post_var('wgc-free-shipping'));

		//limits
		$product->set_coupon_limit_usage_to_x_items(wgc_get_post_var('wgc-limit-usage-to-x-items'));
		$product->set_coupon_usage_limit(wgc_get_post_var('wgc-usage-limit'));
		$product->set_coupon_usage_limit_per_user(wgc_get_post_var('wgc-usage-limit-per-user'));

		//misc
		$product->set_coupon_schedule(wgc_get_post_var('wgc-schedule'));
		$product->set_coupon_expiry_days(wgc_get_post_var('wgc-expiry-days'));

		//linked products
		$product->set_coupon_products(wgc_get_post_var('wgc-products'));
		$product->set_coupon_excluded_products(wgc_get_post_var('wgc-excluded-products'));
		$product->set_coupon_product_categories(wgc_get_post_var('wgc-product-categories'));
		$product->set_coupon_excluded_product_categories(wgc_get_post_var('wgc-excluded-product-categories'));

		$product->save_meta_data();
	}

	/**
	 * Add is thank you gift voucher product type to woocommerce product edit screen
	 *
	 * @param array $product_types
	 * @return array
	 */
	public function product_type_options($product_types)
	{
		if (wc_coupons_enabled()) {

			$product_types['virtual']['wrapper_class'] .= " show_if_" . $this->plugin_name;
			$product_types['downloadable']['wrapper_class'] .= " show_if_" . $this->plugin_name;
		}

		return $product_types;
	}

	/**
	 * Filter gift cards if they can be displayed in store
	 * @param array $tax_query
	 * @param \WC_Query $query_object
	 */
	public function woocommerce_product_query_tax_query($tax_query, $query_object)
	{

		if (get_option("wgc-list-shop") != "on" || !wc_coupons_enabled()) {
			//hide product if not enabled or cannot be listed in shop
			$tax_query[] = array(
				'taxonomy' => 'product_type',
				'field' => 'slug',
				'terms' => $this->plugin_name,
				'operator' => 'NOT IN',
			);
		}

		return $tax_query;
	}

	/**
	 * Save cart meta data to order
	 * 
	 * @param \WC_Order_Item_Product $item
	 * @param string $cart_item_key
	 * @param array $values
	 * @param \WC_Order $order
	 */
	public function woocommerce_checkout_create_order_line_item($item, $cart_item_key, $values, $order)
	{

		$product = $item->get_product();

		if ($product->is_type($this->plugin_name)) {

			if (isset($values['wgc-options'])) {
				foreach ($values['wgc-options'] as $key => $value) {
					$item->add_meta_data($key, $value);
				}
			}
		}
	}

	/**
	 * Called when the customer order has been paid. creates the gift card if it does not already exists.
	 *
	 * @param int $order_id
	 * @return void
	 */
	public function woocommerce_order_status_completed($order_id)
	{

		/**
		 * Basically what this function does is iterate through all order product items
		 * A marker is then set on each processed item if successfully processed
		 */

		if (wc_coupons_enabled()) {

			$order = wc_get_order($order_id);

			//if not exists or false then we have not yet successfully processed this order
			if (!$order->get_meta("wgc-processed")) {

				$processed = true;
				foreach ($order->get_items() as $item) {

					$order_item = new WC_Order_Item_Product($item);

					$product = $order_item->get_product();
					if ($product->is_type($this->plugin_name)) {

						if (!$order_item->get_meta("wgc-processed")) {

							//if gift card create for tracking
							$item_processed = true;
							for ($index = 0; $index < $order_item->get_quantity(); $index++) {

								$item_processed &= wgc_product_to_coupon($product, $order_item, $index);
							}

							//update markers
							$order_item->update_meta_data("wgc-processed", $item_processed);
							$processed &= $item_processed;
						}
					}
				}

				$order->update_meta_data("wgc-processed", $processed);
				$order->save_meta_data();
			}
		}
	}

	/**
	 *
	 * @param string $coupon
	 * @return string
	 */
	public function get_inventory_coupon_code($coupon = '')
	{

		if (empty($coupon)) {

			$prefix = get_option("wgc-code-prefix");
			$suffix = get_option("wgc-code-suffix");

			$special = get_option("wgc-code-special") == "on";

			do {
				$coupon = $prefix . wp_generate_password(get_option("wgc-code-length"), $special, $special) . $suffix;
			} while (post_exists($coupon, "", "", "shop_coupon") != 0);
		}

		return $coupon;
	}

	public function get_default_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * Get our custom product class name
	 *
	 * @param string $product_class_name
	 * @param type $product_type
	 * @param type $variation
	 * @param type $product_id
	 * @return string
	 */
	public function woocommerce_product_class($product_class_name, $product_type, $variation, $product_id)
	{

		if ($product_type === $this->plugin_name) {
			$product_class_name = "WGC_Product";
		}
		return $product_class_name;
	}
}
