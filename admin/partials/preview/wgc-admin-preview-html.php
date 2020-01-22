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
<form id="wgc-preview-form" class="wgc-preview-form" method="post">
    <?php wc_get_template("wgc-preview-html.php", array(), "", plugin_dir_path(dirname(__DIR__)) . "../public/partials/preview/"); ?>
</form>