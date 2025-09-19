<?php

/**
 * Rejestruje shortcody do wyświetlania niestandardowych pól produktu w Elementorze
 */
if ( ! function_exists( 'toneka_register_custom_fields_shortcodes' ) ) {
	function toneka_register_custom_fields_shortcodes() {
		// Shortcode dla roku produkcji
		add_shortcode('toneka_rok_produkcji', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$rok_produkcji = get_post_meta($product->get_id(), '_rok_produkcji', true);
			if (empty($rok_produkcji)) {
				return '';
			}
			
			return '<div class="toneka-rok-produkcji"><strong>' . esc_html__('Rok produkcji: ', 'boldnote-child') . '</strong> ' . esc_html($rok_produkcji) . '</div>';
		});
		
		// Shortcode dla czasu trwania
		add_shortcode('toneka_czas_trwania', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$czas_trwania = get_post_meta($product->get_id(), '_czas_trwania', true);
			if (empty($czas_trwania)) {
				return '';
			}
			
			return '<div class="toneka-czas-trwania"><strong>' . esc_html__('Czas trwania: ', 'boldnote-child') . '</strong> ' . esc_html($czas_trwania) . ' ' . esc_html__('min', 'boldnote-child') . '</div>';
		});
		
		// Shortcode dla autorów
		add_shortcode('toneka_autorzy', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$autor_title = get_post_meta($product->get_id(), '_autor_title', true);
			$autors = get_post_meta($product->get_id(), '_autors', true);
			
			if (empty($autors)) {
				return '';
			}
			
			$output = '<div class="toneka-autorzy">';
			
			if (!empty($autor_title)) {
				$output .= '<strong>' . esc_html($autor_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Autorzy: ', 'boldnote-child') . '</strong>';
			}
			
			// Użyj nowej funkcji do generowania linków
			$output .= toneka_display_creators_with_links($autors, 'Autor');
			$output .= '</div>';
			
			return $output;
		});
		
		// Shortcode dla obsady
		add_shortcode('toneka_obsada', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$obsada_title = get_post_meta($product->get_id(), '_obsada_title', true);
			$obsada = get_post_meta($product->get_id(), '_obsada', true);
			
			if (empty($obsada)) {
				return '';
			}
			
			$output = '<div class="toneka-obsada">';
			
			if (!empty($obsada_title)) {
				$output .= '<strong>' . esc_html($obsada_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Obsada: ', 'boldnote-child') . '</strong>';
			}
			
			// Użyj nowej funkcji do generowania linków z rolami
			$obsada_with_links = toneka_make_creators_clickable($obsada, 'Aktor/Aktorka');
			$obsada_list = array();
			
			foreach ($obsada_with_links as $osoba) {
				if (!empty($osoba['imie_nazwisko'])) {
					$rola = !empty($osoba['rola']) ? ' - ' . esc_html($osoba['rola']) : '';
					$name_with_link = isset($osoba['clickable_name']) ? $osoba['clickable_name'] : esc_html($osoba['imie_nazwisko']);
					$obsada_list[] = $name_with_link . $rola;
				}
			}
			
			$output .= implode(', ', $obsada_list);
			$output .= '</div>';
			
			return $output;
		});
		
		// Shortcode dla reżyserii
		add_shortcode('toneka_rezyserzy', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$rezyser_title = get_post_meta($product->get_id(), '_rezyser_title', true);
			$rezyserzy = get_post_meta($product->get_id(), '_rezyserzy', true);
			
			if (empty($rezyserzy)) {
				return '';
			}
			
			$output = '<div class="toneka-rezyserzy">';
			
			if (!empty($rezyser_title)) {
				$output .= '<strong>' . esc_html($rezyser_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Reżyseria: ', 'boldnote-child') . '</strong>';
			}
			
			// Użyj nowej funkcji do generowania linków
			$output .= toneka_display_creators_with_links($rezyserzy, 'Reżyser');
			$output .= '</div>';
			
			return $output;
		});
		
		// Shortcode dla muzyki
		add_shortcode('toneka_muzycy', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$muzyka_title = get_post_meta($product->get_id(), '_muzyka_title', true);
			$muzycy = get_post_meta($product->get_id(), '_muzycy', true);
			
			if (empty($muzycy)) {
				return '';
			}
			
			$output = '<div class="toneka-muzycy">';
			
			if (!empty($muzyka_title)) {
				$output .= '<strong>' . esc_html($muzyka_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Muzyka: ', 'boldnote-child') . '</strong>';
			}
			
			// Użyj nowej funkcji do generowania linków
			$output .= toneka_display_creators_with_links($muzycy, 'Muzyk');
			$output .= '</div>';
			
			return $output;
		});
		
		// Shortcode dla tłumaczy
		add_shortcode('toneka_tlumacze', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$tlumacz_title = get_post_meta($product->get_id(), '_tlumacz_title', true);
			$tlumacze = get_post_meta($product->get_id(), '_tlumacze', true);
			
			if (empty($tlumacze)) {
				return '';
			}
			
			$output = '<div class="toneka-tlumacze">';
			
			if (!empty($tlumacz_title)) {
				$output .= '<strong>' . esc_html($tlumacz_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Tłumaczenie: ', 'boldnote-child') . '</strong>';
			}
			
			// Użyj nowej funkcji do generowania linków
			$output .= toneka_display_creators_with_links($tlumacze, 'Tłumacz');
			$output .= '</div>';
			
			return $output;
		});
		
		// Shortcode dla adaptacji tekstu
		add_shortcode('toneka_adaptacja', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$adaptacja_title = get_post_meta($product->get_id(), '_adaptacja_title', true);
			$adaptatorzy = get_post_meta($product->get_id(), '_adaptatorzy', true);
			
			if (empty($adaptatorzy)) {
				return '';
			}
			
			$output = '<div class="toneka-adaptacja">';
			
			if (!empty($adaptacja_title)) {
				$output .= '<strong>' . esc_html($adaptacja_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Adaptacja tekstu: ', 'boldnote-child') . '</strong>';
			}
			
			// Użyj nowej funkcji do generowania linków
			$output .= toneka_display_creators_with_links($adaptatorzy, 'Adaptator');
			$output .= '</div>';
			
			return $output;
		});

		// Shortcode dla wydawcy
		add_shortcode('toneka_wydawca', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$wydawca_title = get_post_meta($product->get_id(), '_wydawca_title', true);
			$wydawcy = get_post_meta($product->get_id(), '_wydawcy', true);
			
			if (empty($wydawcy)) {
				return '';
			}
			
			$output = '<div class="toneka-wydawca">';
			
			if (!empty($wydawca_title)) {
				$output .= '<strong>' . esc_html($wydawca_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Wydawca: ', 'boldnote-child') . '</strong>';
			}
			
			// Wydawcy mają pole 'nazwa' zamiast 'imie_nazwisko' - dostosuj strukturę
			$wydawcy_adapted = array();
			foreach ($wydawcy as $wydawca) {
				if (!empty($wydawca['nazwa'])) {
					$wydawcy_adapted[] = array('imie_nazwisko' => $wydawca['nazwa']);
				}
			}
			
			// Użyj nowej funkcji do generowania linków
			$output .= toneka_display_creators_with_links($wydawcy_adapted, 'Wydawca');
			$output .= '</div>';
			
			return $output;
		});

		// Shortcode dla grafiki
		add_shortcode('toneka_grafika', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			$grafika_title = get_post_meta($product->get_id(), '_grafika_title', true);
			$grafika = get_post_meta($product->get_id(), '_grafika', true);
			
			if (empty($grafika)) {
				return '';
			}
			
			$output = '<div class="toneka-grafika">';
			
			if (!empty($grafika_title)) {
				$output .= '<strong>' . esc_html($grafika_title) . ': </strong>';
			} else {
				$output .= '<strong>' . esc_html__('Grafika: ', 'boldnote-child') . '</strong>';
			}
			
			// Użyj nowej funkcji do generowania linków
			$output .= toneka_display_creators_with_links($grafika, 'Grafik');
			$output .= '</div>';
			
			return $output;
		});

		// Shortcode dla wszystkich pól produktu
		add_shortcode('toneka_product_meta', function($atts) {
			$output = '';
			
			$output .= do_shortcode('[toneka_rok_produkcji]');
			$output .= do_shortcode('[toneka_czas_trwania]');
			$output .= do_shortcode('[toneka_autorzy]');
			$output .= do_shortcode('[toneka_obsada]');
			$output .= do_shortcode('[toneka_rezyserzy]');
			$output .= do_shortcode('[toneka_muzycy]');
			$output .= do_shortcode('[toneka_tlumacze]');
			$output .= do_shortcode('[toneka_adaptacja]');
			$output .= do_shortcode('[toneka_wydawca]');
			$output .= do_shortcode('[toneka_grafika]');
			$output .= do_shortcode('[toneka_kategoria]');
			
			return $output;
		});
		
		// Shortcode dla kategorii produktu WooCommerce
		add_shortcode('toneka_kategoria', function($atts) {
			global $product;
			
			if (!is_a($product, 'WC_Product')) {
				$product = wc_get_product(get_the_ID());
				if (!$product) {
					return '';
				}
			}
			
			// Parseuj atrybuty shortcode
			$atts = shortcode_atts(array(
				'separator' => ', ',
				'show_hierarchy' => 'no',
				'link_to_category' => 'no',
				'show_label' => 'yes',
				'label' => 'Kategoria:'
			), $atts, 'toneka_kategoria');
			
			$show_hierarchy = $atts['show_hierarchy'] === 'yes';
			$link_to_category = $atts['link_to_category'] === 'yes';
			$show_label = $atts['show_label'] === 'yes';
			
			// Pobierz kategorie produktu
			$terms = wp_get_post_terms($product->get_id(), 'product_cat');
			
			if (is_wp_error($terms) || empty($terms)) {
				return '';
			}
			
			$output = '<div class="toneka-kategoria">';
			
			if ($show_label) {
				$output .= '<strong>' . esc_html($atts['label']) . ' </strong>';
			}
			
			$category_names = array();
			
			foreach ($terms as $term) {
				$category_name = '';
				
				if ($show_hierarchy) {
					// Pokaż pełną hierarchię
					$hierarchy = array();
					$current_term = $term;
					
					// Buduj hierarchię od dziecka do rodzica
					while ($current_term) {
						array_unshift($hierarchy, $current_term->name);
						$current_term = $current_term->parent ? get_term($current_term->parent, 'product_cat') : null;
					}
					
					$category_name = implode(' > ', $hierarchy);
				} else {
					$category_name = $term->name;
				}
				
				if ($link_to_category) {
					$category_link = get_term_link($term);
					if (!is_wp_error($category_link)) {
						$category_name = '<a href="' . esc_url($category_link) . '">' . esc_html($category_name) . '</a>';
					}
				} else {
					$category_name = esc_html($category_name);
				}
				
				$category_names[] = $category_name;
			}
			
			$output .= implode($atts['separator'], $category_names);
			$output .= '</div>';
			
			return $output;
		});
	}
	add_action('init', 'toneka_register_custom_fields_shortcodes');
}


