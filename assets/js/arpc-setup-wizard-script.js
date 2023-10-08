;(function ($) {
    'use strict';

    arpcRenderTab()
    function arpcRenderTab(step = 0) {
        var contents = document.getElementsByClassName('setup-content'),
            prev = document.getElementById('arpc-prev'),
            nextElement = document.getElementById('arpc-next'),
            saveElement = document.getElementById('arpc-save')

        if (contents.length < 1) {
            return
        }

        contents[step].style.display = 'block'
        prev.style.display = (step == 0) ? 'none' : 'inline'
        if (step == contents.length - 1) {
            saveElement.style.display = 'inline'
            nextElement.style.display = 'none'
        } else {
            saveElement.style.display = 'none'
            nextElement.style.display = 'inline'
        }
        arpcStepIndicator(step)

    }

    function arpcStepIndicator(stepNumber) {
        var steps = document.getElementsByClassName('arpc-popup-creator-step'),
            container = document.getElementsByClassName( 'setup-wizard-ul' );

        container[0].setAttribute('data-step', stepNumber);

        for( var i = 0; i < steps.length; i++ ) {
            steps[i].className = steps[i].className.replace(" active","");
        }
        steps[stepNumber].className += ' active';
    }

    $(document).on('click', '#arpc-next, #arpc-prev', function(e){
        var container = document.getElementsByClassName('setup-wizard-ul'),
            stepNumber = parseInt( container[0].getAttribute('data-step')),
            contents   = document.getElementsByClassName("setup-content");

        contents[stepNumber].style.display = "none";
        stepNumber = (e.target.id =='arpc-prev') ? stepNumber - 1 : stepNumber + 1;

        if( e.target.id == 'arpc-next' && stepNumber == 2 ) {
            $.ajax({
                url: localize.ajaxurl,
                type: "POST",
                data: {
                    action: "save_arpc_elements_data",
                    security: localize.nonce,
                    fields: $("form.eael-setup-wizard-form").serialize()
                }
            })
        }
        console.log( 'contents.length', contents.length )
        console.log( 'StepNumber', stepNumber )
        if( stepNumber >= contents.length ) {
            console.log( 'hello hey' )
            return false;
        }
        arpcRenderTab(stepNumber);
    })
})(jQuery)
