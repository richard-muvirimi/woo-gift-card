<?php

/**
 * Class WGC_Email_Coupon_Status file.
 *
 * @package WooGiftCard\Emails
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

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
abstract class WGC_Email_Coupon extends WC_Email
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->customer_email = true;
        $this->template_base = plugin_dir_path(__DIR__);

        // Call parent constructor.
        parent::__construct();
    }

    /**
     * Trigger.
     *
     * @param \WGC_Coupon    $coupon Coupon object.
     */
    public function trigger($coupon)
    {
        $this->setup_locale();

        $this->set_coupon($coupon);

        if ($this->is_enabled() && $this->get_recipient()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }

        $this->restore_locale();
    }

    /**
     *
     * @param \WGC_Coupon $coupon
     */
    protected function set_coupon($coupon)
    {
        $this->object = $coupon;
    }

    /**
     *
     * @return \WGC_Coupon
     */
    protected function get_coupon()
    {
        return $this->object;
    }

    /**
     * Default content to show below main email content.
     *
     * @since 3.7.0
     * @return string
     */
    public function get_default_additional_content()
    {
        return __('We look forward to seeing you soon.', 'woo-gift-card');
    }

    /**
     * Get user display name if they exists else return their email or nothing if multiple users
     * @return string
     */
    protected function get_recipient_name()
    {
        if ($this->get_recipient()) {
            //if one recipient
            $email = explode(",", $this->get_recipient());
            if (count($email) === 1) {

                $user = get_user_by("email", $email);
                return $user !== false ? $user->display_name : $email;
            }
        }
        return "";
    }
}
