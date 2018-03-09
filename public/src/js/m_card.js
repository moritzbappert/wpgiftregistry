var mCard = (function($) {

    /******************************************************************
        VARS
    ******************************************************************/

    var $btnToggle = $('.m_btn__toggle');
    var $btnView = $('.m_btn__view');
    var $popup = $('.m_popup');


    /******************************************************************
        EVENTS
    ******************************************************************/

    $btnToggle.on('click', toggleContent);

    $btnView.on('click', openPopup);

    /******************************************************************
        FUNCTIONS
    ******************************************************************/

    function toggleContent() {
        $(this).closest('.m_card').find('.m_card__content').slideToggle('fast');
    }

    function openPopup() {
        var $clickedBtn = $(this);
        var $clickedCard = $clickedBtn.closest('.m_card');
        var wishID = $clickedCard.data('wish');

        $popup.attr('data-wish', wishID);
        $popup.addClass('is-active');
    }


})(jQuery);
