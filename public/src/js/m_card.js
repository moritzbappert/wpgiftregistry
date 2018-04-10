var mCard = (function($) {

    /******************************************************************
        VARS
    ******************************************************************/

    var $card              = $('.wpgr-m_card');
    var $cardContent       = $('.wpgr-m_card--content');
    var $btntoggle         = $('.wpgr-m_card__toggle');
    var $btnOpen           = $('.wpgr-m_btn__open');
    var $popup             = $('.wpgr-o_popup');
    var $description       = $('.wpgr-m_card__desc');
    var $body              = $('body');
    var descriptionStrings = [];


    /******************************************************************
        EVENTS
    ******************************************************************/

    $btnOpen.on('click', function(e) {
        e.stopPropagation();
        openPopup(e);
    });

    $cardContent.on('click', function(e) {
        var $this    = $(e.target);
        var $card = $this.closest('.wpgr-m_card');
        var $content = $this.closest('.wpgr-m_card').find('.wpgr-m_card__content');
        var $toggle  = $this.closest('.wpgr-m_card').find('.wpgr-m_card__toggle');

        if ($this.hasClass('wpgr-m_card__figure')) {
            return;
        }

        if (!$content.hasClass('is-active')) {
            toggleContent()
        } else if ($content.hasClass('is-active') && $this.hasClass('wpgr-m_card__toggle-icon') || $this.hasClass('wpgr-m_card__toggle')) {
            toggleContent()
        } else {
            return;
        }

        function toggleContent() {
            $content.slideToggle('fast');
            $card.toggleClass('is-open');
            $content.toggleClass('is-active');
            $toggle.toggleClass('is-active');
        }
    });

    /******************************************************************
        FUNCTIONS
    ******************************************************************/

    function openPopup(e) {
        var $clickedBtn = $(e.target);
        var $clickedCard = $clickedBtn.closest('.wpgr-m_card');
        var wishID = $clickedCard.data('wish-id');
        var wishlistID = $clickedCard.closest('.wpgr-wishlist').data('id');

        // open popup
        $popup.data('wish-id', wishID);
        $popup.data('wishlist-id', wishlistID);
        $popup.addClass('is-active');
        $body.addClass('no-scroll');

        // make single step one active
        if ($clickedCard.hasClass('wpgr-m_card--single')) {
            var popupStepOne = $popup.find('#wpgr_popup_name');
            popupStepOne.addClass('is-active');

        // make multiple step one active
        } else {
            var popupStepOne = $popup.find('#wpgr_popup_parts');
            var popupStepTwo = $popup.find('#wpgr_popup_buyer');
            popupStepOne.addClass('is-active');
        }
    }
})(jQuery);
