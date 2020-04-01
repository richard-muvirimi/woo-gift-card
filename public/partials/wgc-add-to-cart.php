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
defined('ABSPATH') || exit;
?>
<div class="wgc-options">

	<?php do_action("before-wgc-product-options") ?>

	<fieldset>
		<legend><?php esc_html_e("Gift Voucher recipient details", $plugin_name); ?></legend>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="wgc-receiver-email"><?php esc_html_e('Receiver email(s)', $plugin_name); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-email" id="wgc-receiver-email" value="<?php esc_attr_e(wgc_get_post_var('wgc-receiver-email') ?: get_user_option("user_email")); ?>" required />
			<span><em><?php esc_html_e('Separate multiple recipients with commas.', $plugin_name) ?></em></span>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="wgc-receiver-message"><?php esc_html_e('Receiver message', $plugin_name); ?></label>
			<textarea class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-message" id="wgc-receiver-message" rows="3" maxlength="<?php esc_attr_e(get_option('wgc-message-length')) ?>"></textarea>
			<span id="wgc-message-length"></span>
		</p>

		<!-- if can be scheduled-->
		<?php if ($product->get_coupon_schedule() == "yes") : ?>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="wgc-receiver-schedule"><?php esc_html_e('Date to send Gift Voucher', $plugin_name); ?></label>
				<input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="wgc-receiver-schedule" id="wgc-receiver-schedule" value="<?php esc_attr_e(date('Y-m-d')) ?>" min="<?php esc_attr_e(date('Y-m-d')) ?>" />

				<?php if ($product->get_coupon_expiry_days() !== false) : ?>
					<span><em><?php printf(esc_html('Will expire in %s days after purchase or scheduled send.', $plugin_name), $product->get_coupon_expiry_days()) ?></em></span>
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</fieldset>

	<?php do_action("after-wgc-product-options") ?>

</div>