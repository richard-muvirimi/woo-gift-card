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
?>

<button type="submit" id="wgc-preview" name="wgc-preview" value="<?php echo esc_attr($product->get_id()); ?>" class="button alt"><?php _e("Preview", 'woo-gift-card'); ?></button>