(function ($) {
	$(document).ready(function () {
		$('#next').click(function () {
			$.post(
					PT_Ajax.ajaxurl,
					{
						// wp ajax action
				    	action: 'ajax-inputtitleSubmit',

						// vars
						title: $('input[name=title]').val(),

						// send the nonce along with the request
						nextNonce: PT_Ajax.nextNonce
					},
					function (response) {
						console.warn(response);
					}
			);
			return false;
		});

	});
	})(jQuery);