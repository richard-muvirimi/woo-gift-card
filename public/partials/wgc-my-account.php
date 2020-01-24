<?php
/**
 * Displays in woocommerce my account page, All the gift vouchers a customer owns
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public/partials
 */
defined('ABSPATH') || exit;

$coupons = get_posts(
	array(
	    "numberposts" => -1,
	    'post_type' => 'shop_coupon',
	    'author' => get_current_user_id(),
	    'post_status' => 'publish',
	    "meta_key" => "wgc-order"
	)
);

if ($coupons) :
    ?>

    <table class="shop_table shop_table_responsive my_account_wgc-vouchers">
        <caption><?php printf(__('Gift vouchers you own (%s).', 'woo-gift-card'), get_user_option("user_email")); ?></caption>
        <thead>
    	<tr>
		<?php
		$gift_card_columns = array(
		    'wgc-code' => esc_html__('Gift Voucher', 'woo-gift-card'),
		    'wgc-type' => esc_html__('Type', 'woo-gift-card'),
		    'wgc-amount' => esc_html__('Amount', 'woo-gift-card'),
		    'wgc-description' => esc_html__('Description', 'woo-gift-card'),
		    'wgc-expiry' => esc_html__('Expiry Date', 'woo-gift-card'));

		foreach ($gift_card_columns as $column_id => $column_name) :
		    ?>
		    <th class="<?php esc_attr_e($column_id); ?>">
			<span class="nobr">
			    <?php esc_html_e($column_name); ?>
			</span>
		    </th>
		<?php endforeach; ?>
    	</tr>
        </thead>

        <tbody>
	    <?php
	    foreach ($coupons as $coupon) :
		$coupon_meta = get_post_meta($coupon->ID);
		?>
		<tr class="woo-gift-card">
		    <td>
			<?php esc_html_e(wc_format_coupon_code($coupon->post_title)); ?>
		    </td>
		    <td>
			<?php esc_html_e(wc_get_coupon_types()[$coupon_meta['discount_type'][0]]); ?>
		    </td>
		    <td>
			<?php esc_html_e(wgc_format_coupon_value($coupon->ID)); ?>
		    </td>
		    <td>
			<?php esc_html_e($coupon->post_excerpt); ?>
		    </td>
		    <td>
			<?php
			$date = new WC_DateTime();
			$date->setTimestamp($coupon_meta['date_expires'][0]);

			esc_html_e(wc_format_datetime($date));
			?>
		    </td>
		</tr>
	    <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
        <a class="woocommerce-Button button"
           href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
	       <?php esc_html_e('Go shop', 'woocommerce'); ?>
        </a>
	<?php esc_html_e('No gift vouchers available yet.', 'wooc-gift-card'); ?>
    </div>
<?php endif; ?>