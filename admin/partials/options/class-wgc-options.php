<?php

/**
 * Options Page
 *
 */
defined('ABSPATH') || exit;

class Woo_gift_card_Options {

    public function register_settings() {
	register_setting('wgc-customise', 'wgc-list-shop', array('default' => 'on'));
	register_setting('wgc-customise', 'wgc-thank-you');

	register_setting('wgc-generation', 'wgc-code-length', array('type' => 'integer', 'default' => 12));
	register_setting('wgc-generation', 'wgc-code-special');
	register_setting('wgc-generation', 'wgc-code-prefix');
	register_setting('wgc-generation', 'wgc-code-suffix');

	register_setting('wgc-email', 'wgc-message-length', array('type' => 'integer', 'default' => 300));
	register_setting('wgc-email', 'wgc-message-disclaimer');
	//register_setting('wgc-email', 'wgc-message-bcc');
    }

    public function add_settings_section() {
	add_settings_section(
		'wgc-customise-section', __('General Options', 'woo-gift-card'), array($this, 'print_general_options_section'), 'wgc-customise'
	);

	add_settings_section(
		'wgc-generation-section', __('Gift Voucher Generation', 'woo-gift-card'), array($this, 'print_generation_options_section'), 'wgc-generation'
	);

	add_settings_section(
		'wgc-email-section', __('Gift Voucher Email Options', 'woo-gift-card'), array($this, 'print_email_options_section'), 'wgc-email'
	);
    }

    public function do_customise_options() {

	add_settings_field(
		'wgc-list-shop', __('Gift Vouchers in shop', 'woo-gift-card'), array($this, 'output_list_vouchers_field'), 'wgc-customise', 'wgc-customise-section', array('label_for' => 'wgc-list-shop')
	);

	add_settings_field(
		'wgc-thank-you', __('Thank you gift vouchers', 'woo-gift-card'), array($this, 'output_thank_you_vouchers_field'), 'wgc-customise', 'wgc-customise-section', array('label_for' => 'wgc-thank-you')
	);
    }

    public function do_generation_options() {
	add_settings_field('wgc-code-length', __('Gift Voucher length', 'woo-gift-card'), array($this, 'output_code_length_field'), 'wgc-generation', 'wgc-generation-section', array('label_for' => 'wgc-code-length')
	);

	add_settings_field('wgc-code-special', __('Gift Voucher special characters', 'woo-gift-card'), array($this, 'output_code_special_field'), 'wgc-generation', 'wgc-generation-section', array('label_for' => 'wgc-code-special')
	);

	add_settings_field('wgc-code-prefix', __('Gift Voucher prefix', 'woo-gift-card'), array($this, 'output_code_prefix_field'), 'wgc-generation', 'wgc-generation-section', array('label_for' => 'wgc-code-prefix')
	);

	add_settings_field('wgc-code-suffix', __('Gift Voucher suffix', 'woo-gift-card'), array($this, 'output_code_suffix_field'), 'wgc-generation', 'wgc-generation-section', array('label_for' => 'wgc-code-suffix')
	);
    }

    public function do_email_options() {
	add_settings_field('wgc-message-length', __('Gift Voucher message length', 'woo-gift-card'), array($this, 'output_message_length_field'), 'wgc-email', 'wgc-email-section', array('label_for' => 'wgc-message-length')
	);

	add_settings_field('wgc-message-disclaimer', __('Gift Voucher message disclaimer', 'woo-gift-card'), array($this, 'output_message_disclaimer_field'), 'wgc-email', 'wgc-email-section', array('label_for' => 'wgc-message-disclaimer')
	);
    }

    public function init() {

	$this->register_settings();

	$this->add_settings_section();

	$this->do_customise_options();

	$this->do_generation_options();

	$this->do_email_options();
    }

    /**
     * Print the Section text
     */
    public function print_general_options_section() {
	echo "<p>" . __('General Gift Voucher Options.', 'woo-gift-card') . "</p>";
    }

