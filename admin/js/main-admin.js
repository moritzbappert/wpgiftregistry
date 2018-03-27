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

var adminJS = (function($) {

    var $box = $( '#wpgr_wishlist_repeat, #wishlist_group_repeat' );
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
    var setRadioDefaults = function( evt, $row ) {
        var iterator = $row.data('iterator');
        var $gift_availability_true = $('#wpgr_wishlist_' + iterator + '_gift_availability1');
        $gift_availability_true.prop('checked', true);
    };
    var replaceOnKeyUp = function( evt ) {
        var $this = $( evt.target );
        var id = 'title';
        if ( evt.target.id.indexOf(id, evt.target.id.length - id.length) !== -1 ) {
            $this.parents( '.cmb-row.cmb-repeatable-grouping' ).find( '.cmb-group-title' ).text( $this.val() );
        }
    };
    $box
        .on( 'cmb2_add_row', setRadioDefaults )
        .on( 'cmb2_shift_rows_complete', replaceTitles )
        .on( 'keyup', replaceOnKeyUp );
    replaceTitles();

})(jQuery);
