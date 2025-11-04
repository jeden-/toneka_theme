jQuery(document).ready(function($) {
    function initCustomSelect() {
        // Target all selects except those already processed or specific ones to exclude
        $('select:not(.custom-select-hidden)').each(function() {
            var $originalSelect = $(this);
            $originalSelect.addClass('custom-select-hidden');

            var $customSelectContainer = $('<div class="custom-select-container" />');
            var $customSelectTrigger = $('<div class="custom-select-trigger" />');
            
            // Set initial text from the selected option
            var initialText = $originalSelect.find('option:selected').text();
            $customSelectTrigger.text(initialText);

            var $customOptions = $('<div class="custom-select-options" />');

            $originalSelect.find('option').each(function() {
                var $option = $(this);
                var $customOption = $('<div class="custom-select-option" />');
                $customOption.text($option.text());
                $customOption.data('value', $option.val());

                if ($option.is(':selected')) {
                    $customOption.addClass('selected');
                }

                $customOption.on('click', function() {
                    if (!$(this).hasClass('selected')) {
                        // Update original select value
                        $originalSelect.val($(this).data('value')).trigger('change');
                        
                        // Update trigger text
                        $customSelectTrigger.text($(this).text());
                        
                        // Update selected class
                        $customOptions.find('.custom-select-option.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    $customOptions.hide();
                });

                $customOptions.append($customOption);
            });

            $customSelectTrigger.on('click', function() {
                // Close other open select dropdowns
                $('.custom-select-options').not($customOptions).hide();
                $customOptions.toggle();
            });

            $customSelectContainer.append($customSelectTrigger);
            $customSelectContainer.append($customOptions);

            $originalSelect.after($customSelectContainer);
            $originalSelect.hide();
        });
    }

    // Initial load
    initCustomSelect();

    // Re-initialize for AJAX loaded content (like in WooCommerce checkout)
    $(document.body).on('updated_checkout', function() {
        initCustomSelect();
    });
    
    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.custom-select-container').length) {
            $('.custom-select-options').hide();
        }
    });
});