    /**
     * Print the Section text
     */
    public function print_generation_options_section() {
	echo "<p>" . __('Options pertaining to the generation of gift vouchers.', 'woo-gift-card') . "</p>";
    }

    /**
     * Print the Section text
     */
    public function print_email_options_section() {
	echo "<p>" . __('Email Templating Options.', 'woo-gift-card') . "</p>";
    }

    public function output_thank_you_vouchers_field() {

	$setting = get_option('wgc-thank-you');

	$attr = array(
	    'type' => "checkbox",
	    'class' => "checkbox",
	    'checked' => $setting == "on" ? 'checked' : "",
	    'name' => "wgc-thank-you",
	    'id' => "wgc-thank-you"
	);

	$this->outputfield($attr);

	echo '<p class="description">' . __("check to enable thank you gift vouchers.", "woo-gift-card") . '</p>';
    }

    public function output_code_length_field() {

	$setting = get_option('wgc-code-length');

	$attr = array(
	    'type' => "number",
	    'min' => 6,
	    'value' => intval($setting),
	    'name' => "wgc-code-length",
	    'id' => "wgc-code-length",
	    'required' => "true"
	);

	$this->outputfield($attr);
    }

    public function output_code_special_field() {

	$setting = get_option('wgc-code-special');

	$attr = array(
	    'type' => "checkbox",
	    'class' => "checkbox",
	    'checked' => $setting == "on" ? 'checked' : "",
	    'name' => "wgc-code-special",
	    'id' => "wgc-code-special"
	);

	$this->outputfield($attr);

	echo '<p class="description">' . __("Include special characters in generated voucher codes.", "woo-gift-card") . '</p>';
    }

    public function output_code_prefix_field() {

	$setting = get_option('wgc-code-prefix');

	$attr = array(
	    'type' => "text",
	    'value' => $setting,
	    'name' => "wgc-code-prefix",
	    'id' => "wgc-code-prefix"
	);

	$this->outputfield($attr);
    }

    public function output_code_suffix_field() {

	$setting = get_option('wgc-code-suffix');

	$attr = array(
	    'type' => "text",
	    'value' => $setting,
	    'name' => "wgc-code-suffix",
	    'id' => "wgc-code-suffix"
	);

	$this->outputfield($attr);
    }

    public function output_list_vouchers_field() {

	$setting = get_option('wgc-list-shop', 'on');

	$attr = array(
	    'type' => "checkbox",
	    'class' => "checkbox",
	    'checked' => $setting == "on" ? 'checked' : "",
	    'name' => "wgc-list-shop",
	    'id' => "wgc-list-shop"
	);

	$this->outputfield($attr);

	echo '<p class="description">' . __("List gift voucher products in store.", "woo-gift-card") . '</p>';
    }

    public function output_message_length_field() {

	$setting = get_option('wgc-message-length');

	$attr = array(
	    'type' => "number",
	    'min' => 150,
	    'value' => intval($setting),
	    'name' => "wgc-message-length",
	    'id' => "wgc-message-length",
	    'required' => "true"
	);

	$this->outputfield($attr);

	echo '<p class="description">' . __("The maximum message length a customer can send.", "woo-gift-card") . '</p>';
    }

    public function output_message_disclaimer_field() {

	$setting = get_option('wgc-message-disclaimer');

	wp_editor($setting, "wgc-message-disclaimer", array(
	    'media_buttons' => false,
	    'editor_height' => 100));

	echo '<p class="description">' . __("The disclaimer message to show to customer.", "woo-gift-card") . '</p>';
    }

    public function outputfield($attr) {
	$attributes = array();
	foreach ($attr as $attribute => $value) {
	    if (!empty($value)) {
		$attributes[] = esc_attr($attribute) . ' = "' . esc_attr($value) . '"';
	    }
	}

	echo '<input ' . implode(' ', $attributes) . '>';
    }

}

$options = new Woo_gift_card_Options();
$options->init();

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
