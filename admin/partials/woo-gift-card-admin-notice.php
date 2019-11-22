<?php
/**
 * Provide a admin notification area for the plugin
 *
 * This file is used to markup the admin-facing notification aspects of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/admin/partials
 */
defined('ABSPATH') || exit;
?>

<div class="notice <?php echo get_transient('woo-gift-card-notice-class'); ?> is-dismissible">
    <p>
	<?php echo get_transient('woo-gift-card-notice'); ?>
    </p>
</div>