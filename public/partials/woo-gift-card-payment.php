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

<p class="form-row form-row-wide validate-required validate-email" id="woo-gift-card-email-field">
    <label for="woo-gift-card-email" class="">
        <?php _e('Gift Card Owner', 'woo-gift-card') ?>
        <abbr class="required" title="required">
            *
        </abbr>
    </label>
    <span class="woocommerce-input-wrapper">
        <input class="input-text" type="email" name="woo-gift-card-email" id="woo-gift-card-email"
            placeholder="<?php _e('Email', 'woo-gift-card') ?>" required=" true" autocomplete="email" />
    </span>
</p>

<p class="form-row form-row-wide validate-required" id="woo-gift-card-key-field">
    <label for="woo-gift-card-key" class="">
        <?php _e('Gift Voucher', 'woo-gift-card') ?>
        <abbr class="required" title="required">
            *
        </abbr>
    </label>
    <span class="woocommerce-input-wrapper">
        <input class="input-text" type="text" name="woo-gift-card-key" id="woo-gift-card-key" placeholder=""
            required=" true" />
    </span>
</p>