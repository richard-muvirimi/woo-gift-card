<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The file that defines the gift card product type
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/modal/wgc-data
 */

/**
 * The file that defines the gift card product type
 *
 * @since      1.0.0
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/includes/model/wgc-data
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
abstract class WGC_Data extends WC_Data {

    /**
     * If the object has an ID, read using the data store.
     *
     * @since 1.0.0
     */
    protected function read_object_from_database() {
	$this->data_store = WC_Data_Store::load($this->get_object_name());

	if ($this->get_id() > 0) {
	    $this->data_store->read($this);
	}
    }

    /**
     * Get object name for use on object load
     *
     * @since 1.0.0
     */
    abstract protected function get_object_name();
}
