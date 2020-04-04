<?php

/**
 * Provide a preview template view for admin about
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/about/partials/admin
 */
defined('ABSPATH') || exit;
?>

<table class="wgc-environment">
	<thead>
		<tr>
			<th colspan="2">
				<?php esc_html_e("Environment", $plugin_name) ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<?php esc_html_e("PHP version:", $plugin_name) ?>
			</td>
			<td>
				<?php
				if (version_compare($php_version, $php_required_version, ">=")) :
				?>
					<strong class="wgc-status-ok">
						<span class="dashicons dashicons-yes"></span>
						<?php esc_html_e($php_version); ?>
					</strong>
				<?php else : ?>
					<strong class="wgc-status-upgrade">
						<span class="dashicons dashicons-warning"></span>
						<?php printf(esc_html__("At least %s required!", $plugin_name), $php_required_version); ?>
					</strong>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e("WordPress version:", $plugin_name) ?>
			</td>
			<td>
				<?php
				if (version_compare($wp_version, $wp_required_version, ">=")) :
				?>
					<strong class="wgc-status-ok">
						<span class="dashicons dashicons-yes"></span>
						<?php esc_html_e($wp_version); ?>
					</strong>
				<?php else : ?>
					<strong class="wgc-status-upgrade">
						<span class="dashicons dashicons-warning"></span>
						<?php printf(esc_html__("At least %s required!", $plugin_name), $wp_required_version); ?>
					</strong>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e("WooCommerce version:", $plugin_name) ?>
			</td>
			<td>
				<?php
				if (version_compare($wc_version, $wc_required_version, ">=")) :
				?>
					<strong class="wgc-status-ok">
						<span class="dashicons dashicons-yes"></span>
						<?php esc_html_e($wc_version); ?>
					</strong>
				<?php else : ?>
					<strong class="wgc-status-upgrade">
						<span class="dashicons dashicons-warning"></span>
						<?php printf(esc_html__("At least %s required!", $plugin_name), $wc_required_version); ?>
					</strong>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e("WooCommerce Coupons:", $plugin_name) ?>
			</td>
			<td>
				<?php
				if (wc_coupons_enabled()) :
				?>
					<strong class="wgc-status-ok">
						<span class="dashicons dashicons-yes"></span>
						<?php esc_html_e("Enabled", $plugin_name); ?>
					</strong>
				<?php else : ?>
					<strong class="wgc-status-upgrade">
						<span class="dashicons dashicons-warning"></span>
						<?php esc_html_e("Disabled", $plugin_name); ?>
					</strong>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>