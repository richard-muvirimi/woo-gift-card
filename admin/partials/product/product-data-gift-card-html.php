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
	    </select> <?php echo wc_help_tip(__('The gift voucher template list customers can select from', 'woo-gift-card')); // WPCS: XSS ok.                                                                                              ?>
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
	    'description' => __('The gift voucher can be used multiple times (if checked) or just once', 'woo-gift-card'),
	    'value' => $product_object->get_meta('wgc-multiple')
	));

	woocommerce_wp_text_input(array(
	    'id' => 'wgc-expiry-days',
	    'value' => $product_object->get_meta('wgc-expiry-days') ?: 5,
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

</div>
