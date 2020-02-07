(function ($) {
    'use strict';

    $(document).ready(() => {

	$("table.table-wgc-vouchers a.wgc-btn-more").click((e) => {
	    e.preventDefault();

	    //close all open
	    let coupon = $(e.target).data("coupon");
	    let info_tr = "#wgc-more-" + coupon;

	    $("tr[id^='wgc-more-']:not(" + info_tr + ")").hide();
	    $(info_tr).toggle("slow");
	});

	$("table.table-wgc-vouchers a.wgc-send-email").click((e) => {
	    e.preventDefault();

	    let which = $(e.target).data("which");

	    $.post({url: window.wgc_account.ajax_url,
		data: {
		    _ajax_nonce: $(e.target).data("nonce"),
		    action: "wgc_send_mail",
		    which: which
		},
		success: function (response) {

		    if (response.success) {

		    } else {

		    }
		}}
	    );
	});

	$("table.table-wgc-vouchers a.wgc-btn-view").click((e) => {
	    e.preventDefault();

	    let form = $("form.wgc-preview-form").get(0);

	    //coupon code
	    let template_input = document.createElement("input");
	    template_input.type = "hidden";
	    template_input.name = "wgc-coupon";
	    template_input.value = $(e.target).data("code");
	    form.appendChild(template_input);

	    form.submit();

	    $(".wgc-preview-modal:first").show();

	});

	$("table.table-wgc-vouchers a.wgc-delete-voucher").click((e) => {
	    e.preventDefault();

	    let which = $(e.target).data("which");

	    $.post({url: window.wgc_account.ajax_url,
		data: {
		    _ajax_nonce: $(e.target).data("nonce"),
		    action: "wgc_delete_voucher",
		    which: which
		},
		success: function (response) {

		    if (response.success) {
			$("#wgc-voucher-" + which + ", #wgc-more-" + which).fadeOut();
		    }

		    //empty notice after one minute
		    $(".woocommerce-notices-wrapper:first").empty().append(response.data);
		    $(".woocommerce-notices-wrapper:first .woocommerce-Message").delay(1000 * 30).fadeOut();
		}}
	    );
	});

    });

})(jQuery);
