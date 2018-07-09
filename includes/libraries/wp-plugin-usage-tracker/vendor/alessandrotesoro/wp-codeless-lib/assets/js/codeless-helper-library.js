/**
 * Codeless library WP modal helper.
 * http://alessandrotesoro.me
 *
 * Borrowed and stripped down from the free Custom Sidebars plugin on wordpress.org
 *
 * Copyright (c) 2016 Alessandro Tesoro
 * Licensed under the GPLv2+ license.
 */

( function( codelessUi ) {

	/**
	 * Document element.
	 *
	 * @type object
	 */
	var _doc = null;

	/**
	 * The html tag.
	 *
	 * @type object
	 */
	var _html = null;

	/**
	 * The body tag.
	 *
	 * @type object
	 */
	var _body = null;

	/**
	 * The modal created by this object.
	 *
	 * @type object
	 */
	var _modal_overlay = null;

	/**
	 * Opens a new popup window.
	 *
	 * @return codelessUiWindow the modal window.
	 */
	codelessUi.popup = function popup() {
		_init();
		return new codelessUiWindow();
	};

	/**
	 * Get things started.
	 *
	 * @return null
	 */
	function _init() {
		if ( null !== _html ) { return; }

		_doc = jQuery( document );
		_html = jQuery( 'html' );
		_body = jQuery( 'body' );

	}

	/**
	 * Append overlay to body.
	 *
	 * @return void
	 */
	function _make_modal() {
		if ( null === _modal_overlay ) {
			_modal_overlay = jQuery( '<div></div>' )
				.addClass( 'codelessui-overlay' )
				.appendTo( _body );
		}
		_body.addClass( 'codelessui-has-overlay' );
		_html.addClass( 'codelessui-no-scroll' );
	}

	/**
	 * Close the modal overlay.
	 *
	 * @return void
	 */
	function _close_modal() {
		_body.removeClass( 'codelessui-has-overlay' );
		_html.removeClass( 'codelessui-no-scroll' );
	}

	// Initialize the object.
	jQuery(function() {
		_init();
	});

	/**
	 * Popup window functionality.
	 *
	 * @return object the casted window.
	 */
	var codelessUiWindow = function() {

		/**
		 * Reference to this own window.
		 * @type object
		 */
		var _me = this;

		/**
		 * State of the window
		 * @type {Boolean}
		 */
		var _visible = false;

		/**
		 * Whether a modal background should appear or not.
		 * @type {Boolean}
		 */
		var _modal = false;

		/**
		 * Store the width of the window.
		 * @type {Number}
		 */
		var _width = 740;

		/**
		 * Store the height of the window.
		 * @type {Number}
		 */
		var _height = 400;

		/**
		 * The title of the window.
		 * @type {String}
		 */
		var _title = 'Window';

		/**
		 * The content of the window. Attach this via class or ID.
		 * @type {String}
		 */
		var _content = '';

		/**
		 * Any classes to append to the window.
		 * @type {String}
		 */
		var _classes = '';

		/**
		 * Flag to check if the content has changed.
		 * @type {Boolean}
		 */
		var _content_changed = false;

		/**
		 * Flag to enable if the window size changes.
		 * @type {Boolean}
		 */
		var _need_check_size = false;

		/**
		 * Callback after the window is visible.
		 * @type {[type]}
		 */
		var _onshow = null;

		/**
		 * Callback after the window is hidden.
		 * @type {[type]}
		 */
		var _onhide = null;

		/**
		 * Callback after the window is closed and destroyed.
		 * @type {[type]}
		 */
		var _onclose = null;

		/**
		 * The window element.
		 * @type {[type]}
		 */
		var _wnd = null;

		/**
		 * The title bar inside the window.
		 * @type {[type]}
		 */
		var _el_title = null;

		/**
		 * Close button inside the window.
		 * @type {[type]}
		 */
		var _btn_close = null;

		/**
		 * The content of the window.
		 * @type {[type]}
		 */
		var _el_content = null;

		/**
		 * Current status of the window.
		 * @type {String}
		 */
		var _status = 'hidden';

		/**
		 * Set the modal property.
		 *
		 * @param  {string} state status of the window.
		 * @return {object}       current window.
		 */
		this.modal = function modal( state ) {
			_modal = ( state ? true : false );

			_update_window();
			return _me;
		};

		/**
		 * Setup the window size.
		 *
		 * @param  {number} width
		 * @param  {number} height
		 * @return object
		 */
		this.size = function size( width, height ) {
			var new_width = Math.abs( parseFloat( width ) ),
				new_height = Math.abs( parseFloat( height ) );

			if ( ! isNaN( new_width ) ) { _width = new_width; }
			if ( ! isNaN( new_height ) ) { _height = new_height; }

			_need_check_size = true;
			_update_window();
			return _me;
		};

		/**
		 * Setup the window title.
		 *
		 * @param  {string} new_title title of the window.
		 * @return object
		 */
		this.title = function title( new_title ) {
			_title = new_title;

			_update_window();
			return _me;
		};

		/**
		 * Content of the window.
		 *
		 * @param  {string} data
		 * @return object
		 */
		this.content = function content( data ) {
			_content = data;
			_need_check_size = true;
			_content_changed = true;

			_update_window();
			return _me;
		};

		/**
		 * Set classes for the window.
		 *
		 * @param string
		 */
		this.set_class = function set_class( class_names ) {
			_classes = class_names;
			_content_changed = true;

			_update_window();
			return _me;
		};

		/**
		 * Callback fucntionality.
		 *
		 * @param  {Function} callback
		 * @return object
		 */
		this.onshow = function onshow( callback ) {
			_onshow = callback;
			return _me;
		};

		/**
		 * Callback fucntionality.
		 *
		 * @param  {Function} callback
		 * @return object
		 */
		this.onhide = function onhide( callback ) {
			_onhide = callback;
			return _me;
		};

		/**
		 * Callback fucntionality.
		 *
		 * @param  {Function} callback
		 * @return object
		 */
		this.onclose = function onclose( callback ) {
			_onclose = callback;
			return _me;
		};

		/**
		 * Add a loading overlay to the window or remove it.
		 *
		 * @param  {string} state
		 * @return object
		 */
		this.loading = function loading( state ) {
			if ( state ) {
				_wnd.addClass( 'codelessui-loading' );
			} else {
				_wnd.removeClass( 'codelessui-loading' );
			}
			return _me;
		};

		/**
		 * Show a confirmation box inside the window.
		 *
		 * @param  {object} args message options.
		 * @return object
		 */
		this.confirm = function confirm( args ) {
			if ( _status !== 'visible' ) { return _me; }
			if ( ! args instanceof Object ) { return _me; }

			args['layout'] = 'absolute';
			args['parent'] = _wnd;

			codelessui.confirm( args );

			return _me;
		};

		/**
		 * Show the window.
		 *
		 * @return object
		 */
		this.show = function show() {
			_visible = true;
			_need_check_size = true;
			_status = 'visible';

			_update_window();

			if ( typeof _onshow === 'function' ) {
				_onshow.apply( _me, [ _me.$() ] );
			}
			return _me;
		};

		/**
		 * Hide the window.
		 *
		 * @return object
		 */
		this.hide = function hide() {
			_visible = false;
			_status = 'hidden';

			_update_window();

			if ( typeof _onhide === 'function' ) {
				_onhide.apply( _me, [ _me.$() ] );
			}
			return _me;
		};

		/**
		 * Close the window and destroy it.
		 */
		this.close = function close() {
			// Prevent infinite loop when calling .close inside onclose handler.
			if ( _status === 'closing' ) { return; }

			_me.hide();

			_status = 'closing';

			if ( typeof _onclose === 'function' ) {
				_onclose.apply( _me, [ _me.$() ] );
			}

			_unhook();
			_wnd.remove();
			_wnd = null;
		};

		/**
		 * Completely removes the popup window.
		 * The popup object cannot be re-used after calling this function.
		 *
		 */
		this.destroy = function destroy() {
			var orig_onhide = _onhide;

			// Prevent infinite loop when calling .destroy inside onclose handler.
			if ( _status === 'closing' ) { return; }

			_onhide = function() {
				if ( typeof orig_onhide === 'function' ) {
					orig_onhide.apply( _me, [ _me.$() ] );
				}

				_status = 'closing';

				if ( typeof _onclose === 'function' ) {
					_onclose.apply( _me, [ _me.$() ] );
				}

				// Completely remove the popup from the memory.
				_wnd.remove();
				_wnd = null;
				_popup = null;

				_me = null;
			};

			_me.hide();
		};

		/**
		 * Adds an event handler to the dialog.
		 *
		 * @since  2.0.1
		 */
		this.on = function on( event, selector, callback ) {
			_wnd.on( event, selector, callback );

			if ( _wnd.filter( selector ).length ) {
				_wnd.on( event, callback );
			}

			return _me;
		};

		/**
		 * Removes an event handler from the dialog.
		 *
		 * @since  2.0.1
		 */
		this.off = function off( event, selector, callback ) {
			_wnd.off( event, selector, callback );

			if ( _wnd.filter( selector ).length ) {
				_wnd.off( event, callback );
			}

			return _me;
		};

		/**
		 * Returns the jQuery object of the window
		 *
		 * @since  1.0.0
		 */
		this.$ = function $( selector ) {
			if ( selector ) {
				return _wnd.find( selector );
			} else {
				return _wnd;
			}
		};


		/**
		 * Create dom elements for the window.
		 */
		function _init() {

			// Create the DOM elements.
			_wnd = jQuery( '<div class="codelessui-wnd"></div>' );
			_el_title = jQuery( '<div class="codelessui-wnd-title"><span class="the-title"></span></div>' );
			_btn_close = jQuery( '<a href="#" class="codelessui-wnd-close"><i class="dashicons dashicons-no-alt"></i></a>' );
			_el_content = jQuery( '<div class="codelessui-wnd-content"></div>' );

			// Attach the window to the current page.
			_el_title.appendTo( _wnd );
			_el_content.appendTo( _wnd );
			_btn_close.appendTo( _el_title );
			_wnd.appendTo( _body ).hide();

			// Add event handlers.
			_hook();

			// Refresh the window layout.
			_visible = false;
			_update_window();

		}

		/**
		 * Add event listeners.
		 */
		function _hook() {
			if ( _wnd ) {
				_wnd.on( 'click', '.codelessui-wnd-close', _me.close );
				jQuery( window ).on( 'resize', _check_size );
			}
		}

		/**
		 * Remove event listeners.
		 */
		function _unhook() {
			if ( _wnd ) {
				_wnd.off( 'click', '.codelessui-wnd-close', _me.close );
				jQuery( window ).off( 'resize', _check_size );
			}
		}

		/**
		 * Update size and position of the window.
		 *
		 * @param  {number} width
		 * @param  {number} height
		 */
		function _update_window( width, height ) {
			if ( ! _wnd ) { return false; }

			width = width || _width;
			height = height || _height;

			var styles = {
				'width': width,
				'height': height,
				'margin-left': -1 * (width / 2),
				'margin-top': -1 * (height / 2)
			};

			// Window title.
			_el_title.find( '.the-title' ).text( _title );

			// Display a copy of the specified content.
			if ( _content_changed ) {
				// Remove the current button bar.
				_wnd.find( '.buttons' ).remove();
				_wnd.removeClass();
				_wnd.addClass( 'codelessui-wnd no-buttons' );

				// Update the content.
				if ( _content instanceof jQuery ) {
					_el_content.html( _content.html() );
				} else {
					_el_content.html( jQuery( _content ).html() );
				}

				// Move the buttons out of the content area.
				var buttons = _el_content.find( '.buttons' );
				if ( buttons.length ) {
					buttons.appendTo( _wnd );
					_wnd.removeClass( 'no-buttons' );
				}

				// Add custom class to the popup.
				_wnd.addClass( _classes );

				_content_changed = false;
			}

			// Size and position.
			if ( _wnd.is( ':visible' ) ) {
				_wnd.animate(styles, 200);
			} else {
				_wnd.css(styles);
			}

			if ( _modal_overlay instanceof jQuery ) {
				_modal_overlay.off( 'click', _modal_close );
			}

			// Show or hide the window and modal background.
			if ( _visible ) {
				_wnd.show();
				if ( _modal ) { _make_modal(); }
				_modal_overlay.on( 'click', _modal_close );

				if ( _need_check_size ) {
					_need_check_size = false;
					_check_size();
				}
			} else {
				_wnd.hide();
				_close_modal();
			}
		}

		/**
		 * Close window when user clicks the overlay.
		 */
		function _modal_close() {
			if ( ! _wnd ) { return false; }
			if ( ! _modal_overlay instanceof jQuery ) { return false; }

			_modal_overlay.off( 'click', _modal_close );
			_me.close();
		}

		/**
		 * Check size of the window.
		 */
		function _check_size() {
			if ( ! _wnd ) { return false; }

			var me = jQuery( this ), // this is jQuery( window )
				window_width = me.innerWidth(),
				window_height = me.innerHeight(),
				real_width = _width,
				real_height = _height;

			if ( window_width < _width ) {
				real_width = window_width;
			}
			if ( window_height < _height ) {
				real_height = window_height;
			}
			_update_window( real_width, real_height );
		}

		// Initialize the popup window.
		_me = this;
		_init();

	}; /* ** End: codelessUiWindow ** */

}( window.codelessUi = window.codelessUi || {} ));
