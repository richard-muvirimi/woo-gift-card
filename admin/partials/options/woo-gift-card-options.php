<?php
/**
 * Options Page
 *
 */
defined('ABSPATH') || exit;


/**
 * qr code
 * replace, both, disable
 * ecc
 * size width and height
 * margin
 * barcode/qrcode
 *
 * gift card can applied on items on sale
 *
 * //front end
 * date format
 * message lenth
 *
 * enable bcc
 * enable pdf generation
 * size a3 a4
 *
 * todo export gift cards
 *
 */
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

	settings_fields('wgc-generation');
	do_settings_sections('wgc-generation');

	settings_fields('wgc-email');
	do_settings_sections('wgc-email');

	submit_button();
	?>
    </form>
</div>