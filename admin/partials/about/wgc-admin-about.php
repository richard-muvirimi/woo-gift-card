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
<h1 class="wgc-about">
    <?php esc_html_e($plugin_name) ?>
    <p>
	<?php _e($plugin_description) ?>
    </p>
    <p>
	<?php esc_html_e($plugin_version) ?>
    </p>
</h1>

<p>
    <?php _e("The below listed software minimum versions and dependencies are required for the plugin to run correctly, otherwise we cannot gurantee the smooth operation of the plugin.", "woo-gift-card") ?>
</p>
<div class="wgc-flex-container">
    <table class="wgc-versions">
	<caption>
	    <h2>
		<?php esc_html_e("Recommended Software Versions", "woo-gift-card") ?>
	    </h2>
	</caption>
	<thead>
	    <tr>
		<th>
		    <?php esc_html_e("Environment (Version)", "woo-gift-card") ?>
		</th>
		<th>
		    <?php esc_html_e("Requires (At Least)", "woo-gift-card") ?>
		</th>
		<th>
		    <?php esc_html_e("Current", "woo-gift-card") ?>
		</th>
		<th>
		    <?php esc_html_e("Status", "woo-gift-card") ?>
		</th>
	    </tr>
	</thead>
	<tbody>
	    <tr>
		<td>
		    <?php esc_html_e("Php", "woo-gift-card") ?>
		</td>
		<td>
		    <?php esc_html_e($php_required_version); ?>
		</td>
		<td>
		    <?php esc_html_e($php_version); ?>
		</td>
		<td>
		    <?php if (version_compare($php_version, $php_required_version, ">=")) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("OK", "woo-gift-card"); ?>
    		    </strong>
		    <?php else: ?>
    		    <strong class="wgc-status-upgrade">
			    <?php esc_html_e("Need Upgrade", "woo-gift-card"); ?>
			<?php endif; ?>
		    </strong>
		</td>
	    </tr>
	    <tr>
		<td>
		    <?php esc_html_e("WordPress", "woo-gift-card") ?>
		</td>
		<td>
		    <?php esc_html_e($wp_required_version); ?>
		</td>
		<td>
		    <?php esc_html_e($wp_version); ?>
		</td>
		<td>
		    <?php if (version_compare($wp_version, $wp_required_version, ">=")) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("OK", "woo-gift-card"); ?>
    		    </strong>
		    <?php else: ?>
    		    <strong class="wgc-status-upgrade">
			    <?php esc_html_e("Need Upgrade", "woo-gift-card"); ?>
			<?php endif; ?>
		    </strong>
		</td>
	    </tr>
	    <tr>
		<td>
		    <?php esc_html_e("WooCommerce", "woo-gift-card") ?>
		</td>
		<td>
		    <?php esc_html_e($wc_required_version); ?>
		</td>
		<td>
		    <?php esc_html_e($wc_version); ?>
		</td>
		<td>
		    <?php if (version_compare($wc_version, $wc_required_version, ">=")) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("OK", "woo-gift-card"); ?>
    		    </strong>
		    <?php else: ?>
    		    <strong class="wgc-status-upgrade">
			    <?php esc_html_e("Need Upgrade", "woo-gift-card"); ?>
			<?php endif; ?>
		    </strong>
		</td>
	    </tr>
	</tbody>
    </table>

    <table class="wgc-dependencies">
	<caption>
	    <h2>
		<?php esc_html_e("Required Dependencies", "woo-gift-card") ?>
	    </h2>
	</caption>
	<thead>
	    <tr>
		<th>
		    <?php esc_html_e("Extension", "woo-gift-card") ?>
		</th>
		<th>
		    <?php esc_html_e("Status", "woo-gift-card") ?>
		</th>
	    </tr>
	</thead>
	<tbody>
	    <tr>
		<td>
		    <?php esc_html_e("MBString", "woo-gift-card") ?>
		</td>
		<td>
		    <?php if ($MBString) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("Installed", "woo-gift-card"); ?>
    		    </strong>
		    <?php else: ?>
    		    <strong class="wgc-status-upgrade">
			    <?php esc_html_e("Required", "woo-gift-card"); ?>
			<?php endif; ?>
		    </strong>
		</td>
	    </tr>
	    <tr>
		<td>
		    <?php esc_html_e("DOM", "woo-gift-card") ?>
		</td>
		<td>
		    <?php if ($DOM) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("Installed", "woo-gift-card"); ?>
    		    </strong>
		    <?php else: ?>
    		    <strong class="wgc-status-upgrade">
			    <?php esc_html_e("Required", "woo-gift-card"); ?>
			<?php endif; ?>
		    </strong>
		</td>
	    </tr>
	    <tr>
		<td>
		    <?php esc_html_e("GD or Imagick", "woo-gift-card") ?>
		</td>
		<td>
		    <?php if ($Imagick && $GD) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("Both Installed", "woo-gift-card"); ?>
    		    </strong>
		    <?php elseif ($Imagick) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("Imagick Installed", "woo-gift-card"); ?>
    		    </strong>
		    <?php elseif ($GD) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("GD Installed", "woo-gift-card"); ?>
    		    </strong>
		    <?php else: ?>
    		    <strong class="wgc-status-upgrade">
			    <?php esc_html_e("At least one Required", "woo-gift-card"); ?>
			<?php endif; ?>
		    </strong>
		</td>
	    </tr>
	    <tr>
		<td>
		    <?php esc_html_e("WooCommerce Coupons", "woo-gift-card") ?>
		</td>
		<td>
		    <?php if (wc_coupons_enabled()) : ?>
    		    <strong class="wgc-status-ok">
			    <?php esc_html_e("Enabled", "woo-gift-card"); ?>
    		    </strong>
		    <?php else: ?>
    		    <strong class="wgc-status-upgrade">
			    <?php esc_html_e("Required", "woo-gift-card"); ?>
			<?php endif; ?>
		    </strong>
		</td>
	    </tr>
	</tbody>
    </table>
</div>