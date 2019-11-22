(function ($) {
    'use strict';

    $(document).ready(function () {

	$('.options_group.pricing').children("p").addClass('hide_if_woo-gift-card');
	$('.options_group.pricing').addClass('show_if_woo-gift-card');

	$('#woo-gift-card-pricing').change(() => {

	    let selectClass = "wgc-pricing-" + $('#woo-gift-card-pricing').val();
	    let selector = "[class~='" + selectClass + "']";

	    //hide all pricing options
	    $(".wgc-pricing-options").children(":not(" + selector + ")").hide();
	    $(".wgc-pricing-options").children(selector).show();

	});
	$('#woo-gift-card-pricing').change();

	$('#woo-gift-card-discount').change(() => {

	    let selectClass = 'woo-gift-card-discount-' + $('#woo-gift-card-discount').val();
	    let selector = "[class~='" + selectClass + "']";

	    $('div.woo-gift-card-discount').children(":not(" + selector + ")").hide();
	    $('div.woo-gift-card-discount').children(selector).show();

	});
	$('#woo-gift-card-discount').change();
    });

})(jQuery);