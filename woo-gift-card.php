<?php

/**
 * The Main Woocommerce Gift Card Plugin.
 *
 * @link              tyganeutronics.com
 * @since             1.0.0
 * @package           Woo_gift_card
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Gift Voucher
 * Plugin URI:        tyganeutronics.com
 * Description:       The Main Woocommerce Gift Voucher Plugin.
 * Requires PHP:      5.6
 * Requires at least: 5.0.0
 * Version:           1.0.0
 * Author:            Richard Muvirimi
 * Author URI:        tyganeutronics.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo_gift_card
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to:   3.9.0
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WOO_GIFT_CARD_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo_gift_card-activator.php
 */
function activate_wgc_main() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-gift-card-activator.php';
    WGC_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo_gift_card-deactivator.php
 */
function deactivate_wgc_main() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-gift-card-deactivator.php';
    WGC_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wgc_main');
register_deactivation_hook(__FILE__, 'deactivate_wgc_main');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woo-gift-card.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wgc_main() {

    $plugin = new Woo_gift_card();
    $plugin->run();
}

run_wgc_main();

