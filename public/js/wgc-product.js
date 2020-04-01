(function ($) {
    'use strict';

    $(document).ready(function () {

	$('textarea#wgc-receiver-message').on("keydown keyup", () => {
	    let text = $('textarea#wgc-receiver-message').val();
	    if (text.length === 0) {
		$('span#wgc-message-length').text("");
	    } else {
		$('span#wgc-message-length').text(text.length + '/' + window.wgc_product.maxlength);
	    }

	});
    });
}
)(jQuery);
