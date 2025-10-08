jQuery(document).ready(function($) {
    'use strict';

    // Obsługa dodawania nowej osoby
    $('.toneka-add-person').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var field = button.data('field');
        var fieldId = button.data('field-id');
        var repeater = $('#' + field + '_repeater');
        var currentGroups = repeater.find('.toneka-person-group');
        var newIndex = currentGroups.length;
        
        var hasRole = (field === 'obsada');
        var roleInput = hasRole ? `
            <p class="form-field">
                <label for="${fieldId}[${newIndex}][rola]">Rola</label>
                <input type="text" class="short" name="${fieldId}[${newIndex}][rola]" id="${fieldId}[${newIndex}][rola]" value="">
            </p>
        ` : '';
        
        var newGroup = `
            <div class="${field}_group toneka-person-group" data-index="${newIndex}">
                <p class="form-field">
                    <label for="${fieldId}[${newIndex}][imie_nazwisko]">Imię i nazwisko</label>
                    <input type="text" class="short" name="${fieldId}[${newIndex}][imie_nazwisko]" id="${fieldId}[${newIndex}][imie_nazwisko]" value="">
                </p>
                ${roleInput}
                <button type="button" class="button toneka-remove-person" data-field="${field}">Usuń</button>
            </div>
        `;
        
        repeater.append(newGroup);
    });

    // Obsługa usuwania osoby
    $(document).on('click', '.toneka-remove-person', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var field = button.data('field');
        var group = button.closest('.toneka-person-group');
        var repeater = $('#' + field + '_repeater');
        
        // Nie usuwaj jeśli to jedyna grupa
        if (repeater.find('.toneka-person-group').length > 1) {
            group.remove();
            
            // Przeindeksuj pozostałe grupy
            reindexGroups(field);
        } else {
            // Wyczyść pola w ostatniej grupie
            group.find('input').val('');
        }
    });
    
    // Funkcja do przeindeksowania grup po usunięciu
    function reindexGroups(field) {
        var repeater = $('#' + field + '_repeater');
        var groups = repeater.find('.toneka-person-group');
        
        groups.each(function(index) {
            var group = $(this);
            group.attr('data-index', index);
            
            // Zaktualizuj nazwy i ID pól
            group.find('input').each(function() {
                var input = $(this);
                var name = input.attr('name');
                var id = input.attr('id');
                
                if (name) {
                    var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                    input.attr('name', newName);
                }
                
                if (id) {
                    var newId = id.replace(/\[\d+\]/, '[' + index + ']');
                    input.attr('id', newId);
                    
                    // Zaktualizuj odpowiadający label
                    var label = group.find('label[for="' + id + '"]');
                    if (label.length) {
                        label.attr('for', newId);
                    }
                }
            });
        });
    }
});
























