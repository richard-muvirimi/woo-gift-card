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
class WooGiftCardsUtils {

    /**
     * Get a particular dimension
     * @param string $id
     * @return Dimension
     */
    public static function getTemplateDimension($id) {
	$dimensions = WooGiftCardsUtils::getTemplateSizes();
	foreach ($dimensions as $dimension) {
	    if ($dimension->get_id() === strtolower($id)) {
		return $dimension;
	    }
	}
	//default to a4 if not found
	return WooGiftCardsUtils::getTemplateDimension("a4");
    }

    /**
     * Get an array list of all available dimensions
     * @return array|Dimension
     */
    public static function getTemplateSizes() {
	$dimensions = array();

	$dimensions[] = new Dimension("us_letter", "US Letter", 8.5, 11, "in");
	$dimensions[] = new Dimension("us_legal", "US Legal", 8.5, 14, "in");
	$dimensions[] = new Dimension("us_executive", "US Executive", 7.2, 10.5, "in");
	$dimensions[] = new Dimension("cse", "CSE", 462, 649, "pt");
	$dimensions[] = new Dimension("us_#10_envelope", "US #10 Envelope", 4.1, 9.5, "in");
	$dimensions[] = new Dimension("dl_envelope", "DL Envelope", 110, 220, "mm");
	$dimensions[] = new Dimension("ledger/tabloid", "Ledger/Tabloid", 11, 17, "in");
	$dimensions[] = new Dimension("banner", "Banner", 60, 468, "px");

	$dimensions[] = new Dimension("icon16*16", "Icon 16 * 16", 16, 16, "px");
	$dimensions[] = new Dimension("icon32*32", "Icon 32 * 32", 32, 32, "px");
	$dimensions[] = new Dimension("icon48*48", "Icon 48 * 48", 48, 48, "px");

	$dimensions[] = new Dimension("businesscard(iso7810)", "Business Card (ISO 7810)", 54, 85.6, "mm");
	$dimensions[] = new Dimension("businesscard(us)", "Business Card (US)", 2, 305, "in");
	$dimensions[] = new Dimension("businesscard(europe)", "Business Card (Europe)", 55, 85, "mm");
	$dimensions[] = new Dimension("businesscard(auz/nz)", "Business Card (Aus/NZ)", 54, 90, "mm");

	$dimensions[] = new Dimension("arch_a", "Arch A", 9, 12, "in");
	$dimensions[] = new Dimension("arch_b", "Arch B", 12, 18, "in");
	$dimensions[] = new Dimension("arch_c", "Arch C", 18, 24, "in");
	$dimensions[] = new Dimension("arch_d", "Arch D", 24, 36, "in");
	$dimensions[] = new Dimension("arch_e", "Arch E", 36, 48, "in");
	$dimensions[] = new Dimension("arch_e1", "Arch E1", 30, 42, "in");

	//series based
	$a_series = WooGiftCardsUtils::getSeries("a", 841, 1189, 0, 10);
	$b_series = WooGiftCardsUtils::getSeries("b", 1000, 1414, 0, 10);
	$c_series = WooGiftCardsUtils::getSeries("c", 917, 1297, 0, 10);
	$d_series = WooGiftCardsUtils::getSeries("d", 545, 771, 1, 7);
	$e_series = WooGiftCardsUtils::getSeries("e", 400, 560, 3, 6);

	/**
	 * Filters the template dimension list.
	 *
	 * @since 1.0
	 *
	 * @param array   $dimensions     The dimension list
	 */
	return apply_filters("wgc_template_dimensions", array_merge($dimensions, $a_series, $b_series, $c_series, $d_series, $e_series));
    }

    private static function getSeries($series, $value1, $value2, $min = 0, $max = 1) {
	$dimensions = array();

	//offset by doubling
	$value1 *= 2;

	//loop through adding values
	for ($index = $min; $index <= $max; $index++) {
	    $id = $series . $index;

	    //bisect value one and set valuetwo
	    $dimension = new Dimension($id, strtoupper($id), intval(floor($value1 / 2)), $value2);
	    $dimensions[] = $dimension;

	    $value2 = $dimension->get_value1();
	    $value1 = $dimension->get_value2();
	}

	return $dimensions;
    }

    public static function getSupportedShortCodes() {
	return array(
	    "amount" => __("The monetary value of the gift voucher.", 'woo-gift-card'),
	    "code" => __("The code to uniquely identify the gift voucher.", 'woo-gift-card'),
	    "disclaimer" => __("Disclaimer message to show to the receipent of the gift voucher.", 'woo-gift-card'),
	    "event" => __("What event is this gift voucher for.", 'woo-gift-card'),
	    "expiry-date" => __("The expiry date of the gift voucher", 'woo-gift-card'),
	    "from" => __("The sender of the gift voucher", 'woo-gift-card'),
	    "logo" => __("This companies logo", 'woo-gift-card'),
	    "message" => __("A message sent by customer to the recipient of the gift voucher", 'woo-gift-card'),
	    "order-id" => __("The order id to use as reference of the gift voucher", 'woo-gift-card'),
	    "product-name" => __("The product name of this gift voucher", 'woo-gift-card'),
	    "to" => __("The recipient of the gift voucher", 'woo-gift-card')
	);
    }

    public static function getShortCodePrefix() {
	return "wgc-";
    }

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
