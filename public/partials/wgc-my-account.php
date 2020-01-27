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
	    // 'author' => get_current_user_id(),
	    'post_status' => 'publish',
	    'meta_query' => array(
		array(
		    'key' => 'wgc-order'
		),
		array(
		    'key' => 'wgc-order-item'
		),
		array(
		    'key' => 'wgc-order-item-index'
		),
		array(
		    //add all recipient emails
		    'key' => 'customer_email'
		),
	    )
	)
);

if (!empty($coupons)) :
    ?>

    <table class="shop_table shop_table_responsive my_account_wgc-vouchers">
        <caption><?php printf(__('Gift vouchers you own (%s).', 'woo-gift-card'), get_user_option("user_email")); ?></caption>
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
		$coupon_meta = get_post_meta($coupon->ID);
		?>
		<tr class="woo-gift-card">
		    <td>
			<?php esc_html_e(wc_format_coupon_code($coupon->post_title)); ?>
		    </td>
		    <td>
			<?php esc_html_e(wgc_format_coupon_value($coupon->ID)); ?>
		    </td>
		    <?php
		    $order = wc_get_order(get_post_meta($coupon->ID, "wgc-order", true));
		    $item = $order->get_item(get_post_meta($coupon->ID, "wgc-order-item", true));
		    $has_template = $item->get_meta("wgc-receiver-template");
		    ?>
		    <td colspan=" <?php esc_attr_e($has_template ? 1 : 2) ?>">
			<?php
			$product = $item->get_product();
			if ($product->get_meta("wgc-expiry-days")) {

			    $date = new WC_DateTime();
			    $date->setTimestamp($coupon_meta['date_expires'][0]);

			    esc_html_e(wc_format_datetime($date));
			} else {
			    _e("Never", "woo-gift-card");
			}
			?>
		    </td>
		    <?php if ($has_template): ?>
	    	    <td>
	    		<a target="_blank" href="<?php echo esc_url(get_rest_url(null, $plugin_name . "/v1/coupon/" . urlencode($coupon->post_title))); ?>" class="woocommerce-button button view">
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