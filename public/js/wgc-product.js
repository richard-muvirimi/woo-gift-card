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

	//prepare woocommerce add to cart form
	$("[name='add-to-cart']").parent().addClass("wgc-preview-form");

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

	    console.log(window.wgc_product.pdf_template_url);

	    let form = $("form.wgc-preview-form:first").get(0);
	    form.action = window.wgc_product.pdf_template_url;
	    form.target = "wgc-preview-frame";

	    //product id
	    let product_input = document.createElement("input");
	    product_input.type = "hidden";
	    product_input.name = "wgc-product";
	    product_input.value = $("#wgc-preview").val();
	    form.appendChild(product_input);

	    form.submit();

	    $("form.cart .wgc-preview-modal").show();

	});
    }
    );
}
)(jQuery);
