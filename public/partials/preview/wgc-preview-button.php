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
<button type="submit" formtarget="wgc-preview-frame" id="wgc-product" name="wgc-product" value="<?php esc_attr_e($product->get_id()); ?>" class="button alt"><?php _e("Preview Template", 'woo-gift-card'); ?></button>
