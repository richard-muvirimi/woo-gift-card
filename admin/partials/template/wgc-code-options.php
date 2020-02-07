<?php
/**
 * Provide a template background image customization area view for the plugin
 *
 * This file is used to markup the template background aspects of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/admin/partials/template
 */
defined('ABSPATH') || exit;

$meta = get_post_meta($post_id);
?>

<div class="options_group">

    <?php
    woocommerce_wp_select(array(
	'id' => 'wgc-coupon-type',
	'label' => __('Gift Voucher Display', 'woo-gift-card'),
	'description' => __('The coupon code display format', 'woo-gift-card'),
	'options' => wgc_get_supported_code_types(),
	'value' => isset($meta['wgc-coupon-type']) ? $meta['wgc-coupon-type'][0] : "code",
	'desc_tip' => true
    ));
    ?>

    <div class="wgc-coupon-options">
	<div class="wgc-coupon-code-options">
	    <p><?php _e('The coupon code will be displayed as plain text.', 'woo-gift-card') ?></p>
	</div>
	<div class="wgc-coupon-qrcode-options">
	    <?php
	    woocommerce_wp_select(array(
		'id' => 'wgc-coupon-qrcode-ecc',
		'label' => __('ECC Level', 'woo-gift-card'),
		'description' => __('The Qr Code ECC Level', 'woo-gift-card'),
		'options' => array(
		    QR_ECLEVEL_L => __("L", 'woo-gift-card'),
		    QR_ECLEVEL_M => __("M", 'woo-gift-card'),
		    QR_ECLEVEL_Q => __("Q", 'woo-gift-card'),
		    QR_ECLEVEL_H => __("H", 'woo-gift-card'),
		),
		'value' => isset($meta['wgc-coupon-qrcode-ecc']) ? $meta['wgc-coupon-qrcode-ecc'][0] : QR_ECLEVEL_L,
		'desc_tip' => true
	    ));

	    woocommerce_wp_text_input(array(
		'id' => 'wgc-coupon-qrcode-size',
		'value' => isset($meta['wgc-coupon-qrcode-size']) ? $meta['wgc-coupon-qrcode-size'][0] : 3,
		'label' => __('Qr Code Size', 'woo-gift-card'),
		'description' => __('The pixel size of the QrCode', 'woo-gift-card'),
		'custom_attributes' => array(
		    "min" => 1,
		    "max" => 4
		),
		'type' => "number",
		'desc_tip' => true
	    ));

	    woocommerce_wp_text_input(array(
		'id' => 'wgc-coupon-qrcode-margin',
		'value' => isset($meta['wgc-coupon-qrcode-margin']) ? $meta['wgc-coupon-qrcode-margin'][0] : 4,
		'label' => __('QrCode Margin', 'woo-gift-card'),
		'description' => __('The margin of the Qr Code', 'woo-gift-card'),
		'custom_attributes' => array(
		    "min" => 1
		),
		'type' => "number",
		'desc_tip' => true
	    ));

	    woocommerce_wp_checkbox(array(
		'id' => 'wgc-coupon-qrcode-code',
		'label' => __('Display Coupon Code Number &nbsp;', 'woo-gift-card'),
		'value' => isset($meta['wgc-coupon-qrcode-code']) ? $meta['wgc-coupon-qrcode-code'][0] : 'yes'
	    ));
	    ?>
	</div>
	<div class="wgc-coupon-barcode-options">
	    <?php
	    woocommerce_wp_select(array(
		'id' => 'wgc-coupon-barcode-type',
		'label' => __('BarCode Type', 'woo-gift-card'),
		'description' => __('The BarCode Type', 'woo-gift-card'),
		'options' => array(
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39 => __("Code 39", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39_CHECKSUM => __("Code 39 Checksum", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39E => __("Code 39E", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39E_CHECKSUM => __("Code 39E Checksum", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_93 => __("Code 93", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_STANDARD_2_5 => __("Standard 2 5", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_STANDARD_2_5_CHECKSUM => __("Standard 2 5 Checksum", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_INTERLEAVED_2_5 => __("Interleaved 2 5", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_INTERLEAVED_2_5_CHECKSUM => __("Interleaved 2 5 Checksum", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128 => __("Code 128", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128_A => __("Code 128 A", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128_B => __("Code 128 B", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128_C => __("Code 128 C", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_EAN_2 => __("EAN 2", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_EAN_5 => __("EAN 5", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_EAN_8 => __("EAN 8", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_EAN_13 => __("EAN 13", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_UPC_A => __("UPC A", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_UPC_E => __("EAN E", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_MSI => __("MSI", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_MSI_CHECKSUM => __("MSI Checksum", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_POSTNET => __("Postnet", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_PLANET => __("Planet", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_RMS4CC => __("RMS4CC", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_KIX => __("KIX", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_IMB => __("IMB", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODABAR => __("CODABAR", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_CODE_11 => __("CODE 11", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_PHARMA_CODE => __("Pharma Code", 'woo-gift-card'),
		    Picqer\Barcode\BarcodeGenerator::TYPE_PHARMA_CODE_TWO_TRACKS => __("Pharma Code Two Tracks", 'woo-gift-card'),
		),
		'value' => isset($meta['wgc-coupon-barcode-type']) ? $meta['wgc-coupon-barcode-type'][0] : Picqer\Barcode\BarcodeGenerator::TYPE_EAN_13,
		'desc_tip' => true
	    ));

	    woocommerce_wp_select(array(
		'id' => 'wgc-coupon-barcode-image-type',
		'label' => __('Image Type', 'woo-gift-card'),
		'description' => __('The BarCode Type', 'woo-gift-card'),
		'options' => wgc_get_barcode_output_types(),
		'value' => isset($meta['wgc-coupon-barcode-image-type']) ? $meta['wgc-coupon-barcode-image-type'][0] : "html",
		'desc_tip' => true
	    ));

	    woocommerce_wp_text_input(array(
		'id' => 'wgc-coupon-barcode-width',
		'value' => isset($meta['wgc-coupon-barcode-width']) ? $meta['wgc-coupon-barcode-width'][0] : 2,
		'label' => __('Code Bar Width', 'woo-gift-card'),
		'description' => __('The width of each bar in pixels', 'woo-gift-card'),
		'custom_attributes' => array(
		    "min" => 1
		),
		'type' => "number",
		'desc_tip' => true
	    ));

	    woocommerce_wp_text_input(array(
		'id' => 'wgc-coupon-barcode-height',
		'value' => isset($meta['wgc-coupon-barcode-height']) ? $meta['wgc-coupon-barcode-height'][0] : 30,
		'label' => __('Code Total Height', 'woo-gift-card'),
		'description' => __('The height of the bar code in pixels', 'woo-gift-card'),
		'custom_attributes' => array(
		    "min" => 1
		),
		'type' => "number",
		'desc_tip' => true
	    ));

	    woocommerce_wp_text_input(array(
		'id' => 'wgc-coupon-barcode-color',
		'value' => isset($meta['wgc-coupon-barcode-color']) ? $meta['wgc-coupon-barcode-color'][0] : "#000000",
		'label' => __('Code Color', 'woo-gift-card'),
		'type' => "color"
	    ));
	    ?>
	</div>
    </div>
</div>
