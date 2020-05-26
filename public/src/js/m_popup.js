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
    $body.on('mousedown', function(e) {
        checkIfPopupWasClicked(e);
    });

    // close popup on ESC key
    $body.on('keyup', function(e) {
        if (e.which == 27) {
            closePopup();
        }
    });

    // navigate to next step
    $btnNext.on('click', function(e) {
        e.preventDefault();
        nextStep(e);
    });

    // navigate between steps
    $indicatorOne.on('click', goToStepOne);
    $indicatorTwo.on('click', goToStepTwo);

    // save data on submit of the form (click on btn save)
    $popup.on('submit', function(e) {
        e.preventDefault();
        saveData(e);
    });
    // $btnSave.on('click', function(e) {
    //     e.preventDefault();
    //     saveData(e);
    // });

    // submit form on enter
    $popup.find('#your_name2').on('keypress', function (e) {
        if (e.which == 13) {
            e.preventDefault(); // prevent triggering click event
            $popup.submit();
        }
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

        // reset popup
        //$popup.get(0).reset(); // currently not working
        $popup.find('#your_name2').val('');
        $popup.find('#your_email').val('');
        $popup.find('#your_message').val('');
        $popup.find('.wpgr-o_popup__parts').hide();
        $popup.find('.wpgr-o_popup__rangeslider').val('');
        $popup.find('.wpgr-o_popup__rangeslider').rangeslider('destroy');
        $popup.find('.rangeslider').remove();
        $popup.find('.rangeslider__min').remove();
        $popup.find('.wpgr-o_popup__rangeslider-parts').empty();
        $popup.find('.wpgr-o_popup__rangeslider-wrapper').removeClass('no-price');
        $popup.removeAttr('data-wish-id');
        $popup.removeAttr('data-wishlist-id');
    }

    function saveData(e) {
        var $currentGiftPopup = $(e.target).closest('.wpgr-o_popup');
        var giftID = $currentGiftPopup.data('wish-id');
        var wishlistID = $currentGiftPopup.data('wishlist-id');
        var isUnlimited = $currentGiftPopup.data('unlimited') == 'true';
        var hasParts = $currentGiftPopup.data('has-parts');
        var rangeValue = $currentGiftPopup.find('#no_of_parts').val();
        var noOfParts = (hasParts ? rangeValue - $currentGiftPopup.data('parts-given') : 1);
        var totalParts = $currentGiftPopup.data('parts');
        var reserverName = $currentGiftPopup.find('#your_name2').val();
        var reserverEmail = $currentGiftPopup.find('#your_email').val();
        var reserverMessage = $currentGiftPopup.find('#your_message').val();

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
                gift_has_parts: hasParts,
                gift_parts_reserved: noOfParts,
                gift_reserver: reserverName,
                gift_reserver_email: reserverEmail,
                gift_reserver_message: reserverMessage,
            },
        })
        .done(function(response) {

            if ( hasParts ) {
                // update progress bar
                $('.wpgr-m_card[data-wish-id="' + giftID + '"]').find('.wpgr-m_card__progress span').width(rangeValue / totalParts * 100 + '%');
                $('.wpgr-m_card[data-wish-id="' + giftID + '"]').find('.wpgr-m_card__progress-wrapper > span').text(rangeValue);

                // update data
                $('.wpgr-m_card[data-wish-id="' + giftID + '"]').data('parts-given', rangeValue);
            }

            // only if all parts given
            if (( rangeValue == totalParts || !hasParts ) && !isUnlimited) {
                // deactivate card
                $('.wpgr-m_card[data-wish-id="' + giftID + '"]').addClass('wpgr-m_card--bought');
            }

        })
        .fail(function() {

            // implement error message here

        });

        closePopup();
    }

})(jQuery);
