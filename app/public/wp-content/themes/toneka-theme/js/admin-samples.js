jQuery(document).ready(function($) {
    'use strict';

    // Obsługa dodawania nowego wiersza próbki
    $('.insert-sample-row').on('click', function(e) {
        e.preventDefault();
        var newRow = `
            <tr class="sample-row">
                <td>
                    <input type="text" class="input_text" placeholder="Nazwa próbki" name="_product_sample_names[]" value="">
                </td>
                <td class="file_url_choose">
                    <input type="text" class="input_text" placeholder="https://" name="_product_sample_files[]" value="">
                    <button type="button" class="button upload_sample_file_button">Wybierz plik</button>
                </td>
                <td>
                    <a href="#" class="delete button">Usuń</a>
                </td>
            </tr>
        `;
        $('.toneka-samples-tbody').append(newRow);
    });

    // Obsługa usuwania wiersza próbki
    $('.toneka-samples-tbody').on('click', '.delete', function(e) {
        e.preventDefault();
        $(this).closest('.sample-row').remove();
    });

    // Obsługa przycisku "Wybierz plik"
    $('.toneka-samples-tbody').on('click', '.upload_sample_file_button', function(e) {
        e.preventDefault();
        var button = $(this);
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Wybierz plik próbki',
            button: {
                text: 'Użyj tego pliku'
            },
            multiple: false
        });

        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            button.siblings('.input_text').val(attachment.url);
        });

        file_frame.open();
    });
});
