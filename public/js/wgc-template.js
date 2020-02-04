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

	setUpPdf();
	setUpCloseBtn();

    });

    function setUpCloseBtn() {

	if (window.parent.jQuery != undefined) {
	    let closeBtn = $("#documentPropertiesClose").clone();
	    closeBtn.attr("id", "previewTemplateClose");
	    closeBtn.click(e => {
		e.preventDefault();
		window.parent.jQuery("form.wgc-preview-form .wgc-preview-modal:visible").hide();

		//set to browsers default blank page
		window.parent.jQuery("form.wgc-preview-form .wgc-preview-frame").attr("src", "about:blank");
	    });

	    $("#secondaryToolbarToggle").before(closeBtn);
	}
    }

    function setUpPdf() {

	let pdf = base64ToBinary(window.wgc_pdf_base64);
	window.PDFViewerApplicationOptions.set("defaultUrl", pdf);
    }

    function base64ToBinary(base64) {
	let raw = window.atob(base64);
	let rawLength = raw.length;
	let array = new Uint8Array(new ArrayBuffer(rawLength));

	for (let i = 0; i < rawLength; i++) {
	    array[i] = raw.charCodeAt(i);
	}
	return array;
    }
}
)(jQuery);


