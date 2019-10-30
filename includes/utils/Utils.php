<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The file that defines the plugin util class
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/utils
 */

/**
 * The file that defines the plugin util class
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/utils
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_cards_utils
{

    /**
     * Gets a unique gift card key for each customer
     *
     * @param string $email
     * @return void
     */
    public static function get_unique_key($email)
    {

        $key = strtoupper(wp_generate_password());

        $giftCards = get_posts(array(
            'posts_per_page' => 1,
            'post_type' => 'woo-gift-card',
            'author' => $email,
            'meta_key' => 'woo-gift-card-key',
            'meta_value' => $key,
            'fields' => 'ids'
        ));

        //if exists then redo
        if (count($giftCards)) {

            return Woo_gift_cards_utils::get_unique_key($email);
        }

        return $key;
    }
}