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
