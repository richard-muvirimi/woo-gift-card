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
//wp_nonce_field('wgc-preview', 'wgc-preview-nonce');
?>
<!DOCTYPE html>
<html>
    <head>
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
	<form action="<?php esc_attr_e(get_rest_url(null, "woo-gift-card/v1/template/")) ?>" id="wgc-preview-form" class="wgc-preview-form" method="post" target="wgc-preview-frame">
	    <input type="hidden" name="wgc-receiver-template" value="<?php esc_attr_e($_GET["preview_id"]) ?>" >
	</form>
	<iframe name="wgc-preview-frame" class="wgc-preview-frame" frameborder="0" width="100%" height="100%"></iframe>
    </body>
</html>