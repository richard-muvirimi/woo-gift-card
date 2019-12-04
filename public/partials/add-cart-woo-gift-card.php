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
	        <input type="number" min="<?php echo esc_attr($min) ?>" max="<?php echo esc_attr($max) ?>" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-price" id="wgc-receiver-price" value="<?php echo esc_attr($price); ?>" required/>
	        <span><em><?php echo sprintf(esc_html('Enter a price value in the range %s', 'woo-gift-card'), $range); ?></em></span>
		<?php
		break;
	    case 'user':
		$default = $product->get_meta('wgc-price-user');
		?>
	        <input type="number" min="0" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-price" id="wgc-receiver-price" value="<?php echo esc_attr($default); ?>" required/>
	        <span><em><?php esc_html_e('Enter a price value for the gift voucher', 'woo-gift-card') ?></em></span>
		<?php
		break;
	    case "selected":
		$prices = explode("|", $product->get_meta('wgc-price-selected'));
		foreach ($prices as $price) :
		    ?>
		    <input type="radio" class="woocommerce-Input woocommerce-Input--text input-radio" name="wgc-receiver-price" id="wgc-receiver-price" value="<?php echo esc_attr($price); ?>" required <?php checked($prices[0], $price); ?>/>
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
    	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-name" id="wgc-receiver-name" value="<?php echo esc_attr(get_user_option("display_name")); ?>" required/>
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    	<label for="wgc-receiver-email"><?php esc_html_e('Receiver email', 'woo-gift-card'); ?>&nbsp;<span class="required">*</span></label>
    	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-email" id="wgc-receiver-email" value="<?php echo esc_attr(get_user_option("user_email")); ?>" required/>
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    	<label for="wgc-receiver-message"><?php esc_html_e('Receiver message', 'woo-gift-card'); ?></label>
    	<textarea class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-message" id="wgc-receiver-message" rows="3" maxlength="<?php echo esc_attr(get_option('wgc-message-length')) ?>"></textarea>
    	<span id="wgc-message-length"></span>
        </p>

        <!-- if has template file-->
	<?php if (!empty($product->get_meta('wgc-template'))): ?>
	    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="wgc-receiver-image"><?php esc_html_e('Gift Voucher image', 'woo-gift-card'); ?></label>
		<input type="file" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-image" id="wgc-receiver-image"/>
	    </p>
	<?php endif; ?>

        <!--add calculate entered text code-->
        <span><em><?php esc_html_e('Will default to account details if empty', 'woo-gift-card') ?></em></span>

    </fieldset>

    <!-- if can be scheduled-->
    <?php if (!empty($product->get_meta('wgc-schedule'))): ?>
	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
	    <label for="wgc-receiver-schedule"><?php esc_html_e('Date to send Gift Voucher', 'woo-gift-card'); ?></label>
	    <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-schedule" id="wgc-receiver-schedule" value="<?php echo esc_attr(date('Y-m-d')) ?>"  min="<?php echo esc_attr(date('Y-m-d')) ?>"/>
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

  <!--<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
    <label for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>" />
</p>
<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
    <label for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>" />
</p>
<div class="clear"></div>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="account_display_name"><?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr($user->display_name); ?>" /> <span><em><?php esc_html_e('This will be how your name will be displayed in the account section and in reviews', 'woocommerce'); ?></em></span>
</p>
<div class="clear"></div>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
    <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" />
</p>-->



