var mCard = (function($) {

    /******************************************************************
        VARS
    ******************************************************************/

    var $card = $('.wpgr-m_card');
    var $btntoggle = $('.wpgr-m_card__toggle');
    var $btnOpen = $('.wpgr-m_btn__open');
    var $popup = $('.wpgr-o_popup');
    var $description = $('.wpgr-m_card__desc');
    var $body = $('body');
    var descriptionStrings = [];


    /******************************************************************
        EVENTS
    ******************************************************************/

    // truncate description text
    // $description.each(function(index, el) {
    //     // truncateDescriptionText(el);
    //     var truncatedText = truncate($(el).text());
    //     $(el).text(truncatedText);
    // });

    // show/hide collapsed content
    $card.on('click', showContent);
    $btntoggle.on('click', function(e) {
        e.stopPropagation();
        toggleContent(e);
    });

    $btnOpen.on('click', openPopup);

    /******************************************************************
        FUNCTIONS
    ******************************************************************/

    function showContent() {
        $(this).closest('.wpgr-m_card').removeClass('is-collapsed');
        $(this).closest('.wpgr-m_card').find('.wpgr-m_card__toggle').addClass('is-active');
    }

    function toggleContent(e) {
        $(e.target).closest('.wpgr-m_card').toggleClass('is-collapsed');
        $(e.target).closest('.wpgr-m_card').find('.wpgr-m_card__toggle').toggleClass('is-active');
    }

    function truncate(string){
        if (string.length > 70)
            return string.substring(0,70) + '...';
        else
            return string;
    };

    function openPopup() {
        var $clickedBtn = $(this);
        var $clickedCard = $clickedBtn.closest('.wpgr-m_card');
        var wishID = $clickedCard.data('wish-id');

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

        // open popup
        $popup.attr('data-wish', wishID);
        $popup.addClass('is-active');
        $body.addClass('no-scroll');
    }

    function truncateDescriptionText(el) {
        // var $this = $(el);

        // // create wish object
        // var wishEl = {};

        // // create unique wishlist ID
        // wishEl[$this.closest('.wpgr-section').data('id') + '-' + $this.closest('.wpgr-m_card').data('wish-id')] = $this.text();

        // // get description text
        // // wishEl.text = $this.text();

        // // save complete text in array to re-display it later
        // descriptionStrings.push(wishEl: wishEl);
        // console.log(descriptionStrings);

        // // truncate text
        // var truncatedString = truncate($(el).text());
    }


})(jQuery);
