<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined('ABSPATH') || exit;
?>

<div class="options_group show_if_woo-gift-card">

    <?php
    $meta = get_post_meta($thepostid);

    woocommerce_wp_select(array(
	'id' => 'woo-gift-card-pricing',
	//'wrapper_class' => 'show_if_' . 'woo-gift-card',
	'label' => __('Gift Voucher Pricing', 'woo-gift-card'),
	'description' => __('The pricing system for gift voucher', 'woo-gift-card'),
	'options' => array(
	    "fixed" => __("Fixed Price", 'woo-gift-card'),
	    "selected" => __("Selected Price", 'woo-gift-card'),
	    "range" => __("Range Price", 'woo-gift-card'),
	    "user" => __("User Price", 'woo-gift-card'),
	// "variable" => __("Variable Price", 'woo-gift-card')
	),
	'value' => esc_html($meta['woo-gift-card-pricing'][0]),
	'desc_tip' => true
    ));
    ?>
    <div class="wgc-pricing-options">
	<div class="wgc-pricing-fixed">
	    <?php
	    woocommerce_wp_text_input(
		    array(
			'id' => '_woo-gift-card-regular-price',
			'value' => $product_object->get_regular_price('edit'),
			'label' => __('Regular price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
			'data_type' => 'price',
		    )
	    );

	    woocommerce_wp_text_input(
		    array(
			'id' => '_woo-gift-card-sale-price',
			'value' => $product_object->get_sale_price('edit'),
			'data_type' => 'price',
			'label' => __('Sale price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
			'description' => '<a href="#" class="sale_schedule">' . __('Schedule', 'woocommerce') . '</a>',
		    )
	    );

	    $sale_price_dates_from_timestamp = $product_object->get_date_on_sale_from('edit') ? $product_object->get_date_on_sale_from('edit')->getOffsetTimestamp() : false;
	    $sale_price_dates_to_timestamp = $product_object->get_date_on_sale_to('edit') ? $product_object->get_date_on_sale_to('edit')->getOffsetTimestamp() : false;

	    $sale_price_dates_from = $sale_price_dates_from_timestamp ? date_i18n('Y-m-d', $sale_price_dates_from_timestamp) : '';
	    $sale_price_dates_to = $sale_price_dates_to_timestamp ? date_i18n('Y-m-d', $sale_price_dates_to_timestamp) : '';

	    echo '<p class="form-field sale_price_dates_fields">
				<label for="_woo-gift-card_sale_price_dates_from">' . esc_html__('Sale price dates', 'woocommerce') . '</label>
				<input type="text" class="short" name="_woo-gift-card_sale_price_dates_from" id="_woo-gift-card_sale_price_dates_from" value="' . esc_attr($sale_price_dates_from) . '" placeholder="' . esc_html(_x('From&hellip;', 'placeholder', 'woocommerce')) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr(apply_filters('woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) . '" />
				<input type="text" class="short" name="_woo-gift-card_sale_price_dates_to" id="_woo-gift-card_sale_price_dates_to" value="' . esc_attr($sale_price_dates_to) . '" placeholder="' . esc_html(_x('To&hellip;', 'placeholder', 'woocommerce')) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr(apply_filters('woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) . '" />
				<a href="#" class="description cancel_sale_schedule">' . esc_html__('Cancel', 'woocommerce') . '</a>' . wc_help_tip(__('The sale will start at 00:00:00 of "From" date and end at 23:59:59 of "To" date.', 'woocommerce')) . '
			</p>';
	    ?>
	</div>

	<div class="wgc-pricing-selected">

	    <?php
	    woocommerce_wp_text_input(
		    array(
			'id' => 'woo-gift-card-selected',
			'value' => esc_html($meta['woo-gift-card-selected'][0]),
			'data_type' => 'price',
			'label' => __('Gift Voucher Values', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
			'description' => __('The monetary values of the gift voucher separated by |', 'woo-gift-card'),
			'placeholder' => __("10|20|30", "woo-gift-card"),
			'desc_tip' => true
		    )
	    );
	    ?>
	</div>

	<div class="wgc-pricing-range">

	    <?php
	    woocommerce_wp_text_input(
		    array(
			'id' => 'woo-gift-card-range-from',
			'value' => esc_html($meta['woo-gift-card-range-from'][0]),
			'data_type' => 'price',
			'label' => __('From Price', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ')',
			'description' => __('The minimum monetary value of the gift voucher', 'woo-gift-card'),
			'desc_tip' => true
		    )
	    );

	    woocommerce_wp_text_input(
		    array(
			'id' => 'woo-gift-card-range-to',
			'value' => esc_html($meta['woo-gift-card-range-to'][0]),
			'data_type' => 'price',
			'label' => __('To Price', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
			'description' => __('The maximum monetary value of the gift voucher', 'woo-gift-card '),
			'desc_tip' => true
		    )
	    );
	    ?>
	</div>

	<div class="wgc-pricing-user">
	    <p>	    <?php _e("The user can enter the price they want for the gift voucher.", "woo-gift-card") ?>	    </p>
	</div>
    </div>

</div>
<div class="options_group show_if_woo-gift-card">

    <?php
    woocommerce_wp_text_input(array(
	'id' => 'woo-gift-card-discount-min',
	'value' => $meta['woo-gift-card-discount-min'][0] ? esc_html($meta['woo-gift-card-discount-min'][0]) : "",
	'data_type' => 'price',
	'label' => __('Discount Minimum', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	'description' => __('The minimum monetary value of customer cart before a gift voucher discount can be applied', 'woo-gift-card'),
	'desc_tip' => true
    ));

    woocommerce_wp_text_input(array(
	'id' => 'woo-gift-card-discount-max',
	'value' => $meta['woo-gift-card-discount-max'][0] ? esc_html($meta['woo-gift-card-discount-max'][0]) : "",
	'data_type' => 'price',
	'label' => __('Discount Maximum', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	'description' => __('The maximum monetary value of customer\'s cart the gift voucher discount can be applied to', 'woo-gift-card'),
	'desc_tip' => true
    ));

    woocommerce_wp_select(array(
	'id' => 'woo-gift-card-discount',
	'label' => __('Gift Voucher Discount System', 'woo-gift-card'),
	'description' => __('The discounting system for gift voucher', 'woo-gift-card'),
	'options' => array(
	    "fixed" => __("Fixed Discount", 'woo-gift-card'),
	    "percentage" => __("Percentage Discount", 'woo-gift-card')
	),
	'value' => $meta['woo-gift-card-discount'][0] ? esc_html($meta['woo-gift-card-discount'][0]) : "fixed",
	'desc_tip' => true
    ));
    ?>

    <div class="woo-gift-card-discount">
	<?php
	woocommerce_wp_text_input(array(
	    'wrapper_class' => 'woo-gift-card-discount-fixed',
	    'id' => 'woo-gift-card-discount-fixed',
	    'value' => $meta['woo-gift-card-discount-fixed'][0] ? esc_html($meta['woo-gift-card-discount-fixed'][0]) : "",
	    'data_type' => 'price',
	    'label' => __('Discount Amount', 'woo-gift-card') . ' (' . get_woocommerce_currency_symbol() . ') ',
	    'description' => __('The monetary value to discount on customer cart', 'woo-gift-card'),
	    'desc_tip' => true
	));

	woocommerce_wp_text_input(array(
	    'wrapper_class' => 'woo-gift-card-discount-percentage',
	    'id' => 'woo-gift-card-discount-percentage',
	    'value' => $meta['woo-gift-card-discount-percentage'][0] ? esc_html($meta['woo-gift-card-discount-percentage'][0]) : "",
	    'label' => __('Discount Percentage', 'woo-gift-card'),
	    'custom_attributes' => array(
		"min" => 1,
		"max" => 100
	    ),
	    'type' => "number",
	    'description' => __('The percentage value to discount on customer cart', 'woo-gift-card'),
	    'desc_tip' => true
	));
	?>
    </div>
</div>;

