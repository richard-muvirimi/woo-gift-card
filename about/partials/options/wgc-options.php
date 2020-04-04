<?php

/**
 * Options Page
 *
 */
defined('ABSPATH') || exit;

// check user capabilities
if (!current_user_can('manage_options')) {
	return;
}
?>

<div class="wrap">
	<form method="post" action="options.php">
		<?php
		settings_fields('wgc-customise');
		do_settings_sections('wgc-customise');

		submit_button();
		?>
	</form>

	<form method="post" action="options.php">
		<?php
		settings_fields('wgc-generation');
		do_settings_sections('wgc-generation');

		submit_button();
		?>
	</form>

	<form method="post" action="options.php">
		<?php
		settings_fields('wgc-email');
		do_settings_sections('wgc-email');

		submit_button();
		?>
	</form>
</div>