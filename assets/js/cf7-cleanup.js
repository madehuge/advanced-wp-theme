jQuery(document).ready(function($) {
    // Target your CF7 form
    $('.wpcf7 form').each(function() {
        
        // 1. Remove all <p> tags but keep their contents
        $(this).find('p').each(function() {
            $(this).replaceWith($(this).contents());
        });

        // 2. Remove all style attributes from form elements
        $(this).find('[style]').removeAttr('style');
    });

    var $form = $(this);

        // Replace the default submit input with a custom button
        $form.find('input.wpcf7-submit').each(function() {
            var $input = $(this);
            var btnText = $input.val(); // Get the value (Submit)
            
            // Build custom button
            var $button = $('<button>', {
                type: 'submit',
                class: 'btn btn_deep_blue w-100 wpcf7-submit',
                'aria-label': btnText
            }).html('<span class="bnt_text_wrap"><span>' + btnText + '</span></span><span class="arrow"></span>');

            // Replace the input with the custom button
            $input.replaceWith($button);
        });


});