<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined('ABSPATH') || exit;
?>

<div class="options_group show_if_woo-gift-card hide_if_thankyouvoucher">

    <?php
    woocommerce_wp_select(array(
	'id' => 'wgc-pricing',
	//'wrapper_class' => 'show_if_' . 'woo-gift-card',
	'label' => __('Pricing', 'woo-gift-card'),
	'description' => __('The pricing system for gift voucher', 'woo-gift-card'),
	'options' => array(
	    "fixed" => __("Fixed Price", 'woo-gift-card'),
	    "selected" => __("Selected Price", 'woo-gift-card'),
	    "range" => __("Range Price", 'woo-gift-card'),
	    "user" => __("User Price", 'woo-gift-card'),
	// "variable" => __("Variable Price", 'woo-gift-card')
	),
	'value' => $product_object->get_meta('wgc-pricing'),
	'desc_tip' => true
    ));
    ?>
    <div class="wgc-pricing-options">
	<div class="wgc-pricing-fixed">
	    <?php
	    woocommerce_wp_text_input(array(
		'id' => 'wgc-price-regular',
		'value' => $product_object->get_regular_price('edit'),
		'label' => __('Regular price', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
		'data_type' => 'price',
	    ));

	    woocommerce_wp_text_input(array(
		'id' => 'wgc-price-sale',
		'value' => $product_object->get_sale_price('edit'),
		'data_type' => 'price',
		'label' => __('Sale price', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
		'description' => '<a href="#" class="sale_schedule">' . __('Schedule', 'woo-gift-card') . '</a>',
	    ));

	    $sale_price_dates_from_timestamp = $product_object->get_date_on_sale_from('edit') ? $product_object->get_date_on_sale_from('edit')->getOffsetTimestamp() : false;
	    $sale_price_dates_to_timestamp = $product_object->get_date_on_sale_to('edit') ? $product_object->get_date_on_sale_to('edit')->getOffsetTimestamp() : false;

	    $sale_price_dates_from = $sale_price_dates_from_timestamp ? date_i18n('Y-m-d', $sale_price_dates_from_timestamp) : '';
	    $sale_price_dates_to = $sale_price_dates_to_timestamp ? date_i18n('Y-m-d', $sale_price_dates_to_timestamp) : '';

	    echo '<p class="form-field sale_price_dates_fields">
				<label for="wgc-sale-price-dates-from">' . esc_html__('Sale price dates', 'woo-gift-card') . '</label>
				<input type="text" class="short" name="wgc-sale-price-dates-from" id="wgc-sale-price-dates-from" value="' . esc_attr($sale_price_dates_from) . '" placeholder="' . esc_html(_x('From&hellip;', 'placeholder', 'woo-gift-card')) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr(apply_filters('woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) . '" />
				<input type="text" class="short" name="wgc-sale-price-dates-to" id="wgc-sale-price-dates-to" value="' . esc_attr($sale_price_dates_to) . '" placeholder="' . esc_html(_x('To&hellip;', 'placeholder', 'woo-gift-card')) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr(apply_filters('woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) . '" />
				<a href="#" class="description cancel_sale_schedule">' . esc_html__('Cancel', 'woo-gift-card') . '</a>' . wc_help_tip(__('The sale will start at 00:00:00 of "From" date and end at 23:59:59 of "To" date.', 'woo-gift-card')) . '
			</p>';
	    ?>
	</div>

	<div class="wgc-pricing-selected">

	    <?php
	    woocommerce_wp_text_input(array(
		'id' => 'wgc-price-selected',
		'value' => $product_object->get_meta('wgc-price-selected'),
		'label' => __('Prices', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
		'description' => __('The selectable prices of the gift voucher separated by |', 'woo-gift-card'),
		'placeholder' => __("10|20|30", "woo-gift-card"),
		'desc_tip' => true
	    ));
	    ?>
	</div>

	<div class="wgc-pricing-range">

	    <?php
	    woocommerce_wp_text_input(array(
		'id' => 'wgc-price-range-from',
		'value' => $product_object->get_meta('wgc-price-range-from'),
		'data_type' => 'price',
		'label' => __('Price From', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
		'description' => __('The minimum pricing of the gift voucher', 'woo-gift-card'),
		'desc_tip' => true
	    ));

	    woocommerce_wp_text_input(array(
		'id' => 'wgc-price-range-to',
		'value' => $product_object->get_meta('wgc-price-range-to'),
		'data_type' => 'price',
		'label' => __('Price To', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
		'description' => __('The maximum pricing of the gift voucher', 'woo-gift-card '),
		'desc_tip' => true
	    ));
	    ?>
	</div>

	<div class="wgc-pricing-user">
	    <?php
	    woocommerce_wp_text_input(array(
		'id' => 'wgc-price-user',
		'value' => $product_object->get_meta('wgc-price-user'),
		'data_type' => 'price',
		'label' => __('Default price', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
		'description' => __('The initial price of the gift voucher', 'woo-gift-card '),
		'desc_tip' => true
	    ));
	    ?>
	</div>
    </div>

</div>
<div class="options_group show_if_woo-gift-card">

    <?php
    woocommerce_wp_select(array(
	'id' => 'wgc-discount',
	'label' => __('Discount Type', 'woo-gift-card'),
	'description' => __('The discounting system for gift voucher', 'woo-gift-card'),
	'options' => wc_get_coupon_types(),
	'value' => $product_object->get_meta('wgc-discount'),
	'desc_tip' => true
    ));
    ?>

    <div class="wgc-discount">
	<?php
	woocommerce_wp_text_input(array(
	    'wrapper_class' => 'wgc-discount-fixed',
	    'id' => 'wgc-discount-fixed',
	    'value' => $product_object->get_meta('wgc-discount-fixed') ?: 0,
	    'data_type' => 'price',
	    'label' => __('Discount Amount', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	    'description' => __('The monetary value to discount. Leave blank to default to this product\'s price.', 'woo-gift-card'),
	    'desc_tip' => true
	));

	woocommerce_wp_text_input(array(
	    'wrapper_class' => 'wgc-discount-percentage',
	    'id' => 'wgc-discount-percentage',
	    'value' => $product_object->get_meta('wgc-discount-percentage') ?: 0,
	    'label' => __('Discount Percentage', 'woo-gift-card'),
	    'custom_attributes' => array(
		"min" => 1,
		"max" => 100
	    ),
	    'type' => "number",
	    'description' => __('The percentage value to discount.', 'woo-gift-card'),
	    'desc_tip' => true
	));
	?>
    </div>
</div>
<div class="options_group show_if_woo-gift-card">
    <p class="form-field show_if_virtual">
	<label for="wgc-template"><?php _e('Templates', 'woo-gift-card'); ?></label>
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
		    echo '<option value="' . esc_attr($template->ID) . '"' . wc_selected($template->ID, $template_ids) . '>' . esc_html($template->post_title) . '</option>';
		}
	    }
	    ?>
	</select> <?php echo wc_help_tip(__('The gift voucher template list customers can select from', 'woo-gift-card')); // WPCS: XSS ok.                                                                                                                 ?>
    </p>
</div>

