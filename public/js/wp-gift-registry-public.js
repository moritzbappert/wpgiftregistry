(function( $ ) {
	'use strict';

	/**
	 * DOCUMENT READY
	 */
	$(function() {

		var wishlistItems = $('.wishlist li');
		var overlay = $('.wishlist .overlay');

		if ( overlay ) {
			$('button#no', overlay).on('click', function() {
				overlay.toggleClass('hidden');
			});

			$('button#yes', overlay).on('click', function(e) {
				var itemName = $(this).data('item-name');

				$.ajax({
					url: variablesOld.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'update_gift_availability',
						nonce: variablesOld.updateGiftAvailabiltyNonce,
						itemName: itemName,
						availability: 'false'
					},
				})
				.done(function(response) {

					// update the button
					$('li[data-item-name="' + itemName + '"] .buy-button').toggleClass('unavailable');

				});

				overlay.toggleClass('hidden');
			});
		}
		wishlistItems.each(function(index, el) {
			$('.buy-button', $(this)).on('click', function(e) {
				//e.preventDefault();

				var itemName = $('h2', $(this).parent()).html();
				$('#item-name', overlay).html(itemName);
				$('button#yes', overlay).data('item-name', itemName);
				overlay.toggleClass('hidden');
			});
		});
	});

})( jQuery );
