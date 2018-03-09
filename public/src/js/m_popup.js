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
