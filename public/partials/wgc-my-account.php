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

    <table class="shop_table shop_table_responsive my_account_orders table-wgc-vouchers">
        <caption><?php printf(__('Gift vouchers you own (%s).', 'woo-gift-card'), get_user_option("user_email", get_current_user_id())); ?></caption>
        <thead>
    	<tr>
    	    <th>
		    <?php esc_html_e('Coupon Code', 'woo-gift-card') ?>
    	    </th>
    	    <th>
		    <?php esc_html_e('Status', 'woo-gift-card') ?>
    	    </th>
    	    <th>
		    <?php esc_html_e('&nbsp;', 'woo-gift-card') ?>
    	    </th>
    	</tr>
        </thead>

        <tbody>
	    <?php
	    foreach ($coupons as $coupon) :

		$order = wc_get_order($coupon->get_meta("wgc-order"));
		$item = $order->get_item($coupon->get_meta("wgc-order-item"));
		?>
		<tr class="wgc-voucher" id="wgc-voucher-<?php esc_attr_e($coupon->get_id()) ?>">
		    <td data-title="<?php esc_attr_e('Coupon Code', 'woo-gift-card') ?>">
			<?php esc_html_e(wc_format_coupon_code($coupon->get_code())); ?>
		    </td>
		    <td data-title="<?php esc_attr_e('Status', 'woo-gift-card') ?>">
			<?php
			$discounts = new WC_Discounts(WC()->cart);

			$valid = $discounts->is_coupon_valid($coupon);

			is_wp_error($valid) ? _e($valid->get_error_message()) : esc_html_e("Ready to use!", "woo-gift-card");
			?>
		    </td>
		    <td class="wgc-right">
			<a href="JavaScript:void()" class="woocommerce-button button view wgc-btn-more wgc-center" data-coupon="<?php esc_attr_e($coupon->get_id()) ?>">
			    <?php esc_html_e("Details", "woo-gift-card") ?>
			</a>
		    </td>
		</tr>
		<tr class="wgc-more" id="wgc-more-<?php esc_attr_e($coupon->get_id()) ?>" style="display: none;">
		    <td colspan="2">
			<dl>
			    <!--description-->
			    <?php if (!empty($coupon->get_description())): ?>
	    		    <dt><?php esc_html_e("Description", "woo-gift-card") ?></dt>
	    		    <dd>&diamondsuit; <?php _e($coupon->get_description(), "woo-gift-card") ?></dd>
			    <?php endif; ?>

			    <!--discount type-->
			    <?php $discount = wc_get_coupon_types()[$coupon->get_discount_type()]; ?>
			    <dt><?php esc_html_e("Coupon Type", "woo-gift-card") ?></dt>
			    <dd>&diamondsuit; <?php echo $discount . " (" . (strpos($discount, "fixed") !== false ? wc_price($coupon->get_amount()) : $coupon->get_amount() . "%") . ")" ?></dd>

			    <!--usage limits-->
			    <?php if ($coupon->get_usage_limit() > 0): ?>
	    		    <dt><?php esc_html_e("Current Usage / Limit", "woo-gift-card") ?></dt>
	    		    <dd>&diamondsuit; <?php echo $coupon->get_usage_count() . " / " . $coupon->get_usage_limit() ?></dd>
			    <?php endif; ?>

			    <!--date purchased-->
			    <dt><?php esc_html_e("Date purchased", "woo-gift-card") ?></dt>
			    <dd>&diamondsuit; <?php echo wc_format_datetime($order->get_date_completed()) ?></dd>

			    <!--expiry date-->
			    <?php if ($item->get_product()->get_meta('wgc-schedule') == "yes" && $coupon->get_date_expires() != null): ?>
	    		    <dt><?php esc_html_e("Usage Period", "woo-gift-card") ?></dt>
	    		    <dd>&diamondsuit; <?php echo wc_format_datetime((new WC_DateTime())->setTimestamp(strtotime($item->get_meta("wgc-receiver-schedule")))) . " - " . wc_format_datetime($coupon->get_date_expires()) ?></dd>

			    <?php else: ?>
	    		    <dt><?php esc_html_e("Usable From", "woo-gift-card") ?></dt>

				<?php if ($item->get_product()->get_meta('wgc-schedule') == "yes"): ?>
				    <dd>&diamondsuit; <?php echo wc_format_datetime((new WC_DateTime())->setTimestamp(strtotime($item->get_meta("wgc-receiver-schedule")))) ?></dd>
				<?php else: ?>
				    <dd>&diamondsuit; <?php echo wc_format_datetime($coupon->get_date_created()) ?></dd>
				<?php endif; ?>

			    <?php endif; ?>
			</dl>
		    </td>
		    <td class="wgc-right">
			<?php //if there are templates and pdf generation can be done ?>
			<?php if ($item->get_meta("wgc-receiver-template") !== false && wgc_supports_pdf_generation()): ?>
	    		<p>
	    		    <a href="JavaScript:void()" class="woocommerce-button button wgc-btn-view" data-code="<?php esc_attr_e(urlencode($coupon->get_code())) ?>">
				    <?php _e("View Template", "woo-gift-card") ?>
	    		    </a>
	    		</p>
	    		<p>
	    		    <a href="<?php echo esc_url(wgc_download_link() . urlencode($coupon->get_code())); ?>" class="woocommerce-button button">
				    <?php _e("Download PDF", "woo-gift-card") ?>
	    		    </a>
	    		</p>
			<?php endif; ?>

			<p>
			    <a href="JavaScript:void()" class="woocommerce-button button wgc-send-email" data-nonce="<?php esc_attr_e(wp_create_nonce("wgc-send-mail")) ?>" data-which="<?php esc_attr_e($coupon->get_id()) ?>">
				<?php _e("Resend Email", "woo-gift-card") ?>
			    </a>
			</p>
			<p>
			    <a href="JavaScript:void()" class="woocommerce-button button wgc-delete-voucher" data-nonce="<?php esc_attr_e(wp_create_nonce("wgc-delete-voucher")) ?>" data-which="<?php esc_attr_e($coupon->get_id()) ?>">
				<?php _e("Delete Voucher", "woo-gift-card") ?>
			    </a>
			</p>

		    </td>
		</tr>
	    <?php endforeach; ?>
        </tbody>
    </table>
    <form id="wgc-preview-form" class="wgc-preview-form" method="post" target="wgc-preview-frame" action="<?php esc_attr_e(wgc_preview_link()) ?>">
	<?php wc_get_template("wgc-preview-html.php", array(), "", plugin_dir_path(__FILE__) . "preview/"); ?>
    </form>
<?php else : ?>
    <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
        <a class="woocommerce-Button button"
           href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
	       <?php esc_html_e('Go shop', 'woocommerce'); ?>
        </a>
	<?php esc_html_e('No gift vouchers available yet.', 'wooc-gift-card'); ?>
    </div>
<?php endif;