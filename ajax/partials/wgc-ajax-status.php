<?php

/**
 * Displays in woocommerce my account page, All the gift vouchers a customer owns
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/ajax/partials
 */
defined('ABSPATH') || exit;
?>

<div class="woocommerce-Message woocommerce-Message--<?php esc_attr_e($status); ?> woocommerce-<?php esc_attr_e($status); ?>">
    <span><?php esc_html_e($message, $plugin_name); ?></span>
</div>