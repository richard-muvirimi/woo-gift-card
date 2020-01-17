<?php

if (!defined('ABSPATH')) {
    exit;
}

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
	    $filtered = filter_input(INPUT_POST, $name);
	}
	return wc_clean(wp_unslash(trim($filtered)));
    }
    return false;
}

/**
 * The file that defines the plugin util class
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/utils
 */

/**
 * The file that defines the plugin util class
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/utils
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class WooGiftCardsUtils {

    /**
     * Gets a unique gift card key for each customer
     *
     * @param string $email
     * @return void
     */
    public static function get_unique_key($email) {

	$key = strtoupper(wp_generate_password());

	$giftCards = get_posts(array(
	    'posts_per_page' => 1,
	    'post_type' => 'woo-gift-card',
	    'author' => $email,
	    'meta_key' => 'woo-gift-card-key',
	    'meta_value' => $key,
	    'fields' => 'ids'
	));

	//if exists then redo
	if (count($giftCards)) {

	    return WooGiftCardsUtils::get_unique_key($email);
	}

	return $key;
    }

}
