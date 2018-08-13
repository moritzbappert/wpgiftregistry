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

    // Reset reserved gift parts
    $('a#reset-reserved-parts').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);

        var giftID = $($button.parents('.cmb-field-list')[0]).find('.cmb2_unique_id').val();
        var wishlistID = $button.data('wishlist');
        var nonce = $button.data('nonce');

        $.ajax({
            url: variables.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'reset_reserved_parts',
                wishlist_id: wishlistID,
                gift_id: giftID,
                nonce: nonce,
            },
        }).done(function() {
            $button.after("&nbsp;<span style='color: #0073aa'>✓</span>");
            $('.' + giftID).remove();
        });
    });

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    // Copy shortcode to clipboard
    $('.copy-to-clipboard').on('click', function(){
        copyToClipboard($('code.shortcode'));
    });

})(jQuery);
