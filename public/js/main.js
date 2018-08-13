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
        var $clickedBtn = $(e.target),
            $clickedCard = $clickedBtn.closest('.wpgr-m_card'),
            wishID = $clickedCard.data('wish-id'),
            wishlistID = $clickedCard.closest('.wpgr-wishlist').data('id'),
            parts = parseInt($clickedCard.data('parts')),
            partsGiven = parseInt($clickedCard.data('parts-given')),
            pricePerPart = parseFloat($clickedCard.data('price-per-part')),
            partsString = $clickedCard.data('parts-string'),
            partString = $clickedCard.data('part-string'),
            currency = $clickedCard.data('currency'),
            currencyPlacement = $clickedCard.data('currency-placement');

        // open popup
        $popup.data('wish-id', wishID);
        $popup.data('wishlist-id', wishlistID);
        $popup.data('has-parts', parts > 1);
        $popup.data('parts', parts);
        $popup.data('parts-given', partsGiven);
        $popup.addClass('is-active');
        $body.addClass('no-scroll');

        // make single step one active
        if ($clickedCard.hasClass('wpgr-m_card--single') || $clickedCard.hasClass('wpgr-m_card--parts')) {
            var popupStepOne = $popup.find('#wpgr_popup_name');
            popupStepOne.addClass('is-active');

            // only show range slider if gift has parts
            if ( parts > 1 ) {

                // show parts section
                $popup.find('.wpgr-o_popup__parts').show();

                // init rangeslider
                $r = $('.wpgr-o_popup__rangeslider');

                var $result = $('.wpgr-o_popup__rangeslider-result'),
                    $output = $('.wpgr-o_popup__rangeslider-val'),
                    $total = $('.wpgr-o_popup__rangeslider-total'),
                    $parts = $('.wpgr-o_popup__rangeslider-parts'),
                    attributes = {
                        min: partsGiven,
                        max: parts,
                        step: 1,
                        value: partsGiven + 1,
                    };

                $r.attr(attributes);

                // set parts strings
                $parts.data('parts', partsString);
                $parts.data('part', partString);

                $r.rangeslider({
                    polyfill : false,
                    onInit : function() {

                        var $rangeEl = this.$range;

                        if (pricePerPart != '') {
                            // add value label to handle
                            var $handle = $rangeEl.find('.rangeslider__handle'),
                                priceTotal = (this.value - partsGiven) * pricePerPart;

                            priceTotal = (priceTotal % 1 === 0) ? priceTotal : parseFloat(priceTotal).toFixed(2);

                            var handleValue = '<div class="rangeslider__handle__value">' + (currencyPlacement === 'before' ? currency + priceTotal : priceTotal + currency) + '</div>';

                            $handle.append(handleValue);
                        } else {
                            $popup.find('.wpgr-o_popup__rangeslider-wrapper').addClass('no-price');
                        }

                        // set to max to obtain max-width
                        $output.html( this.max );
                        // get width
                        var maxWidth = $result.css('width');
                        $result.css('flex-basis', maxWidth);

                        $output.html( this.value );
                        $total.html( parts );

                        if ( this.min > 0 ) {
                            var percentage = (this.min / this.max) * 100;
                            $( '<div class="rangeslider__min" />' ).insertBefore( this.$range ).width(percentage + '%');
                        }

                        // set to max to obtain max-width
                        $parts.html( '<span>' + parseInt(this.max - this.min) + '</span> ' + $parts.data('parts') );
                        // get width
                        var partsMaxWidth = $parts.css('width');
                        $parts.css('flex-basis', partsMaxWidth);

                        if ( (this.value - this.min) > 1 || this.value - this.min == 0 ) {
                            $parts.html( '<span>' + parseInt(this.value - this.min) + '</span> ' + $parts.data('parts') );
                        } else {
                            $parts.html( '<span>' + parseInt(this.value - this.min) + '</span> ' + $parts.data('part') );
                        }

                        this.rangeDimension = this.$range.width();
                        this.maxHandlePos = this.$range.width() - this.handleDimension;



                    },
                    onSlide : function( position, value ) {
                        var $handle = this.$range.find('.rangeslider__handle__value'),
                            priceTotal = (this.value - partsGiven) * pricePerPart;

                        priceTotal = (priceTotal % 1 === 0) ? priceTotal : parseFloat(priceTotal).toFixed(2);

                        $handle.text( (currencyPlacement === 'before' ? currency + priceTotal : priceTotal + currency) );

                        $output.html( value );  

                        if ( (this.value - this.min) > 1 || this.value - this.min == 0 ) {
                            $parts.html( '<span>' + parseInt(this.value - this.min) + '</span> ' + $parts.data('parts') );
                        } else {
                            $parts.html( '<span>' + parseInt(this.value - this.min) + '</span> ' + $parts.data('part') );
                        }
                    }
                })
                .on('input', function() {
                    $output[0].textContent = this.value;
                });

                $r.change();
            }

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
            if ( rangeValue == totalParts || !hasParts ) {
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
