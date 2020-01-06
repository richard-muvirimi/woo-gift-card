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
	$("#wgc-preview").click((e) => {
	    e.preventDefault();
	    let data = getFormData();

	    //clear iframe
	    $("form.cart .wgc-preview-content:visible").html("");

	    $("form.cart .wgc-preview-modal").show();
	    getTemplate(window.wgc_product.template_url, data, function (response) {
		$("form.cart .wgc-preview-content:visible").html(response);
	    });
	});
	function getTemplate(url, data, success) {

	    $.ajax({
		url: url,
		dataType: "json",
		cache: false,
		contentType: false,
		processData: false,
		data: data,
		type: "POST",
		success: function (response) {
		    success(response);
		}
	    });
	}

	function getFormData() {
	    let data = new FormData();
	    let form = $("form.cart .wgc-options textarea,form.cart .wgc-options input:not(:file)");
	    for (let i = 0; i < form.length; i++) {
		let input = form.eq(i);
		data.append(input.attr("name"), input.val());
	    }

	    data.append("wgc-product", $("#wgc-preview").val());
	    if ($("#wgc-receiver-image")[0].files.length > 0) {
		data.append("wgc-receiver-image", $("#wgc-receiver-image")[0].files[0]);
	    }

	    return data;
	}
    });
})(jQuery);
