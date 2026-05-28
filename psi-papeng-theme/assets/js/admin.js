/**
 * PSI Papua Pegunungan — Admin JavaScript
 * @version 1.0.0
 */
(function($) {
    'use strict';

    /* Simple admin enhancements */
    $(document).ready(function() {
        /* Highlight active metabox fields */
        $('.psi-metabox input, .psi-metabox textarea').on('focus', function() {
            $(this).closest('p').addClass('psi-field-active');
        }).on('blur', function() {
            $(this).closest('p').removeClass('psi-field-active');
        });
    });

})(jQuery);
