/*global woocommerce_admin_meta_boxes */
(function ($) {
    'use strict';
    $(document).ready(function () {

	//hide all internal pricing tabs
	$('#general_product_data .options_group.pricing:has(p._regular_price_field)').addClass('hide_if_woo-gift-card');

	//show tax fields if taxing enabled
	$('#general_product_data .options_group:has(p._tax_status_field)').addClass('show_if_woo-gift-card');
	$('#inventory_product_data .options_group:has(p._sold_individually_field)').addClass('show_if_woo-gift-card');
	$('#inventory_product_data .options_group p').addClass('show_if_woo-gift-card');

	//handle coupon code changes
	$('textarea#wgc-coupon-codes').on("keydown keyup change", (e) => {

	    //replace all whitespace characters with comma
	    let coupons = $(e.target).val().replace(/\s/g, ",");
	    let length = coupons.split(",").filter((coupon) => {
		//remove all empty entries
		return coupon.trim().length > 0;
	    }).length;

	    let stock = $("#_stock");
	    if (coupons.length > 0 && length > 0) {
		if (!stock.attr("readonly")) {
		    stock.attr("readonly", "true");
		}
		stock.val(length);
	    } else {
		stock.removeAttr("readonly");
		stock.val(0);
	    }
	}).change();

	//handle pricing type changes
	$('#wgc-pricing').change((e) => {

	    let selectClass = "wgc-pricing-" + $(e.target).val();
	    let selector = "[class~='" + selectClass + "']";

	    //hide all pricing options
	    $(".wgc-pricing-options").children(":not(" + selector + ")").hide();
	    $(".wgc-pricing-options").children(selector).show();

	}).change();

	//handle discount type changes
	$('#wgc-discount').change((e) => {

	    let discount = "fixed";
	    if ($(e.target).val().indexOf(discount) === -1) {
		discount = "percentage";
	    }

	    let selector = "[class~='wgc-discount-" + discount + "']";
	    $('div.wgc-discount').children(":not(" + selector + ")").hide();
	    $('div.wgc-discount').children(selector).show();
	}).change();

	//on product type change trigger panel changes
	$(document.body).on('woocommerce-product-type-change', () => {

	    if ($('select#product-type').val() === "woo-gift-card") {
		show_hide_panels();
	    }
	});

	//on change to thank you gift card
	$('input#_thankyouvoucher').change(() => {
	    if ($('select#product-type').val() === "woo-gift-card") {
		show_hide_panels();
	    }
	}).change();

	function show_hide_panels() {

	    var product_type = $('select#product-type').val();
	    var is_thankyou = $('input#_thankyouvoucher').prop('checked');
	    // Hide/Show all with rules.
	    var hide_classes = '.hide_if_thankyouvoucher';
	    var show_classes = '.show_if_thankyouvoucher';
	    $.each(woocommerce_admin_meta_boxes.product_types, function (index, value) {
		hide_classes = hide_classes + ', .hide_if_' + value;
		show_classes = show_classes + ', .show_if_' + value;
	    });
	    $(hide_classes).show();
	    $(show_classes).hide();
	    // Shows rules.
	    if (is_thankyou) {
		$('.show_if_thankyouvoucher').show();
	    }

	    $('.show_if_' + product_type).show();
	    // Hide rules.
	    if (is_thankyou) {
		$('.hide_if_thankyouvoucher').hide();
	    }

	    $('.hide_if_' + product_type).hide();
	    $('input#_manage_stock').change();
	    // Hide empty panels/tabs after display.
	    $('.woocommerce_options_panel').each(function () {
		var $children = $(this).children('.options_group');
		if (0 === $children.length) {
		    return;
		}

		var $invisble = $children.filter(function () {
		    return 'none' === $(this).css('display');
		});
		// Hide panel.
		if ($invisble.length === $children.length) {
		    var $id = $(this).prop('id');
		    $('.product_data_tabs').find('li a[href="#' + $id + '"]').parent().hide();
		}
	    });
	}
    });
}
)(jQuery);