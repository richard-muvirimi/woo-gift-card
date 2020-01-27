<?php

/**
 * Class WGC_Email_Coupon_Status file.
 *
 * @package WooGiftCard\Emails
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WGC_Email_Coupon_Status', false)) :

    /**
     * Coupon Status.
     *
     * An email sent to the customer when their gift card balance is changed.
     *
     * @class       WGC_Email_Coupon_Status
     * @version     3.5.0
     * @package     WooGiftCard/Classes/Emails
     * @extends     WC_Email
     */
    class WGC_Email_Coupon_Status extends WC_Email {

	/**
	 * User login name.
	 *
	 * @var string
	 */
	public $user_login;

	/**
	 * User email.
	 *
	 * @var string
	 */
	public $user_email;

	/**
	 * User password.
	 *
	 * @var string
	 */
	public $user_pass;

	/**
	 * Is the password generated?
	 *
	 * @var bool
	 */
	public $password_generated;

	/**
	 * Constructor.
	 */
	public function __construct() {
	    $this->id = 'wgc_coupon_status';
	    $this->customer_email = true;
	    $this->title = __('Gift Voucher Coupon Status', 'woo-gift-card');
	    $this->description = __('Customer "Gift Voucher Coupon" emails are sent to the customer when their "Gift Voucher Coupon" balances change.', 'woo-gift-card');
	    $this->template_base = plugin_dir_path(__DIR__);
	    $this->template_html = 'partials/wgc-coupon-status.php';
	    $this->template_plain = 'partials/plain/wgc-coupon-status.php';

	    // Triggers for this email.
	    //add_action( 'woocommerce_order_status_completed_notification', array( $this, 'trigger' ), 10, 2 );
	    // Call parent constructor.
	    parent::__construct();
	}

	/**
	 * Get email subject.
	 *
	 * @since  3.1.0
	 * @return string
	 */
	public function get_default_subject() {
	    return __('Your {site_title} account has been created!', 'woocommerce');
	}

	/**
	 * Get email heading.
	 *
	 * @since  3.1.0
	 * @return string
	 */
	public function get_default_heading() {
	    return __('Welcome to {site_title}', 'woocommerce');
	}

	/**
	 * Trigger.
	 *
	 * @param int    $user_id User ID.
	 * @param string $user_pass User password.
	 * @param bool   $password_generated Whether the password was generated automatically or not.
	 */
	public function trigger($user_id, $user_pass = '', $password_generated = false) {
	    $this->setup_locale();

	    if ($user_id) {
		$this->object = new WP_User($user_id);

		$this->user_pass = $user_pass;
		$this->user_login = stripslashes($this->object->user_login);
		$this->user_email = stripslashes($this->object->user_email);
		$this->recipient = $this->user_email;
		$this->password_generated = $password_generated;
	    }

	    if ($this->is_enabled() && $this->get_recipient()) {
		$this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
	    }

	    $this->restore_locale();
	}

	/**
	 * Get content html.
	 *
	 * @return string
	 */
	public function get_content_html() {
	    return wc_get_template_html(
		    $this->template_html, array(
		'email_heading' => $this->get_heading(),
		'additional_content' => $this->get_additional_content(),
		'user_login' => $this->user_login,
		'user_pass' => $this->user_pass,
		'blogname' => $this->get_blogname(),
		'password_generated' => $this->password_generated,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $this,
		    )
	    );
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
	    return wc_get_template_html(
		    $this->template_plain, array(
		'email_heading' => $this->get_heading(),
		'additional_content' => $this->get_additional_content(),
		'user_login' => $this->user_login,
		'user_pass' => $this->user_pass,
		'blogname' => $this->get_blogname(),
		'password_generated' => $this->password_generated,
		'sent_to_admin' => false,
		'plain_text' => true,
		'email' => $this,
		    )
	    );
	}

	/**
	 * Default content to show below main email content.
	 *
	 * @since 3.7.0
	 * @return string
	 */
	public function get_default_additional_content() {
	    return __('We look forward to seeing you soon.', 'woocommerce');
	}

    }

    endif;

return new WGC_Email_Coupon_Status();
