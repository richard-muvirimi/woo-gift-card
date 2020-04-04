(function ($) {
	'use strict';
	$(document).ready(function () {

		$(".wgc-tablink").click((e) => {

			let clicked = $(e.target).data("which");

			$(".wgc-tabcontent:not(#" + clicked + ")").hide();

			$(".wgc-tablink").not("#" + clicked).removeClass("open-tab");

			$(e.target).addClass("open-tab");
			$("#" + clicked).show();


		});

		$(".open-tab").click();

	});

})(jQuery);