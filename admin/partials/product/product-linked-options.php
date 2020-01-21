<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined('ABSPATH') || exit;
?>

<div class="options_group show_if_woo-gift-card">
    <p class="form-field">
	<label for="wgc-products"><?php esc_html_e('Products', 'woo-gift_card'); ?></label>
	<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="wgc-products" name="wgc-products[]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woo-gift-card'); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval($post->ID); ?>">
	    <?php
	    foreach ($product_object->get_meta('wgc-products') as $product_id) {
		$product = wc_get_product($product_id);
		if (is_object($product)) {
		    echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
		}
	    }
	    ?>
	</select> <?php echo wc_help_tip(sprintf(__('Products that the coupon will be applied to, or that need to be in the cart in order for the "%s" to be applied', 'woo-gift-card'), wc_get_coupon_types()["fixed_cart"])); // WPCS: XSS ok.                                                         ?>
    </p>

    <p class="form-field">
	<label for="wgc-excluded-products"><?php esc_html_e('Excluded Products', 'woo-gift_card'); ?></label>
	<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="wgc-excluded-products" name="wgc-excluded-products[]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woo-gift-card'); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval($post->ID); ?>">
	    <?php
	    foreach ($product_object->get_meta('wgc-excluded-products') as $product_id) {
		$product = wc_get_product($product_id);
		if (is_object($product)) {
		    echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
		}
	    }
	    ?>
	</select> <?php echo wc_help_tip(sprintf(__('Products that the coupon will not be applied to, or that cannot be in the cart in order for the "%s" to be applied.', 'woo-gift-card'), wc_get_coupon_types()["fixed_cart"])); // WPCS: XSS ok.                                                         ?>
    </p>

</div>
<div class="options_group show_if_woo-gift-card">

    <p class="form-field">
	<label for="wgc-product-categories"><?php _e('Product Categories', 'woo-gift-card'); ?></label>
	<select id="wgc-product-categories" name="wgc-product-categories[]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e('Any category', 'woo-gift-card'); ?>">
	    <?php
	    $category_ids = $product_object->get_meta('wgc-product-categories');
	    $categories = get_terms('product_cat', 'orderby=name&hide_empty=0');

	    if ($categories) {
		foreach ($categories as $cat) {
		    echo '<option value="' . esc_attr($cat->term_id) . '"' . wc_selected($cat->term_id, $category_ids) . '>' . esc_html($cat->name) . '</option>';
		}
	    }
	    ?>
	</select> <?php echo wc_help_tip(sprintf(__('Product categories that the coupon will be applied to, or that need to be in the cart in order for the "%s" to be applied', 'woo-gift-card'), wc_get_coupon_types()["fixed_cart"])); // WPCS: XSS ok.                      ?>
    </p>

    <p class="form-field">
	<label for="wgc-excluded-product-categories"><?php _e('Excluded Categories', 'woo-gift-card'); ?></label>
	<select id="wgc-excluded-product-categories" name="wgc-excluded-product-categories[]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e('No categories', 'woo-gift-card'); ?>">
	    <?php
	    $excluded_category_ids = $product_object->get_meta('wgc-excluded-product-categories');
	    $excluded_categories = get_terms('product_cat', 'orderby=name&hide_empty=0');

	    if ($excluded_categories) {
		foreach ($excluded_categories as $cat) {
		    echo '<option value="' . esc_attr($cat->term_id) . '"' . wc_selected($cat->term_id, $excluded_category_ids) . '>' . esc_html($cat->name) . '</option>';
		}
	    }
	    ?>
	</select> <?php echo wc_help_tip(sprintf(__('Product categories that the coupon will not be applied to, or that cannot be in the cart in order for the "%s" to be applied.', 'woo-gift-card'), wc_get_coupon_types()["fixed_cart"])); // WPCS: XSS ok.                      ?>
    </p>
</div>