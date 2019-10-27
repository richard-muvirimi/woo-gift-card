<?php

/**
 * Fired during plugin deactivation
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_card_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{

		if (get_term_by('slug', 'woo-gift-card', 'product_type')) {
			wp_delete_term('woo-gift-card', 'product_type');
		}
	}
}