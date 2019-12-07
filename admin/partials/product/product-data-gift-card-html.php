<?php
/**
 * Product general data panel.
 *
 * @package WooCommerce/Admin
 */
defined('ABSPATH') || exit;
?>
<div id="wgc-general" class="panel woocommerce_options_panel">

    <div class="options_group show_if_woo-gift-card">
	<p class="form-field show_if_virtual">
	    <label for="wgc-template"><?php _e('Gift Voucher Template', 'woo-gift-card'); ?></label>
	    <select id="wgc-template" name="wgc-template[]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e('No templates', 'woo-gift-card'); ?>">
		<?php
		$template_ids = $product_object->get_meta('wgc-template');
		$templates = get_posts(array(
		    'numberposts' => -1,
		    'post_type' => 'wgc-template',
		    'post_status' => 'publish',
		    'fields' => array('ids'),
		    'orderby' => "name"
		));

		if ($templates) {
		    foreach ($templates as $template) {
			echo '<option value="' . esc_attr($template->ID) . '"' . wc_selected($template->ID, $template_ids) . '>' . esc_html($template->post_name) . '</option>';
		    }
		}
		?>
	    </select> <?php echo wc_help_tip(__('The gift voucher template list customers can select from', 'woo-gift-card')); // WPCS: XSS ok.                                                                                         ?>
	</p>

	<?php
	woocommerce_wp_checkbox(array(
	    'id' => 'wgc-sale',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Exclude Sale Items', 'woo-gift-card'),
	    'description' => __('Apply gift voucher to items on sale', 'woo-gift-card'),
	    'value' => $product_object->get_meta("wgc-sale")
	));

	woocommerce_wp_checkbox(array(
	    'id' => 'wgc-multiple',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Multiple Usability', 'woo-gift-card'),
	    'description' => __('The gift voucher can be used multiple times or just once', 'woo-gift-card'),
	    'value' => $product_object->get_meta('wgc-multiple')
	));

	woocommerce_wp_text_input(array(
	    'id' => 'wgc-expiry-days',
	    'value' => $product_object->get_meta('wgc-expiry-days'),
	    'label' => __('Expiry Days', 'woo-gift-card'),
	    'description' => __('The number of days after purchase that a gift voucher will become invalid', 'woo-gift-card'),
	    'desc_tip' => true,
	    'custom_attributes' => array(
		"min" => 1
	    ),
	    'type' => "number",
	));

	woocommerce_wp_checkbox(array(
	    'id' => 'wgc-individual',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Individual Use', 'woo-gift-card'),
	    'description' => __('The gift voucher can be used by multiple customers', 'woo-gift-card'),
	    'value' => $product_object->get_meta('wgc-individual')
	));

	woocommerce_wp_checkbox(array(
	    'id' => 'wgc-schedule',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Gift Voucher Scheduling', 'woo-gift-card'),
	    'description' => __('Gift voucher can be scheduled to be sent later', 'woo-gift-card'),
	    'value' => $product_object->get_meta('wgc-schedule')
	));
	?>
    </div>

    <div class="options_group show_if_woo-gift-card">

	<?php
	woocommerce_wp_select(array(
	    'id' => 'wgc-coupon-type',
	    'label' => __('Gift Voucher Display', 'woo-gift-card'),
	    'description' => __('The coupon code display format', 'woo-gift-card'),
	    'options' => array(
		"code" => __("Coupon Code", 'woo-gift-card'),
		"qrcode" => __("QrCode", 'woo-gift-card'),
		"barcode" => __("Bar Code", 'woo-gift-card')
	    ),
	    'value' => $product_object->get_meta('wgc-coupon-type'),
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
		    'value' => $product_object->get_meta('wgc-coupon-qrcode-ecc'),
		    'desc_tip' => true
		));

		woocommerce_wp_text_input(array(
		    'id' => 'wgc-coupon-qrcode-size',
		    'value' => $product_object->get_meta('wgc-coupon-qrcode-size'),
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
		    'value' => $product_object->get_meta('wgc-coupon-qrcode-margin'),
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
		    'label' => __('Show Coupon Code', 'woo-gift-card'),
		    'description' => __('Show coupon code below the QrCode', 'woo-gift-card'),
		    'value' => $product_object->get_meta('wgc-coupon-qrcode-code')
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
		    'value' => $product_object->get_meta('wgc-coupon-barcode-type'),
		    'desc_tip' => true
		));

		woocommerce_wp_select(array(
		    'id' => 'wgc-coupon-barcode-image-type',
		    'label' => __('Image Type', 'woo-gift-card'),
		    'description' => __('The BarCode Type', 'woo-gift-card'),
		    'options' => array(
			"svg" => __("Svg", 'woo-gift-card'),
			"png" => __("Png", 'woo-gift-card'),
			"jpg" => __("Jpg", 'woo-gift-card'),
			"html" => __("Html", 'woo-gift-card'),
		    ),
		    'value' => $product_object->get_meta('wgc-coupon-barcode-image-type'),
		    'desc_tip' => true
		));

		woocommerce_wp_text_input(array(
		    'id' => 'wgc-coupon-barcode-width',
		    'value' => $product_object->get_meta('wgc-coupon-barcode-width') ?: 2,
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
		    'value' => $product_object->get_meta('wgc-coupon-barcode-height') ?: 30,
		    'label' => __('Code Total Height', 'woo-gift-card'),
		    'description' => __('The height of the bar code in pixels', 'woo-gift-card'),
		    'custom_attributes' => array(
			"min" => 1
		    ),
		    'type' => "number",
		    'desc_tip' => true
		));
		?>
	    </div>
	</div>
    </div>

</div>
