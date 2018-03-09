/******************************************************************
    _GLOBAL.JS

        > FUNCTIONS
        > PUBLIC_FUNCTIONS

******************************************************************/


var global = (function($) {


    /******************************************************************
        FUNCTIONS
    ******************************************************************/

    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }


    /******************************************************************
        PUBLIC_FUNCTIONS
    ******************************************************************/

    return {
        debounce: debounce
    };

})(jQuery);

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

/******************************************************************
    _EXAMPLE.JS

        > VARS
        > EVENTS
        > FUNCTIONS
        > PUBLIC_FUNCTIONS

        @USAGE
        e.g. nMain.showNav();
        e.g. $(window).on('scroll', global.debounce(nMain.hideNav, 1000));

******************************************************************/


var example = (function($) {


    /******************************************************************
        VARS
    ******************************************************************/

    // your code here


    /******************************************************************
        EVENTS
    ******************************************************************/

    // your code here


    /******************************************************************
        FUNCTIONS
    ******************************************************************/

    // your code here


    /******************************************************************
        PUBLIC_FUNCTIONS
    ******************************************************************/

    return {
        // your code here
    };

})(jQuery);
