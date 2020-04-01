<?php

/**
 * Class WGC_Email_Coupon_Status file.
 *
 * @package WooGiftCard\Emails
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('WGC_Email_Coupon_Received', false)) :

	/**
	 * Coupon Status.
	 *
	 * An email sent to the customer when they receive a gift voucher.
	 *
	 * @class       WC_Email_Coupon_Received
	 * @version     3.5.0
	 * @package     WooGiftCard/Classes/Emails
	 * @extends     WC_Email
	 */
	class WGC_Email_Coupon_Received extends WGC_Email_Coupon
	{

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->id = 'wgc_coupon_published';
			$this->title = __('Coupon Received (Gift Voucher)', 'woo-gift-card');
			$this->description = __('New Coupon Emails are sent to clients if they are listed as the receipint of a gift voucher purchase.', 'woo-gift-card');
			$this->template_html = 'partials/wgc-coupon-received.php';
			$this->template_plain = 'partials/plain/wgc-coupon-received.php';

			// Triggers for this email.
			add_action('wgc_coupon_published_notification', array($this, 'trigger'));
			add_action('wgc_coupon_email_notification', array($this, 'trigger'));

			// Call parent constructor.
			parent::__construct();
		}

		/**
		 * Get email subject.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_subject()
		{
			return __('[{site_title}]: New Gift Voucher', 'woo-gift-card');
		}

		/**
		 * Get email heading.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_heading()
		{
			return $this->get_default_subject();
		}

		/**
		 * Trigger.
		 *
		 * @param \WC_Coupon    $coupon Coupon object.
		 */
		public function trigger($coupon)
		{
			$this->setup_locale();

			$this->set_coupon($coupon);
			$this->recipient = wgc_get_emails_for_coupon($coupon);

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
		public function get_content_html()
		{

			$order = wc_get_order($this->get_coupon()->get_meta("wgc-order"));

			return wc_get_template_html(
				$this->template_html,
				array(
					'email_heading' => $this->get_heading(),
					'additional_content' => $this->get_additional_content(),
					'blogname' => $this->get_blogname(),
					'coupon_sender' => get_user_option("display_name", is_object($order) ? $order->get_customer_id() : 0),
					'sent_to_admin' => false,
					"recipient" => $this->get_recipient_name(),
					'plain_text' => false,
					'email' => $this,
					'plugin_name' => 'woo-gift-card'
				),
				"",
				$this->template_base
			);
		}

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain()
		{

			$order = wc_get_order($this->get_coupon()->get_meta("wgc-order"));

			return wc_get_template_html(
				$this->template_plain,
				array(
					'email_heading' => $this->get_heading(),
					'additional_content' => $this->get_additional_content(),
					'blogname' => $this->get_blogname(),
					'coupon_sender' => get_user_option("display_name", is_object($order) ? $order->get_customer_id() : 0),
					'sent_to_admin' => false,
					"recipient" => $this->get_recipient_name(),
					'plain_text' => true,
					'email' => $this,
					'plugin_name' => 'woo-gift-card'
				),
				"",
				$this->template_base
			);
		}
	}

endif;

return new WGC_Email_Coupon_Received();
