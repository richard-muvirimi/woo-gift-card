<?php
/**
 * Provide a payment view for the plugin
 *
 * This file is used to markup the payment aspects of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public/partials
 */
global $product;

$pricing = $product->get_meta("wgc-pricing");
?>
<div class="wgc-options">
    <?php
    if ($pricing !== 'fixed'):
	?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    	<label for="wgc-receiver-price"><?php echo sprintf(esc_html('Gift Voucher Price (%s)', 'woo-gift-card'), get_woocommerce_currency_symbol()); ?>&nbsp;<span class="required">*</span></label>
	    <?php
	    switch ($product->get_meta("wgc-pricing")) :
		case "range":
		    $min = $product->get_meta('wgc-price-range-from');
		    $max = $product->get_meta('wgc-price-range-to');

		    $minmax = array($min, $max);
		    $price = ceil(array_sum($minmax) / count($minmax));

		    $range = wc_format_price_range(wc_get_price_to_display($product, array('price' => $min)) . $product->get_price_suffix($min), wc_get_price_to_display($product, array('price' => $max)) . $product->get_price_suffix($max));
		    ?>
	    	<input type="number" min="<?php esc_attr_e($min) ?>" max="<?php esc_attr_e($max) ?>" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-price" id="wgc-receiver-price" value="<?php esc_attr_e($price); ?>" required/>
	    	<span><em><?php echo sprintf(esc_html('Enter a price value in the range %s', 'woo-gift-card'), $range); ?></em></span>
		    <?php
		    break;
		case 'user':
		    $default = $product->get_meta('wgc-price-user');
		    ?>
	    	<input type="number" min="0" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-price" id="wgc-receiver-price" value="<?php esc_attr_e($default); ?>" required/>
	    	<span><em><?php esc_html_e('Enter a price value for the gift voucher', 'woo-gift-card') ?></em></span>
		    <?php
		    break;
		case "selected":
		    $prices = explode("|", $product->get_meta('wgc-price-selected'));
		    foreach ($prices as $price) :
			?>
			<input type="radio" class="woocommerce-Input woocommerce-Input--text input-radio" name="wgc-receiver-price" id="wgc-receiver-price" value="<?php esc_attr_e($price); ?>" required <?php checked($prices[0], $price); ?>/>
			<span><?php echo wc_price(wc_get_price_to_display($product, array('price' => $price))) . $product->get_price_suffix($price) ?></span>
			<?php
		    endforeach;
		    break;
		default:
	    endswitch;
	    ?>
        </p>
	<?php
    endif;

    if ($product->is_virtual()):
	?>

        <fieldset>
    	<legend><?php esc_html_e("Details to customise the gift voucher", 'woo-gift-card'); ?></legend>

    	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    	    <label for="wgc-receiver-name"><?php esc_html_e('Receiver name', 'woo-gift-card'); ?>&nbsp;<span class="required">*</span></label>
    	    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-name" id="wgc-receiver-name" value="<?php esc_attr_e(get_user_option("display_name")); ?>" required/>
    	    <span><em><?php esc_html_e('Will default to account name', 'woo-gift-card') ?></em></span>
    	</p>

    	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    	    <label for="wgc-receiver-email"><?php esc_html_e('Receiver email', 'woo-gift-card'); ?>&nbsp;<span class="required">*</span></label>
    	    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-email" id="wgc-receiver-email" value="<?php esc_attr_e(get_user_option("user_email")); ?>" required/>
    	    <span><em><?php esc_html_e('Will default to account email', 'woo-gift-card') ?></em></span>
    	</p>

    	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    	    <label for="wgc-receiver-message"><?php esc_html_e('Receiver message', 'woo-gift-card'); ?></label>
    	    <textarea class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-message" id="wgc-receiver-message" rows="3" maxlength="<?php esc_attr_e(get_option('wgc-message-length')) ?>"></textarea>
    	    <span id="wgc-message-length"></span>
    	</p>

    	<!-- if has template file-->
	    <?php
	    $templates = $product->get_meta('wgc-template');
	    if (!empty($templates)):
		?>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		    <label for="wgc-event"><?php esc_html_e('Event Title', 'woo-gift-card'); ?></label>
		    <input class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-event" id="wgc-event" />
		    <span><em><?php esc_html_e('Will default to template name if empty', 'woo-gift-card') ?></em></span>
		</p>

		<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		    <span><?php esc_html_e('Gift Voucher Template', 'woo-gift-card'); ?></span>
		    <div class="wgc-flex-container">
			<?php foreach ($templates as $template) : ?>
	    		<div>
	    		    <input type="radio" class="woocommerce-Input woocommerce-Input--text input-radio" name="wgc-receiver-template" id="wgc-template-<?php esc_attr_e($template) ?>" value="<?php esc_attr_e($template); ?>" required <?php checked($templates[1], $template); ?>/>
	    		    <label for="wgc-template-<?php esc_attr_e($template) ?>">
	    			<span>
					<?php esc_html_e(get_post_field('post_title', $template)) ?>
	    			</span>
				    <?php
				    if (has_post_thumbnail($template)) {
					$thumbnail_id = get_post_thumbnail_id($template);
					echo wp_get_attachment_image($thumbnail_id);
				    } else {
					echo $product->get_image("thumbnail", "thumbnail");
				    }
				    ?>
	    			<sub>
					<?php
					$orientation = get_post_meta($template, "wgc-template-orientation", true);
					$dimension = wp_get_post_terms($template, "wgc-template-dimension")[0];

					$title = $dimension->name . " (";
					$meta = get_term_meta($dimension->term_id);

					$val1 = $meta["wgc-dimension-value1"][0];
					$val2 = $meta["wgc-dimension-value2"][0];

					if ($val1 && $val2) {
					    if ($orientation === "landscape") {
						$title .= max(array($val1, $val2)) . " * " . min(array($val1, $val2));
					    } else {
						$title .= min(array($val1, $val2)) . " * " . max(array($val1, $val2));
					    }
					    $title .= " " . $meta["wgc-dimension-unit"][0];
					} else {
					    $title .= __("From Image", "woo-gift-card");
					}
					$title .= ")";

					esc_html_e($title);
					?>
	    			</sub>
	    		    </label>
	    		</div>
			<?php endforeach; ?>
		    </div>
		</div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		    <label for="wgc-receiver-image"><?php esc_html_e('Gift Voucher background image', 'woo-gift-card'); ?></label>
		    <input type="file" accept="image/*" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-image" id="wgc-receiver-image"/>
		</p>
	    <?php endif; ?>

        </fieldset>

        <!-- if can be scheduled-->
	<?php if (!empty($product->get_meta('wgc-schedule'))): ?>
	    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="wgc-receiver-schedule"><?php esc_html_e('Date to send Gift Voucher', 'woo-gift-card'); ?></label>
		<input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-schedule" id="wgc-receiver-schedule" value="<?php esc_attr_e(date('Y-m-d')) ?>"  min="<?php esc_attr_e(date('Y-m-d')) ?>"/>
	    </p>
	<?php endif; ?>

	<?php
//send to different account
//receiver name
//receiver email
// receiver message
//gift card image
//
//schedule
    endif;

    /**
     *
     * send gift card
     * emails, from name, gift message
     *
     * schedule gift card
     * image for gift card
     *
     * pricing
     * range, selected, user
     */
    ?>
</div>