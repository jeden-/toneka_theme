
jQuery(document).ready(function($) {
    // Sortowanie próbek
    $(".downloadable_files tbody").sortable({
        items: "tr",
        cursor: "move",
        axis: "y",
        handle: ".sort",
        scrollSensitivity: 40,
        helper: function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            ui.css("left", "0");
            return ui;
        }
    });

    // Przycisk dodawania próbki
    $(".insert").on("click", function(e) {
        e.preventDefault();
        var $tbody = $(".downloadable_files").find("tbody");
        var $row = $($(this).data("row"));
        $tbody.append($row);
        $row.find(".upload_sample_file_button").click();
    });

    // Przycisk usuwania próbki
    $(".downloadable_files").on("click", ".delete", function(e) {
        e.preventDefault();
        $(this).closest("tr").remove();
    });

    // Przycisk wyboru pliku
    $(".downloadable_files").on("click", ".upload_sample_file_button", function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var file_frame;
        
        // Jeśli media frame już istnieje, otwórz go
        if (file_frame) {
            file_frame.open();
            return;
        }
        
        // Utwórz media frame
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $button.data("choose"),
            button: {
                text: $button.data("update")
            },
            multiple: false,
            library: {
                type: "audio,video" // Przyjmuj tylko pliki audio i wideo
            }
        });
        
        // Gdy wybrano plik
        file_frame.on("select", function() {
            var attachment = file_frame.state().get("selection").first().toJSON();
            
            // Ustaw URL pliku i aktualizuj nazwę, jeśli jest pusta
            $button.closest("tr").find("input[name='_product_sample_files[]']").val(attachment.url);
            
            var $nameField = $button.closest("tr").find("input[name='_product_sample_names[]']");
            if ($nameField.val() === "") {
                $nameField.val(attachment.title);
            }
        });
        
        // Otwórz media frame
        file_frame.open();
    });
});
