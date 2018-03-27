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
        $popup.attr('data-wish-id', wishID);
        $popup.attr('data-wishlist-id', wishlistID);
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

var mPopup = (function($) {

    /******************************************************************
        VARS
    ******************************************************************/

    var $popup = $('.wpgr-o_popup');
    var $step = $('.wpgr-o_popup__step');
    var $btnPrev = $step.find('.wpgr-o_popup__btn-prev');
    var $btnNext = $step.find('.wpgr-o_popup__btn-next');
    var $btnSave = $step.find('.wpgr-o_popup__btn-save');
    var $btnClose = $step.find('.wpgr-o_popup__btn-close');
    var $indicatorOne = $step.find('.wpgr-o_popup__list-item-01');
    var $indicatorTwo = $step.find('.wpgr-o_popup__list-item-02');
    var $input = $('.wpgr-o_popup__input-wrapper input');
    var $inputArea = $('.wpgr-o_popup__input-textarea');
    var $label = $('.wpgr-o_popup__input-label');
    var $radioYes = $('#buyer_yes');
    var $radioNo = $('#buyer_no');
    var $radioContent = $('.wpgr-m_btn__buyer-content');
    var $body = $('body');


    /******************************************************************
        EVENTS
    ******************************************************************/

    // close popup on click on body
    $body.on('click', function(e){
        checkIfPopupWasClicked(e);
    });

    // navigate to next step
    $btnNext.on('click', function(e) {
        e.preventDefault();
        nextStep(e);
    });

    // navigate between steps
    $indicatorOne.on('click', goToStepOne);
    $indicatorTwo.on('click', goToStepTwo);

    // save data on click on btn save
    $btnSave.on('click', function(e) {
        e.preventDefault();
        saveData(e);
    });

    // close popup on click on btn close
    $btnClose.on('click', function(e) {
        e.preventDefault();
        closePopup();
    });

    // close popup on click on btn prev/abort
    $btnPrev.on('click', function(e) {
        e.preventDefault();
        closePopup();
    });

    // trigger floatlabels
    $input.on('focusin', activateFloatLabel);
    $inputArea.on('focusin', activateFloatLabel);
    $input.on('focusout', deactivateFloatLabel);
    $inputArea.on('focusout', deactivateFloatLabel);

    // open/close additional question on radio btn click
    $radioYes.on('click', openAdditionalQuestion);
    $radioNo.on('click', closeAdditionalQuestion);

    /******************************************************************
        FUNCTIONS
    ******************************************************************/

    function openAdditionalQuestion() {
        if ($radioContent.hasClass('is-active')) return;
        $radioContent.addClass('is-active');
    }

    function closeAdditionalQuestion() {
        if (!$radioContent.hasClass('is-active')) return;
        $radioContent.removeClass('is-active');
    }

    function activateFloatLabel() {
        var $this = $(this);
        $this.prev().removeClass('is-done');
        $this.prev().addClass('is-active');
    }

    function deactivateFloatLabel() {
        var $this = $(this);
        // check if user has entered something
        if ($this.val().length) {
            $this.prev().addClass('is-done');
        }
        $this.prev().removeClass('is-active');
    }

    function goToStepOne() {
        $this = $(this);
        var $currentStep = $this.closest('.wpgr-o_popup__step');

        // hide current step
        $currentStep.removeClass('is-active');

        // make first step active
        $('#wpgr_popup_parts').addClass('is-active');
    }

    function goToStepTwo() {
        $this = $(this);
        var $currentStep = $this.closest('.wpgr-o_popup__step');

        // hide current step
        $currentStep.removeClass('is-active');

        // make first step active
        $('#wpgr_popup_buyer').addClass('is-active');
    }

    function nextStep(e) {
        var $clickedBtn = $(e.target);
        var $currentStep = $clickedBtn.closest('.wpgr-o_popup__step');

        // hide current step
        $currentStep.removeClass('is-active');

        // make next step active
        $currentStep.next('#wpgr_popup_buyer').addClass('is-active');
    }

    function checkIfPopupWasClicked(e) {
        var $clickTarget = $(e.target);

        // do not prevent click of view button
        if ($clickTarget.hasClass('wpgr-m_btn__open')) return;

        // check if popup is even active
        if (!$popup.hasClass('is-active')) return;

        // prevent closing on click on popup elements
        if ($clickTarget.closest('.wpgr-o_popup__step').length) {
            // allow x icon and save button to be clicked
            if (!$clickTarget.hasClass('wpgr-o_popup__btn-close') || !$clickTarget.hasClass('wpgr-o_popup__btn-save')) return;
        }
        closePopup();
    }

    function closePopup() {
        // make first step active for future popups
        $step.removeClass('is-active');

        // close popup
        $popup.removeClass('is-active');
        $body.removeClass('no-scroll');
    }

    function saveData(e) {
        var $currentGiftPopup = $(e.target).closest('.wpgr-o_popup');
        var giftID = $currentGiftPopup.data('wish-id');
        var wishlistID = $currentGiftPopup.data('wishlist-id');
        var reserverName = $currentGiftPopup.find('#your_name2').val();

        $.ajax({
            url: variables.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'update_gift_availability',
                nonce: variables.update_gift_availabilty_nonce,
                version: 'new',
                wishlist_id: wishlistID,
                gift_id: giftID,
                gift_availability: 'false',
                gift_reserver: reserverName,
            },
        })
        .done(function(response) {

            // deactivate card
            $('.wpgr-m_card[data-wish-id="' + giftID + '"]').addClass('wpgr-m_card--bought');

        });

        closePopup();
    }

})(jQuery);

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
