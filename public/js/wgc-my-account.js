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

    });

})(jQuery);
