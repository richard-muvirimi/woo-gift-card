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

?>

<p><?php echo sprintf(__('Gift vouchers attached to %s', 'woo-gift-card'), get_userdata(get_current_user_id())->user_email); ?>
</p>

<?php

$gift_card_columns =     array(
    'woo-gift-card-key'  => esc_html__('Gift Voucher', 'woo-gift-card'),
    'woo-gift-card-date'    => esc_html__('Date Created', 'woo-gift-card'),
    'woo-gift-card-value'  => esc_html__('Initial Balance', 'woo-gift-card'),
    'woo-gift-card-balance'   => esc_html__('Balance', 'woo-gift-card')
);

$gift_cards = get_posts(
    array(
        'post_type' => 'woo-gift-card',
        'author' => get_current_user_id(),
        'post_status' => 'publish'
    )
);

if ($gift_cards) : ?>

<table class="shop_table shop_table_responsive my_account_woo-gift-card">

    <thead>
        <tr>
            <?php foreach ($gift_card_columns as $column_id => $column_name) : ?>
            <th class="<?php echo esc_attr($column_id); ?>"><span
                    class="nobr"><?php echo esc_html($column_name); ?></span></th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>
        <?php
                foreach ($gift_cards as $gift_card) :
                    $gift_card_data = get_post_meta($gift_card->ID);
                    ?>
        <tr class="woo-gift-card">
            <td>
                <?php echo esc_html($gift_card_data['woo-gift-card-key'][0]); ?>
            </td>
            <td>
                <?php echo esc_html($gift_card->post_date); ?>
            </td>
            <td class="">
                <?php echo esc_html(get_woocommerce_currency_symbol() . $gift_card_data['woo-gift-card-value'][0]); ?>
            </td>
            <td class="">
                <?php echo esc_html(get_woocommerce_currency_symbol() . $gift_card_data['woo-gift-card-balance'][0]); ?>
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