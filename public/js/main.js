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

var mPopup = (function($) {

    /******************************************************************
        VARS
    ******************************************************************/

    var $popup = $('.m_popup');
    var $step = $('.m_popup__step');
    var $btnNext = $step.find('.m_btn--next');
    var $btnPrev = $step.find('.m_btn--prev');
    var $btnSave = $step.find('.m_btn--save');
    var $btnClose = $step.find('.m_btn--close');
    var $body = $('body');


    /******************************************************************
        EVENTS
    ******************************************************************/

    $body.on('click', function(e){
        checkIfPopupWasClicked(e);
    });
    $btnNext.on('click', nextStep);
    $btnPrev.on('click', prevStep);
    $btnSave.on('click', saveData);
    $btnClose.on('click', closePopup);

    /******************************************************************
        FUNCTIONS
    ******************************************************************/

    function nextStep() {
        var $clickedBtn = $(this);
        var $currentStep = $clickedBtn.closest('.m_popup__step');

        // hide current step
        $currentStep.removeClass('is-active');

        // make next step active
        $currentStep.next('.m_popup__step').addClass('is-active');
    }

    function prevStep() {
        var $clickedBtn = $(this);
        var $currentStep = $clickedBtn.closest('.m_popup__step');

        // hide current step
        $currentStep.removeClass('is-active');

        // make next step active
        $currentStep.prev('.m_popup__step').addClass('is-active');
    }

    function checkIfPopupWasClicked(e) {
        var $clickTarget = $(e.target);

        // do not prevent click of view button
        if ($(e.target).hasClass('m_btn__view')) return;

        // check if popup is even active
        if (!$popup.hasClass('is-active')) return;

        // prevent closing on click on popup elements
        if ($(e.target).closest('.m_popup').length) {
            // allow x icon and save button to be clicked
            if (!$(e.target).hasClass('m_btn--close') || !$(e.target).hasClass('m_btn--save')) return;
        }
        closePopup();
    }

    function closePopup() {
        // make first step active for future popups
        $step.removeClass('is-active');
        $('.m_popup__step:first-child').addClass('is-active');

        // close popup
        $popup.removeClass('is-active');
    }

    function saveData() {

        // $.ajax({
        //     url: '/path/to/file',
        //     type: 'default GET (Other values: POST)',
        //     dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
        //     data: {param1: 'value1'},
        // })
        // .done(function() {
        //     console.log("success");
        // })
        // .fail(function() {
        //     console.log("error");
        // })
        // .always(function() {
        //     console.log("complete");
        // });

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
