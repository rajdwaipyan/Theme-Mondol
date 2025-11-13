/**
 * Mondol Theme Main Script
 * Additional functionality and enhancements
 */

(function($) {
    'use strict';

    var MondolTheme = {
        init: function() {
            this.setupResponsive();
            this.enhanceAccessibility();
        },

        setupResponsive: function() {
            // Handle mobile menu if needed
            var navToggle = $('.nav-toggle');
            if (navToggle.length) {
                navToggle.on('click', function() {
                    $('.site-navigation').toggleClass('open');
                });
            }
        },

        enhanceAccessibility: function() {
            // Add aria-labels where needed
            $('.grid-item').each(function() {
                var title = $(this).find('.grid-item-title').text();
                $(this).attr('aria-label', title);
            });

            // Ensure filter checkboxes are accessible
            $('.filter-checkbox-item label').each(function() {
                var checkboxId = $(this).attr('for');
                if (!checkboxId) {
                    var checkbox = $(this).prev('input');
                    var newId = 'label-' + Math.random().toString(36).substr(2, 9);
                    checkbox.attr('id', newId);
                    $(this).attr('for', newId);
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        MondolTheme.init();
    });

})(jQuery);
