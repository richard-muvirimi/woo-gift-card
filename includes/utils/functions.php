<?php

defined('ABSPATH') || exit;

function wgc_get_post_var($name)
{

	if (isset($_POST[$name])) {
		$filtered = "";
		if (is_array($_POST[$name])) {
			$filtered = $_POST[$name];
		} else {
			$filtered = trim(filter_input(INPUT_POST, $name));
		}
		return wc_clean(wp_unslash($filtered));
	}
	return false;
}

function wgc_format_coupon_value($coupon_id)
{

	if (strpos(get_post_meta($coupon_id, 'discount_type', true), "fixed") !== false) {
		esc_html_e(get_woocommerce_currency_symbol() . get_post_meta($coupon_id, 'coupon_amount', true));
	} else {
		esc_html_e(get_post_meta($coupon_id, 'coupon_amount', true) . "%");
	}
}

/**
 * get a coupon object from coupon code
 *
 * @param string $which
 * @return \WC_Coupon|false
 */
function wgc_get_coupon($which = "")
{

	$coupons = get_posts(array(
		"posts_per_page" => 1,
		"title" => $which,
		"post_type" => "shop_coupon",
		'post_status' => 'publish',
		'orderby' => 'date',
		'meta_query' => array(
			array(
				'key' => 'wgc-order',
				'compare' => "IN",
				'value' => array_map("\WC_Order_Factory::get_order_id", wc_get_orders(array(
					"numberposts" => -1,
				)))
			),
			array(
				'key' => 'wgc-order-item'
			),
			array(
				'key' => 'wgc-order-item-index'
			),
		)
	));

	return empty($coupons) ? false : new WC_Coupon($coupons[0]->ID);
}

/**
 * Get all customer coupons
 *
 * @return \WC_Coupon|array
 */
function wgc_get_coupons()
{
	return array_map(function ($coupon) {
		return new WC_Coupon($coupon->ID);
	}, get_posts(
		array(
			"numberposts" => -1,
			'post_type' => 'shop_coupon',
			'post_status' => 'publish',
			'orderby' => 'date'
		)
	));
}

/**
 * Get all coupons that can be applied by customer
 *
 * @return array|\WC_Coupon
 */
function wgc_get_coupons_for_customer()
{

	return array_filter(wgc_get_coupons(), function ($coupon) {
		// Limit to defined email addresses.
		$restrictions = $coupon->get_email_restrictions();
		$emails = array(get_user_option("user_email", get_current_user_id()));

		//from \WC_Cart::is_coupon_emails_allowed
		return !(is_array($restrictions) && 0 < count($restrictions) && !WC()->cart->is_coupon_emails_allowed($emails, $restrictions));
	});
}

function wgc_has_coupon($coupon)
{
	return is_a(wgc_get_coupon($coupon), "WC_Coupon");
}

/**
 * Get all users that can use a coupon code
 *
 * @param \WC_Coupon $coupon
 * @return array
 */
function wgc_get_emails_for_coupon($coupon)
{

	$emails = array();

	foreach ($coupon->get_email_restrictions() as $restriction) {
		$user_emails = get_users(
			array(
				'search_columns' => array('user_email'),
				'search' => $restriction,
				'fields' => array('user_email'),
				'count_total' => false
			)
		);

		$emails = array_merge($emails, array_column($user_emails, 'user_email'));
	}

	return array_unique($emails);
}

/**
 **** creates a coupon from a product ***
 * 
 * This method if passed a product creates a coupon
 * 
 * @param \WGC_Product $product
 * @param \WC_Order_Item_Product $order_item
 * @return boolean
 */
function wgc_product_to_coupon(\WGC_Product $product, \WC_Order_Item_Product $order_item = null)
{

	/**
	 * This is basically creating a post but of type coupon
	 */

	$post_date = "";
	if ($product->get_coupon_schedule() == "yes" && is_a($order_item, "WC_Order_Item_Product")) {

		//if coupon can be scheduled and is scheduled we want to schedule it's publication
		$post_date =  date('Y-m-d 00:00:00', strtotime($order_item->get_meta('wgc-receiver-schedule')) ?: time());
	}

	$coupon_id = wp_insert_post(array(
		'post_type' => 'shop_coupon',
		'post_title' => apply_filters("wgc-coupon-code", "", $product),
		'post_status' => 'publish',
		'post_content' => '',
		'post_date' => $post_date,

		//Let's mark our territory by placing plugin name in coupon description
		'post_excerpt' => get_plugin_data(plugin_dir_path(dirname(__DIR__)) .  "woo-gift-card.php")["Name"]
	));

	/**
	 * Now onto the coupon meta data
	 */
	if ($coupon_id == 0) {
		return false;
	} else {
		$coupon = new WC_Coupon($coupon_id);

		//discount
		$coupon->set_discount_type($product->get_coupon_discount());
		$coupon->get_amount($product->get_coupon_discount_amount());

		//restrictions
		$coupon->set_minimum_amount($product->get_coupon_cart_min());
		$coupon->set_maximum_amount($product->get_coupon_cart_max());

		//email restrictions
		if (is_a($order_item, "WC_Order_Item_Product")) {
			$coupon->set_email_restrictions($order_item->get_meta('wgc-receiver-email'));
		}

		//options
		$coupon->set_individual_use($product->get_coupon_individual());
		$coupon->set_exclude_sale_items($product->get_coupon_sale());

		//limits
		$coupon->set_usage_limit($product->get_coupon_usage_limit());
		$coupon->set_limit_usage_to_x_items($product->get_coupon_limit_usage_to_x_items());
		$coupon->set_usage_limit_per_user($product->get_coupon_usage_limit_per_user());

		//misc
		$expiry_days = $product->get_coupon_expiry_days();
		if ($expiry_days) {
			//if can expire then add days depending on scheduled date
			$days =  "+" . $expiry_days . " days";

			$coupon->set_date_expires(strtotime($days, strtotime($post_date ?: "now")));
		}

		//linked products
		$coupon->set_product_ids($product->get_coupon_products());
		$coupon->set_excluded_product_ids($product->get_coupon_excluded_products());
		$coupon->set_product_categories($product->get_coupon_product_categories());
		$coupon->set_excluded_product_categories($product->get_coupon_excluded_product_categories());

		//defaults
		$coupon->set_free_shipping(false);

		do_action("before-wgc-save-coupon", $coupon);

		$coupon->save();
		$coupon->save_meta_data();

		do_action("after-wgc-save-coupon", $coupon);

		return true;
	}
}
