<?php

defined('ABSPATH') || exit;

/**
 * Convert a file to a base 64 string
 *
 * @param string $path
 * @return string
 */
function wgc_path_to_base64($path) {
    $data = file_get_contents($path);

    $mime = wgc_get_mime_type_for_file($path);

    return wgc_content_to_base64($data, $mime);
}

/**
 * Convert data to base64
 *
 * @param string $content
 * @param string $mime
 * @return string
 */
function wgc_content_to_base64($content, $mime) {
    return 'data:' . $mime . ';base64,' . base64_encode($content);
}

function wgc_get_mime_type_for_file($file) {
    $ext = pathinfo(basename($file), PATHINFO_EXTENSION);

    foreach (wp_get_mime_types() as $key => $value) {
	if (in_array($ext, explode("|", $key))) {
	    return $value;
	}
    }

    return wgc_get_mime_type_for_file("file.txt");
}

function wgc_image_html($image, $size = "thumbnail", $class = "", $alt = "") {
    $dimensions = wc_get_image_size($size);

    $image_html = '<img src="' . esc_attr($image) . '" alt="' . esc_attr__($alt) . '" width="' . esc_attr($dimensions['width']) . '" class="' . esc_attr($class) . '" height="' . esc_attr($dimensions['height']) . '" />';

    return $image_html;
}

function wgc_supported_shortcodes() {
    return array(
	"amount" => __("The monetary value of the gift voucher.*", 'woo-gift-card'),
	"code" => __("The code to uniquely identify the gift voucher.", 'woo-gift-card'),
	"disclaimer" => __("Disclaimer message to show to the receipent of the gift voucher.", 'woo-gift-card'),
	"event" => __("What event is this gift voucher for.*", 'woo-gift-card'),
	"expiry-days" => __("The expiry days of the gift voucher", 'woo-gift-card'),
	"from" => __("The sender of the gift voucher", 'woo-gift-card'),
	"logo" => __("This websites logo or name of site", 'woo-gift-card'),
	"message" => __("A message sent by customer to the recipient of the gift voucher", 'woo-gift-card'),
	"order-id" => __("The order id to use as reference of the gift voucher", 'woo-gift-card'),
	"product-name" => __("The product name of this gift voucher", 'woo-gift-card'),
	"to-name" => __("The name of the recipient of the gift voucher*", 'woo-gift-card'),
	"to-email" => __("The email of the recipient of the gift voucher*", 'woo-gift-card'),
    );
}

function wgc_get_post_var($name) {

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

function wgc_format_coupon_value($coupon_id) {

    if (strpos(get_post_meta($coupon_id, 'discount_type', true), "fixed") !== false) {
	esc_html_e(get_woocommerce_currency_symbol() . get_post_meta($coupon_id, 'coupon_amount', true));
    } else {
	esc_html_e(get_post_meta($coupon_id, 'coupon_amount', true) . "%");
    }
}

function wgc_get_pricing_types() {
    return array(
	"fixed" => __("Fixed Price", 'woo-gift-card'),
	"selected" => __("Selected Price", 'woo-gift-card'),
	"range" => __("Range Price", 'woo-gift-card'),
	"user" => __("User Price", 'woo-gift-card'),
	    // "variable" => __("Variable Price", 'woo-gift-card')
    );
}

function wgc_get_barcode_output_types() {
    $generators = array(
	"svg" => __("Svg", 'woo-gift-card'),
	"html" => __("Html", 'woo-gift-card')
    );

    if (wgc_supports_image_barcode()) {
	$generators["png"] = __("Png", 'woo-gift-card');
	$generators["jpg"] = __("Jpg", 'woo-gift-card');
    }

    return $generators;
}

function wgc_preview_link($plugin_name = "woo-gift-card") {
    return get_rest_url(null, $plugin_name . "/v1/coupon/view/");
}

function wgc_download_link($plugin_name = "woo-gift-card") {
    return get_rest_url(null, $plugin_name . "/v1/download/coupon/");
}

/**
 *
 * @return array|\WC_Coupon
 */
function wgc_get_coupons_for_customer() {
    $coupons = array_map(function($coupon) {
	return new WC_Coupon($coupon->ID);
    }, get_posts(
		    array(
			"numberposts" => -1,
			'post_type' => 'shop_coupon',
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
			    array(
				'key' => 'customer_email',
			    ),
			)
		    )
    ));

    return array_filter($coupons, function ($coupon) {
	// Limit to defined email addresses.
	$restrictions = $coupon->get_email_restrictions();
	$emails = array(get_user_option("user_email", get_current_user_id()));

	//from \WC_Cart::is_coupon_emails_allowed
	return !(is_array($restrictions) && 0 < count($restrictions) && !WC()->cart->is_coupon_emails_allowed($emails, $restrictions));
    });
}

/**
 * Whether image generation is supported
 *
 * @return boolean
 */
function wgc_supports_image_barcode() {
    return wgc_supports_qrcode() || extension_loaded('imagick');
}

/**
 * Whether qrcode generation is supported
 *
 * @return boolean
 */
function wgc_supports_qrcode() {
    return function_exists('imagecreate');
}

/**
 * Whether pdf generation is supported
 *
 * @return boolean
 */
function wgc_supports_pdf_generation() {
    return extension_loaded("DOM") && extension_loaded("MBString") && wgc_supports_image_barcode();
}

function wgc_has_coupon($coupon) {
    return count(get_posts(array(
		"numberposts" => 1,
		"post_title" => $coupon,
		"post_type" => "shop_coupon",
		'meta_query' => array(
		    array(
			'key' => 'wgc-order'
		    ),
		    array(
			'key' => 'wgc-order-item'
		    ),
		    array(
			'key' => 'wgc-order-item-index'
		    ),
		)
	    ))) > 0;
}

function wgc_get_supported_code_types() {
    $codes = array();
    $codes["code"] = __("Coupon Code", 'woo-gift-card');

    if (count(wgc_get_barcode_output_types()) > 0) {
	$codes["barcode"] = __("Bar Code", 'woo-gift-card');
    }

    if (wgc_supports_qrcode()) {
	$codes["qrcode"] = __("QrCode", 'woo-gift-card');
    }

    return $codes;
}
