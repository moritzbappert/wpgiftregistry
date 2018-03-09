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
                    url: variables.ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'update_gift_availability',
                        nonce: variables.updateGiftAvailabiltyNonce,
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
