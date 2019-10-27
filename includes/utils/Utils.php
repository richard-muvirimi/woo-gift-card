<?php

if ( ! defined( 'ABSPATH' ) ) {
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

    public static function getPostLabels($title)
    {
        return array(
            'name' => __($title),
            'singular_name' => __($title),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New ' . $title),
            'edit_item' => __('Edit ' . $title),
            'new_item' => __('New ' . $title),
            'view_item' => __('View ' . $title),
            'search_items' => __('Search ' . $title),
            'not_found' => __('No ' . $title . ' found, Add new.'),
            'not_found_in_trash' => __('No ' . $title . ' found in trash'),
            'parent_item_colon' => __('Parent ' . $title . ':'),
            'all_items' => __($title),
            'archives' => __($title . ' archives'),
            'insert_into_item' => __('Insert into ' . $title . ' profile'),
            'uploaded_to_this_item' => __('Uploaded to ' . $title . ' profile'),
            'menu_name' => __($title),
            'name_admin_bar' => __($title)
        );
    }
}