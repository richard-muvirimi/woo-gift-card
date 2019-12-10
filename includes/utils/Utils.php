<?php

if (!defined('ABSPATH')) {
    exit;
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
class Woo_gift_cards_utils {

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

	    return Woo_gift_cards_utils::get_unique_key($email);
	}

	return $key;
    }

    public static function getBarCodeTypes() {

	//include_once '';
//	return array( TYPE_CODE_39 = 'C39';
//	const TYPE_CODE_39_CHECKSUM = 'C39+';
//	const TYPE_CODE_39E = 'C39E';
//	const TYPE_CODE_39E_CHECKSUM = 'C39E+';
//	const TYPE_CODE_93 = 'C93';
//	const TYPE_STANDARD_2_5 = 'S25';
//	const TYPE_STANDARD_2_5_CHECKSUM = 'S25+';
//	const TYPE_INTERLEAVED_2_5 = 'I25';
//	const TYPE_INTERLEAVED_2_5_CHECKSUM = 'I25+';
//	const TYPE_CODE_128 = 'C128';
//	const TYPE_CODE_128_A = 'C128A';
//	const TYPE_CODE_128_B = 'C128B';
//	const TYPE_CODE_128_C = 'C128C';
//	const TYPE_EAN_2 = 'EAN2';
//	const TYPE_EAN_5 = 'EAN5';
//	const TYPE_EAN_8 = 'EAN8';
//	const TYPE_EAN_13 = 'EAN13';
//	const TYPE_UPC_A = 'UPCA';
//	const TYPE_UPC_E = 'UPCE';
//	const TYPE_MSI = 'MSI';
//	const TYPE_MSI_CHECKSUM = 'MSI+';
//	const TYPE_POSTNET = 'POSTNET';
//	const TYPE_PLANET = 'PLANET';
//	const TYPE_RMS4CC = 'RMS4CC';
//	const TYPE_KIX = 'KIX';
//	const TYPE_IMB = 'IMB';
//	const TYPE_CODABAR = 'CODABAR';
//	const TYPE_CODE_11 = 'CODE11';
//	const TYPE_PHARMA_CODE = 'PHARMA';
//	const TYPE_PHARMA_CODE_TWO_TRACKS = 'PHARMA2T';
//	);
    }

}
