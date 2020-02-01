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

$coupons = wgc_get_coupons_for_customer();

if (!empty($coupons)) :
    ?>

    <table class="shop_table shop_table_responsive my_account_wgc-vouchers">
        <caption><?php printf(__('Gift vouchers you own (%s).', 'woo-gift-card'), get_user_option("user_email", get_current_user_id())); ?></caption>
        <thead>
    	<tr>
		<?php
		$gift_card_columns = array(
		    'wgc-code' => esc_html__('Coupon', 'woo-gift-card'),
		    'wgc-amount' => esc_html__('Amount', 'woo-gift-card'),
		    'wgc-expiry' => esc_html__('Expires', 'woo-gift-card'),
		    'wgc-actions' => esc_html__('Actions', 'woo-gift-card'));

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

		$order = wc_get_order($coupon->get_meta("wgc-order"));
		?>
		<tr class="woo-gift-card">
		    <td>
			<?php esc_html_e(wc_format_coupon_code($coupon->get_code())); ?>
		    </td>
		    <td class="">
			<?php esc_html_e(wgc_format_coupon_value($coupon->get_id())); ?>
		    </td>
		    <?php
		    $item = $order->get_item($coupon->get_meta("wgc-order-item"));

		    $has_template = $item->get_meta("wgc-receiver-template");
		    ?>
		    <td colspan=" <?php esc_attr_e($has_template ? 1 : 2) ?>">
			<?php
			$product = $item->get_product();
			if ($product->get_meta("wgc-expiry-days")) {

			    esc_html_e(wc_format_datetime($coupon->get_date_expires()));
			} else {
			    _e("Never", "woo-gift-card");
			}
			?>
		    </td>
		    <?php if ($has_template): ?>
	    	    <td>
	    		<a href="javascript:void()" class="woocommerce-button button view">
				<?php _e("More", "woo-gift-card") ?>
	    		</a>
	    		<a target="_blank" href="<?php echo esc_url(get_rest_url(null, $plugin_name . "/v1/coupon/" . urlencode($coupon->get_code()))); ?>" class="woocommerce-button button view">
				<?php _e("View", "woo-gift-card") ?>
	    		</a>
	    	    </td>
		    <?php endif; ?>
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