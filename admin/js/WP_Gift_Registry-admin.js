(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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



	 // Replace CMB2 Group Field Titles with title subfield value
	 jQuery( function( $ ) {
			var $box = $( document.getElementById( 'wishlist_group_repeat' ) );
			var replaceTitles = function() {
				$box.find( '.cmb-group-title' ).each( function() {
					var $this = $( this );
					var txt = $this.next().find( '[id$="gift_title"]' ).val();
					var rowindex;
					if ( ! txt ) {
						txt = $box.find( '[data-grouptitle]' ).data( 'grouptitle' );
						if ( txt ) {
							rowindex = $this.parents( '[data-iterator]' ).data( 'iterator' );
							txt = txt.replace( '{#}', ( rowindex + 1 ) );
						}
					}
					if ( txt ) {
						$this.text( txt );
					}
				});
			};
			var replaceOnKeyUp = function( evt ) {
				var $this = $( evt.target );
				var id = 'title';
				if ( evt.target.id.indexOf(id, evt.target.id.length - id.length) !== -1 ) {
					console.log( 'val', $this.val() );
					$this.parents( '.cmb-row.cmb-repeatable-grouping' ).find( '.cmb-group-title' ).text( $this.val() );
				}
			};
			$box
				.on( 'cmb2_add_row cmb2_shift_rows_complete', function( evt ) {
					replaceTitles();
				})
				.on( 'keyup', replaceOnKeyUp );
			replaceTitles();
		});


})( jQuery );
