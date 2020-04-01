<?php

/**
 * Displays in woocommerce my account page, All the gift vouchers a customer owns or can apply
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public/partials
 */
defined('ABSPATH') || exit;

$coupons = wgc_get_coupons_for_customer();

if (!empty($coupons)) :
?>

	<table class="shop_table shop_table_responsive my_account_orders table-wgc-vouchers">
		<caption><?php printf(__('Gift vouchers you own (%s) or can apply.', $plugin_name), get_user_option("user_email", get_current_user_id())); ?></caption>
		<thead>
			<tr>
				<th>
					<?php esc_html_e('Coupon Code', $plugin_name) ?>
				</th>
				<th>
					<?php esc_html_e('Status', $plugin_name) ?>
				</th>
				<th>
					<?php esc_html_e('&nbsp;', $plugin_name) ?>
				</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($coupons as $coupon) : ?>
				<tr class="wgc-voucher" id="wgc-voucher-<?php esc_attr_e($coupon->get_id()) ?>">
					<td data-title="<?php esc_attr_e('Coupon Code', $plugin_name) ?>">
						<?php esc_html_e(wc_format_coupon_code($coupon->get_code())); ?>
					</td>
					<td data-title="<?php esc_attr_e('Status', $plugin_name) ?>">
						<?php
						$discounts = new WC_Discounts(WC()->cart);

						$valid = $discounts->is_coupon_valid($coupon);

						is_wp_error($valid) ? _e($valid->get_error_message()) : esc_html_e("Ready to use!", $plugin_name);
						?>
					</td>
					<td class="wgc-right">
						<a href="JavaScript:void()" class="woocommerce-button button view wgc-btn-more wgc-center" data-coupon="<?php esc_attr_e($coupon->get_id()) ?>">
							<?php esc_html_e("Details", $plugin_name) ?>
						</a>
					</td>
				</tr>
				<tr class="wgc-more" id="wgc-more-<?php esc_attr_e($coupon->get_id()) ?>" style="display: none;">
					<td colspan="2">
						<dl>
							<!--description-->
							<?php if (!empty($coupon->get_description())) : ?>
								<dt><?php esc_html_e("Description", $plugin_name) ?></dt>
								<dd>&diamondsuit; <?php _e($coupon->get_description(), $plugin_name) ?></dd>
							<?php endif; ?>

							<!--discount type-->
							<?php $discount = wc_get_coupon_types()[$coupon->get_discount_type()]; ?>
							<dt><?php esc_html_e("Coupon Type", $plugin_name) ?></dt>
							<dd>&diamondsuit; <?php echo $discount . " (" . (strpos($discount, "fixed") !== false ? wc_price($coupon->get_amount()) : $coupon->get_amount() . "%") . ")" ?></dd>

							<!--usage limits-->
							<?php if ($coupon->get_usage_limit() > 0) : ?>
								<dt><?php esc_html_e("Current Usage / Limit", $plugin_name) ?></dt>
								<dd>&diamondsuit; <?php echo $coupon->get_usage_count() . " / " . $coupon->get_usage_limit() ?></dd>
							<?php endif; ?>

							<!--date purchased-->
							<dt><?php esc_html_e("Date purchased", $plugin_name) ?></dt>
							<dd>&diamondsuit; <?php echo wc_format_datetime($coupon->get_date_created()) ?></dd>

							<!--expiry date-->
							<?php if ($coupon->get_date_expires()) : ?>
								<dt><?php esc_html_e("Usage Period", $plugin_name) ?></dt>
								<dd>&diamondsuit; <?php echo wc_format_datetime($coupon->get_date_created()) . " - " . wc_format_datetime($coupon->get_date_expires()) ?></dd>

							<?php else : ?>
								<dt><?php esc_html_e("Usable From", $plugin_name) ?></dt>
								<dd>&diamondsuit; <?php echo wc_format_datetime($coupon->get_date_created()) ?></dd>

							<?php endif; ?>
						</dl>
					</td>
					<td class="wgc-right">

						<p>
							<a href="JavaScript:void()" class="woocommerce-button button wgc-send-email" data-nonce="<?php esc_attr_e(wp_create_nonce("wgc-send-mail")) ?>" data-which="<?php esc_attr_e($coupon->get_id()) ?>">
								<?php _e("Resend Email", $plugin_name) ?>
							</a>
						</p>
						<p>
							<a href="JavaScript:void()" class="woocommerce-button button wgc-delete-voucher" data-nonce="<?php esc_attr_e(wp_create_nonce("wgc-delete-voucher")) ?>" data-which="<?php esc_attr_e($coupon->get_id()) ?>">
								<?php _e("Delete Voucher", $plugin_name) ?>
							</a>
						</p>

					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else : ?>
	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
			<?php esc_html_e('Go shop', 'woocommerce'); ?>
		</a>
		<?php esc_html_e('No gift vouchers available yet.', 'wooc-gift-card'); ?>
	</div>
<?php endif;
