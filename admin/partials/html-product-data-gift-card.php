<?php
/**
 * Product general data panel.
 *
 * @package WooCommerce/Admin
 */
defined('ABSPATH') || exit;
?>
<div id="woo-gift-card" class="panel woocommerce_options_panel">

    <div class="options_group show_if_woo-gift-card">
	<?php $meta = get_post_meta($thepostid); ?>

	<p class="form-field">
	    <label for="woo-gift-card-template"><?php _e('Gift Voucher Template', 'woo-gift-card'); ?></label>
	    <select id="woo-gift-card-template" name="woo-gift-card-template[]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e('No templates', 'woo-gift-card'); ?>">
		<?php
		$template_ids = unserialize($meta['woo-gift-card-template'][0]);
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
	    </select> <?php echo wc_help_tip(__('If excluded categories are available in a cart then the gift voucher will not be applied', 'woo-gift-card')); // WPCS: XSS ok.               ?>
	</p>

	<?php
	woocommerce_wp_checkbox(array(
	    'id' => 'woo-gift-card-sale',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Exclude Sale Items', 'woo-gift-card'),
	    'description' => __('Apply gift voucher to items on sale', 'woo-gift-card'),
	    'value' => $meta['woo-gift-card-sale'][0] ? esc_html($meta['woo-gift-card-sale'][0]) : "no"
	));

	woocommerce_wp_checkbox(array(
	    'id' => 'woo-gift-card-multiple',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Multiple Usability', 'woo-gift-card'),
	    'description' => __('The gift voucher can be used multiple times or just once', 'woo-gift-card'),
	    'value' => $meta['woo-gift-card-multiple'][0] ? esc_html($meta['woo-gift-card-multiple'][0]) : "no"
	));

	woocommerce_wp_text_input(array(
	    'id' => 'woo-gift-card-expiry-days',
	    'value' => $meta['woo-gift-card-expiry-days'][0] ? esc_html($meta['woo-gift-card-expiry-days'][0]) : "",
	    'label' => __('Expiry Days', 'woo-gift-card'),
	    'description' => __('The number of days after purchase that a gift voucher will become invalid', 'woo-gift-card'),
	    'desc_tip' => true,
	    'custom_attributes' => array(
		"min" => 1
	    ),
	    'type' => "number",
	));

	woocommerce_wp_text_input(array(
	    'id' => 'woo-gift-card-cart-min',
	    'value' => $meta['woo-gift-card-cart-min'][0] ? esc_html($meta['woo-gift-card-cart-min'][0]) : "",
	    'data_type' => 'price',
	    'label' => __('Cart Total Minimum', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	    'description' => __('The minimum monetary value of customer cart before gift voucher can be applied', 'woo-gift-card'),
	    'desc_tip' => true
	));

	woocommerce_wp_text_input(array(
	    'id' => 'woo-gift-card-cart-max',
	    'value' => $meta['woo-gift-card-cart-max'][0] ? esc_html($meta['woo-gift-card-cart-max'][0]) : "",
	    'data_type' => 'price',
	    'label' => __('Cart Total Maximum', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	    'description' => __('The maximum monetary value of customer\'s cart the gift voucher can be applied to', 'woo-gift-card'),
	    'desc_tip' => true
	));

	woocommerce_wp_checkbox(array(
	    'id' => 'woo-gift-card-individual',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Individual Use', 'woo-gift-card'),
	    'description' => __('The gift voucher can be used by multiple customers', 'woo-gift-card'),
	    'value' => $meta['woo-gift-card-individual'][0] ? esc_html($meta['woo-gift-card-individual'][0]) : "yes"
	));

	woocommerce_wp_checkbox(array(
	    'id' => 'woo-gift-card-schedule',
	    'wrapper_class' => 'show_if_woo-gift-card',
	    'label' => __('Gift Voucher Scheduling', 'woo-gift-card'),
	    'description' => __('Gift voucher can be scheduled to be sent later', 'woo-gift-card'),
	    'value' => $meta['woo-gift-card-schedule'][0] ? esc_html($meta['woo-gift-card-schedule'][0]) : "no"
	));
	?>
    </div>

</div>
