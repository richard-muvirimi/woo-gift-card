<?php

/**
 * Provide a preview template view for both admin and front end
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/public/partials/preview
 */
defined('ABSPATH') || exit;
?>
<div class="wgc-container">
	<?php do_action("wgc-about-before-header") ?>
	<div class="wgc-head">
		<?php do_action("wgc-about-before-header-content") ?>
		<h1 class="wgc-center"><?php esc_html_e($plugin_official_name) ?></h1>
		<h4 class="wgc-center"><?php _e($plugin_description) ?></h4>
		<h5 class="wgc-center"><?php esc_html_e($plugin_version) ?></h5>
		<?php do_action("wgc-about-after-header-content") ?>
	</div>
	<?php do_action("wgc-about-after-header") ?>
	<div class="wgc-content">
		<button class="wgc-tablink" data-which="home"><?php _e("Home", $plugin_name) ?></button>
		<button class="wgc-tablink open-tab" data-which="system-status"><?php _e("Status", $plugin_name) ?></button>
		<button class="wgc-tablink" data-which="help"><?php _e("Help", $plugin_name) ?></button>
		<button class="wgc-tablink" data-which="about"><?php _e("About", $plugin_name) ?></button>
		<?php do_action("wgc-after-tablink") ?>

		<div id="home" class="wgc-tabcontent">
			<?php do_action("wgc-tabcontent-home") ?>
		</div>

		<div id="system-status" class="wgc-tabcontent">
			<?php do_action("wgc-tabcontent-system-status") ?>
		</div>

		<div id="help" class="wgc-tabcontent">
			<?php do_action("wgc-tabcontent-help") ?>
		</div>

		<div id="about" class="wgc-tabcontent">
			<?php do_action("wgc-tabcontent-about") ?>
		</div>

		<?php do_action("wgc-after-tabcontent") ?>

	</div>
</div>