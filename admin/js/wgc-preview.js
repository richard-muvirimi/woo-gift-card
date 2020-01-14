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

	$(".wgc-template-preview, a#post-preview").click((e) => {
	    e.preventDefault();

	    let form = $("form.wgc-preview-form").get(0);
	    form.action = window.wgc_product.pdf_template_url;
	    form.target = "wgc-preview-frame";

	    //template id
	    let template_input = document.createElement("input");
	    template_input.type = "hidden";
	    template_input.name = "wgc-receiver-template";
	    template_input.value = $(e.target).data("template");
	    form.appendChild(template_input);

	    form.submit();

	    $(".wgc-preview-modal:first").show();

	});
    }
    );
}
)(jQuery);
