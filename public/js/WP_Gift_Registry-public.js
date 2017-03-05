(function( $ ) {
	'use strict';

	/**
	 * DOCUMENT READY
	 */
	$(function() {

		const wishlistItems = document.querySelectorAll('.wishlist li');
		const overlay = document.querySelector('.wishlist .overlay');

		if ( overlay ) {
			overlay.querySelector('button#no').addEventListener('click', () => {
				overlay.classList.toggle('hidden');
			});

			overlay.querySelector('button#yes').addEventListener('click', (e) => {

				$.ajax({
					url: variables.ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'update_gift_availability',
						nonce: variables.updateGiftAvailabiltyNonce,
						itemName: e.target.dataset.itemName,
						availability: 'false'
					},
				})
				.done(function(response) {

					// update the button
					document.querySelector('li[data-item-name="' + e.target.dataset.itemName + '"] .buy-button').classList.toggle('unavailable');

				});

				overlay.classList.toggle('hidden');
			});
		}

		wishlistItems.forEach(item => item.querySelector('.buy-button').addEventListener('click', (e) => {
			//e.preventDefault();

			const itemName = e.target.parentElement.querySelector('h2').innerHTML;
			overlay.querySelector('#item-name').innerHTML = itemName;
			overlay.querySelector('button#yes').dataset.itemName = itemName;
			overlay.classList.toggle('hidden');
		}));
	});

})( jQuery );
