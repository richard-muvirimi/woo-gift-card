<?php

/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooGiftCard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package Woo_gift_card/Emails/Partials
 * @version 1.0
 */
defined('ABSPATH') || exit();

do_action('woocommerce_email_header', $email_heading, $email);
?>

<?php /* translators: %s: Customer username */ ?>
<p><?php printf(esc_html__('Hi %s,', $plugin_name), esc_html($recipient)); ?></p>
<?php /* translators: %1$s: Coupon Sender, %2$s: Site title, %3$s: My account link */ ?>
<p>
    <?php printf(esc_html__('Just to let you know &mdash; you\'ve received gift voucher(s) from %1$s on %2$s which you can access at %3$s', $plugin_name), '<strong>' . esc_html($coupon_sender) . '</strong>', esc_html($blogname), make_clickable(esc_url(wc_get_page_permalink('myaccount')))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  
    ?>
</p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ($additional_content) {
    echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

do_action('woocommerce_email_footer', $email);
