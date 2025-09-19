<?php

require_once( get_stylesheet_directory() . '/inc/shortcodes.php' );

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
 * Rejestruje niestandardowe meta pola dla produktów WooCommerce
 * Jako widoczne w REST API (wymagane przez Elementor)
 */
function toneka_register_product_meta() {
	// Rok produkcji
	register_post_meta('product', '_rok_produkcji', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Czas trwania
	register_post_meta('product', '_czas_trwania', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Autor title
	register_post_meta('product', '_autor_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Autorzy
	register_post_meta('product', '_autors', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Obsada title
	register_post_meta('product', '_obsada_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Obsada
	register_post_meta('product', '_obsada', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Reżyseria title
	register_post_meta('product', '_rezyser_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Reżyserzy
	register_post_meta('product', '_rezyserzy', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Muzyka title
	register_post_meta('product', '_muzyka_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Muzycy
	register_post_meta('product', '_muzycy', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Tłumacz title
	register_post_meta('product', '_tlumacz_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Tłumacze
	register_post_meta('product', '_tlumacze', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Adaptacja title
	register_post_meta('product', '_adaptacja_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Adaptatorzy
	register_post_meta('product', '_adaptatorzy', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Wydawca title
	register_post_meta('product', '_wydawca_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Wydawcy
	register_post_meta('product', '_wydawcy', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Grafika title
	register_post_meta('product', '_grafika_title', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
	
	// Grafika
	register_post_meta('product', '_grafika', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
}
add_action('init', 'toneka_register_product_meta');

/**
 * Dodaje alternatywny sposób dostępu do niestandardowych pól produktu poprzez
 * publiczne klucze meta (bez podkreślnika)
 */
function toneka_register_alt_product_meta() {
	// Lista par klucz prywatny => klucz publiczny
	$meta_keys_mapping = [
		'_rok_produkcji' => 'rok_produkcji',
		'_autor_title' => 'autor_title',
		'_autors' => 'autorzy',
		'_obsada_title' => 'obsada_title',
		'_obsada' => 'obsada',
		'_rezyser_title' => 'rezyser_title',
		'_rezyserzy' => 'rezyserzy',
		'_muzyka_title' => 'muzyka_title',
		'_muzycy' => 'muzycy',
		'_tlumacz_title' => 'tlumacz_title',
		'_tlumacze' => 'tlumacze',
		'_adaptacja_title' => 'adaptacja_title',
		'_adaptatorzy' => 'adaptatorzy',
		'_wydawca_title' => 'wydawca_title',
		'_wydawcy' => 'wydawcy',
		'_grafika_title' => 'grafika_title',
		'_grafika' => 'grafika',
	];
	
	// Dodaj filtry dla każdego klucza
	foreach ($meta_keys_mapping as $private_key => $public_key) {
		add_filter('get_post_metadata', function($value, $object_id, $meta_key, $single) use ($private_key, $public_key) {
			if ($meta_key !== $public_key) {
				return $value;
			}
			
			// Pobierz wartość z prywatnego klucza meta
			return get_post_meta($object_id, $private_key, $single);
		}, 10, 4);
		
		// Rejestruj również publiczne klucze
		register_post_meta('product', $public_key, array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
			'auth_callback' => function() { return current_user_can('edit_posts'); }
		));
	}
}
add_action('init', 'toneka_register_alt_product_meta');

/**
 * Dodaje niestandardowe pola do produktów WooCommerce
 */
function toneka_add_custom_product_fields() {
	global $post;
	
	// Pole "Rok produkcji"
	woocommerce_wp_text_input(
		array(
			'id'          => '_rok_produkcji',
			'label'       => __( 'Rok produkcji', 'boldnote-child' ),
			'placeholder' => __( 'Np. 2023', 'boldnote-child' ),
			'description' => __( 'Rok produkcji', 'boldnote-child' ),
			'desc_tip'    => true,
			'type'        => 'number',
		)
	);
	
	// Pole "Czas trwania"
	woocommerce_wp_text_input(
		array(
			'id'          => '_czas_trwania',
			'label'       => __( 'Czas trwania', 'boldnote-child' ),
			'placeholder' => __( 'Np. 120', 'boldnote-child' ),
			'description' => __( 'Czas trwania w minutach', 'boldnote-child' ),
			'desc_tip'    => true,
			'type'        => 'number',
		)
	);
	
	// Grupa pól "Autor"
	woocommerce_wp_text_input(
		array(
			'id'          => '_autor_title',
			'label'       => __( 'Autorzy', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji autorów.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla autorów
	$autors = get_post_meta( $post->ID, '_autors', true );
	
	echo '<div id="autor_repeater" class="options_group">';
	echo '<p>' . __( 'Autorzy', 'boldnote-child' ) . '</p>';
	
	if ( empty( $autors ) ) {
		$autors = array(
			array(
				'imie_nazwisko' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola autorów
	foreach ( $autors as $index => $autor ) {
		echo '<div class="autor_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_autors[' . $index . '][imie_nazwisko]',
				'label'       => __( 'Imię i nazwisko', 'boldnote-child' ),
				'placeholder' => __( 'Np. Jan Kowalski', 'boldnote-child' ),
				'value'       => isset( $autor['imie_nazwisko'] ) ? $autor['imie_nazwisko'] : '',
			)
		);
		echo '<button type="button" class="button remove_autor">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_autor">' . __( 'Dodaj autora', 'boldnote-child' ) . '</button>';
	echo '</div>';
	
	// Grupa pól "Obsada"
	woocommerce_wp_text_input(
		array(
			'id'          => '_obsada_title',
			'label'       => __( 'Obsada', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji obsady.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla obsady
	$obsada = get_post_meta( $post->ID, '_obsada', true );
	
	echo '<div id="obsada_repeater" class="options_group">';
	echo '<p>' . __( 'Obsada', 'boldnote-child' ) . '</p>';
	
	if ( empty( $obsada ) ) {
		$obsada = array(
			array(
				'imie_nazwisko' => '',
				'rola' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola obsady
	foreach ( $obsada as $index => $osoba ) {
		echo '<div class="obsada_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_obsada[' . $index . '][imie_nazwisko]',
				'label'       => __( 'Imię i nazwisko', 'boldnote-child' ),
				'placeholder' => __( 'Np. Jan Kowalski', 'boldnote-child' ),
				'value'       => isset( $osoba['imie_nazwisko'] ) ? $osoba['imie_nazwisko'] : '',
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_obsada[' . $index . '][rola]',
				'label'       => __( 'Rola', 'boldnote-child' ),
				'placeholder' => __( 'Np. Kolejarz', 'boldnote-child' ),
				'value'       => isset( $osoba['rola'] ) ? $osoba['rola'] : '',
			)
		);
		echo '<button type="button" class="button remove_obsada">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_obsada">' . __( 'Dodaj osobę', 'boldnote-child' ) . '</button>';
	echo '</div>';
	
	// Grupa pól "Reżyseria"
	woocommerce_wp_text_input(
		array(
			'id'          => '_rezyser_title',
			'label'       => __( 'Reżyseria', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji reżyserii.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla reżyserów
	$rezyserzy = get_post_meta( $post->ID, '_rezyserzy', true );
	
	echo '<div id="rezyser_repeater" class="options_group">';
	echo '<p>' . __( 'Reżyseria', 'boldnote-child' ) . '</p>';
	
	if ( empty( $rezyserzy ) ) {
		$rezyserzy = array(
			array(
				'imie_nazwisko' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola reżyserów
	foreach ( $rezyserzy as $index => $rezyser ) {
		echo '<div class="rezyser_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_rezyserzy[' . $index . '][imie_nazwisko]',
				'label'       => __( 'Imię i nazwisko', 'boldnote-child' ),
				'placeholder' => __( 'Np. Jan Kowalski', 'boldnote-child' ),
				'value'       => isset( $rezyser['imie_nazwisko'] ) ? $rezyser['imie_nazwisko'] : '',
			)
		);
		echo '<button type="button" class="button remove_rezyser">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_rezyser">' . __( 'Dodaj reżysera', 'boldnote-child' ) . '</button>';
	echo '</div>';
	
	// Grupa pól "Muzyka"
	woocommerce_wp_text_input(
		array(
			'id'          => '_muzyka_title',
			'label'       => __( 'Muzyka', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji muzyki.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla muzyków
	$muzycy = get_post_meta( $post->ID, '_muzycy', true );
	
	echo '<div id="muzyka_repeater" class="options_group">';
	echo '<p>' . __( 'Muzyka', 'boldnote-child' ) . '</p>';
	
	if ( empty( $muzycy ) ) {
		$muzycy = array(
			array(
				'imie_nazwisko' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola muzyków
	foreach ( $muzycy as $index => $muzyk ) {
		echo '<div class="muzyka_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_muzycy[' . $index . '][imie_nazwisko]',
				'label'       => __( 'Imię i nazwisko', 'boldnote-child' ),
				'placeholder' => __( 'Np. Jan Kowalski', 'boldnote-child' ),
				'value'       => isset( $muzyk['imie_nazwisko'] ) ? $muzyk['imie_nazwisko'] : '',
			)
		);
		echo '<button type="button" class="button remove_muzyk">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_muzyk">' . __( 'Dodaj muzyka', 'boldnote-child' ) . '</button>';
	echo '</div>';
	
	// Grupa pól "Tłumacz"
	woocommerce_wp_text_input(
		array(
			'id'          => '_tlumacz_title',
			'label'       => __( 'Tłumaczenie', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji tłumaczenia.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla tłumaczy
	$tlumacze = get_post_meta( $post->ID, '_tlumacze', true );
	
	echo '<div id="tlumacz_repeater" class="options_group">';
	echo '<p>' . __( 'Tłumaczenie', 'boldnote-child' ) . '</p>';
	
	if ( empty( $tlumacze ) ) {
		$tlumacze = array(
			array(
				'imie_nazwisko' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola tłumaczy
	foreach ( $tlumacze as $index => $tlumacz ) {
		echo '<div class="tlumacz_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_tlumacze[' . $index . '][imie_nazwisko]',
				'label'       => __( 'Imię i nazwisko', 'boldnote-child' ),
				'placeholder' => __( 'Np. Jan Kowalski', 'boldnote-child' ),
				'value'       => isset( $tlumacz['imie_nazwisko'] ) ? $tlumacz['imie_nazwisko'] : '',
			)
		);
		echo '<button type="button" class="button remove_tlumacz">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_tlumacz">' . __( 'Dodaj tłumacza', 'boldnote-child' ) . '</button>';
	echo '</div>';
	
	// Grupa pól "Adaptacja tekstu"
	woocommerce_wp_text_input(
		array(
			'id'          => '_adaptacja_title',
			'label'       => __( 'Adaptacja tekstu', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji adaptacji tekstu.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla adaptatorów
	$adaptatorzy = get_post_meta( $post->ID, '_adaptatorzy', true );
	
	echo '<div id="adaptacja_repeater" class="options_group">';
	echo '<p>' . __( 'Adaptacja tekstu', 'boldnote-child' ) . '</p>';
	
	if ( empty( $adaptatorzy ) ) {
		$adaptatorzy = array(
			array(
				'imie_nazwisko' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola adaptatorów
	foreach ( $adaptatorzy as $index => $adaptator ) {
		echo '<div class="adaptacja_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_adaptatorzy[' . $index . '][imie_nazwisko]',
				'label'       => __( 'Imię i nazwisko', 'boldnote-child' ),
				'placeholder' => __( 'Np. Jan Kowalski', 'boldnote-child' ),
				'value'       => isset( $adaptator['imie_nazwisko'] ) ? $adaptator['imie_nazwisko'] : '',
			)
		);
		echo '<button type="button" class="button remove_adaptator">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_adaptator">' . __( 'Dodaj osobę', 'boldnote-child' ) . '</button>';
	echo '</div>';
	
	// Grupa pól "Wydawca"
	woocommerce_wp_text_input(
		array(
			'id'          => '_wydawca_title',
			'label'       => __( 'Wydawca', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji wydawcy.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla wydawców
	$wydawcy = get_post_meta( $post->ID, '_wydawcy', true );
	
	echo '<div id="wydawca_repeater" class="options_group">';
	echo '<p>' . __( 'Wydawca', 'boldnote-child' ) . '</p>';
	
	if ( empty( $wydawcy ) ) {
		$wydawcy = array(
			array(
				'nazwa' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola wydawców
	foreach ( $wydawcy as $index => $wydawca ) {
		echo '<div class="wydawca_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_wydawcy[' . $index . '][nazwa]',
				'label'       => __( 'Nazwa wydawcy', 'boldnote-child' ),
				'placeholder' => __( 'Np. Wydawnictwo XYZ', 'boldnote-child' ),
				'value'       => isset( $wydawca['nazwa'] ) ? $wydawca['nazwa'] : '',
			)
		);
		echo '<button type="button" class="button remove_wydawca">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_wydawca">' . __( 'Dodaj wydawcę', 'boldnote-child' ) . '</button>';
	echo '</div>';
	
	// Grupa pól "Grafika"
	woocommerce_wp_text_input(
		array(
			'id'          => '_grafika_title',
			'label'       => __( 'Grafika', 'boldnote-child' ),
			'placeholder' => '',
			'description' => __( 'Nagłówek sekcji grafiki.', 'boldnote-child' ),
			'desc_tip'    => true,
		)
	);
	
	// Pobierz zapisane wartości dla grafików
	$grafika = get_post_meta( $post->ID, '_grafika', true );
	
	echo '<div id="grafika_repeater" class="options_group">';
	echo '<p>' . __( 'Grafika', 'boldnote-child' ) . '</p>';
	
	if ( empty( $grafika ) ) {
		$grafika = array(
			array(
				'imie_nazwisko' => '',
			)
		);
	}
	
	// Wyświetl istniejące pola grafików
	foreach ( $grafika as $index => $grafika_item ) {
		echo '<div class="grafika_group" data-index="' . esc_attr( $index ) . '">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_grafika[' . $index . '][imie_nazwisko]',
				'label'       => __( 'Imię i nazwisko', 'boldnote-child' ),
				'placeholder' => __( 'Np. Jan Kowalski', 'boldnote-child' ),
				'value'       => isset( $grafika_item['imie_nazwisko'] ) ? $grafika_item['imie_nazwisko'] : '',
			)
		);
		echo '<button type="button" class="button remove_grafika">' . __( 'Usuń', 'boldnote-child' ) . '</button>';
		echo '</div>';
	}
	
	echo '<button type="button" class="button add_grafika">' . __( 'Dodaj grafika', 'boldnote-child' ) . '</button>';
	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'toneka_add_custom_product_fields' );

/**
 * Zapisuje niestandardowe pola produktów
 */
function toneka_save_custom_product_fields( $post_id ) {
	// Zapisz rok produkcji
	if ( isset( $_POST['_rok_produkcji'] ) ) {
		update_post_meta( $post_id, '_rok_produkcji', sanitize_text_field( $_POST['_rok_produkcji'] ) );
	}
	
	// Zapisz czas trwania
	if ( isset( $_POST['_czas_trwania'] ) ) {
		update_post_meta( $post_id, '_czas_trwania', sanitize_text_field( $_POST['_czas_trwania'] ) );
	}
	
	// Zapisz nagłówki sekcji
	if ( isset( $_POST['_autor_title'] ) ) {
		update_post_meta( $post_id, '_autor_title', sanitize_text_field( $_POST['_autor_title'] ) );
	}
	
	if ( isset( $_POST['_obsada_title'] ) ) {
		update_post_meta( $post_id, '_obsada_title', sanitize_text_field( $_POST['_obsada_title'] ) );
	}
	
	if ( isset( $_POST['_rezyser_title'] ) ) {
		update_post_meta( $post_id, '_rezyser_title', sanitize_text_field( $_POST['_rezyser_title'] ) );
	}
	
	if ( isset( $_POST['_muzyka_title'] ) ) {
		update_post_meta( $post_id, '_muzyka_title', sanitize_text_field( $_POST['_muzyka_title'] ) );
	}
	
	// Zapisz dane autorów
	if ( isset( $_POST['_autors'] ) ) {
		$autors = array();
		
		foreach ( $_POST['_autors'] as $index => $autor ) {
			if ( ! empty( $autor['imie_nazwisko'] ) ) {
				$autors[] = array(
					'imie_nazwisko' => sanitize_text_field( $autor['imie_nazwisko'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_autors', $autors );
	}
	
	// Zapisz dane obsady
	if ( isset( $_POST['_obsada'] ) ) {
		$obsada = array();
		
		foreach ( $_POST['_obsada'] as $index => $osoba ) {
			if ( ! empty( $osoba['imie_nazwisko'] ) ) {
				$obsada[] = array(
					'imie_nazwisko' => sanitize_text_field( $osoba['imie_nazwisko'] ),
					'rola' => sanitize_text_field( $osoba['rola'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_obsada', $obsada );
	}
	
	// Zapisz dane reżyserów
	if ( isset( $_POST['_rezyserzy'] ) ) {
		$rezyserzy = array();
		
		foreach ( $_POST['_rezyserzy'] as $index => $rezyser ) {
			if ( ! empty( $rezyser['imie_nazwisko'] ) ) {
				$rezyserzy[] = array(
					'imie_nazwisko' => sanitize_text_field( $rezyser['imie_nazwisko'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_rezyserzy', $rezyserzy );
	}
	
	// Zapisz dane muzyków
	if ( isset( $_POST['_muzycy'] ) ) {
		$muzycy = array();
		
		foreach ( $_POST['_muzycy'] as $index => $muzyk ) {
			if ( ! empty( $muzyk['imie_nazwisko'] ) ) {
				$muzycy[] = array(
					'imie_nazwisko' => sanitize_text_field( $muzyk['imie_nazwisko'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_muzycy', $muzycy );
	}
	
	// Zapisz nagłówki nowych sekcji
	if ( isset( $_POST['_tlumacz_title'] ) ) {
		update_post_meta( $post_id, '_tlumacz_title', sanitize_text_field( $_POST['_tlumacz_title'] ) );
	}
	
	if ( isset( $_POST['_adaptacja_title'] ) ) {
		update_post_meta( $post_id, '_adaptacja_title', sanitize_text_field( $_POST['_adaptacja_title'] ) );
	}
	
	// Zapisz dane tłumaczy
	if ( isset( $_POST['_tlumacze'] ) ) {
		$tlumacze = array();
		
		foreach ( $_POST['_tlumacze'] as $index => $tlumacz ) {
			if ( ! empty( $tlumacz['imie_nazwisko'] ) ) {
				$tlumacze[] = array(
					'imie_nazwisko' => sanitize_text_field( $tlumacz['imie_nazwisko'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_tlumacze', $tlumacze );
	}
	
	// Zapisz dane adaptatorów
	if ( isset( $_POST['_adaptatorzy'] ) ) {
		$adaptatorzy = array();
		
		foreach ( $_POST['_adaptatorzy'] as $index => $adaptator ) {
			if ( ! empty( $adaptator['imie_nazwisko'] ) ) {
				$adaptatorzy[] = array(
					'imie_nazwisko' => sanitize_text_field( $adaptator['imie_nazwisko'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_adaptatorzy', $adaptatorzy );
	}
	
	// Zapisz nagłówek wydawcy
	if ( isset( $_POST['_wydawca_title'] ) ) {
		update_post_meta( $post_id, '_wydawca_title', sanitize_text_field( $_POST['_wydawca_title'] ) );
	}
	
	// Zapisz dane wydawców
	if ( isset( $_POST['_wydawcy'] ) ) {
		$wydawcy = array();
		
		foreach ( $_POST['_wydawcy'] as $index => $wydawca ) {
			if ( ! empty( $wydawca['nazwa'] ) ) {
				$wydawcy[] = array(
					'nazwa' => sanitize_text_field( $wydawca['nazwa'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_wydawcy', $wydawcy );
	}
	
	// Zapisz dane grafików
	if ( isset( $_POST['_grafika'] ) ) {
		$grafika = array();
		
		foreach ( $_POST['_grafika'] as $index => $grafika_item ) {
			if ( ! empty( $grafika_item['imie_nazwisko'] ) ) {
				$grafika[] = array(
					'imie_nazwisko' => sanitize_text_field( $grafika_item['imie_nazwisko'] ),
				);
			}
		}
		
		update_post_meta( $post_id, '_grafika', $grafika );
	}
	
	// Zapisz nagłówek grafiki
	if ( isset( $_POST['_grafika_title'] ) ) {
		update_post_meta( $post_id, '_grafika_title', sanitize_text_field( $_POST['_grafika_title'] ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'toneka_save_custom_product_fields' );

/**
 * Dodaje skrypt JavaScript do obsługi dynamicznych pól
 */
function toneka_custom_product_fields_js() {
	global $post, $pagenow, $typenow;
	
	// Sprawdź, czy jesteśmy na stronie edycji produktu
	if ( ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) && $typenow === 'product' ) {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				// Obsługa dodawania/usuwania autorów
				var autorIndex = $('.autor_group').length;
				
				$('.add_autor').on('click', function() {
					var template = `
						<div class="autor_group" data-index="${autorIndex}">
							<p class="form-field _autors[${autorIndex}][imie_nazwisko]_field">
								<label for="_autors[${autorIndex}][imie_nazwisko]">Imię i nazwisko</label>
								<input type="text" class="short" name="_autors[${autorIndex}][imie_nazwisko]" id="_autors[${autorIndex}][imie_nazwisko]" value="" placeholder="Np. Jan Kowalski">
							</p>
							<button type="button" class="button remove_autor">Usuń</button>
						</div>
					`;
					
					$('#autor_repeater').append(template);
					autorIndex++;
				});
				
				$(document).on('click', '.remove_autor', function() {
					$(this).closest('.autor_group').remove();
				});
				
				// Obsługa dodawania/usuwania obsady
				var obsadaIndex = $('.obsada_group').length;
				
				$('.add_obsada').on('click', function() {
					var template = `
						<div class="obsada_group" data-index="${obsadaIndex}">
							<p class="form-field _obsada[${obsadaIndex}][imie_nazwisko]_field">
								<label for="_obsada[${obsadaIndex}][imie_nazwisko]">Imię i nazwisko</label>
								<input type="text" class="short" name="_obsada[${obsadaIndex}][imie_nazwisko]" id="_obsada[${obsadaIndex}][imie_nazwisko]" value="" placeholder="Np. Jan Kowalski">
							</p>
							<p class="form-field _obsada[${obsadaIndex}][rola]_field">
								<label for="_obsada[${obsadaIndex}][rola]">Rola</label>
								<input type="text" class="short" name="_obsada[${obsadaIndex}][rola]" id="_obsada[${obsadaIndex}][rola]" value="" placeholder="Np. Kolejarz">
							</p>
							<button type="button" class="button remove_obsada">Usuń</button>
						</div>
					`;
					
					$('#obsada_repeater').append(template);
					obsadaIndex++;
				});
				
				$(document).on('click', '.remove_obsada', function() {
					$(this).closest('.obsada_group').remove();
				});
				
				// Obsługa dodawania/usuwania reżyserów
				var rezyserIndex = $('.rezyser_group').length;
				
				$('.add_rezyser').on('click', function() {
					var template = `
						<div class="rezyser_group" data-index="${rezyserIndex}">
							<p class="form-field _rezyserzy[${rezyserIndex}][imie_nazwisko]_field">
								<label for="_rezyserzy[${rezyserIndex}][imie_nazwisko]">Imię i nazwisko</label>
								<input type="text" class="short" name="_rezyserzy[${rezyserIndex}][imie_nazwisko]" id="_rezyserzy[${rezyserIndex}][imie_nazwisko]" value="" placeholder="Np. Jan Kowalski">
							</p>
							<button type="button" class="button remove_rezyser">Usuń</button>
						</div>
					`;
					
					$('#rezyser_repeater').append(template);
					rezyserIndex++;
				});
				
				$(document).on('click', '.remove_rezyser', function() {
					$(this).closest('.rezyser_group').remove();
				});
				
				// Obsługa dodawania/usuwania muzyków
				var muzykIndex = $('.muzyka_group').length;
				
				$('.add_muzyk').on('click', function() {
					var template = `
						<div class="muzyka_group" data-index="${muzykIndex}">
							<p class="form-field _muzycy[${muzykIndex}][imie_nazwisko]_field">
								<label for="_muzycy[${muzykIndex}][imie_nazwisko]">Imię i nazwisko</label>
								<input type="text" class="short" name="_muzycy[${muzykIndex}][imie_nazwisko]" id="_muzycy[${muzykIndex}][imie_nazwisko]" value="" placeholder="Np. Jan Kowalski">
							</p>
							<button type="button" class="button remove_muzyk">Usuń</button>
						</div>
					`;
					
					$('#muzyka_repeater').append(template);
					muzykIndex++;
				});
				
				$(document).on('click', '.remove_muzyk', function() {
					$(this).closest('.muzyka_group').remove();
				});
				
				// Obsługa dodawania/usuwania tłumaczy
				var tlumaczIndex = $('.tlumacz_group').length;
				
				$('.add_tlumacz').on('click', function() {
					var template = `
						<div class="tlumacz_group" data-index="${tlumaczIndex}">
							<p class="form-field _tlumacze[${tlumaczIndex}][imie_nazwisko]_field">
								<label for="_tlumacze[${tlumaczIndex}][imie_nazwisko]">Imię i nazwisko</label>
								<input type="text" class="short" name="_tlumacze[${tlumaczIndex}][imie_nazwisko]" id="_tlumacze[${tlumaczIndex}][imie_nazwisko]" value="" placeholder="Np. Jan Kowalski">
							</p>
							<button type="button" class="button remove_tlumacz">Usuń</button>
						</div>
					`;
					
					$('#tlumacz_repeater').append(template);
					tlumaczIndex++;
				});
				
				$(document).on('click', '.remove_tlumacz', function() {
					$(this).closest('.tlumacz_group').remove();
				});
				
				// Obsługa dodawania/usuwania adaptatorów
				var adaptatorIndex = $('.adaptacja_group').length;
				
				$('.add_adaptator').on('click', function() {
					var template = `
						<div class="adaptacja_group" data-index="${adaptatorIndex}">
							<p class="form-field _adaptatorzy[${adaptatorIndex}][imie_nazwisko]_field">
								<label for="_adaptatorzy[${adaptatorIndex}][imie_nazwisko]">Imię i nazwisko</label>
								<input type="text" class="short" name="_adaptatorzy[${adaptatorIndex}][imie_nazwisko]" id="_adaptatorzy[${adaptatorIndex}][imie_nazwisko]" value="" placeholder="Np. Jan Kowalski">
							</p>
							<button type="button" class="button remove_adaptator">Usuń</button>
						</div>
					`;
					
					$('#adaptacja_repeater').append(template);
					adaptatorIndex++;
				});
				
				$(document).on('click', '.remove_adaptator', function() {
					$(this).closest('.adaptacja_group').remove();
				});
				
				// Obsługa dodawania/usuwania wydawców
				var wydawcaIndex = $('.wydawca_group').length;
				
				$('.add_wydawca').on('click', function() {
					var template = `
						<div class="wydawca_group" data-index="${wydawcaIndex}">
							<p class="form-field _wydawcy[${wydawcaIndex}][nazwa]_field">
								<label for="_wydawcy[${wydawcaIndex}][nazwa]">Nazwa wydawcy</label>
								<input type="text" class="short" name="_wydawcy[${wydawcaIndex}][nazwa]" id="_wydawcy[${wydawcaIndex}][nazwa]" value="" placeholder="Np. Wydawnictwo XYZ">
							</p>
							<button type="button" class="button remove_wydawca">Usuń</button>
						</div>
					`;
					
					$('#wydawca_repeater').append(template);
					wydawcaIndex++;
				});
				
				$(document).on('click', '.remove_wydawca', function() {
					$(this).closest('.wydawca_group').remove();
				});
				
				// Obsługa dodawania/usuwania grafików
				var grafikaIndex = $('.grafika_group').length;
				
				$('.add_grafika').on('click', function() {
					var template = `
						<div class="grafika_group" data-index="${grafikaIndex}">
							<p class="form-field _grafika[${grafikaIndex}][imie_nazwisko]_field">
								<label for="_grafika[${grafikaIndex}][imie_nazwisko]">Imię i nazwisko</label>
								<input type="text" class="short" name="_grafika[${grafikaIndex}][imie_nazwisko]" id="_grafika[${grafikaIndex}][imie_nazwisko]" value="" placeholder="Np. Jan Kowalski">
							</p>
							<button type="button" class="button remove_grafika">Usuń</button>
						</div>
					`;
					
					$('#grafika_repeater').append(template);
					grafikaIndex++;
				});
    
				$(document).on('click', '.remove_grafika', function() {
					$(this).closest('.grafika_group').remove();
				});

			});
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'toneka_custom_product_fields_js' );

/**
 * Wyświetla niestandardowe pola produktów na stronie produktu
 */
function toneka_display_custom_product_fields() {
	global $product;
	
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return;
	}
	
	$post_id = $product->get_id();
	
	// Rok produkcji
	$rok_produkcji = get_post_meta( $post_id, '_rok_produkcji', true );
	
	if ( ! empty( $rok_produkcji ) ) {
		echo '<div class="toneka-product-meta toneka-product-rok-produkcji">';
		echo '<strong>' . esc_html__( 'Rok produkcji: ', 'boldnote-child' ) . '</strong>';
		echo '<span>' . esc_html( $rok_produkcji ) . '</span>';
		echo '</div>';
	}
	
	// Czas trwania
	$czas_trwania = get_post_meta( $post_id, '_czas_trwania', true );
	
	if ( ! empty( $czas_trwania ) ) {
		echo '<div class="toneka-product-meta toneka-product-czas-trwania">';
		echo '<strong>' . esc_html__( 'Czas trwania: ', 'boldnote-child' ) . '</strong>';
		echo '<span>' . esc_html( $czas_trwania ) . ' ' . esc_html__( 'min', 'boldnote-child' ) . '</span>';
		echo '</div>';
	}
	
	// Sekcja "Autor"
	$autor_title = get_post_meta( $post_id, '_autor_title', true );
	$autors = get_post_meta( $post_id, '_autors', true );
	
	if ( ! empty( $autors ) ) {
		echo '<div class="toneka-product-meta toneka-product-autor">';
		
		if ( ! empty( $autor_title ) ) {
			echo '<strong>' . esc_html( $autor_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Autorzy: ', 'boldnote-child' ) . '</strong>';
		}
		
		$authors_list = array();
		foreach ( $autors as $autor ) {
			if ( ! empty( $autor['imie_nazwisko'] ) ) {
				$authors_list[] = esc_html( $autor['imie_nazwisko'] );
			}
		}
		echo '<span>' . implode(', ', $authors_list) . '</span>';
		
		echo '</div>';
	}
	
	// Sekcja "Obsada"
	$obsada_title = get_post_meta( $post_id, '_obsada_title', true );
	$obsada = get_post_meta( $post_id, '_obsada', true );
	
	if ( ! empty( $obsada ) ) {
		echo '<div class="toneka-product-meta toneka-product-obsada">';
		
		if ( ! empty( $obsada_title ) ) {
			echo '<strong>' . esc_html( $obsada_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Obsada: ', 'boldnote-child' ) . '</strong>';
		}
		
		$obsada_list = array();
		foreach ( $obsada as $osoba ) {
			if ( ! empty( $osoba['imie_nazwisko'] ) ) {
				$rola = ! empty( $osoba['rola'] ) ? ' - ' . esc_html( $osoba['rola'] ) : '';
				$obsada_list[] = esc_html( $osoba['imie_nazwisko'] ) . $rola;
			}
		}
		echo '<span>' . implode(', ', $obsada_list) . '</span>';
		
		echo '</div>';
	}
	
	// Sekcja "Reżyseria"
	$rezyser_title = get_post_meta( $post_id, '_rezyser_title', true );
	$rezyserzy = get_post_meta( $post_id, '_rezyserzy', true );
	
	// Napraw format reżyserów
	$rezyserzy = toneka_fix_product_meta_format($post_id);
	
	if ( ! empty( $rezyserzy ) ) {
		echo '<div class="toneka-product-meta toneka-product-rezyser">';
		
		if ( ! empty( $rezyser_title ) ) {
			echo '<strong>' . esc_html( $rezyser_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Reżyseria: ', 'boldnote-child' ) . '</strong>';
		}
		
		$rezyserzy_list = array();
        if (is_array($rezyserzy)) {
            foreach ($rezyserzy as $rezyser) {
                if (is_array($rezyser) && !empty($rezyser['imie_nazwisko'])) {
                    $rezyserzy_list[] = esc_html($rezyser['imie_nazwisko']);
                } elseif (is_string($rezyser) && !empty($rezyser)) {
                    $rezyserzy_list[] = esc_html($rezyser);
                }
            }
        } elseif (is_string($rezyserzy) && !empty($rezyserzy)) {
            $rezyserzy_list[] = esc_html($rezyserzy);
        }
		
		echo '<span>' . implode(', ', $rezyserzy_list) . '</span>';
		
		echo '</div>';
	}
	
	// Sekcja "Muzyka"
	$muzyka_title = get_post_meta( $post_id, '_muzyka_title', true );
	$muzycy = get_post_meta( $post_id, '_muzycy', true );
	
	if ( ! empty( $muzycy ) ) {
		echo '<div class="toneka-product-meta toneka-product-muzyka">';
		
		if ( ! empty( $muzyka_title ) ) {
			echo '<strong>' . esc_html( $muzyka_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Muzyka: ', 'boldnote-child' ) . '</strong>';
		}
		
		$muzycy_list = array();
		foreach ( $muzycy as $muzyk ) {
			if ( ! empty( $muzyk['imie_nazwisko'] ) ) {
				$muzycy_list[] = esc_html( $muzyk['imie_nazwisko'] );
			}
		}
		echo '<span>' . implode(', ', $muzycy_list) . '</span>';
		
		echo '</div>';
	}
	
	// Sekcja "Tłumacz"
	$tlumacz_title = get_post_meta( $post_id, '_tlumacz_title', true );
	$tlumacze = get_post_meta( $post_id, '_tlumacze', true );
	
	if ( ! empty( $tlumacze ) ) {
		echo '<div class="toneka-product-meta toneka-product-tlumacz">';
		
		if ( ! empty( $tlumacz_title ) ) {
			echo '<strong>' . esc_html( $tlumacz_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Tłumaczenie: ', 'boldnote-child' ) . '</strong>';
		}
		
		$tlumacze_list = array();
		foreach ($tlumacze as $tlumacz) {
			if (!empty($tlumacz['imie_nazwisko'])) {
				$tlumacze_list[] = esc_html($tlumacz['imie_nazwisko']);
			}
		}
		echo '<span>' . implode(', ', $tlumacze_list) . '</span>';
		
		echo '</div>';
	}
	
	// Sekcja "Adaptacja tekstu"
	$adaptacja_title = get_post_meta( $post_id, '_adaptacja_title', true );
	$adaptatorzy = get_post_meta( $post_id, '_adaptatorzy', true );
	
	if ( ! empty( $adaptatorzy ) ) {
		echo '<div class="toneka-product-meta toneka-product-adaptacja">';
		
		if ( ! empty( $adaptacja_title ) ) {
			echo '<strong>' . esc_html( $adaptacja_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Adaptacja tekstu: ', 'boldnote-child' ) . '</strong>';
		}
		
		$adaptacja_list = array();
		foreach ( $adaptatorzy as $adaptator ) {
			if ( ! empty( $adaptator['imie_nazwisko'] ) ) {
				$adaptacja_list[] = esc_html( $adaptator['imie_nazwisko'] );
			}
		}
		echo '<span>' . implode(', ', $adaptacja_list) . '</span>';
		
		echo '</div>';
	}
	
	// Sekcja "Wydawca"
	$wydawca_title = get_post_meta( $post_id, '_wydawca_title', true );
	$wydawcy = get_post_meta( $post_id, '_wydawcy', true );
	
	if ( ! empty( $wydawcy ) ) {
		echo '<div class="toneka-product-meta toneka-product-wydawca">';
		
		if ( ! empty( $wydawca_title ) ) {
			echo '<strong>' . esc_html( $wydawca_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Wydawca: ', 'boldnote-child' ) . '</strong>';
		}
		
		$wydawcy_list = array();
		foreach ( $wydawcy as $wydawca ) {
			if ( ! empty( $wydawca['nazwa'] ) ) {
				$wydawcy_list[] = esc_html( $wydawca['nazwa'] );
			}
		}
		echo '<span>' . implode(', ', $wydawcy_list) . '</span>';
		
		echo '</div>';
	}
	
	// Sekcja "Grafika"
	$grafika_title = get_post_meta( $post_id, '_grafika_title', true );
	$grafika = get_post_meta( $post_id, '_grafika', true );
	
	if ( ! empty( $grafika ) ) {
		echo '<div class="toneka-product-meta toneka-product-grafika">';
		
		if ( ! empty( $grafika_title ) ) {
			echo '<strong>' . esc_html( $grafika_title ) . ': </strong>';
		} else {
			echo '<strong>' . esc_html__( 'Grafika: ', 'boldnote-child' ) . '</strong>';
		}
		
		$grafika_list = array();
		foreach ( $grafika as $grafika_item ) {
			if ( ! empty( $grafika_item['imie_nazwisko'] ) ) {
				$grafika_list[] = esc_html( $grafika_item['imie_nazwisko'] );
			}
		}
		echo '<span>' . implode(', ', $grafika_list) . '</span>';
		
		echo '</div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'toneka_display_custom_product_fields', 25 );

/**
 * Rejestruje niestandardowe pola produktów WooCommerce dla Elementora
 */
function toneka_register_product_custom_fields_for_elementor() {
    // Upewnij się, że Elementor jest aktywny
    if (!did_action('elementor/loaded')) {
        return;
    }

    // Rejestruj pola jako widoczne dla Elementora
    add_filter('elementor/editor/localize_settings', function ($settings) {
        // Przygotuj nasze pola niestandardowe, aby były dostępne w Elementorze
        $custom_fields = [
            '_rok_produkcji' => 'Rok produkcji',
            '_autors' => 'Autorzy',
            '_obsada' => 'Obsada',
            '_rezyserzy' => 'Reżyseria',
            '_muzycy' => 'Muzyka',
            '_grafika' => 'Grafika',
        ];

        // Dodaj nasze pola do listy pól niestandardowych w Elementorze
        if (!isset($settings['dynamicTags'])) {
            $settings['dynamicTags'] = [];
        }
        
        if (!isset($settings['dynamicTags']['fields'])) {
            $settings['dynamicTags']['fields'] = [];
        }
        
        foreach ($custom_fields as $key => $label) {
            $settings['dynamicTags']['fields'][$key] = $label;
        }
        
        return $settings;
    });
    
    // Bezpośrednio rejestruj nasze meta pola w WordPress
    add_filter('acf/settings/load_json', function($paths) {
        // Emulacja istnienia ACF dla Elementora
        global $wpdb;
        $post_meta_keys = [
            '_rok_produkcji',
            '_autors',
            '_obsada', 
            '_rezyserzy',
            '_muzycy',
        ];
        
        foreach ($post_meta_keys as $key) {
            // Upewnij się, że meta klucze są indeksowane w bazie danych
            $wpdb->query($wpdb->prepare(
                "INSERT IGNORE INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) 
                SELECT p.ID, %s, '' 
                FROM {$wpdb->posts} p 
                WHERE p.post_type = 'product' 
                AND NOT EXISTS (
                    SELECT 1 FROM {$wpdb->postmeta} pm 
                    WHERE pm.post_id = p.ID AND pm.meta_key = %s
                ) LIMIT 1",
                $key, $key
            ));
        }
        
        return $paths;
    });

    // Dodaj nasze meta pola do listy dynamicznych tagów
    add_filter('elementor_pro/dynamic_tags/post_meta/keys', function($keys) {
        $keys[] = '_rok_produkcji';
        // Dodaj pozostałe klucze meta
        $keys[] = '_autor_title';
        $keys[] = '_autors';
        $keys[] = '_obsada_title';
        $keys[] = '_obsada';
        $keys[] = '_rezyser_title';
        $keys[] = '_rezyserzy';
        $keys[] = '_muzyka_title';
        $keys[] = '_muzycy';
        $keys[] = '_grafika_title';
        $keys[] = '_grafika';
        return $keys;
    });

    // Rejestruj dodatkowe pola jako dostępne w postmeta
    add_filter('elementor/dynamic_tags/wp_post_custom_field_id/fields', function($fields) {
        $new_fields = [
            'rok_produkcji' => 'Rok produkcji',
            'autors' => 'Autorzy',
            'obsada' => 'Obsada',
            'rezyserzy' => 'Reżyseria',
            'muzycy' => 'Muzyka',
            'grafika' => 'Grafika',
        ];
        
        foreach ($new_fields as $key => $label) {
            $fields["_$key"] = $label;
        }
        
        return $fields;
    });
}
add_action('init', 'toneka_register_product_custom_fields_for_elementor', 99); // Priorytet 99, aby wykonało się po inicjalizacji Elementora

/**
 * Rejestruje niestandardowe Dynamic Tags dla Elementora
 */
function toneka_register_dynamic_tags_module($dynamic_tags_manager) {
    // Upewnij się, że klasa Tag istnieje
    if (!class_exists('ElementorPro\Modules\DynamicTags\Tags\Base\Tag')) {
        return;
    }
    
    // Dodaj nasz niestandardowy moduł Dynamic Tags
    \Elementor\Plugin::$instance->dynamic_tags->register_group('toneka-tags', [
        'title' => 'Toneka - Pola produktu'
    ]);
    
    // Wymaga Elementor Pro
    if (class_exists('ElementorPro\Plugin')) {
        // Zdefiniuj klasę dla pola Rok Produkcji
        class Toneka_Rok_Produkcji_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-rok-produkcji';
            }
            
            public function get_title() {
                return esc_html__('Rok Produkcji', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $rok_produkcji = get_post_meta($product->get_id(), '_rok_produkcji', true);
                echo esc_html($rok_produkcji);
            }
        }
        
        // Zdefiniuj klasę dla pola Czas Trwania
        class Toneka_Czas_Trwania_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-czas-trwania';
            }
            
            public function get_title() {
                return esc_html__('Czas Trwania', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $czas_trwania = get_post_meta($product->get_id(), '_czas_trwania', true);
                if (!empty($czas_trwania)) {
                    echo esc_html($czas_trwania) . ' ' . esc_html__('min', 'boldnote-child');
                }
            }
        }
        
        // Zdefiniuj klasę dla pola Autorzy
        class Toneka_Autorzy_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-autorzy';
            }
            
            public function get_title() {
                return esc_html__('Autorzy', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $autors = get_post_meta($product->get_id(), '_autors', true);
                if (empty($autors)) {
                    return;
                }
                
                $authors_list = array();
                foreach ($autors as $autor) {
                    if (!empty($autor['imie_nazwisko'])) {
                        $authors_list[] = esc_html($autor['imie_nazwisko']);
                    }
                }
                
                echo implode(', ', $authors_list);
            }
        }
        
        // Zdefiniuj klasę dla pola Obsada
        class Toneka_Obsada_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-obsada';
            }
            
            public function get_title() {
                return esc_html__('Obsada', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $obsada = get_post_meta($product->get_id(), '_obsada', true);
                if (empty($obsada)) {
                    return;
                }
                
                $obsada_list = array();
                foreach ($obsada as $osoba) {
                    if (!empty($osoba['imie_nazwisko'])) {
                        $rola = !empty($osoba['rola']) ? ' - ' . esc_html($osoba['rola']) : '';
                        $obsada_list[] = esc_html($osoba['imie_nazwisko']) . $rola;
                    }
                }
                
                echo implode(', ', $obsada_list);
            }
        }
        
        // Zdefiniuj klasę dla pola Reżyseria
        class Toneka_Rezyserzy_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-rezyserzy';
            }
            
            public function get_title() {
                return esc_html__('Reżyseria', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $post_id = $product->get_id();
                
                // Napraw format danych reżyserów przed wyświetleniem
                $rezyserzy = toneka_fix_product_meta_format($post_id);
                
                if (empty($rezyserzy)) {
                    return;
                }
                
                // Debug - wyświetl typ danych i zawartość
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    echo '<!-- DEBUG: Typ: ' . gettype($rezyserzy) . ' -->';
                    echo '<!-- DEBUG: Zawartość: ' . print_r($rezyserzy, true) . ' -->';
                }
                
                $rezyserzy_list = array();
                if (is_array($rezyserzy)) {
                    foreach ($rezyserzy as $rezyser) {
                        if (is_array($rezyser) && !empty($rezyser['imie_nazwisko'])) {
                            $rezyserzy_list[] = esc_html($rezyser['imie_nazwisko']);
                        } elseif (is_string($rezyser) && !empty($rezyser)) {
                            $rezyserzy_list[] = esc_html($rezyser);
                        }
                    }
                } elseif (is_string($rezyserzy) && !empty($rezyserzy)) {
                    $rezyserzy_list[] = esc_html($rezyserzy);
                }
                
                if (empty($rezyserzy_list)) {
                    return;
                }
                
                echo implode(', ', $rezyserzy_list);
            }
        }
        
        // Zdefiniuj klasę dla pola Muzyka
        class Toneka_Muzycy_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-muzycy';
            }
            
            public function get_title() {
                return esc_html__('Muzyka', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $muzycy = get_post_meta($product->get_id(), '_muzycy', true);
                if (empty($muzycy)) {
                    return;
                }
                
                $muzycy_list = array();
                foreach ($muzycy as $muzyk) {
                    if (!empty($muzyk['imie_nazwisko'])) {
                        $muzycy_list[] = esc_html($muzyk['imie_nazwisko']);
                    }
                }
                
                echo implode(', ', $muzycy_list);
            }
        }
        
        // Zdefiniuj klasę dla pola Tłumacze
        class Toneka_Tlumacze_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-tlumacze';
            }
            
            public function get_title() {
                return esc_html__('Tłumaczenie', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $tlumacze = get_post_meta($product->get_id(), '_tlumacze', true);
                if (empty($tlumacze)) {
                    return;
                }
                
                $tlumacze_list = array();
                foreach ($tlumacze as $tlumacz) {
                    if (!empty($tlumacz['imie_nazwisko'])) {
                        $tlumacze_list[] = esc_html($tlumacz['imie_nazwisko']);
                    }
                }
                
                echo implode(', ', $tlumacze_list);
            }
        }
        
        // Zdefiniuj klasę dla pola Adaptacja
        class Toneka_Adaptacja_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-adaptacja';
            }
            
            public function get_title() {
                return esc_html__('Adaptacja tekstu', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $adaptatorzy = get_post_meta($product->get_id(), '_adaptatorzy', true);
                if (empty($adaptatorzy)) {
                    return;
                }
                
                $adaptatorzy_list = array();
                foreach ($adaptatorzy as $adaptator) {
                    if (!empty($adaptator['imie_nazwisko'])) {
                        $adaptatorzy_list[] = esc_html($adaptator['imie_nazwisko']);
                    }
                }
                
                echo implode(', ', $adaptatorzy_list);
            }
        }
        
        // Zdefiniuj klasę dla pola Wydawca
        class Toneka_Wydawca_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-wydawca';
            }
            
            public function get_title() {
                return esc_html__('Wydawca', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $wydawcy = get_post_meta($product->get_id(), '_wydawcy', true);
                if (empty($wydawcy)) {
                    return;
                }
                
                $wydawcy_list = array();
                foreach ($wydawcy as $wydawca) {
                    if (!empty($wydawca['nazwa'])) {
                        $wydawcy_list[] = esc_html($wydawca['nazwa']);
                    }
                }
                
                echo implode(', ', $wydawcy_list);
            }
        }
        
        // Zdefiniuj klasę dla pola Grafika
        class Toneka_Grafika_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-grafika';
            }
            
            public function get_title() {
                return esc_html__('Grafika', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $grafika = get_post_meta($product->get_id(), '_grafika', true);
                if (empty($grafika)) {
                    return;
                }
                
                $grafika_list = array();
                foreach ($grafika as $grafika_item) {
                    if (!empty($grafika_item['imie_nazwisko'])) {
                        $grafika_list[] = esc_html($grafika_item['imie_nazwisko']);
                    }
                }
                
                echo implode(', ', $grafika_list);
            }
        }
        
        // Zdefiniuj klasę dla kategorii produktu WooCommerce
        class Toneka_Product_Category_Tag extends \ElementorPro\Modules\DynamicTags\Tags\Base\Tag {
            public function get_name() {
                return 'toneka-product-category';
            }
            
            public function get_title() {
                return esc_html__('Kategoria Produktu', 'boldnote-child');
            }
            
            public function get_group() {
                return 'toneka-tags';
            }
            
            public function get_categories() {
                return ['text'];
            }
            
            protected function register_controls() {
                $this->add_control(
                    'separator',
                    [
                        'label' => esc_html__('Separator', 'boldnote-child'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => ', ',
                        'description' => esc_html__('Separator między kategoriami (jeśli produkt ma więcej niż jedną)', 'boldnote-child'),
                    ]
                );
                
                $this->add_control(
                    'show_hierarchy',
                    [
                        'label' => esc_html__('Pokaż hierarchię', 'boldnote-child'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__('Tak', 'boldnote-child'),
                        'label_off' => esc_html__('Nie', 'boldnote-child'),
                        'return_value' => 'yes',
                        'default' => 'no',
                        'description' => esc_html__('Pokaż pełną ścieżkę kategorii (np. "Główna > Podkategoria")', 'boldnote-child'),
                    ]
                );
                
                $this->add_control(
                    'link_to_category',
                    [
                        'label' => esc_html__('Linkuj do kategorii', 'boldnote-child'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__('Tak', 'boldnote-child'),
                        'label_off' => esc_html__('Nie', 'boldnote-child'),
                        'return_value' => 'yes',
                        'default' => 'no',
                    ]
                );
            }
            
            public function render() {
                global $product;
                
                if (!is_a($product, 'WC_Product')) {
                    $product = wc_get_product(get_the_ID());
                    if (!$product) {
                        return '';
                    }
                }
                
                $settings = $this->get_settings_for_display();
                $separator = $settings['separator'] ?: ', ';
                $show_hierarchy = $settings['show_hierarchy'] === 'yes';
                $link_to_category = $settings['link_to_category'] === 'yes';
                
                // Pobierz kategorie produktu
                $terms = wp_get_post_terms($product->get_id(), 'product_cat');
                
                if (is_wp_error($terms) || empty($terms)) {
                    return;
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
                
                echo implode($separator, $category_names);
            }
        }
        
        // Zarejestruj wszystkie tagi dynamiczne
        $dynamic_tags_manager->register(new Toneka_Rok_Produkcji_Tag());
        $dynamic_tags_manager->register(new Toneka_Czas_Trwania_Tag());
        $dynamic_tags_manager->register(new Toneka_Autorzy_Tag());
        $dynamic_tags_manager->register(new Toneka_Obsada_Tag());
        $dynamic_tags_manager->register(new Toneka_Rezyserzy_Tag());
        $dynamic_tags_manager->register(new Toneka_Muzycy_Tag());
        $dynamic_tags_manager->register(new Toneka_Tlumacze_Tag());
        $dynamic_tags_manager->register(new Toneka_Adaptacja_Tag());
        $dynamic_tags_manager->register(new Toneka_Wydawca_Tag());
        $dynamic_tags_manager->register(new Toneka_Grafika_Tag());
        $dynamic_tags_manager->register(new Toneka_Product_Category_Tag());
    }
}

// Dodaj akcję po załadowaniu Elementora
function toneka_init_dynamic_tags() {
    if (defined('ELEMENTOR_PRO_VERSION')) {
        add_action('elementor/dynamic_tags/register', 'toneka_register_dynamic_tags_module');
    }
}
add_action('elementor/init', 'toneka_init_dynamic_tags');

/**
 * Dodaje niestandardowe widgety do Elementora dla edycji pól produktu
 */
function toneka_register_elementor_widgets() {
    // Sprawdź, czy Elementor jest aktywny
    if (!did_action('elementor/loaded')) {
        return;
    }
    
    // Zarejestruj niestandardowy widget dla edycji pól produktu
    class Toneka_Product_Fields_Widget extends \Elementor\Widget_Base {
        public function get_name() {
            return 'toneka_product_fields_editor';
        }
        
        public function get_title() {
            return esc_html__('Edytor Pól Produktu', 'boldnote-child');
        }
        
        public function get_icon() {
            return 'eicon-editor-list-ul';
        }
        
        public function get_categories() {
            return ['woocommerce-elements'];
        }
        
        protected function register_controls() {
            global $post;
            
            // Pobierz ID produktu
            $product_id = get_the_ID();
            if (!$product_id) {
                $product_id = isset($post->ID) ? $post->ID : 0;
            }
            
            // Pobierz dane produktu
            $rok_produkcji = get_post_meta($product_id, '_rok_produkcji', true);
            $autor_title = get_post_meta($product_id, '_autor_title', true);
            $obsada_title = get_post_meta($product_id, '_obsada_title', true);
            $rezyser_title = get_post_meta($product_id, '_rezyser_title', true);
            $muzyka_title = get_post_meta($product_id, '_muzyka_title', true);
            
            // Sekcja główna
            $this->start_controls_section(
                'section_product_fields',
                [
                    'label' => esc_html__('Edycja Pól Produktu', 'boldnote-child'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
            
            // Rok produkcji
            $this->add_control(
                'rok_produkcji',
                [
                    'label' => esc_html__('Rok produkcji', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1900,
                    'max' => date('Y') + 10, // Bieżący rok + 10 lat
                    'step' => 1,
                    'default' => $rok_produkcji ? $rok_produkcji : date('Y'),
                    'description' => esc_html__('Wprowadź rok produkcji', 'boldnote-child'),
                ]
            );
            
            // Tytuły sekcji
            $this->add_control(
                'autor_title',
                [
                    'label' => esc_html__('Tytuł sekcji Autorzy', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => $autor_title ? $autor_title : esc_html__('Autorzy', 'boldnote-child'),
                    'placeholder' => esc_html__('Autorzy', 'boldnote-child'),
                ]
            );
            
            $this->add_control(
                'obsada_title',
                [
                    'label' => esc_html__('Tytuł sekcji Obsada', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => $obsada_title ? $obsada_title : esc_html__('Obsada', 'boldnote-child'),
                    'placeholder' => esc_html__('Obsada', 'boldnote-child'),
                ]
            );
            
            $this->add_control(
                'rezyser_title',
                [
                    'label' => esc_html__('Tytuł sekcji Reżyseria', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => $rezyser_title ? $rezyser_title : esc_html__('Reżyseria', 'boldnote-child'),
                    'placeholder' => esc_html__('Reżyseria', 'boldnote-child'),
                ]
            );
            
            $this->add_control(
                'muzyka_title',
                [
                    'label' => esc_html__('Tytuł sekcji Muzyka', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => $muzyka_title ? $muzyka_title : esc_html__('Muzyka', 'boldnote-child'),
                    'placeholder' => esc_html__('Muzyka', 'boldnote-child'),
                ]
            );
            
            $this->add_control(
                'tlumacz_title',
                [
                    'label' => esc_html__('Tytuł sekcji Tłumaczenie', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => $tlumacz_title ? $tlumacz_title : esc_html__('Tłumaczenie', 'boldnote-child'),
                    'placeholder' => esc_html__('Tłumaczenie', 'boldnote-child'),
                ]
            );
            
            $this->add_control(
                'adaptacja_title',
                [
                    'label' => esc_html__('Tytuł sekcji Adaptacja', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => $adaptacja_title ? $adaptacja_title : esc_html__('Adaptacja tekstu', 'boldnote-child'),
                    'placeholder' => esc_html__('Adaptacja tekstu', 'boldnote-child'),
                ]
            );
            
            $this->add_control(
                'wydawca_title',
                [
                    'label' => esc_html__('Tytuł sekcji Wydawca', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => $wydawca_title ? $wydawca_title : esc_html__('Wydawca', 'boldnote-child'),
                    'placeholder' => esc_html__('Wydawca', 'boldnote-child'),
                ]
            );
            
            // Informacja o edycji zaawansowanych pól
            $this->add_control(
                'info_content',
                [
                    'label' => esc_html__('Informacja', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => esc_html__('Aby edytować listę autorów, obsady, reżyserów i muzyków, przejdź do panelu edycji produktu.', 'boldnote-child'),
                    'content_classes' => 'elementor-descriptor',
                ]
            );
            
            $this->add_control(
                'save_button',
                [
                    'label' => esc_html__('Zapisz zmiany', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::BUTTON,
                    'text' => esc_html__('Zapisz', 'boldnote-child'),
                    'event' => 'toneka:save_product_fields',
                ]
            );
            
            $this->end_controls_section();
            
            // Sekcja stylów
            $this->start_controls_section(
                'section_style',
                [
                    'label' => esc_html__('Styles Display', 'boldnote-child'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            $this->add_control(
                'display_labels',
                [
                    'label' => esc_html__('Show Field Labels', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'boldnote-child'),
                    'label_off' => esc_html__('No', 'boldnote-child'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
            
            $this->add_control(
                'label_color',
                [
                    'label' => esc_html__('Label Color', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .toneka-field-label' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'display_labels' => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'value_color',
                [
                    'label' => esc_html__('Value Color', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .toneka-field-value' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'label_typography',
                    'label' => esc_html__('Label Typography', 'boldnote-child'),
                    'selector' => '{{WRAPPER}} .toneka-field-label',
                    'condition' => [
                        'display_labels' => 'yes',
                    ],
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'value_typography',
                    'label' => esc_html__('Value Typography', 'boldnote-child'),
                    'selector' => '{{WRAPPER}} .toneka-field-value',
                ]
            );
            
            $this->end_controls_section();
        }
        
        protected function render() {
            global $product;
            
            if (!is_a($product, 'WC_Product')) {
                $product = wc_get_product(get_the_ID());
                if (!$product) {
                    echo esc_html__('Podgląd niedostępny. Otwórz edytor na stronie produktu.', 'boldnote-child');
                    return;
                }
            }
            
            $settings = $this->get_settings_for_display();
            $post_id = $product->get_id();
            
            // Zapisz dane, jeśli widget jest renderowany w trybie edycji Elementora
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                // Zapisz wartości, jeśli są ustawione
                if (isset($settings['rok_produkcji'])) {
                    update_post_meta($post_id, '_rok_produkcji', sanitize_text_field($settings['rok_produkcji']));
                }
                
                if (isset($settings['autor_title'])) {
                    update_post_meta($post_id, '_autor_title', sanitize_text_field($settings['autor_title']));
                }
                
                if (isset($settings['obsada_title'])) {
                    update_post_meta($post_id, '_obsada_title', sanitize_text_field($settings['obsada_title']));
                }
                
                if (isset($settings['rezyser_title'])) {
                    update_post_meta($post_id, '_rezyser_title', sanitize_text_field($settings['rezyser_title']));
                }
                
                if (isset($settings['muzyka_title'])) {
                    update_post_meta($post_id, '_muzyka_title', sanitize_text_field($settings['muzyka_title']));
                }
                
                if (isset($settings['tlumacz_title'])) {
                    update_post_meta($post_id, '_tlumacz_title', sanitize_text_field($settings['tlumacz_title']));
                }
                
                if (isset($settings['adaptacja_title'])) {
                    update_post_meta($post_id, '_adaptacja_title', sanitize_text_field($settings['adaptacja_title']));
                }
            }
            
            // Pobierz aktualne dane
            $rok_produkcji = get_post_meta($post_id, '_rok_produkcji', true);
            $autor_title = get_post_meta($post_id, '_autor_title', true) ?: esc_html__('Autorzy', 'boldnote-child');
            $autors = get_post_meta($post_id, '_autors', true);
            $obsada_title = get_post_meta($post_id, '_obsada_title', true) ?: esc_html__('Obsada', 'boldnote-child');
            $obsada = get_post_meta($post_id, '_obsada', true);
            $rezyser_title = get_post_meta($post_id, '_rezyser_title', true) ?: esc_html__('Reżyseria', 'boldnote-child');
            $rezyserzy = get_post_meta($post_id, '_rezyserzy', true);
            $muzyka_title = get_post_meta($post_id, '_muzyka_title', true) ?: esc_html__('Muzyka', 'boldnote-child');
            $muzycy = get_post_meta($post_id, '_muzycy', true);
            $tlumacz_title = get_post_meta($post_id, '_tlumacz_title', true) ?: esc_html__('Tłumaczenie', 'boldnote-child');
            $tlumacze = get_post_meta($post_id, '_tlumacze', true);
            $adaptacja_title = get_post_meta($post_id, '_adaptacja_title', true) ?: esc_html__('Adaptacja tekstu', 'boldnote-child');
            $adaptatorzy = get_post_meta($post_id, '_adaptatorzy', true);
            
            // Wyświetl podgląd
            echo '<div class="toneka-product-fields-editor">';
            
            // Rok produkcji
            if (!empty($rok_produkcji)) {
                echo '<div class="toneka-field toneka-rok-produkcji">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html__('Rok produkcji: ', 'boldnote-child') . '</span>';
                }
                echo '<span class="toneka-field-value">' . esc_html($rok_produkcji) . '</span>';
                echo '</div>';
            }
            
            // Czas trwania
            if (!empty($czas_trwania)) {
                echo '<div class="toneka-field toneka-czas-trwania">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html__('Czas trwania: ', 'boldnote-child') . '</span>';
                }
                echo '<span class="toneka-field-value">' . esc_html($czas_trwania) . ' ' . esc_html__('min', 'boldnote-child') . '</span>';
                echo '</div>';
            }
            
            // Autorzy
            if (!empty($autors)) {
                echo '<div class="toneka-field toneka-autorzy">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html($autor_title) . ': </span>';
                }
                
                $authors_list = array();
                foreach ($autors as $autor) {
                    if (!empty($autor['imie_nazwisko'])) {
                        $authors_list[] = esc_html($autor['imie_nazwisko']);
                    }
                }
                
                echo '<span class="toneka-field-value">' . implode(', ', $authors_list) . '</span>';
                echo '</div>';
            }
            
            // Obsada
            if (!empty($obsada)) {
                echo '<div class="toneka-field toneka-obsada">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html($obsada_title) . ': </span>';
                }
                
                echo '<div class="toneka-field-value">';
                foreach ($obsada as $osoba) {
                    if (!empty($osoba['imie_nazwisko'])) {
                        $rola = !empty($osoba['rola']) ? ' - ' . esc_html($osoba['rola']) : '';
                        echo '<div>' . esc_html($osoba['imie_nazwisko']) . $rola . '</div>';
                    }
                }
                echo '</div>';
                echo '</div>';
            }
            
            // Reżyseria
            if (!empty($rezyserzy)) {
                echo '<div class="toneka-field toneka-rezyserzy">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html($rezyser_title) . ': </span>';
                }
                
                // Napraw format danych reżyserów
                $rezyserzy = toneka_fix_product_meta_format($post_id);
                
                $rezyserzy_list = array();
                if (is_array($rezyserzy)) {
                    foreach ($rezyserzy as $rezyser) {
                        if (is_array($rezyser) && !empty($rezyser['imie_nazwisko'])) {
                            $rezyserzy_list[] = esc_html($rezyser['imie_nazwisko']);
                        } elseif (is_string($rezyser) && !empty($rezyser)) {
                            $rezyserzy_list[] = esc_html($rezyser);
                        }
                    }
                } elseif (is_string($rezyserzy) && !empty($rezyserzy)) {
                    $rezyserzy_list[] = esc_html($rezyserzy);
                }
                
                if (!empty($rezyserzy_list)) {
                    echo '<span class="toneka-field-value">' . implode(', ', $rezyserzy_list) . '</span>';
                } else {
                    echo '<span class="toneka-field-value">' . esc_html__('Brak reżyserów', 'boldnote-child') . '</span>';
                }
                
                echo '</div>';
            }
            
            // Muzyka
            if (!empty($muzycy)) {
                echo '<div class="toneka-field toneka-muzycy">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html($muzyka_title) . ': </span>';
                }
                
                $muzycy_list = array();
                foreach ($muzycy as $muzyk) {
                    if (!empty($muzyk['imie_nazwisko'])) {
                        $muzycy_list[] = esc_html($muzyk['imie_nazwisko']);
                    }
                }
                
                echo '<span class="toneka-field-value">' . implode(', ', $muzycy_list) . '</span>';
                echo '</div>';
            }
            
            // Tłumaczenie
            if (!empty($tlumacze)) {
                echo '<div class="toneka-field toneka-tlumacze">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html($tlumacz_title) . ': </span>';
                }
                
                $tlumacze_list = array();
                foreach ($tlumacze as $tlumacz) {
                    if (!empty($tlumacz['imie_nazwisko'])) {
                        $tlumacze_list[] = esc_html($tlumacz['imie_nazwisko']);
                    }
                }
                
                echo '<span class="toneka-field-value">' . implode(', ', $tlumacze_list) . '</span>';
                echo '</div>';
            }
            
            // Adaptacja tekstu
            if (!empty($adaptatorzy)) {
                echo '<div class="toneka-field toneka-adaptacja">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html($adaptacja_title) . ': </span>';
                }
                
                $adaptatorzy_list = array();
                foreach ($adaptatorzy as $adaptator) {
                    if (!empty($adaptator['imie_nazwisko'])) {
                        $adaptatorzy_list[] = esc_html($adaptator['imie_nazwisko']);
                    }
                }
                
                echo '<span class="toneka-field-value">' . implode(', ', $adaptatorzy_list) . '</span>';
                echo '</div>';
            }
            
            // Wydawca
            if (!empty($wydawcy)) {
                echo '<div class="toneka-field toneka-wydawca">';
                if ($settings['display_labels'] === 'yes') {
                    echo '<span class="toneka-field-label">' . esc_html($wydawca_title) . ': </span>';
                }
                
                $wydawcy_list = array();
                foreach ($wydawcy as $wydawca) {
                    if (!empty($wydawca['nazwa'])) {
                        $wydawcy_list[] = esc_html($wydawca['nazwa']);
                    }
                }
                
                echo '<span class="toneka-field-value">' . implode(', ', $wydawcy_list) . '</span>';
                echo '</div>';
            }
            
            echo '</div>';
            
            // Dodaj JavaScript do obsługi zapisywania danych
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<script>
                    jQuery(document).on("toneka:save_product_fields", function() {
                        elementor.reloadPreview();
                        elementor.saver.update();
                    });
                </script>';
            }
        }
    }
    
    // Zarejestruj widget
    \Elementor\Plugin::instance()->widgets_manager->register(new Toneka_Product_Fields_Widget());

    // NOWY WIDGET: Creator Products Grid
    class Toneka_Creator_Products_Widget extends \Elementor\Widget_Base {
        public function get_name() { return 'toneka_creator_products'; }
        public function get_title() { return esc_html__('Creator Products Grid', 'boldnote-child'); }
        public function get_icon() { return 'eicon-posts-grid'; }
        public function get_categories() { return ['general']; }
        public function get_keywords() { return ['creator', 'products', 'grid', 'toneka']; }
        
        protected function register_controls() {
            $this->start_controls_section('content_section', [
                'label' => esc_html__('Content', 'boldnote-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]);
            
            $this->add_control('creator_id', [
                'label' => esc_html__('Creator ID', 'boldnote-child'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => get_the_ID(),
                'description' => esc_html__('Pozostaw puste, aby użyć bieżącej strony.', 'boldnote-child'),
            ]);
            
            $this->add_control('posts_per_page', [
                'label' => esc_html__('Liczba produktów', 'boldnote-child'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 48,
                'default' => 6,
            ]);
            
            $this->add_responsive_control('columns', [
                'label' => esc_html__('Kolumny', 'boldnote-child'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .toneka-creator-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]);
            
            $this->add_control('show_image', [
                'label' => esc_html__('Pokaż obraz', 'boldnote-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]);
            $this->add_control('show_title', [
                'label' => esc_html__('Pokaż tytuł', 'boldnote-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]);
            $this->add_control('show_price', [
                'label' => esc_html__('Pokaż cenę', 'boldnote-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]);
            
            $this->end_controls_section();
        }
        
        protected function render() {
            $settings = $this->get_settings_for_display();
            $creator_id = !empty($settings['creator_id']) ? (int)$settings['creator_id'] : get_the_ID();
            if (!$creator_id) { echo '<p>' . esc_html__('Brak twórcy.', 'boldnote-child') . '</p>'; return; }
            $creator_name = get_the_title($creator_id);
            if (!$creator_name) { echo '<p>' . esc_html__('Nie znaleziono twórcy.', 'boldnote-child') . '</p>'; return; }
            $limit = !empty($settings['posts_per_page']) ? (int)$settings['posts_per_page'] : 6;
            if (!function_exists('toneka_get_creator_products')) { echo '<p>' . esc_html__('Brak funkcji pobierania produktów twórcy.', 'boldnote-child') . '</p>'; return; }
            $products = toneka_get_creator_products($creator_name, $limit);
            if (empty($products)) { echo '<p>' . esc_html__('Brak produktów dla tego twórcy.', 'boldnote-child') . '</p>'; return; }
            
            echo '<div class="toneka-creator-grid" style="display:grid; gap:0px;">';
            foreach ($products as $p) {
                $pid = $p->ID; $wc = wc_get_product($pid); if (!$wc) { continue; }
                echo '<div class="toneka-creator-item" style="background:rgba(255,255,255,0.08); border-radius:0px; padding:0px;">';
                if ($settings['show_image'] === 'yes') {
                    $thumb = get_the_post_thumbnail_url($pid, 'medium');
                    if ($thumb) {
                        echo '<a href="' . esc_url(get_permalink($pid)) . '" class="toneka-creator-thumb" style="display:block; overflow:hidden;">';
                        echo '<img src="' . esc_url($thumb) . '" alt="' . esc_attr(get_the_title($pid)) . '" style="width:100%; height:auto; display:block;">';
                        echo '</a>';
                    }
                }
                if ($settings['show_title'] === 'yes') {
                    echo '<h4 class="toneka-creator-title" style="margin:10px 0 0; font-size:1rem;">';
                    echo '<a href="' . esc_url(get_permalink($pid)) . '" style="color:#fff; text-decoration:none;">' . esc_html(get_the_title($pid)) . '</a>';
                    echo '</h4>';
                }
                if ($settings['show_price'] === 'yes') {
                    echo '<div class="toneka-creator-price" style="margin-top:8px; color:#fff;">' . $wc->get_price_html() . '</div>';
                }
                echo '</div>';
            }
            echo '</div>';
        }
    }
    
    // ... existing code ...
    // Zarejestruj widget
    \Elementor\Plugin::instance()->widgets_manager->register(new Toneka_Product_Fields_Widget());
    \Elementor\Plugin::instance()->widgets_manager->register(new Toneka_Creator_Products_Widget());
}
add_action('elementor/widgets/register', 'toneka_register_elementor_widgets');

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

/**
 * Dodaje shortcode do debugowania
 */
add_shortcode('toneka_debug', function() {
    return toneka_debug_product_metadata();
});

/**
 * Naprawia i standaryzuje format danych
 */
function toneka_fix_product_meta_format($product_id) {
    // Sprawdź i napraw format danych reżyserów
    $rezyserzy = get_post_meta($product_id, '_rezyserzy', true);
    
    // Jeśli dane są w nieprawidłowym formacie, napraw je
    if (!empty($rezyserzy) && !is_array($rezyserzy)) {
        // Próba dekodowania JSON
        $decoded = json_decode($rezyserzy, true);
        if (is_array($decoded)) {
            update_post_meta($product_id, '_rezyserzy', $decoded);
            $rezyserzy = $decoded;
        } else {
            // Utwórz prawidłową strukturę
            $fixed_rezyserzy = array(
                array(
                    'imie_nazwisko' => $rezyserzy
                )
            );
            update_post_meta($product_id, '_rezyserzy', $fixed_rezyserzy);
            $rezyserzy = $fixed_rezyserzy;
        }
    }
    
    // Jeśli dane są puste lub nadal nie są tablicą, utwórz pustą strukturę
    if (empty($rezyserzy) || !is_array($rezyserzy)) {
        $rezyserzy = array();
        update_post_meta($product_id, '_rezyserzy', $rezyserzy);
    }
    
    return $rezyserzy;
}

/**
 * Modyfikuje funkcję render dla tagu reżyserów w funkcji Toneka_Rezyserzy_Tag::render()
 */
add_action('wp_footer', function() {
    if (is_singular('product')) {
        ?>
        <script>
        // Naprawia problemy z wyświetlaniem reżyserów w tagach dynamicznych
        document.addEventListener('DOMContentLoaded', function() {
            let rezyserElements = document.querySelectorAll('.toneka-rezyserzy, .elementor-widget-text-editor');
            rezyserElements.forEach(function(el) {
                if (el.innerHTML.includes('rezyser') && el.innerHTML.trim() === '') {
                    console.log('Znaleziono pusty element reżysera, próba ponownego załadowania');
                    // Możemy dodać kod naprawiający tutaj
                }
            });
        });
        </script>
        <?php
    }
});

/**
 * Automatycznie naprawia format danych przy zapisie produktu
 */
add_action('save_post_product', 'toneka_fix_product_data_on_save', 10, 3);
function toneka_fix_product_data_on_save($post_id, $post, $update) {
    // Nie wykonuj dla autosave lub revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (wp_is_post_revision($post_id)) {
        return;
    }
    
    // Sprawdź uprawnienia
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Napraw format danych reżyserów
    toneka_fix_product_meta_format($post_id);
    
    // Dodaj hook do debugowania, jeśli WP_DEBUG jest włączony
    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('admin_notices', function() use ($post_id) {
            $screen = get_current_screen();
            if ($screen->id === 'product' && $screen->base === 'post' && isset($_GET['post']) && $_GET['post'] == $post_id) {
                $rezyserzy = get_post_meta($post_id, '_rezyserzy', true);
                $debugInfo = "Typ danych reżyserów: " . gettype($rezyserzy);
                if (is_array($rezyserzy)) {
                    $debugInfo .= ", Ilość elementów: " . count($rezyserzy);
                }
                
                echo '<div class="notice notice-info"><p>' . esc_html__('Debug: ', 'boldnote-child') . esc_html($debugInfo) . '</p></div>';
            }
        });
    }
}

add_filter('wc_add_to_cart_message_html', function($message, $products, $show_qty) {
    // Zwraca pusty string tylko dla notice po dodaniu do koszyka
    return '';
}, 10, 3);

/**
 * Shortcode [year] wyświetlający bieżący rok
 */
add_shortcode('year', function() {
    return date('Y');
});

/**
 * Rejestruje meta pole dla próbek audio/wideo
 */
function toneka_register_product_samples_meta() {
	// Próbki audio/wideo
	register_post_meta('product', '_product_samples', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'auth_callback' => function() { return current_user_can('edit_posts'); }
	));
}
add_action('init', 'toneka_register_product_samples_meta');

/**
 * Dodaje zakładkę Próbki audio/wideo do panelu produktu WooCommerce
 */
function toneka_add_product_samples_tab($tabs) {
	$tabs['samples'] = array(
		'label'    => __('Próbki audio/wideo', 'boldnote-child'),
		'target'   => 'product_samples_data',
		'class'    => array(),
		'priority' => 65, // umieszczamy po zakładce z plikami do pobrania (60)
	);
	return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'toneka_add_product_samples_tab');

/**
 * Wyświetla panel z próbkami audio/wideo
 */
function toneka_product_samples_data_panel() {
	global $post;
	?>
	<div id="product_samples_data" class="panel woocommerce_options_panel">
		<div class="options_group">
			<div class="form-field downloadable_files">
				<label><?php _e('Próbki audio/wideo', 'boldnote-child'); ?></label>
				<table class="widefat">
					<thead>
						<tr>
							<th class="sort">&nbsp;</th>
							<th><?php _e('Nazwa', 'boldnote-child'); ?> <?php echo wc_help_tip(__('Nazwa pliku, która będzie wyświetlana w playerze.', 'boldnote-child')); ?></th>
							<th colspan="2"><?php _e('Adres URL pliku', 'boldnote-child'); ?> <?php echo wc_help_tip(__('Może to być plik audio (mp3, wav, ogg) lub wideo (mp4, webm).', 'boldnote-child')); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$product_samples = get_post_meta($post->ID, '_product_samples', true);
						
						if (!empty($product_samples) && is_array($product_samples)) {
							foreach ($product_samples as $key => $file) {
								include 'templates/html-product-sample.php';
							}
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="5">
								<a href="#" class="button insert" data-row="<?php
									$file = array(
										'file' => '',
										'name' => '',
									);
									ob_start();
									include 'templates/html-product-sample.php';
									echo esc_attr(ob_get_clean());
								?>"><?php _e('Dodaj plik', 'boldnote-child'); ?></a>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	<?php
}
add_action('woocommerce_product_data_panels', 'toneka_product_samples_data_panel');

/**
 * Tworzy katalog dla szablonów
 */
function toneka_create_templates_directory() {
	$template_dir = get_stylesheet_directory() . '/templates';
	if (!file_exists($template_dir)) {
		mkdir($template_dir, 0755, true);
	}
}
add_action('init', 'toneka_create_templates_directory');

/**
 * Zapisuje szablon pliku próbki
 */
function toneka_create_sample_template() {
	$template_file = get_stylesheet_directory() . '/templates/html-product-sample.php';
	
	if (!file_exists($template_file)) {
		$template_content = '<?php
if (!defined("ABSPATH")) {
    exit;
}
?>
<tr>
    <td class="sort"></td>
    <td>
        <input type="text" class="input_text" placeholder="<?php esc_attr_e("Nazwa pliku", "boldnote-child"); ?>" name="_product_sample_names[]" value="<?php echo isset($file["name"]) ? esc_attr($file["name"]) : ""; ?>" />
    </td>
    <td>
        <input type="text" class="input_text" placeholder="<?php esc_attr_e("http://", "boldnote-child"); ?>" name="_product_sample_files[]" value="<?php echo isset($file["file"]) ? esc_attr($file["file"]) : ""; ?>" />
    </td>
    <td class="file_url_choose">
        <button class="button upload_sample_file_button" data-choose="<?php esc_attr_e("Wybierz plik", "boldnote-child"); ?>" data-update="<?php esc_attr_e("Wstaw plik", "boldnote-child"); ?>"><?php echo esc_html__("Wybierz plik", "boldnote-child"); ?></button>
    </td>
    <td>
        <button class="button delete"><?php _e("Usuń", "boldnote-child"); ?></button>
    </td>
</tr>
';
		file_put_contents($template_file, $template_content);
	}
}
add_action('init', 'toneka_create_sample_template');

/**
 * Zapisuje dane próbek
 */
function toneka_save_product_samples($post_id) {
	$product_samples = array();
	
	if (isset($_POST['_product_sample_files']) && isset($_POST['_product_sample_names'])) {
		$sample_names = $_POST['_product_sample_names'];
		$sample_files = $_POST['_product_sample_files'];
		
		$count = min(count($sample_files), count($sample_names));
		
		for ($i = 0; $i < $count; $i++) {
			if (empty($sample_files[$i])) {
				continue;
			}
			
			$file_name = wc_clean($sample_names[$i]);
			$file_url  = wc_clean($sample_files[$i]);
			
			$product_samples[] = array(
				'name' => $file_name,
				'file' => $file_url,
			);
		}
	}
	
	update_post_meta($post_id, '_product_samples', $product_samples);
}
add_action('woocommerce_process_product_meta', 'toneka_save_product_samples');

/**
 * Dodaje skrypty i style do panelu administracyjnego
 */
function toneka_admin_scripts() {
	global $post, $pagenow, $typenow;
	
	// Sprawdź, czy jesteśmy na stronie edycji produktu
	if (($pagenow === 'post.php' || $pagenow === 'post-new.php') && $typenow === 'product') {
		wp_enqueue_media();
		
		// Dodaj skrypt do obsługi pola próbek
		wp_enqueue_script('toneka-product-samples', get_stylesheet_directory_uri() . '/js/product-samples.js', array('jquery'), '1.0.0', true);
		
		// Jeśli plik JS nie istnieje, utwórz go
		$js_dir = get_stylesheet_directory() . '/js';
		if (!file_exists($js_dir)) {
			mkdir($js_dir, 0755, true);
		}
		
		$js_file = $js_dir . '/product-samples.js';
		if (!file_exists($js_file)) {
			$js_content = '
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
            $button.closest("tr").find("input[name=\'_product_sample_files[]\']").val(attachment.url);
            
            var $nameField = $button.closest("tr").find("input[name=\'_product_sample_names[]\']");
            if ($nameField.val() === "") {
                $nameField.val(attachment.title);
            }
        });
        
        // Otwórz media frame
        file_frame.open();
    });
});
';
			file_put_contents($js_file, $js_content);
		}
	}
}
add_action('admin_enqueue_scripts', 'toneka_admin_scripts');

/**
 * Widget Elementora do wyświetlania playera próbek audio/wideo
 */
function toneka_register_audio_video_player_widget() {
	if (!did_action('elementor/loaded')) {
		return;
	}
	
	class Toneka_Audio_Video_Player_Widget extends \Elementor\Widget_Base {
		public function get_name() {
			return 'toneka_audio_video_player';
		}
		
		public function get_title() {
			return esc_html__('Próbki Audio/Wideo', 'boldnote-child');
		}
		
		public function get_icon() {
			return 'eicon-play';
		}
		
		public function get_categories() {
			return ['general', 'woocommerce-elements'];
		}
		
		protected function register_controls() {
			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__('Ustawienia', 'boldnote-child'),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_control(
				'title',
				[
					'label' => esc_html__('Tytuł sekcji', 'boldnote-child'),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__('Posłuchaj próbki', 'boldnote-child'),
				]
			);
			
			$this->add_control(
				'player_type',
				[
					'label' => esc_html__('Typ playera', 'boldnote-child'),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'auto',
					'options' => [
						'auto' => esc_html__('Automatyczny (audio/wideo)', 'boldnote-child'),
						'audio' => esc_html__('Tylko audio', 'boldnote-child'),
						'video' => esc_html__('Tylko wideo', 'boldnote-child'),
					],
				]
			);
			
			$this->add_control(
				'show_playlist',
				[
					'label' => esc_html__('Pokaż playlistę', 'boldnote-child'),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__('Tak', 'boldnote-child'),
					'label_off' => esc_html__('Nie', 'boldnote-child'),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);
			
			$this->end_controls_section();
			
			// Styl
			$this->start_controls_section(
				'style_section',
				[
					'label' => esc_html__('Style playera', 'boldnote-child'),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			
			$this->add_control(
				'player_color',
				[
					'label' => esc_html__('Kolor akcentów', 'boldnote-child'),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#007bff',
				]
			);
			
			$this->end_controls_section();
		}
		
		protected function render() {
			global $product;
			
			if (!$product && is_singular('product')) {
				$product = wc_get_product(get_the_ID());
			}
			
			if (!$product) {
				echo esc_html__('Ten widget jest przeznaczony do użycia na stronie produktu.', 'boldnote-child');
				return;
			}
			
			$settings = $this->get_settings_for_display();
			$samples = get_post_meta($product->get_id(), '_product_samples', true);
			
			if (empty($samples) || !is_array($samples)) {
				echo esc_html__('Brak próbek audio/wideo dla tego produktu.', 'boldnote-child');
				return;
			}
			
			// Ładujemy własny player HTML5 zamiast biblioteki
			
			// Dodaj styl dla playera - zgodny z projektem
			$player_color = $settings['player_color'];
			
			wp_add_inline_style('wp-mediaelement', '');
			
			// Przygotuj dane dla playera
			$current_index = 0;
			$has_video = false;
			
			foreach ($samples as $index => $sample) {
				$file_url = $sample['file'];
				$file_ext = pathinfo($file_url, PATHINFO_EXTENSION);
				
				if (in_array(strtolower($file_ext), ['mp4', 'webm', 'ogg'])) {
					$samples[$index]['type'] = 'video';
					$has_video = true;
				} else {
					$samples[$index]['type'] = 'audio';
				}
			}
			
			// Generuj unikalny ID dla playera
			$player_id = 'toneka-player-' . uniqid();
			
			// HTML playera
			?>
			<div class="toneka-player-container">
				<?php if (!empty($settings['title'])): ?>
					<div class="toneka-player-title"><?php echo esc_html($settings['title']); ?></div>
				<?php endif; ?>
				
				<div class="toneka-player-preview">
					<?php if ($has_video && $settings['player_type'] !== 'audio'): ?>
						<video id="<?php echo esc_attr($player_id); ?>-video" playsinline></video>
					<?php else: ?>
						<?php 
						$thumbnail = $product->get_image_id() ? wp_get_attachment_image_url($product->get_image_id(), 'large') : wc_placeholder_img_src('large');
						echo '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($product->get_name()) . '" />';
						?>
						<audio id="<?php echo esc_attr($player_id); ?>-audio" preload="metadata"></audio>
					<?php endif; ?>
				</div>
				
				<div class="toneka-player-controls">
					<div class="toneka-audio-player">
						<div class="toneka-audio-controls">
							<button class="toneka-play-button" aria-label="Play">
								<svg class="toneka-icon toneka-play-icon" viewBox="0 0 24 24">
									<path d="M8 5v14l11-7z"/>
								</svg>
								<svg class="toneka-icon toneka-pause-icon" viewBox="0 0 24 24" style="display: none;">
									<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
								</svg>
							</button>
							<div class="toneka-time">00:00</div>
							<div class="toneka-volume-container">
								<button class="toneka-volume-button" aria-label="Wycisz">
									<svg class="toneka-icon toneka-volume-icon" viewBox="0 0 24 24">
										<path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
									</svg>
									<svg class="toneka-icon toneka-mute-icon" viewBox="0 0 24 24" style="display: none;">
										<path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
									</svg>
								</button>
								<input type="range" class="toneka-volume-slider" min="0" max="1" step="0.1" value="1">
							</div>
						</div>
						<div class="toneka-progress-container">
							<div class="toneka-progress-bar"></div>
						</div>
					</div>
					
					<?php if ($settings['show_playlist'] === 'yes' && count($samples) > 1): ?>
						<div class="toneka-playlist">
							<div class="toneka-playlist-header">
								<div class="toneka-playlist-title toneka-current-title"></div>
								<div class="toneka-playlist-count">1/<?php echo count($samples); ?></div>
							</div>
							<ul class="toneka-playlist-items">
								<?php foreach ($samples as $index => $sample): ?>
									<li class="toneka-playlist-item<?php echo ($index === 0) ? ' active' : ''; ?>" 
										data-index="<?php echo esc_attr($index); ?>" 
										data-file="<?php echo esc_attr($sample['file']); ?>" 
										data-type="<?php echo esc_attr($sample['type']); ?>">
										<?php echo esc_html($sample['name']); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>
			
			<script>
			document.addEventListener('DOMContentLoaded', function() {
				const playerId = '<?php echo esc_js($player_id); ?>';
				const samples = <?php echo json_encode($samples); ?>;
				const container = document.querySelector('.toneka-player-container');
				const audioElement = document.getElementById(playerId + '-audio');
				const videoElement = document.getElementById(playerId + '-video');
				const mediaElement = audioElement || videoElement;
				
				const playButton = container.querySelector('.toneka-play-button');
				const playIcon = container.querySelector('.toneka-play-icon');
				const pauseIcon = container.querySelector('.toneka-pause-icon');
				const timeDisplay = container.querySelector('.toneka-time');
				const progressContainer = container.querySelector('.toneka-progress-container');
				const progressBar = container.querySelector('.toneka-progress-bar');
				const volumeButton = container.querySelector('.toneka-volume-button');
				const volumeIcon = container.querySelector('.toneka-volume-icon');
				const muteIcon = container.querySelector('.toneka-mute-icon');
				const volumeSlider = container.querySelector('.toneka-volume-slider');
				const currentTitleDisplay = container.querySelector('.toneka-current-title');
				const playlistCountDisplay = container.querySelector('.toneka-playlist-count');
				
				let currentIndex = 0;
				let isPlaying = false;
				
				// Funkcja ładująca próbkę
				function loadSample(index) {
					if (!samples[index]) return;
					
					const sample = samples[index];
					const prevIndex = currentIndex;
					currentIndex = index;
					
					// Aktualizuj tytuł i licznik
					if (currentTitleDisplay) {
						currentTitleDisplay.textContent = sample.name;
					}
					
					if (playlistCountDisplay) {
						playlistCountDisplay.textContent = (currentIndex + 1) + '/' + samples.length;
					}
					
					// Aktualizuj aktywny element playlisty
					const prevActive = container.querySelector('.toneka-playlist-item.active');
					if (prevActive) {
						prevActive.classList.remove('active');
					}
					
					const activeItem = container.querySelector(`.toneka-playlist-item[data-index="${index}"]`);
					if (activeItem) {
						activeItem.classList.add('active');
					}
					
					// Sprawdź typ próbki
					const isVideo = sample.type === 'video';
					
					// Ustaw źródło pliku
					if (isVideo && videoElement) {
						videoElement.src = sample.file;
						audioElement.src = '';
					} else if (audioElement) {
						audioElement.src = sample.file;
						if (videoElement) videoElement.src = '';
					}
					
					// Jeśli odtwarzanie było włączone, kontynuuj
					if (isPlaying || prevIndex === currentIndex) {
						play();
					}
				}
				
				// Funkcja play
				function play() {
					if (mediaElement.src) {
						mediaElement.play();
						isPlaying = true;
						playIcon.style.display = 'none';
						pauseIcon.style.display = 'block';
					}
				}
				
				// Funkcja pause
				function pause() {
					mediaElement.pause();
					isPlaying = false;
					playIcon.style.display = 'block';
					pauseIcon.style.display = 'none';
				}
				
				// Obsługa kliknięcia przycisku play/pause
				playButton.addEventListener('click', function() {
					if (isPlaying) {
						pause();
					} else {
						play();
					}
				});
				
				// Aktualizacja czasu
				mediaElement.addEventListener('timeupdate', function() {
					// Update time display
					const currentTime = mediaElement.currentTime;
					const minutes = Math.floor(currentTime / 60);
					const seconds = Math.floor(currentTime % 60);
					timeDisplay.textContent = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
					
					// Update progress bar
					const progress = (currentTime / mediaElement.duration) * 100;
					progressBar.style.width = progress + '%';
				});
				
				// Obsługa kliknięcia na pasek postępu
				progressContainer.addEventListener('click', function(e) {
					const rect = progressContainer.getBoundingClientRect();
					const pos = (e.clientX - rect.left) / rect.width;
					mediaElement.currentTime = pos * mediaElement.duration;
				});
				
				// Obsługa przycisku głośności
				volumeButton.addEventListener('click', function() {
					if (mediaElement.muted) {
						mediaElement.muted = false;
						volumeIcon.style.display = 'block';
						muteIcon.style.display = 'none';
						volumeSlider.value = mediaElement.volume;
					} else {
						mediaElement.muted = true;
						volumeIcon.style.display = 'none';
						muteIcon.style.display = 'block';
					}
				});
				
				// Obsługa suwaka głośności
				volumeSlider.addEventListener('input', function() {
					mediaElement.volume = volumeSlider.value;
					mediaElement.muted = (volumeSlider.value === 0);
					
					if (mediaElement.muted) {
						volumeIcon.style.display = 'none';
						muteIcon.style.display = 'block';
					} else {
						volumeIcon.style.display = 'block';
						muteIcon.style.display = 'none';
					}
				});
				
				// Obsługa kliknięć na elementy playlisty
				container.querySelectorAll('.toneka-playlist-item').forEach(item => {
					item.addEventListener('click', function() {
						const index = parseInt(this.dataset.index);
						loadSample(index);
					});
				});
				
				// Obsługa zakończenia odtwarzania
				mediaElement.addEventListener('ended', function() {
					if (currentIndex < samples.length - 1) {
						// Przejdź do następnego utworu
						loadSample(currentIndex + 1);
					} else {
						// Zakończ odtwarzanie
						isPlaying = false;
						playIcon.style.display = 'block';
						pauseIcon.style.display = 'none';
					}
				});
				
				// Inicjalizacja - załaduj pierwszą próbkę
				loadSample(0);
			});
			</script>
			<?php
		}
	}
	
	\Elementor\Plugin::instance()->widgets_manager->register(new Toneka_Audio_Video_Player_Widget());
}
add_action('elementor/widgets/register', 'toneka_register_audio_video_player_widget');

/**
 * Shortcode [year] wyświetlający bieżący rok
 */
add_shortcode('year', function() {
    return date('Y');
});

/**
 * Obsługuje dodawanie wybranych atrybutów do koszyka
 */
function toneka_add_custom_attributes_to_cart($cart_item_data, $product_id) {
    $product = wc_get_product($product_id);
    
    if ($product && $product->get_type() === 'variable') {
        $attributes = $product->get_variation_attributes();
        
        foreach ($attributes as $attribute_name => $options) {
            if (isset($_POST[$attribute_name])) {
                $selected_value = sanitize_text_field($_POST[$attribute_name]);
                $cart_item_data['custom_attributes'][$attribute_name] = $selected_value;
            }
        }
    }
    
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'toneka_add_custom_attributes_to_cart', 10, 2);

/**
 * Wyświetla wybrane atrybuty w koszyku
 */
function toneka_display_custom_attributes_in_cart($item_data, $cart_item) {
    if (isset($cart_item['custom_attributes'])) {
        foreach ($cart_item['custom_attributes'] as $attribute_name => $attribute_value) {
            $attribute_label = wc_attribute_label($attribute_name);
            
            // Get display name for the value
            $term = get_term_by('slug', $attribute_value, $attribute_name);
            $display_value = $term ? $term->name : $attribute_value;
            
            $item_data[] = array(
                'key' => $attribute_label,
                'value' => $display_value
            );
        }
    }
    
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'toneka_display_custom_attributes_in_cart', 10, 2);

/**
 * Zapisuje wybrane atrybuty w zamówieniu
 */
function toneka_save_custom_attributes_to_order($item, $cart_item_key, $values, $order) {
    if (isset($values['custom_attributes'])) {
        foreach ($values['custom_attributes'] as $attribute_name => $attribute_value) {
            $attribute_label = wc_attribute_label($attribute_name);
            
            // Get display name for the value
            $term = get_term_by('slug', $attribute_value, $attribute_name);
            $display_value = $term ? $term->name : $attribute_value;
            
            $item->add_meta_data($attribute_label, $display_value);
        }
    }
}
add_action('woocommerce_checkout_create_order_line_item', 'toneka_save_custom_attributes_to_order', 10, 4);

/**
 * Shortcode [year] wyświetlający bieżący rok
 */
add_shortcode('year', function() {
    return date('Y');
});

/**
 * Rejestruje widget Elementora do wyboru nośnika produktu
 */
function toneka_register_carrier_selection_widget() {
    if (!did_action('elementor/loaded')) {
        return;
    }

    class Toneka_Carrier_Selection_Widget extends \Elementor\Widget_Base {
        
        public function get_name() {
            return 'toneka-carrier-selection';
        }

        public function get_title() {
            return __('Wybór Nośnika Produktu', 'boldnote-child');
        }

        public function get_icon() {
            return 'eicon-radio';
        }

        public function get_categories() {
            return ['woocommerce-elements'];
        }

        protected function register_controls() {
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Ustawienia', 'boldnote-child'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'widget_title',
                [
                    'label' => __('Tytuł widgetu', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Wybierz wariant:', 'boldnote-child'),
                    'description' => __('Tytuł wyświetlany nad opcjami wariantów', 'boldnote-child'),
                ]
            );

            $this->add_control(
                'show_table',
                [
                    'label' => __('Pokaż tabelę informacji (przestarzałe)', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Tak', 'boldnote-child'),
                    'label_off' => __('Nie', 'boldnote-child'),
                    'return_value' => 'yes',
                    'default' => '',
                    'description' => __('Tabela jest przestarzała, używaj nowego widoku opisu i ceny.', 'boldnote-child'),
                ]
            );

            $this->add_control(
                'widget_note',
                [
                    'label' => __('Informacja', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __('Ten widget zawiera pełną funkcjonalność wyboru wariantów, ilości i dodawania do koszyka. Nie musisz dodawać dodatkowych widgetów WooCommerce.', 'boldnote-child'),
                    'content_classes' => 'elementor-control-field-description',
                ]
            );

            $this->end_controls_section();

            // Style section
            $this->start_controls_section(
                'style_section',
                [
                    'label' => __('Style', 'boldnote-child'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __('Kolor tytułu', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .toneka-carrier-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => __('Kolor tekstu', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .toneka-carrier-label' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .toneka-format-table td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'accent_color',
                [
                    'label' => __('Kolor akcentu', 'boldnote-child'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#8b5cf6',
                    'selectors' => [
                        '{{WRAPPER}} .toneka-carrier-radio:checked + .toneka-carrier-radio-custom' => 'border-color: {{VALUE}}; background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        /**
         * Pobiera czytelną etykietę atrybutu produktu
         */
        private function get_attribute_label($attr_name) {
            // Debug informacje
            if (WP_DEBUG) {
                error_log("TONEKA DEBUG: Pobieranie etykiety dla atrybutu: $attr_name");
            }
            
            // Sprawdź czy to atrybut globalny (attribute_pa_*)
            if (strpos($attr_name, 'attribute_pa_') === 0) {
                $taxonomy = str_replace('attribute_', '', $attr_name);
                $label = wc_attribute_label($taxonomy);
                
                if (WP_DEBUG) {
                    error_log("TONEKA DEBUG: Globalny atrybut $taxonomy, Etykieta: $label");
                }
                
                return $label;
            }
            
            // Sprawdź czy to atrybut lokalny (attribute_*)
            if (strpos($attr_name, 'attribute_') === 0) {
                $clean_name = str_replace('attribute_', '', $attr_name);
                $label = wc_attribute_label($clean_name);
                
                if (WP_DEBUG) {
                    error_log("TONEKA DEBUG: Lokalny atrybut $clean_name, Etykieta: $label");
                }
                
                return $label;
            }
            
            // Fallback
            $label = wc_attribute_label($attr_name);
            if (WP_DEBUG) {
                error_log("TONEKA DEBUG: Fallback etykieta: $label");
            }
            return $label;
        }

        /**
         * Pobiera czytelną wartość atrybutu produktu
         */
        private function get_attribute_display_value($attr_name, $attr_value) {
            // Debug informacje
            if (WP_DEBUG) {
                error_log("TONEKA DEBUG: Atrybut: $attr_name, Wartość: $attr_value");
            }
            
            // Sprawdź czy to atrybut globalny (pa_*)
            if (strpos($attr_name, 'attribute_pa_') === 0) {
                $taxonomy = str_replace('attribute_', '', $attr_name);
                $term = get_term_by('slug', $attr_value, $taxonomy);
                $display_value = $term ? $term->name : $attr_value;
                
                if (WP_DEBUG) {
                    error_log("TONEKA DEBUG: Globalny atrybut $taxonomy, Term: " . ($term ? $term->name : 'nie znaleziono') . ", Wynik: $display_value");
                }
                
                return $display_value;
            }
            
            // Sprawdź czy to atrybut lokalny
            if (strpos($attr_name, 'attribute_') === 0) {
                // Dla atrybutów lokalnych, wartość może być już czytelna
                if (WP_DEBUG) {
                    error_log("TONEKA DEBUG: Lokalny atrybut, zwracam: $attr_value");
                }
                return $attr_value;
            }
            
            // Fallback - zwróć wartość jak jest
            if (WP_DEBUG) {
                error_log("TONEKA DEBUG: Fallback, zwracam: $attr_value");
            }
            return $attr_value;
        }

        /**
         * Analizuje strukturę atrybutów produktu
         */
        private function analyze_attributes_structure($available_variations) {
            $attributes = array();
            
            foreach ($available_variations as $variation_data) {
                foreach ($variation_data['attributes'] as $attr_name => $attr_value) {
                    if (!empty($attr_value)) {
                        if (!isset($attributes[$attr_name])) {
                            $attributes[$attr_name] = array(
                                'label' => $this->get_attribute_label($attr_name),
                                'values' => array()
                            );
                        }
                        
                        // Pobierz czytelną wartość atrybutu
                        $display_value = $this->get_attribute_display_value($attr_name, $attr_value);
                        
                        if (!in_array($display_value, $attributes[$attr_name]['values'])) {
                            $attributes[$attr_name]['values'][] = $display_value;
                        }
                    }
                }
            }
            
            return $attributes;
        }
        
        /**
         * Określa czy powinien być użyty dwuetapowy selektor
         */
        private function should_use_two_step_selector($attributes_structure, $total_variations) {
            // Jeśli mamy tylko 1 atrybut lub mało wariantów, używaj obecnego sposobu
            if (count($attributes_structure) <= 1 || $total_variations <= 4) {
                return false;
            }
            
            // Jeśli mamy dokładnie 2 atrybuty i dużo kombinacji, użyj dwuetapowego
            if (count($attributes_structure) == 2 && $total_variations > 4) {
                return true;
            }
            
            // Dla więcej niż 2 atrybutów, zawsze użyj dwuetapowego (można rozszerzyć w przyszłości)
            if (count($attributes_structure) > 2) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Przygotowuje dane dla dwuetapowego selektora
         */
        private function prepare_two_step_data($available_variations, $attributes_structure) {
            $attributes_keys = array_keys($attributes_structure);
            $primary_attr = $attributes_keys[0];
            $secondary_attr = isset($attributes_keys[1]) ? $attributes_keys[1] : null;
            
            $data = array(
                'primary_attribute' => array(
                    'name' => $primary_attr,
                    'label' => $attributes_structure[$primary_attr]['label'],
                    'values' => $attributes_structure[$primary_attr]['values']
                ),
                'secondary_attribute' => $secondary_attr ? array(
                    'name' => $secondary_attr,
                    'label' => $attributes_structure[$secondary_attr]['label'],
                    'values' => $attributes_structure[$secondary_attr]['values']
                ) : null,
                'variations_map' => array()
            );
            
            // Mapowanie kombinacji atrybutów do wariantów
            foreach ($available_variations as $variation_data) {
                $primary_value = '';
                $secondary_value = '';
                
                foreach ($variation_data['attributes'] as $attr_name => $attr_value) {
                    if ($attr_name === $primary_attr) {
                        $primary_value = $this->get_attribute_display_value($attr_name, $attr_value);
                    }
                    if ($secondary_attr && $attr_name === $secondary_attr) {
                        $secondary_value = $this->get_attribute_display_value($attr_name, $attr_value);
                    }
                }
                
                $key = $secondary_attr ? $primary_value . '|' . $secondary_value : $primary_value;
                $data['variations_map'][$key] = $variation_data;
            }
            
            return $data;
        }

        protected function render() {
            if (!is_product()) {
                echo '<p>' . __('Ten widget działa tylko na stronach produktów.', 'boldnote-child') . '</p>';
                return;
            }

            global $product;
            if (!$product || $product->get_type() !== 'variable') {
                echo '<p>' . __('Ten widget działa tylko z produktami zmiennymi.', 'boldnote-child') . '</p>';
                return;
            }

            $settings = $this->get_settings_for_display();
            $widget_title = !empty($settings['widget_title']) ? $settings['widget_title'] : __('Wybierz wariant:', 'boldnote-child');
            
            // Pobierz wszystkie dostępne warianty
            $available_variations = $product->get_available_variations();
            
            if (empty($available_variations)) {
                echo '<p>' . __('Brak dostępnych wariantów produktu.', 'boldnote-child') . '</p>';
                return;
            }

            // Przygotuj dane wariantów z opisami
            foreach ($available_variations as $key => $variation_data) {
                $variation = wc_get_product($variation_data['variation_id']);
                if ($variation) {
                    $description = $variation->get_description();
                    if (!empty($description)) {
                        $available_variations[$key]['variation_description'] = $description;
                    }
                    
                    // Generuj czytelną nazwę wariantu z wszystkich atrybutów
                    $attribute_labels = array();
                    foreach ($variation_data['attributes'] as $attr_name => $attr_value) {
                        if (!empty($attr_value)) {
                            // Pobierz czytelną wartość atrybutu
                            $display_value = $this->get_attribute_display_value($attr_name, $attr_value);
                            $attribute_labels[] = $display_value;
                        }
                    }
                    
                    // Generuj czytelną nazwę wariantu
                    $available_variations[$key]['variation_display_name'] = !empty($attribute_labels) ? 
                        implode(', ', $attribute_labels) : 
                        sprintf(__('Wariant #%s', 'boldnote-child'), $variation_data['variation_id']);
                }
            }
            
            // Analiza struktury atrybutów dla decyzji o dwuetapowym selektorze
            $attributes_structure = $this->analyze_attributes_structure($available_variations);
            $use_two_step = $this->should_use_two_step_selector($attributes_structure, count($available_variations));
            
            // Jeśli używamy dwuetapowego selektora, przygotuj dane
            if ($use_two_step) {
                $attributes_data = $this->prepare_two_step_data($available_variations, $attributes_structure);
            }
            
            ?>
            <div class="toneka-carrier-selection-widget" data-mode="<?php echo $use_two_step ? 'two-step' : 'all-variations'; ?>">
                
                <h3 class="toneka-carrier-title">
                    <?php echo esc_html($widget_title); ?>
                </h3>
                
                <?php if ($use_two_step): ?>
                    <!-- Dwuetapowy selektor -->
                    <div class="toneka-two-step-selector">
                        <!-- Krok 1: Wybór głównego atrybutu -->
                        <div class="toneka-step-container">
                            <h4 class="toneka-step-title"><?php echo esc_html($attributes_data['primary_attribute']['label']); ?>:</h4>
                            <div class="toneka-attribute-options" data-attribute="primary">
                                <?php 
                                $first_primary = true;
                                foreach ($attributes_data['primary_attribute']['values'] as $value): 
                                ?>
                                <label class="toneka-carrier-option">
                                    <input type="radio" 
                                           name="toneka_primary_attribute" 
                                           value="<?php echo esc_attr($value); ?>" 
                                           class="toneka-carrier-radio toneka-primary-radio" 
                                           <?php echo $first_primary ? 'checked' : ''; ?>>
                                    <span class="toneka-carrier-radio-custom"></span>
                                    <span class="toneka-carrier-label"><?php echo esc_html($value); ?></span>
                                </label>
                                <?php 
                                    $first_primary = false;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                        
                        <?php if ($attributes_data['secondary_attribute']): ?>
                        <!-- Krok 2: Wybór drugiego atrybutu -->
                        <div class="toneka-step-container">
                            <h4 class="toneka-step-title"><?php echo esc_html($attributes_data['secondary_attribute']['label']); ?>:</h4>
                            <div class="toneka-attribute-options" data-attribute="secondary">
                                <?php 
                                $first_secondary = true;
                                foreach ($attributes_data['secondary_attribute']['values'] as $value): 
                                ?>
                                <label class="toneka-carrier-option">
                                    <input type="radio" 
                                           name="toneka_secondary_attribute" 
                                           value="<?php echo esc_attr($value); ?>" 
                                           class="toneka-carrier-radio toneka-secondary-radio" 
                                           <?php echo $first_secondary ? 'checked' : ''; ?>>
                                    <span class="toneka-carrier-radio-custom"></span>
                                    <span class="toneka-carrier-label"><?php echo esc_html($value); ?></span>
                                </label>
                                <?php 
                                    $first_secondary = false;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Dane dla JavaScript -->
                        <script type="application/json" class="toneka-two-step-data">
                        <?php echo json_encode($attributes_data); ?>
                        </script>
                    </div>
                <?php else: ?>
                    <!-- Jednoduchowy selektor (obecny sposób) -->
                <div class="toneka-carrier-options">
                    <?php 
                    $first = true;
                        foreach ($available_variations as $variation_data): 
                    ?>
                    <label class="toneka-carrier-option">
                        <input type="radio" 
                                   name="toneka_variation_selection" 
                                   value="<?php echo esc_attr($variation_data['variation_id']); ?>" 
                               class="toneka-carrier-radio" 
                                   data-variation-id="<?php echo esc_attr($variation_data['variation_id']); ?>"
                                   data-variation-data="<?php echo esc_attr(json_encode($variation_data)); ?>"
                               <?php echo $first ? 'checked' : ''; ?>>
                        <span class="toneka-carrier-radio-custom"></span>
                            <span class="toneka-carrier-label"><?php echo esc_html($variation_data['variation_display_name']); ?></span>
                    </label>
                    <?php 
                        $first = false;
                    endforeach; 
                    ?>
                </div>
                <?php endif; ?>

                <?php if ($settings['show_table'] === 'yes'): ?>
                <div class="toneka-format-info">
                    <div class="toneka-selected-variation-info">
                        <!-- Informacje o wybranym wariancie będą ładowane przez JavaScript -->
                        <div class="toneka-variation-details" style="display: none;">
                            <table class="toneka-format-table">
                                <tbody>
                                    <tr>
                                        <td class="toneka-format-name">
                                            <span class="toneka-variation-name"></span>
                                        </td>
                                        <td class="toneka-format-availability"><?php esc_html_e('Dostępność', 'boldnote-child'); ?></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="toneka-variation-description"></div>
                                        </td>
                                        <td class="toneka-format-price">
                                            <span class="toneka-variation-price"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Debug informacje -->
                        <?php if (WP_DEBUG) : ?>
                        <div class="toneka-debug-info" style="display: none;">
                            <h4>Debug: Opisy wariantów</h4>
                            <?php foreach ($available_variations as $key => $variation_data) : 
                                $v = wc_get_product($variation_data['variation_id']);
                                if ($v) : ?>
                                <div>
                                    ID: <?php echo $v->get_id(); ?>, 
                                    Opis: <?php echo $v->get_description(); ?>
                                </div>
                            <?php endif; endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Hidden data for JavaScript -->
                <script type="application/json" class="toneka-variations-data">
                <?php echo json_encode($available_variations); ?>
                </script>
                <?php endif; ?>
                
                <!-- Zawsze widoczny kontener na opis i cenę wariantu -->
                <div class="toneka-variation-info-container">
                    <div class="toneka-variation-description-display"></div>
                    <div class="toneka-variation-price-display"></div>
                </div>
                
                <!-- Hidden data for JavaScript (zawsze dostępne) -->
                <?php if ($settings['show_table'] !== 'yes'): ?>
                <script type="application/json" class="toneka-variations-data">
                <?php echo json_encode($available_variations); ?>
                </script>
                <?php endif; ?>
                
                <!-- Quantity and Add to Cart Section -->
                <div class="toneka-cart-section">
                    <form class="toneka-cart-form" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' style="display: flex !important; flex-direction: row !important; align-items: center !important; gap: 20px !important;">
                        
                        <!-- Quantity Input -->
                        <div class="toneka-quantity-section">
                            <label class="toneka-quantity-label" for="toneka-quantity"><?php esc_html_e('Quantity', 'boldnote-child'); ?></label>
                            <div class="toneka-quantity-wrapper">
                                <button type="button" class="toneka-quantity-minus">−</button>
                                <input 
                                    type="number" 
                                    id="toneka-quantity"
                                    class="toneka-quantity-input" 
                                    name="quantity"
                                    value="1" 
                                    min="1" 
                                    max="999"
                                    step="1" 
                                    inputmode="numeric"
                                    autocomplete="off"
                                />
                                <button type="button" class="toneka-quantity-plus">+</button>
                            </div>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <button 
                            type="submit" 
                            name="add-to-cart" 
                            value="<?php echo esc_attr($product->get_id()); ?>" 
                            class="toneka-add-to-cart-button"
                            data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                            data-quantity="1"
                        >
                            <?php echo esc_html($product->single_add_to_cart_text()); ?>
                        </button>
                        
                        <!-- Hidden inputs for attributes and product ID -->
                        <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                        <input type="hidden" name="variation_id" class="variation_id" value="" />
                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                        <!-- Hidden inputs for attributes will be added by JavaScript -->
                        
                    </form>
                </div>
                
            </div>
            <?php
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register(new Toneka_Carrier_Selection_Widget());
}
add_action('elementor/widgets/register', 'toneka_register_carrier_selection_widget');

/**
 * Enqueue custom JavaScript and CSS for carrier selection
 */
function toneka_enqueue_carrier_selection_assets() {
    // Tylko na pojedynczych stronach produktów
    if (is_product()) {
        // Załaduj nowy skrypt JavaScript
        wp_enqueue_script(
            'toneka-carrier-selection-new', 
            get_stylesheet_directory_uri() . '/js/carrier-selection-new.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
                 // Dodaj skrypt do usuwania natywnych kontrolek
         add_action('wp_footer', function() {
             echo '<script>
             jQuery(document).ready(function($) {
                 // Funkcja do usuwania natywnych kontrolek
                 function removeNativeControls() {
                     $(".woocommerce .quantity, form.cart .quantity").not(".toneka-quantity-wrapper").remove();
                     $(".woocommerce-variation-add-to-cart .quantity").remove();
                     $(".woocommerce div.product form.cart div.quantity").not(".toneka-quantity-wrapper").remove();
                     $(".woocommerce .quantity .qty, .woocommerce .quantity input").not(".toneka-quantity-input").remove();
                                           
                      // Upewnij się, że tekst w polu ilości jest biały
                      $(".toneka-quantity-input").css("color", "#fff");
                      
                      // Ukryj etykietę ilości
                      $(".toneka-quantity-label").hide().css({
                          "display": "none",
                          "visibility": "hidden",
                          "width": "0",
                          "height": "0",
                          "overflow": "hidden",
                          "position": "absolute",
                          "left": "-9999px"
                      });
                 }
                 
                 // Wykonaj funkcję kilka razy
                 removeNativeControls();
                 setTimeout(removeNativeControls, 100);
                 setTimeout(removeNativeControls, 500);
                 setTimeout(removeNativeControls, 1000);
                 setTimeout(removeNativeControls, 2000);
                 
                 // Wykonuj co sekundę przez 10 sekund
                 var interval = setInterval(function() {
                     removeNativeControls();
                 }, 1000);
                 
                 setTimeout(function() {
                     clearInterval(interval);
                 }, 10000);
             });
             </script>';
         }, 999);
         
         // Style zostały przeniesione do pliku style.css
    }
}
add_action('wp_enqueue_scripts', 'toneka_enqueue_carrier_selection_assets');

// Style zostały przeniesione do pliku style.css

/**
 * Dodaje obsługę AJAX dla dodawania produktów do koszyka
 */
function toneka_ajax_add_to_cart() {
    // Debugowanie
    error_log('Toneka AJAX Add to Cart - Request: ' . print_r($_POST, true));
    
    // Sprawdź, czy mamy product_id bezpośrednio lub z add-to-cart
    $product_id = 0;
    
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $product_id = absint($_POST['product_id']);
    } elseif (isset($_POST['add-to-cart']) && !empty($_POST['add-to-cart'])) {
        $product_id = absint($_POST['add-to-cart']);
    }
    
    error_log('Toneka AJAX Add to Cart - Detected product_id: ' . $product_id);
    
    if ($product_id === 0) {
        error_log('Toneka AJAX Add to Cart - No product ID found in request');
        wp_send_json_error(array('message' => 'Nie znaleziono ID produktu w żądaniu'));
        return;
    }
    
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    
    // Pobierz wybrane atrybuty
    $attributes = array();
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'attribute_') === 0) {
            $attributes[$key] = $value;
        }
    }
    
    // Sprawdź czy produkt istnieje
    $product = wc_get_product($product_id);
    if (!$product) {
        error_log('Toneka AJAX Add to Cart - Product not found: ' . $product_id);
        wp_send_json_error(array('message' => 'Produkt nie istnieje'));
        return;
    }
    
    // Dodaj produkt do koszyka
    $added_to_cart = false;
    
    if ($product->is_type('variable') && $variation_id > 0) {
        // Dodaj produkt wariantowy
        $added_to_cart = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $attributes);
        error_log('Toneka AJAX Add to Cart - Adding variable product: ' . $product_id . ', variation: ' . $variation_id . ', result: ' . ($added_to_cart ? 'success' : 'failed'));
    } else {
        // Dodaj prosty produkt
        $added_to_cart = WC()->cart->add_to_cart($product_id, $quantity);
        error_log('Toneka AJAX Add to Cart - Adding simple product: ' . $product_id . ', result: ' . ($added_to_cart ? 'success' : 'failed'));
    }
    
    if ($added_to_cart) {
        // Pobierz fragmenty koszyka do aktualizacji
        $fragments = apply_filters('woocommerce_add_to_cart_fragments', array());
        
        // Pobierz liczbę produktów w koszyku
        $cart_count = WC()->cart->get_cart_contents_count();
        
        // Pobierz hash koszyka
        $cart_hash = WC()->cart->get_cart_hash();
        
        // Przygotuj odpowiedź
        $data = array(
            'fragments' => $fragments,
            'cart_count' => $cart_count,
            'cart_hash' => $cart_hash,
            'message' => 'Produkt dodany do koszyka'
        );
        
        wp_send_json_success($data);
    } else {
        error_log('Toneka AJAX Add to Cart - Failed to add product to cart');
        wp_send_json_error(array('message' => 'Nie udało się dodać produktu do koszyka'));
    }
}
add_action('wp_ajax_toneka_ajax_add_to_cart', 'toneka_ajax_add_to_cart');
add_action('wp_ajax_nopriv_toneka_ajax_add_to_cart', 'toneka_ajax_add_to_cart');

/**
 * Dodaje zmienne JavaScript do skryptów
 */
function toneka_localize_scripts() {
    wp_localize_script('toneka-carrier-selection', 'toneka_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('toneka_ajax_nonce')
    ));
}

/**
 * Rejestruje i dołącza skrypty JavaScript (WYŁĄCZONE - używamy nowego systemu)
 */
/*
function toneka_register_scripts() {
    // Zarejestruj skrypt wyboru nośnika
    wp_register_script('toneka-carrier-selection', get_stylesheet_directory_uri() . '/js/carrier-selection.js', array('jquery'), '1.0.2', true);
    
    // Dodaj lokalizację dla skryptów
    wp_localize_script('toneka-carrier-selection', 'toneka_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('toneka_ajax_nonce'),
        'home_url' => home_url(),
        'is_cart' => is_cart(),
        'is_checkout' => is_checkout()
    ));
    
    // Dołącz skrypty na wszystkich stronach WooCommerce
    if (is_product() || is_shop() || is_product_category() || is_product_tag() || 
        has_shortcode(get_the_content(), 'elementor-template') || is_woocommerce() ||
        is_cart() || is_checkout() || is_account_page()) {
        wp_enqueue_script('toneka-carrier-selection');
    }
}
add_action('wp_enqueue_scripts', 'toneka_register_scripts');
*/

/**
 * Dodaje fragmenty mini-koszyka do odpowiedzi AJAX
 */
function toneka_woocommerce_header_add_to_cart_fragment($fragments) {
    // Fragment głównego kontenera mini koszyka
    ob_start();
    ?>
    <div class="qodef-woo-dropdown-items">
        <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['.qodef-woo-dropdown-items'] = ob_get_clean();
    
    // Fragment dla alternatywnego kontenera
    ob_start();
    ?>
    <div class="qodef-widget-dropdown-cart-content">
        <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['.qodef-widget-dropdown-cart-content'] = ob_get_clean();
    
    // Fragment dla standardowego widget_shopping_cart_content
    ob_start();
    ?>
    <div class="widget_shopping_cart_content">
        <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['.widget_shopping_cart_content'] = ob_get_clean();
    
    // Dodaj licznik produktów w koszyku
    ob_start();
    ?>
    <span class="qodef-m-opener-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.qodef-m-opener-count'] = ob_get_clean();
    
    // Dodatkowe liczniki
    $fragments['.cart-count-number'] = '<span class="cart-count-number">' . WC()->cart->get_cart_contents_count() . '</span>';
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'toneka_woocommerce_header_add_to_cart_fragment');

/**
 * Dodaje skrypt do obsługi kliknięcia w ikonę koszyka po dodaniu produktu
 */
function toneka_enqueue_cart_scripts() {
    if (is_product() || has_shortcode(get_the_content(), 'elementor-template') || is_woocommerce()) {
        wp_add_inline_script('toneka-carrier-selection', '
            // Funkcja do otwierania mini-koszyka
            function openMiniCart() {
                // Znajdź i kliknij w przycisk otwierający koszyk
                var cartOpeners = document.querySelectorAll(".qodef-m-opener, .qodef-woo-dropdown-cart .qodef-m-opener, .qodef-woo-side-area-cart .qodef-m-opener");
                if (cartOpeners.length > 0) {
                    cartOpeners[0].click();
                } else {
                    // Alternatywne selektory
                    var altCartOpeners = document.querySelectorAll(".cart-contents, .cart-icon, .mini-cart-opener, .header-cart");
                    if (altCartOpeners.length > 0) {
                        altCartOpeners[0].click();
                    }
                }
            }
            
            // Obsługa zdarzenia dodania do koszyka - wymusza odświeżenie
            jQuery(document).on("added_to_cart", function(event, fragments, cart_hash) {
                console.log("Cart fragments received:", fragments);
                
                // Wymusza ponowne załadowanie zawartości mini koszyka
                jQuery(document.body).trigger("wc_fragment_refresh");
                
                setTimeout(function() {
                    openMiniCart();
                }, 500);
            });
        ');
    }
}
add_action('wp_enqueue_scripts', 'toneka_enqueue_cart_scripts', 20);

/**
 * Dodaje globalne przechwytywanie formularzy dodawania do koszyka
 */
function toneka_add_global_cart_ajax() {
    if (is_product() || has_shortcode(get_the_content(), 'elementor-template') || is_woocommerce()) {
        wp_add_inline_script('jquery', '
            // Debugowanie - sprawdź czy skrypt jest ładowany
            console.log("Toneka AJAX Cart Handler loaded");
            
            jQuery(document).ready(function($) {
                // Debugowanie - sprawdź czy jQuery jest gotowy
                console.log("jQuery ready - initializing cart handlers");
                
                // Zapisz oryginalny tekst wszystkich przycisków dodawania do koszyka
                function saveOriginalButtonTexts() {
                    $(".ajax_add_to_cart, .single_add_to_cart_button, .toneka-add-to-cart-button").each(function() {
                        var $button = $(this);
                        if (!$button.data("original-text")) {
                            $button.data("original-text", $button.text());
                            console.log("Saved original text for button:", $button.text());
                        }
                    });
                }
                
                // Zapisz oryginalny tekst przycisków
                saveOriginalButtonTexts();
                
                // Wykonaj ponownie po pełnym załadowaniu strony
                $(window).on("load", function() {
                    saveOriginalButtonTexts();
                });
                
                // Przechwytuj wszystkie formularze dodawania do koszyka
                $(document).on("submit", "form.cart, form.toneka-cart-form", function(e) {
                    e.preventDefault();
                    console.log("Form submit intercepted", this);
                    
                    var $form = $(this);
                    var $button = $form.find("button[type=\'submit\'], input[type=\'submit\']");
                    
                    // Zapisz oryginalny tekst przycisku jako atrybut data
                    if (!$button.data("original-text")) {
                        $button.data("original-text", $button.text());
                    }
                    var originalButtonText = $button.data("original-text");
                    console.log("Form button original text:", originalButtonText);
                    
                    // Pokaż loader
                    console.log("Setting button text to Dodawanie...");
                    $button.text("Dodawanie...").addClass("loading").prop("disabled", true);
                    
                    // Zbierz dane formularza
                    var formData = new FormData(this);
                    formData.append("action", "toneka_ajax_add_to_cart");
                    
                    // Dodaj nonce do formularza
                    if (typeof toneka_ajax_object !== "undefined" && toneka_ajax_object.nonce) {
                        formData.append("nonce", toneka_ajax_object.nonce);
                    }
                    
                    // Upewnij się, że mamy product_id
                    var productId = 0;
                    
                    // Sprawdź czy mamy ukryte pole add-to-cart
                    if ($form.find("input[name=\'add-to-cart\']").length) {
                        productId = $form.find("input[name=\'add-to-cart\']").val();
                        console.log("Found product ID from add-to-cart field:", productId);
                    }
                    
                    // Sprawdź czy mamy przycisk z wartością
                    if (productId == 0 && $button.attr("value")) {
                        productId = $button.attr("value");
                        console.log("Found product ID from button value:", productId);
                    }
                    
                    // Sprawdź czy mamy data-product_id na przycisku
                    if (productId == 0 && $button.data("product_id")) {
                        productId = $button.data("product_id");
                        console.log("Found product ID from button data:", productId);
                    }
                    
                    // Sprawdź czy mamy ukryte pole product_id
                    if (productId == 0 && $form.find("input[name=\'product_id\']").length) {
                        productId = $form.find("input[name=\'product_id\']").val();
                        console.log("Found product ID from product_id field:", productId);
                    }
                    
                    // Jeśli znaleźliśmy ID produktu, dodaj je do formularza
                    if (productId > 0) {
                        formData.append("product_id", productId);
                        console.log("Added product_id to form data:", productId);
                    }
                    
                    // Wyświetl wszystkie dane formularza do debugowania
                    console.log("Form data entries:");
                    for (var pair of formData.entries()) {
                        console.log(pair[0] + ": " + pair[1]);
                    }
                    
                    // Wyślij żądanie AJAX
                    $.ajax({
                        type: "POST",
                        url: typeof toneka_ajax_object !== "undefined" ? toneka_ajax_object.ajax_url : "/wp-admin/admin-ajax.php",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log("AJAX response:", response);
                            
                            if (response.success) {
                                // Zmień tekst na "Dodane"
                                console.log("Setting button text to Dodane");
                                $button.text("Dodane").removeClass("loading");
                                
                                // Po 2 sekundach przywróć oryginalny tekst
                                setTimeout(function() {
                                    console.log("Restoring original button text:", originalButtonText);
                                    $button.text(originalButtonText).prop("disabled", false);
                                }, 2000);
                                
                                // Zaktualizuj mini koszyk
                                if (response.data && response.data.fragments) {
                                    $.each(response.data.fragments, function(key, value) {
                                        $(key).replaceWith(value);
                                    });
                                }
                                
                                // Zaktualizuj licznik produktów w koszyku
                                if (response.data && response.data.cart_count) {
                                    $(".cart-count-number, .qodef-m-opener-count").text(response.data.cart_count);
                                }
                                
                                // Otwórz mini koszyk
                                setTimeout(function() {
                                    if (typeof openMiniCart === "function") {
                                        openMiniCart();
                                    } else {
                                        // Spróbuj znaleźć przycisk koszyka
                                        var $cartOpeners = $(".qodef-m-opener, .qodef-woo-dropdown-cart .qodef-m-opener, .qodef-woo-side-area-cart .qodef-m-opener, .mini-cart-opener, .cart-opener, .cart-contents, .header-cart");
                                        if ($cartOpeners.length) {
                                            $cartOpeners.first().trigger("click");
                                        }
                                    }
                                }, 300);
                                
                                // Wywołaj zdarzenie dodania do koszyka
                                $(document.body).trigger("added_to_cart", [response.data.fragments, response.data.cart_hash, $button]);
                            } else {
                                // Przywróć oryginalny tekst przycisku w przypadku błędu
                                console.log("Error response, restoring original button text:", originalButtonText);
                                $button.text(originalButtonText).removeClass("loading").prop("disabled", false);
                                
                                // Pokaż komunikat błędu
                                if (response.data && response.data.message) {
                                    alert(response.data.message);
                                } else {
                                    alert("Wystąpił błąd podczas dodawania produktu do koszyka.");
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error:", xhr, status, error);
                            
                            // Przywróć oryginalny tekst przycisku
                            console.log("AJAX error, restoring original button text:", originalButtonText);
                            $button.text(originalButtonText).removeClass("loading").prop("disabled", false);
                            
                            // Pokaż komunikat błędu
                            alert("Wystąpił błąd podczas dodawania produktu do koszyka.");
                        }
                    });
                });
            });
        ', 'before');
    }
}
add_action('wp_enqueue_scripts', 'toneka_add_global_cart_ajax', 99);

/**
 * Dodaje obsługę AJAX dla formularzy WooCommerce
 */
function toneka_woocommerce_ajax_add_to_cart_js() {
    if (is_product() || has_shortcode(get_the_content(), 'elementor-template') || is_woocommerce()) {
        wc_enqueue_js('
            // Wyłącz domyślną obsługę AJAX WooCommerce dla przycisków dodawania do koszyka
            $(".single_add_to_cart_button").addClass("toneka-ajax-add-to-cart");
            $(document).off("click", ".ajax_add_to_cart");
        ');
    }
}
add_action('wp_enqueue_scripts', 'toneka_woocommerce_ajax_add_to_cart_js', 99);

/**
 * Wyłącza przekierowanie po dodaniu do koszyka
 */
function toneka_disable_redirect_after_add_to_cart() {
    // Wyłącz przekierowanie po dodaniu do koszyka
    add_filter('woocommerce_add_to_cart_redirect', '__return_false');
    
    // Wyłącz przekierowanie do koszyka po dodaniu produktu
    add_filter('woocommerce_cart_redirect_after_add', '__return_false');
    
    // Wyłącz powiadomienia o dodaniu do koszyka
    add_filter('wc_add_to_cart_message_html', '__return_empty_string');
    
    // Wyłącz fragmenty koszyka, które mogą powodować konflikty
    remove_action('wp_ajax_woocommerce_get_refreshed_fragments', array('WC_AJAX', 'get_refreshed_fragments'));
    remove_action('wp_ajax_nopriv_woocommerce_get_refreshed_fragments', array('WC_AJAX', 'get_refreshed_fragments'));
}
add_action('init', 'toneka_disable_redirect_after_add_to_cart');

/**
 * Wyłącza domyślne powiadomienia WooCommerce
 */
add_filter('wc_add_to_cart_message_html', '__return_empty_string');

/**
 * Wyłącza domyślny AJAX WooCommerce
 */
function toneka_disable_woocommerce_ajax() {
    // Usuń domyślne akcje WooCommerce dla AJAX
    remove_action('wp_ajax_woocommerce_add_to_cart', array('WC_AJAX', 'add_to_cart'));
    remove_action('wp_ajax_nopriv_woocommerce_add_to_cart', array('WC_AJAX', 'add_to_cart'));
    
    // Usuń fragmenty koszyka, które mogą powodować konflikty
    remove_action('wp_ajax_woocommerce_get_refreshed_fragments', array('WC_AJAX', 'get_refreshed_fragments'));
    remove_action('wp_ajax_nopriv_woocommerce_get_refreshed_fragments', array('WC_AJAX', 'get_refreshed_fragments'));
}
add_action('init', 'toneka_disable_woocommerce_ajax');

/**
 * System stron twórców - tworzy dynamiczne strony dla twórców z produktów
 */

// Rejestruj custom post type dla twórców
function toneka_register_creator_post_type() {
    register_post_type('creator', array(
        'labels' => array(
            'name' => 'Twórcy',
            'singular_name' => 'Twórca',
            'add_new' => 'Dodaj twórcę',
            'add_new_item' => 'Dodaj nowego twórcę',
            'edit_item' => 'Edytuj twórcę',
            'new_item' => 'Nowy twórca',
            'view_item' => 'Zobacz twórcę',
            'search_items' => 'Szukaj twórców',
            'not_found' => 'Nie znaleziono twórców',
            'not_found_in_trash' => 'Nie znaleziono twórców w koszu',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'tworca'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'elementor'),
        'show_in_rest' => true,
        'can_export' => true,
    ));
}
add_action('init', 'toneka_register_creator_post_type');

// Odśwież permalinki po pierwszej aktywacji
function toneka_flush_rewrite_rules_on_activation() {
    if (get_option('toneka_creator_post_type_activated') !== '1') {
        flush_rewrite_rules();
        update_option('toneka_creator_post_type_activated', '1');
    }
}
add_action('init', 'toneka_flush_rewrite_rules_on_activation', 20);

// Dodaj meta pola dla twórców
function toneka_register_creator_meta() {
    register_post_meta('creator', '_creator_role', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ));
    
    register_post_meta('creator', '_creator_bio', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ));
}
add_action('init', 'toneka_register_creator_meta');

// Funkcja do tworzenia slug-a z imienia i nazwiska
function toneka_create_creator_slug($name) {
    $slug = sanitize_title($name);
    return $slug;
}

// Funkcja do pobierania lub tworzenia strony twórcy
function toneka_get_or_create_creator_page($name, $role = '') {
    $slug = toneka_create_creator_slug($name);
    
    // Sprawdź czy twórca już istnieje
    $existing = get_posts(array(
        'post_type' => 'creator',
        'name' => $slug,
        'post_status' => 'publish',
        'numberposts' => 1
    ));
    
    if (!empty($existing)) {
        return $existing[0]->ID;
    }
    
    // Utwórz nowego twórcę
    $creator_id = wp_insert_post(array(
        'post_title' => $name,
        'post_name' => $slug,
        'post_type' => 'creator',
        'post_status' => 'publish',
        'post_content' => 'Biografia twórcy będzie dostępna wkrótce.',
    ));
    
    if ($creator_id && !is_wp_error($creator_id)) {
        // Dodaj rolę
        if ($role) {
            update_post_meta($creator_id, '_creator_role', $role);
        }
        return $creator_id;
    }
    
    return false;
}

// Funkcja do generowania linku do strony twórcy
function toneka_get_creator_link($name) {
    $slug = toneka_create_creator_slug($name);
    return home_url('/tworca/' . $slug . '/');
}

// Funkcja do zamiany nazw twórców na linki w produktach
function toneka_make_creators_clickable($creators_array, $role = '') {
    if (empty($creators_array) || !is_array($creators_array)) {
        return $creators_array;
    }
    
    foreach ($creators_array as &$creator) {
        if (isset($creator['imie_nazwisko']) && !empty($creator['imie_nazwisko'])) {
            // Utwórz lub pobierz ID twórcy
            toneka_get_or_create_creator_page($creator['imie_nazwisko'], $role);
            
            // Dodaj link
            $link = toneka_get_creator_link($creator['imie_nazwisko']);
            $creator['link'] = $link;
            $creator['clickable_name'] = '<a href="' . esc_url($link) . '" class="creator-link">' . esc_html($creator['imie_nazwisko']) . '</a>';
        }
    }
    
    return $creators_array;
}

// Hook do automatycznego tworzenia stron twórców przy zapisywaniu produktu
function toneka_auto_create_creator_pages($post_id) {
    if (get_post_type($post_id) !== 'product') {
        return;
    }
    
    // Lista wszystkich typów twórców
    $creator_types = array(
        '_autors' => 'Autor',
        '_obsada' => 'Aktor/Aktorka',
        '_rezyserzy' => 'Reżyser',
        '_muzycy' => 'Muzyk',
        '_tlumacze' => 'Tłumacz',
        '_adaptatorzy' => 'Adaptator',
        '_wydawcy' => 'Wydawca',
        '_grafika' => 'Grafik'
    );
    
    foreach ($creator_types as $meta_key => $role) {
        $creators = get_post_meta($post_id, $meta_key, true);
        
        if (!empty($creators) && is_array($creators)) {
            foreach ($creators as $creator) {
                if (isset($creator['imie_nazwisko']) && !empty($creator['imie_nazwisko'])) {
                    toneka_get_or_create_creator_page($creator['imie_nazwisko'], $role);
                }
            }
        }
    }
}
add_action('save_post', 'toneka_auto_create_creator_pages');

// Dodaj pola meta do panelu edycji twórcy
function toneka_add_creator_meta_boxes() {
    add_meta_box(
        'creator-details',
        'Szczegóły twórcy',
        'toneka_creator_meta_box_callback',
        'creator',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'toneka_add_creator_meta_boxes');

function toneka_creator_meta_box_callback($post) {
    wp_nonce_field('toneka_creator_meta', 'toneka_creator_meta_nonce');
    
    $role = get_post_meta($post->ID, '_creator_role', true);
    $bio = get_post_meta($post->ID, '_creator_bio', true);
    
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="creator_role">Rola/Specjalizacja:</label></th>';
    echo '<td><input type="text" id="creator_role" name="creator_role" value="' . esc_attr($role) . '" class="regular-text" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="creator_bio">Biografia:</label></th>';
    echo '<td><textarea id="creator_bio" name="creator_bio" rows="5" class="large-text">' . esc_textarea($bio) . '</textarea></td>';
    echo '</tr>';
    echo '</table>';
}

// Zapisz meta pola twórcy
function toneka_save_creator_meta($post_id) {
    if (!isset($_POST['toneka_creator_meta_nonce']) || 
        !wp_verify_nonce($_POST['toneka_creator_meta_nonce'], 'toneka_creator_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['creator_role'])) {
        update_post_meta($post_id, '_creator_role', sanitize_text_field($_POST['creator_role']));
    }
    
    if (isset($_POST['creator_bio'])) {
        update_post_meta($post_id, '_creator_bio', sanitize_textarea_field($_POST['creator_bio']));
    }
}
add_action('save_post', 'toneka_save_creator_meta');

// Dodaj kolumny w liście twórców w adminie
function toneka_creator_columns($columns) {
    $columns['creator_role'] = 'Rola';
    $columns['products_count'] = 'Liczba produktów';
    return $columns;
}
add_filter('manage_creator_posts_columns', 'toneka_creator_columns');

function toneka_creator_column_content($column, $post_id) {
    switch ($column) {
        case 'creator_role':
            echo get_post_meta($post_id, '_creator_role', true);
            break;
        case 'products_count':
            $creator_name = get_the_title($post_id);
            $count = toneka_count_creator_products($creator_name);
            echo $count;
            break;
    }
}
add_action('manage_creator_posts_custom_column', 'toneka_creator_column_content', 10, 2);

// Funkcja do liczenia produktów twórcy
function toneka_count_creator_products($creator_name) {
    global $wpdb;
    
    $creator_types = array('_autors', '_obsada', '_rezyserzy', '_muzycy', '_tlumacze', '_adaptatorzy', '_wydawcy', '_grafika');
    $count = 0;
    
    foreach ($creator_types as $meta_key) {
        $results = $wpdb->get_results($wpdb->prepare("
            SELECT post_id FROM {$wpdb->postmeta} 
            WHERE meta_key = %s 
            AND meta_value LIKE %s
        ", $meta_key, '%' . $creator_name . '%'));
        
        $count += count($results);
    }
    
    return $count;
}

// Shortcode do wyświetlania listy produktów twórcy
function toneka_creator_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'creator_id' => get_the_ID(),
        'limit' => 10
    ), $atts);
    
    $creator_name = get_the_title($atts['creator_id']);
    $products = toneka_get_creator_products($creator_name, $atts['limit']);
    
    if (empty($products)) {
        return '<p>Brak produktów dla tego twórcy.</p>';
    }
    
    $output = '<div class="creator-products">';
    $output .= '<h3>Produkty autora: ' . esc_html($creator_name) . '</h3>';
    $output .= '<div class="products-grid">';
    
    foreach ($products as $product) {
        $output .= '<div class="product-item">';
        $output .= '<a href="' . get_permalink($product->ID) . '">';
        $output .= '<h4>' . esc_html($product->post_title) . '</h4>';
        $output .= '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}
add_shortcode('creator_products', 'toneka_creator_products_shortcode');

// Funkcja do pobierania produktów twórcy
function toneka_get_creator_products($creator_name, $limit = 10) {
    global $wpdb;
    
    $creator_types = array('_autors', '_obsada', '_rezyserzy', '_muzycy', '_tlumacze', '_adaptatorzy', '_wydawcy', '_grafika');
    $product_ids = array();
    
    foreach ($creator_types as $meta_key) {
        $results = $wpdb->get_results($wpdb->prepare("
            SELECT DISTINCT post_id FROM {$wpdb->postmeta} 
            WHERE meta_key = %s 
            AND meta_value LIKE %s
        ", $meta_key, '%' . $creator_name . '%'));
        
        foreach ($results as $result) {
            $product_ids[] = $result->post_id;
        }
    }
    
    if (empty($product_ids)) {
        return array();
    }
    
    $product_ids = array_unique($product_ids);
    
    return get_posts(array(
        'post_type' => 'product',
        'post__in' => $product_ids,
        'numberposts' => $limit,
        'post_status' => 'publish'
    ));
}

// Funkcja pomocnicza do użycia w szablonach - zamienia nazwiska na linki
function toneka_display_creators_with_links($creators_array, $role = '', $separator = ', ') {
    if (empty($creators_array) || !is_array($creators_array)) {
        return '';
    }
    
    $creators_with_links = toneka_make_creators_clickable($creators_array, $role);
    $names = array();
    
    foreach ($creators_with_links as $creator) {
        if (isset($creator['clickable_name'])) {
            $names[] = $creator['clickable_name'];
        }
    }
    
    return implode($separator, $names);
}

// Funkcja do masowego tworzenia stron twórców z istniejących produktów
function toneka_bulk_create_creator_pages() {
    $products = get_posts(array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish'
    ));
    
    $created_count = 0;
    
    foreach ($products as $product) {
        toneka_auto_create_creator_pages($product->ID);
        $created_count++;
    }
    
    return $created_count;
}

// Admin notice do uruchomienia masowego tworzenia stron
function toneka_creator_pages_admin_notice() {
    if (isset($_GET['toneka_create_creators']) && current_user_can('manage_options')) {
        $count = toneka_bulk_create_creator_pages();
        // Odśwież permalinki po utworzeniu stron
        flush_rewrite_rules();
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p>Utworzono strony twórców na podstawie ' . $count . ' produktów. Permalinki zostały odświeżone.</p>';
        echo '</div>';
    }
    
    if (current_user_can('manage_options') && !isset($_GET['toneka_create_creators'])) {
        echo '<div class="notice notice-info">';
        echo '<p>Chcesz utworzyć strony dla wszystkich twórców z produktów? ';
        echo '<a href="' . admin_url('admin.php?toneka_create_creators=1') . '" class="button">Utwórz strony twórców</a>';
        echo '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'toneka_creator_pages_admin_notice');

// Kompatybilność z Elementorem dla custom post type 'creator'
function toneka_elementor_support_for_creators() {
    // Dodaj support dla Elementor w creator post type
    add_post_type_support('creator', 'elementor');
}
add_action('init', 'toneka_elementor_support_for_creators');

// Dodaj creator do dostępnych post types w Elementorze - bezpieczniejsza wersja
function toneka_elementor_add_creator_cpt($cpts) {
    if (is_array($cpts)) {
        $cpts['creator'] = 'creator';
    }
    return $cpts;
}
add_filter('elementor/utils/get_public_post_types', 'toneka_elementor_add_creator_cpt');

?>
