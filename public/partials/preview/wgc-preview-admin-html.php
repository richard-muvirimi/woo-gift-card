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
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

    <head>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" >

	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php
	wp_site_icon();
	_wp_render_title_tag();
	noindex();
	?>

	<style>
	    html, body {
		height: 100%;
		margin: 0;         /* Reset default margin on the body element */
	    }
	    iframe {
		display: block;       /* iframes are inline by default */
		background: #000;
		border: none;         /* Reset default border */
		width: 100%;
		height: 100%;
	    }

	</style>
	<script>

	    function load_template() {
		let form = document.getElementById('wgc-preview-form');
		form.submit();
	    }
	</script>
    </head>
    <body onload="load_template()" >
	<iframe name="wgc-preview-frame" class="wgc-preview-frame" frameborder="0" width="100%" height="100%"></iframe>
	<form action="<?php esc_attr_e(get_rest_url(null, "woo-gift-card/v1/template/")) ?>" id="wgc-preview-form" class="wgc-preview-form" method="post" target="wgc-preview-frame">
	    <input type="hidden" name="wgc-receiver-template" value="<?php esc_attr_e($post->ID) ?>" >
	</form>
    </body>
</html>