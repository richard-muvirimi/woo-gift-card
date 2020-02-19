(function ($) {
    'use strict';
    $(document).ready(function () {

	//handle coupon qrcode changes
	$('#wgc-coupon-type').change(() => {

	    let selectClass = 'wgc-coupon-' + $('#wgc-coupon-type').val() + '-options';
	    let selector = "[class~='" + selectClass + "']";
	    $('div.wgc-coupon-options').children(":not(" + selector + ")").hide();
	    $('div.wgc-coupon-options').children(selector).show();
	}).change();

    });

})(jQuery);