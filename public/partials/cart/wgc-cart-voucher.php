<?php
/**
 * The page where a customer can apply a gift voucher
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public/partials/preview
 */
defined('ABSPATH') || exit;
?>

<tr>
    <td colspan="6" class="actions">
	<div class="coupon">
	    <label for="wgc-coupon-code"><?php esc_html_e('Gift Voucher:', 'woo-gift-card'); ?></label>
	    <input type="text" name="wgc-coupon-code" class="input-text" id="wgc-coupon-code" value="" placeholder="<?php esc_attr_e('Gift Voucher', 'woo-gift-card'); ?>" />
	    <button type="submit" class="button" name="wgc-apply-coupon" value="<?php esc_attr_e('Apply Gift Voucher', 'woo-gift-card'); ?>"><?php esc_attr_e('Apply Gift Voucher', 'woo-gift-card'); ?></button>
	</div>

	<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

	<?php //wp_nonce_field('wgc-voucher', 'woocommerce-cart-nonce'); ?>
    </td>
</tr>

