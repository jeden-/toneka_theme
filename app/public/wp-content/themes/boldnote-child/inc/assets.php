<?php
/**
 * Enqueue scripts and styles.
 *
 * @package boldnote-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'boldnote_child_theme_enqueue_scripts' ) ) {
	/**
	 * Function that enqueue theme's child style
	 */
	function boldnote_child_theme_enqueue_scripts() {
		$main_style = 'boldnote-main';

		wp_enqueue_style( 'boldnote-child-style', get_stylesheet_directory_uri() . '/style.css', array( $main_style ) );
	}

	add_action( 'wp_enqueue_scripts', 'boldnote_child_theme_enqueue_scripts' );
}

/**
 * Dodaje JS do obsługi niestandardowych pól produktu na ekranie edycji
 * w panelu administracyjnym.
 */
function toneka_custom_product_fields_js() {
	global $post_type;
	
	if ( 'product' === $post_type && is_admin() ) {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Tab "Pola niestandardowe"
			var customFieldsTab = $('#toneka_custom_fields_tab');
			
			// Funkcja do pokazywania / ukrywania pól na podstawie typu produktu
			function toggle_custom_fields() {
				var productType = $('#product-type').val();
				
				if (productType === 'simple' || productType === 'variable') {
					// Pokaż tab dla produktów prostych i wariantowych
					customFieldsTab.show();
				} else {
					// Ukryj tab dla innych typów
					customFieldsTab.hide();
				}
			}
			
			// Uruchom funkcję przy ładowaniu strony
			toggle_custom_fields();
			
			// Uruchom funkcję przy zmianie typu produktu
			$('#product-type').on('change', toggle_custom_fields);

			// Funkcja do pokazywania / ukrywania pól na podstawie wartości checkbox'ów
			function toggle_fields_based_on_checkbox(checkbox_id, fields_to_toggle) {
				var checkbox = $('#' + checkbox_id);
				
				function toggle() {
					if (checkbox.is(':checked')) {
						$(fields_to_toggle.join(', ')).closest('.form-field').show();
					} else {
						$(fields_to_toggle.join(', ')).closest('.form-field').hide();
					}
				}
				
				// Uruchom funkcję przy ładowaniu strony
				toggle();
				
				// Uruchom funkcję przy zmianie stanu checkbox'a
				checkbox.on('change', toggle);
			}

			// Pola zależne od "Pokaż Autora"
			toggle_fields_based_on_checkbox('_show_autor_title', [
				'#_autor_title', 
				'#_autors'
			]);

			// Pola zależne od "Pokaż Obsadę"
			toggle_fields_based_on_checkbox('_show_obsada_title', [
				'#_obsada_title', 
				'#_obsada'
			]);

			// Pola zależne od "Pokaż Reżyserię"
			toggle_fields_based_on_checkbox('_show_rezyser_title', [
				'#_rezyser_title', 
				'#_rezyserzy'
			]);

			// Pola zależne od "Pokaż Muzykę"
			toggle_fields_based_on_checkbox('_show_muzyka_title', [
				'#_muzyka_title', 
				'#_muzycy'
			]);

			// Pola zależne od "Pokaż Tłumacza"
			toggle_fields_based_on_checkbox('_show_tlumacz_title', [
				'#_tlumacz_title', 
				'#_tlumacze'
			]);

			// Pola zależne od "Pokaż Adaptację"
			toggle_fields_based_on_checkbox('_show_adaptacja_title', [
				'#_adaptacja_title', 
				'#_adaptatorzy'
			]);

			// Pola zależne od "Pokaż Wydawcę"
			toggle_fields_based_on_checkbox('_show_wydawca_title', [
				'#_wydawca_title', 
				'#_wydawcy'
			]);

			// Pola zależne od "Pokaż Grafikę"
			toggle_fields_based_on_checkbox('_show_grafika_title', [
				'#_grafika_title', 
				'#_grafika'
			]);
		});
		</script>
		<style>
		#toneka_custom_fields .form-field {
			padding: 5px 0;
		}
		#toneka_custom_fields .form-field label {
			font-weight: bold;
		}
		#toneka_custom_fields .description {
			font-style: italic;
			color: #666;
		}
		#toneka_custom_fields input[type="text"],
		#toneka_custom_fields input[type="number"],
		#toneka_custom_fields textarea {
			width: 100%;
			max-width: 500px;
		}
		.form-field._show_checkbox {
			margin-bottom: 15px;
			padding-bottom: 10px;
			border-bottom: 1px solid #eee;
		}
		.form-field._show_checkbox label {
			display: inline-block;
			width: auto;
			margin-left: 5px;
		}
		</style>
		<?php
	}
}
add_action('admin_footer', 'toneka_custom_product_fields_js');

/**
 * Dodaje niestandardowe skrypty JS i style CSS do panelu admina
 */
function toneka_admin_scripts() {
    global $pagenow, $post;

    // Dodaj skrypty tylko na stronie edycji produktu
    if ( ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) && get_post_type() == 'product' ) {
		// Enqueue WordPress media uploader
        wp_enqueue_media();

        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {

            // Obsługa uploadera dla próbek audio
            $('#toneka_product_samples_data').on('click', '.upload_audio_button', function(e) {
                e.preventDefault();

                var button = $(this);
                var row = button.closest('tr');

                var frame = wp.media({
                    title: 'Wybierz plik audio',
                    button: {
                        text: 'Użyj tego pliku'
                    },
                    multiple: false,
					library: {
						type: 'audio'
					}
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    row.find('.sample_file_url').val(attachment.url);
                });

                frame.open();
            });

            // Dodawanie nowego wiersza
            $('#add_sample_row').on('click', function() {
                var rowCount = $('#samples_table tbody tr').length;
                var newRow = '<tr>' +
                    '<td><input type="text" name="product_samples[' + rowCount + '][title]" class="sample_title" style="width:100%;" /></td>' +
                    '<td><input type="text" name="product_samples[' + rowCount + '][file_url]" class="sample_file_url" style="width: calc(100% - 100px); display: inline-block;" /><a href="#" class="button upload_audio_button">Wybierz</a></td>' +
                    '<td><a href="#" class="button remove_sample_row">Usuń</a></td>' +
                    '</tr>';
                $('#samples_table tbody').append(newRow);
            });

            // Usuwanie wiersza
            $('#toneka_product_samples_data').on('click', '.remove_sample_row', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

        });
        </script>
        <?php
    }
}
add_action('admin_enqueue_scripts', 'toneka_admin_scripts');

/**
 * Enqueue carrier selection script (WYŁĄCZONE - używamy nowego systemu).
 */
/*
function toneka_enqueue_carrier_selection_js() {
    if ( is_product() ) {
        wp_enqueue_script(
            'carrier-selection',
            get_stylesheet_directory_uri() . '/js/carrier-selection.js',
            array( 'jquery' ),
            filemtime( get_stylesheet_directory() . '/js/carrier-selection.js' ),
            true
        );

        wp_localize_script( 'carrier-selection', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}
add_action( 'wp_enqueue_scripts', 'toneka_enqueue_carrier_selection_js' );
*/ 