if ( ! function_exists( 'toneka_debug_product_metadata' ) ) {
	/**
	 * Funkcja pomocnicza do debugowania metadanych produktu
	 */
	function toneka_debug_product_metadata($product_id = null) {
		if (empty($product_id) && is_singular('product')) {
			$product_id = get_the_ID();
		}
		
		if (empty($product_id)) {
			return "Brak ID produktu";
		}
		
		// Pobierz wszystkie metadane produktu
		$meta = get_post_meta($product_id);
		
		// Sprawdź konkretnie metadane reżyserów
		$rezyserzy_meta = get_post_meta($product_id, '_rezyserzy', true);
		$output = '<div class="toneka-debug" style="background: #f8f8f8; padding: 15px; margin: 15px 0; border: 1px solid #ddd; border-radius: 4px;">';
		$output .= '<h3>Debugowanie metadanych reżyserów</h3>';
		
		$output .= '<p><strong>Typ danych:</strong> ' . gettype($rezyserzy_meta) . '</p>';
		$output .= '<p><strong>Wartość surowa:</strong> ' . print_r($rezyserzy_meta, true) . '</p>';
		
		// Spróbuj przetworzyć dane
		if (is_array($rezyserzy_meta)) {
			$output .= '<p><strong>Liczba elementów:</strong> ' . count($rezyserzy_meta) . '</p>';
			
			$output .= '<p><strong>Zawartość tablicy:</strong></p><ul>';
			foreach ($rezyserzy_meta as $index => $rezyser) {
				$output .= '<li>Element ' . $index . ': ';
				if (is_array($rezyser)) {
					$output .= 'Tablica - ';
					foreach ($rezyser as $key => $value) {
						$output .= $key . ': "' . $value . '", ';
					}
				} else {
					$output .= 'Wartość: "' . $rezyser . '"';
				}
				$output .= '</li>';
			}
			$output .= '</ul>';
		}
		
		$output .= '</div>';
		
		return $output;
	}
}

if ( ! function_exists( 'toneka_debug_shortcode_exists' ) ) {
    function toneka_debug_shortcode_exists() {
        global $shortcode_tags;
        return shortcode_exists( 'toneka_debug' );
    }
}

if ( ! toneka_debug_shortcode_exists() ) {
    add_shortcode('toneka_debug', function() {
        return toneka_debug_product_metadata();
    });
}

/**
 * Shortcode [year] wyświetlający bieżący rok
 */
add_shortcode('year', function() {
	return date('Y');
});


