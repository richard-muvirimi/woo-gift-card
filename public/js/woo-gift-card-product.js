(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).ready(function () {

	$('textarea#wgc-receiver-message').on("keydown keyup", () => {
	    let text = $('textarea#wgc-receiver-message').val();
	    if (text.length === 0) {
		$('span#wgc-message-length').text("");
	    } else {
		$('span#wgc-message-length').text(text.length + '/' + window.wgc_product.maxlength);
	    }

	});

	$("[name='wgc-preview']").click((e) => {
	    e.preventDefault();

	    let data = JSON.parse('{"wgc-product":"' + $("[name='wgc-preview']").val() + '"}');

	    let form = $("form.cart .wgc-options textarea,form.cart .wgc-options input:not(:file)");

	    for (let i = 0; i < form.length; i++) {
		let input = form.eq(i);

		data[input.attr("id")] = input.val();
	    }

	    $.post(window.wgc_product.template_url + "woo-gift-card/v1/template",
		    data,
		    function (data, status) {
			alert("Data: " + data.template + "\nStatus: " + status);
		    });
	});
    });

})(jQuery);
