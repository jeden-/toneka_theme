<?php

if ( ! function_exists( 'toneka_theme_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function toneka_theme_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'tonekatheme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'tonekatheme' ),
				'footer'  => esc_html__( 'Footer Menu', 'tonekatheme' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'toneka_theme_setup' );

/**
 * Register footer widget areas.
 */
function toneka_footer_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widget Area 2', 'tonekatheme' ),
			'id'            => 'footer-widget-2',
			'description'   => esc_html__( 'Add widgets here for the second footer column.', 'tonekatheme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widget Area 3', 'tonekatheme' ),
			'id'            => 'footer-widget-3',
			'description'   => esc_html__( 'Add widgets here for the third footer column.', 'tonekatheme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'toneka_footer_widgets_init' );

/**
 * Customize Register - Add scrolling text option
 */
function toneka_customize_register( $wp_customize ) {
	// Add Footer Section
	$wp_customize->add_section( 'toneka_footer_options', array(
		'title'    => __( 'Footer Options', 'tonekatheme' ),
		'priority' => 120,
	) );

	// Add Scrolling Text Setting
	$wp_customize->add_setting( 'toneka_scrolling_text', array(
		'default'           => 'CZYM JEST MUZYKA ? NIE WIEM. MOŻE PO PROSTU NIEBEM. Z NUTAMI ZAMIAST GWIAZD.',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	// Add Scrolling Text Control
	$wp_customize->add_control( 'toneka_scrolling_text', array(
		'label'       => __( 'Przewijający się tekst', 'tonekatheme' ),
		'description' => __( 'Tekst który będzie przewijał się w footerze między produktami a informacjami kontaktowymi.', 'tonekatheme' ),
		'section'     => 'toneka_footer_options',
		'type'        => 'textarea',
	) );

	// Add option to enable/disable scrolling text
	$wp_customize->add_setting( 'toneka_scrolling_text_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'toneka_scrolling_text_enabled', array(
		'label'   => __( 'Włącz przewijający się tekst', 'tonekatheme' ),
		'section' => 'toneka_footer_options',
		'type'    => 'checkbox',
	) );

	// Add scrolling speed setting
	$wp_customize->add_setting( 'toneka_scrolling_speed', array(
		'default'           => 50,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'toneka_scrolling_speed', array(
		'label'       => __( 'Prędkość przewijania (sekundy)', 'tonekatheme' ),
		'description' => __( 'Im większa liczba, tym wolniejsze przewijanie.', 'tonekatheme' ),
		'section'     => 'toneka_footer_options',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 20,
			'max'  => 100,
			'step' => 5,
		),
	) );
}
add_action( 'customize_register', 'toneka_customize_register' );

/**
 * Enqueue scripts and styles.
 */
function toneka_theme_scripts() {
	// Ładuj Google Fonts - tylko Figtree
	wp_enqueue_style( 'toneka-google-fonts', 'https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap', array(), null );
	
	// Główny arkusz stylów
	wp_enqueue_style( 'toneka-theme-style', get_stylesheet_uri(), array('toneka-google-fonts'), wp_get_theme()->get( 'Version' ) );

	// Skrypt nawigacji
	wp_enqueue_script(
		'toneka-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		'1.0.0',
		true
	);

	// Skrypt dla minikoszyka (jeśli istnieje)
	if (file_exists(get_template_directory() . '/js/minicart.js')) {
		wp_enqueue_script(
			'toneka-minicart',
			get_template_directory_uri() . '/js/minicart.js',
			array('jquery'),
			'1.0.0',
			true
		);
	}

	// Skrypt galerii produktów
	wp_enqueue_script(
		'toneka-gallery',
		get_template_directory_uri() . '/js/gallery.js',
		array(),
		'1.0.0',
		true
	);
	
	// Adaptacyjny header - dostosowuje się do jasności tła
	if ( is_product() ) {
		wp_enqueue_script(
			'toneka-adaptive-header',
			get_template_directory_uri() . '/js/adaptive-header.js',
			array(),
			'1.0.0',
			true
		);
	}
	
	// Przewijający się tekst w footerze
	if ( get_theme_mod( 'toneka_scrolling_text_enabled', true ) ) {
		wp_enqueue_script(
			'toneka-scrolling-text',
			get_template_directory_uri() . '/js/scrolling-text.js',
			array(),
			'1.0.0',
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'toneka_theme_scripts' );

/**
 * =================================================================================
 * System stron twórców
 * =================================================================================
 */

/**
 * Rejestruje Custom Post Type dla Twórców.
 */
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
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
        'can_export' => true,
    ));
}
add_action('init', 'toneka_register_creator_post_type');

/**
 * Rejestruje meta pola dla Twórców.
 */
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

/**
 * =================================================================================
 * Pola meta dla produktów WooCommerce
 * =================================================================================
 */

/**
 * Rejestruje wszystkie niestandardowe pola meta dla produktów.
 */
function toneka_register_product_meta() {
	$meta_fields = [
		'_rok_produkcji', '_czas_trwania', '_autor_title', '_autors', 
		'_obsada_title', '_obsada', '_rezyser_title', '_rezyserzy', 
		'_muzyka_title', '_muzycy', '_tlumacz_title', '_tlumacze', 
		'_adaptacja_title', '_adaptatorzy', '_wydawca_title', '_wydawcy', 
		'_grafika_title', '_grafika'
	];

	foreach ($meta_fields as $field) {
		register_post_meta('product', $field, [
			'show_in_rest' => true,
			'single' => true,
			'type' => is_string($field) && (strpos($field, 'title') === false) ? 'array' : 'string',
			'auth_callback' => function() { return current_user_can('edit_posts'); }
		]);
	}
}
add_action('init', 'toneka_register_product_meta');

/**
 * Dodaje niestandardowe pola do panelu edycji produktu WooCommerce.
 */
function toneka_add_custom_product_fields() {
	global $post;

	echo '<div class="options_group">';
	
	// Rok produkcji i Czas trwania
	woocommerce_wp_text_input([
		'id' => '_rok_produkcji',
		'label' => __('Rok produkcji', 'tonekatheme'),
		'type' => 'number',
	]);
	woocommerce_wp_text_input([
		'id' => '_czas_trwania',
		'label' => __('Czas trwania (min)', 'tonekatheme'),
		'type' => 'number',
	]);

	echo '</div>';

	// Pola typu "repeater" dla twórców
	$creator_fields = [
		'autor' => __('Autorzy', 'tonekatheme'),
		'obsada' => __('Obsada', 'tonekatheme'),
		'rezyser' => __('Reżyseria', 'tonekatheme'),
		'muzyk' => __('Muzyka', 'tonekatheme'),
		'tlumacz' => __('Tłumaczenie', 'tonekatheme'),
		'adaptator' => __('Adaptacja', 'tonekatheme'),
		'wydawca' => __('Wydawca', 'tonekatheme'),
		'grafika' => __('Grafika', 'tonekatheme'),
	];

	foreach ($creator_fields as $key => $label) {
		$field_id = '_' . $key . ($key === 'rezyser' ? 'zy' : ($key === 'muzyk' ? 'cy' : ($key === 'tlumacz' ? 'e' : ($key === 'adaptator' ? 'zy' : ($key === 'wydawca' ? 'y' : ($key === 'grafika' ? 'a' : 's'))))));
		$title_id = '_' . $key . '_title';

		echo '<div class="options_group">';
		woocommerce_wp_text_input([
			'id' => $title_id,
			'label' => __('Tytuł sekcji', 'tonekatheme') . ' ' . $label,
			'value' => get_post_meta($post->ID, $title_id, true) ?: $label,
		]);

		$items = get_post_meta($post->ID, $field_id, true);
		if (empty($items)) $items = [['imie_nazwisko' => '', 'rola' => '']];
		
		echo '<div id="' . $key . '_repeater">';
		foreach ($items as $index => $item) {
			echo '<div class="' . $key . '_group">';
			woocommerce_wp_text_input([
				'id' => $field_id . '[' . $index . '][imie_nazwisko]',
				'label' => __('Imię i nazwisko', 'tonekatheme'),
				'value' => $item['imie_nazwisko'] ?? '',
			]);
			if ($key === 'obsada') {
				woocommerce_wp_text_input([
					'id' => $field_id . '[' . $index . '][rola]',
					'label' => __('Rola', 'tonekatheme'),
					'value' => $item['rola'] ?? '',
				]);
			}
			echo '</div>';
		}
		echo '</div>'; // Repeater end
		echo '</div>'; // Options group end
	}
}
add_action('woocommerce_product_options_general_product_data', 'toneka_add_custom_product_fields');

/**
 * Zapisuje wartości niestandardowych pól produktu.
 */
function toneka_save_custom_product_fields($post_id) {
	$fields_to_save = [
		'_rok_produkcji', '_czas_trwania', '_autor_title', '_autors', 
		'_obsada_title', '_obsada', '_rezyser_title', '_rezyserzy', 
		'_muzyka_title', '_muzycy', '_tlumacz_title', '_tlumacze', 
		'_adaptacja_title', '_adaptatorzy', '_wydawca_title', '_wydawcy', 
		'_grafika_title', '_grafika'
	];
	
	foreach ($fields_to_save as $field) {
		if (isset($_POST[$field])) {
			$value = is_array($_POST[$field]) ? array_map('sanitize_text_field', $_POST[$field]) : sanitize_text_field($_POST[$field]);
			update_post_meta($post_id, $field, $value);
		}
	}
}
add_action('woocommerce_process_product_meta', 'toneka_save_custom_product_fields');

/**
 * =================================================================================
 * Automatyczne tworzenie stron twórców
 * =================================================================================
 */

/**
 * Tworzy "slug" (przyjazny URL) z imienia i nazwiska.
 */
function toneka_create_creator_slug($name) {
    $slug = sanitize_title($name);
    return $slug;
}

/**
 * Sprawdza, czy strona twórcy istnieje. Jeśli nie, tworzy ją.
 */
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
        if ($role) {
            update_post_meta($creator_id, '_creator_role', $role);
        }
        return $creator_id;
    }
    
    return false;
}

/**
 * Główna funkcja automatyzacji, uruchamiana przy zapisie posta.
 */
function toneka_auto_create_creator_pages($post_id) {
    // Upewnij się, że to produkt
    if (get_post_type($post_id) !== 'product') {
        return;
    }
    
    // Lista pól meta z twórcami i ich domyślne role
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

/**
 * =================================================================================
 * Niestandardowy selektor wariantów produktu
 * =================================================================================
 */

/**
 * Główna funkcja wyświetlająca niestandardowy selektor wariantów.
 * Zastępuje domyślny formularz WooCommerce.
 */
function toneka_output_variable_product_selector() {
    global $product;

    if (!$product || !$product->is_type('variable')) {
        return;
    }

    $available_variations = $product->get_available_variations();
    
    if (empty($available_variations)) {
        return;
    }

    // Dodajemy dane, które były wcześniej w widgecie
    foreach ($available_variations as $key => $variation_data) {
        $variation = wc_get_product($variation_data['variation_id']);
        if ($variation) {
            $available_variations[$key]['variation_description'] = $variation->get_description();
            if (method_exists($variation, 'get_variation_attributes')) {
                $available_variations[$key]['variation_display_name'] = implode(' / ', $variation->get_variation_attributes(true));
            } else {
                $available_variations[$key]['variation_display_name'] = $variation->get_name();
            }
        }
    }

    ?>
    <div class="toneka-carrier-selection-widget">
        <h3 class="toneka-carrier-title"><?php esc_html_e('Wybierz:', 'tonekatheme'); ?></h3>
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
        
        <div class="toneka-variation-info-container">
            <div class="toneka-variation-description-display"></div>
            <div class="toneka-variation-price-display"></div>
        </div>
        
        <script type="application/json" class="toneka-variations-data">
            <?php echo json_encode($available_variations); ?>
        </script>
        
        <div class="toneka-cart-section">
            <form class="toneka-cart-form" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                <div class="toneka-quantity-section">
                    <div class="toneka-quantity-wrapper">
                        <button type="button" class="toneka-quantity-minus">−</button>
                        <input type="number" id="toneka-quantity" class="toneka-quantity-input" name="quantity" value="1" min="1" />
                        <button type="button" class="toneka-quantity-plus">+</button>
                    </div>
                </div>
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="toneka-add-to-cart-button">
                    <?php echo esc_html($product->single_add_to_cart_text()); ?>
                </button>
                <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                <input type="hidden" name="variation_id" class="variation_id" value="" />
            </form>
        </div>
    </div>
    <?php
}

/**
 * Zastępuje domyślny formularz WooCommerce naszym niestandardowym selektorem.
 */
function toneka_replace_variable_add_to_cart() {
    remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
    add_action('woocommerce_variable_add_to_cart', 'toneka_output_variable_product_selector', 30);
}
add_action('woocommerce_before_single_product', 'toneka_replace_variable_add_to_cart');


/**
 * Ładuje skrypty JS dla selektora wariantów.
 */
function toneka_enqueue_variation_selector_assets() {
    if (is_product()) {
        wp_enqueue_script(
            'toneka-variation-selector', 
            get_template_directory_uri() . '/js/carrier-selection-new.js',
            array('jquery'), '1.0.0', true
        );
        
        wp_localize_script('toneka-variation-selector', 'toneka_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('toneka_ajax_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'toneka_enqueue_variation_selector_assets');

/**
 * Obsługuje dodawanie do koszyka przez AJAX.
 */
function toneka_ajax_add_to_cart() {
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id)) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        WC_AJAX::get_refreshed_fragments();
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        );
        wp_send_json($data);
    }
    wp_die();
}
add_action('wp_ajax_toneka_ajax_add_to_cart', 'toneka_ajax_add_to_cart');
add_action('wp_ajax_nopriv_toneka_ajax_add_to_cart', 'toneka_ajax_add_to_cart');

/**
 * =================================================================================
 * Niestandardowa galeria produktu
 * =================================================================================
 */

/**
 * Wyświetla niestandardową galerię zdjęć produktu.
 */
function toneka_show_product_images_custom() {
    global $product;

    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    
    // Zbierz wszystkie obrazy (główny + galeria)
    $all_images = array();
    
    if ( $main_image_id ) {
        $all_images[] = $main_image_id;
    }
    
    if ( $attachment_ids ) {
        $all_images = array_merge( $all_images, $attachment_ids );
    }

    echo '<div class="toneka-custom-gallery" data-images="' . count($all_images) . '">';
    
    if ( count($all_images) > 1 ) {
        // Galeria slideshow - więcej niż jeden obraz
        echo '<div class="toneka-gallery-slideshow">';
        
        foreach ( $all_images as $index => $image_id ) {
            $image_url = wp_get_attachment_image_url( $image_id, 'full' );
            $active_class = $index === 0 ? ' active' : '';
            echo '<div class="gallery-slide' . $active_class . '"><img src="' . esc_url( $image_url ) . '" alt=""></div>';
        }
        
        // Dodaj wskaźniki/dots dla slideshow
        if ( count($all_images) > 1 ) {
            echo '<div class="gallery-dots">';
            for ( $i = 0; $i < count($all_images); $i++ ) {
                $active_class = $i === 0 ? ' active' : '';
                echo '<span class="gallery-dot' . $active_class . '" data-slide="' . $i . '"></span>';
            }
            echo '</div>';
        }
        
        echo '</div>';
    } else if ( count($all_images) === 1 ) {
        // Pojedynczy obraz wyróżniający
        $image_url = wp_get_attachment_image_url( $all_images[0], 'full' );
        echo '<div class="toneka-featured-image"><img src="' . esc_url( $image_url ) . '" alt=""></div>';
    } else {
        // Brak obrazów - placeholder
        echo '<div class="toneka-no-image"><span>Brak obrazu</span></div>';
    }

    echo '</div>';
}
// Usuwamy domyślną galerię i dodajemy naszą
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'toneka_show_product_images_custom', 20 );

/**
 * =================================================================================
 * Funkcjonalność próbek audio/wideo
 * =================================================================================
 */

/**
 * Rejestruje meta pole dla próbek.
 */
function toneka_register_product_samples_meta() {
	register_post_meta('product', '_product_samples', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'array',
        'auth_callback' => function() { return current_user_can('edit_posts'); }
	]);
}
add_action('init', 'toneka_register_product_samples_meta');

/**
 * Dodaje zakładkę "Próbki" do panelu produktu.
 */
function toneka_add_product_samples_tab($tabs) {
	$tabs['samples'] = [
		'label'    => __('Próbki audio/wideo', 'tonekatheme'),
		'target'   => 'product_samples_data',
		'class'    => [],
		'priority' => 65,
	];
	return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'toneka_add_product_samples_tab');

/**
 * Wyświetla panel z próbkami w adminie.
 */
function toneka_product_samples_data_panel() {
	global $post;
	?>
	<div id="product_samples_data" class="panel woocommerce_options_panel">
		<div class="options_group">
			<div class="form-field downloadable_files">
				<label><?php _e('Próbki audio/wideo', 'tonekatheme'); ?></label>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php _e('Nazwa', 'tonekatheme'); ?></th>
							<th><?php _e('Adres URL pliku', 'tonekatheme'); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody class="toneka-samples-tbody">
						<?php
						$product_samples = get_post_meta($post->ID, '_product_samples', true);
						if (!empty($product_samples) && is_array($product_samples)) {
							foreach ($product_samples as $key => $file) {
                                toneka_render_sample_row($key, $file);
							}
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="3">
								<a href="#" class="button insert-sample-row"><?php _e('Dodaj plik', 'tonekatheme'); ?></a>
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
 * Renderuje pojedynczy wiersz w tabeli próbek w adminie.
 */
function toneka_render_sample_row($key, $file) {
    ?>
    <tr class="sample-row">
        <td>
            <input type="text" class="input_text" placeholder="<?php esc_attr_e('Nazwa próbki', 'tonekatheme'); ?>" name="_product_sample_names[]" value="<?php echo esc_attr($file['name'] ?? ''); ?>">
        </td>
        <td class="file_url_choose">
            <input type="text" class="input_text" placeholder="https://" name="_product_sample_files[]" value="<?php echo esc_attr($file['file'] ?? ''); ?>">
            <button type="button" class="button upload_sample_file_button"><?php echo esc_html__("Wybierz plik", "tonekatheme"); ?></button>
        </td>
        <td>
            <a href="#" class="delete button"><?php _e('Usuń', 'tonekatheme'); ?></a>
        </td>
    </tr>
    <?php
}

/**
 * Zapisuje dane próbek.
 */
function toneka_save_product_samples($post_id) {
	$samples = [];
	if (isset($_POST['_product_sample_files']) && isset($_POST['_product_sample_names'])) {
		$sample_names = $_POST['_product_sample_names'];
		$sample_files = $_POST['_product_sample_files'];

		for ($i = 0; $i < count($sample_files); $i++) {
			if (!empty($sample_files[$i])) {
				$samples[] = [
					'name' => wc_clean($sample_names[$i]),
					'file' => wc_clean($sample_files[$i]),
				];
			}
		}
	}
	update_post_meta($post_id, '_product_samples', $samples);
}
add_action('woocommerce_process_product_meta', 'toneka_save_product_samples');

/**
 * Ładuje skrypty JS dla panelu admina.
 */
function toneka_enqueue_admin_assets($hook) {
    global $post;

    if ($hook == 'post.php' && isset($post->post_type) && $post->post_type == 'product') {
        wp_enqueue_media();
        wp_enqueue_script(
            'toneka-admin-samples',
            get_template_directory_uri() . '/js/admin-samples.js',
            ['jquery'], '1.0.0', true
        );
    }
}
add_action('admin_enqueue_scripts', 'toneka_enqueue_admin_assets');


/**
 * Wyświetla player próbek na stronie produktu.
 */
function toneka_display_product_samples_player() {
    global $product;
    $samples = get_post_meta($product->get_id(), '_product_samples', true);

    if (empty($samples) || !is_array($samples)) {
        return;
    }

    $first_sample = $samples[0];
    $file_extension = strtolower(pathinfo($first_sample['file'], PATHINFO_EXTENSION));
    $video_extensions = ['mp4', 'mov', 'webm'];
    $is_video = in_array($file_extension, $video_extensions);
    ?>
    <div class="toneka-audio-player">
        <div class="toneka-audio-controls">
            <h3><?php _e('ODTWÓRZ FRAGMENT', 'tonekatheme'); ?></h3>
        </div>
        
        <div class="toneka-player-placeholder">
            <!-- Placeholder dla odtwarzacza -->
            <div class="toneka-player-background"></div>
        </div>
        
        <div class="toneka-player-interface">
            <div class="toneka-player-controls">
                <button type="button" class="toneka-play-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </button>
                <button type="button" class="toneka-play-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                </button>
                <button type="button" class="toneka-skip-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white" transform="rotate(180)">
                        <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                    </svg>
                </button>
            </div>
            
            <div class="toneka-track-info">
                <div class="toneka-track-title"><?php echo strtoupper($product->get_name()); ?></div>
                <div class="toneka-track-time">0:10 /0:40</div>
            </div>
            
            <div class="toneka-volume-controls">
                <button type="button" class="toneka-volume-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                    </svg>
                </button>
                <button type="button" class="toneka-volume-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM19 12c0 2.53-1.5 4.71-3.66 5.75l-.67-1.5c1.38-.69 2.33-2.1 2.33-3.75s-.95-3.06-2.33-3.75l.67-1.5C17.5 7.29 19 9.47 19 12z"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Ukryty HTML5 audio player -->
        <?php if (!$is_video): ?>
            <audio id="main-player" src="<?php echo esc_url($first_sample['file']); ?>" style="display: none;"></audio>
        <?php endif; ?>
        <?php if (count($samples) > 1): ?>
        <div class="playlist-container">
            <ul class="samples-playlist">
                <?php foreach ($samples as $index => $sample):
                    if (empty($sample['file'])) continue;
                    $display_name = !empty($sample['name']) ? $sample['name'] : basename($sample['file']);
                    ?>
                    <li class="sample-item <?php echo ($index === 0) ? 'active' : ''; ?>"
                        data-src="<?php echo esc_url($sample['file']); ?>">
                        <span class="sample-name"><?php echo esc_html($display_name); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="playlist-counter">1/<?php echo count($samples); ?></div>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * =================================================================================
 * Wyświetlanie metadanych produktu
 * =================================================================================
 */

/**
 * Wyświetla metadane produktu w sekcji informacji
 */
function toneka_display_product_metadata() {
    global $product;
    
    if (!$product) return;
    
    $product_id = $product->get_id();
    
    // Pobierz metadane
    $rok_produkcji = get_post_meta($product_id, '_rok_produkcji', true);
    $czas_trwania = get_post_meta($product_id, '_czas_trwania', true);
    $autors = get_post_meta($product_id, '_autors', true);
    $tlumacze = get_post_meta($product_id, '_tlumacze', true);
    $adaptatorzy = get_post_meta($product_id, '_adaptatorzy', true);
    $rezyserzy = get_post_meta($product_id, '_rezyserzy', true);
    $obsada = get_post_meta($product_id, '_obsada', true);
    $muzycy = get_post_meta($product_id, '_muzycy', true);
    $wydawcy = get_post_meta($product_id, '_wydawcy', true);
    
    ?>
    <div class="toneka-product-metadata">
        <!-- Tytuł produktu -->
        <div class="toneka-title-row">
            <h1><?php the_title(); ?></h1>
        </div>
        
        <!-- Typ produktu -->
        <div class="toneka-meta-row">
            <div class="toneka-meta-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                </svg>
            </div>
            <div class="toneka-meta-label">SŁUCHOWISKO</div>
            <?php if ($czas_trwania): ?>
                <div class="toneka-meta-value"><?php echo esc_html($czas_trwania); ?> min</div>
            <?php endif; ?>
        </div>
        
        <?php if ($autors && is_array($autors)): ?>
        <div class="toneka-meta-row">
            <div class="toneka-meta-label">autor:</div>
            <div class="toneka-meta-value">
                <?php echo esc_html(implode(', ', array_column($autors, 'imie_nazwisko'))); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($tlumacze && is_array($tlumacze)): ?>
        <div class="toneka-meta-row">
            <div class="toneka-meta-label">tłumacz:</div>
            <div class="toneka-meta-value">
                <?php echo esc_html(implode(', ', array_column($tlumacze, 'imie_nazwisko'))); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($adaptatorzy && is_array($adaptatorzy)): ?>
        <div class="toneka-meta-row">
            <div class="toneka-meta-label">adaptacja tekstu:</div>
            <div class="toneka-meta-value">
                <?php echo esc_html(implode(', ', array_column($adaptatorzy, 'imie_nazwisko'))); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($rezyserzy && is_array($rezyserzy)): ?>
        <div class="toneka-meta-row">
            <div class="toneka-meta-label">reżyseria:</div>
            <div class="toneka-meta-value">
                <?php echo esc_html(implode(', ', array_column($rezyserzy, 'imie_nazwisko'))); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($obsada && is_array($obsada)): ?>
        <div class="toneka-meta-row">
            <div class="toneka-meta-label">obsada:</div>
            <div class="toneka-meta-value">
                <?php echo esc_html(implode(', ', array_column($obsada, 'imie_nazwisko'))); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($muzycy && is_array($muzycy)): ?>
        <div class="toneka-meta-row">
            <div class="toneka-meta-label">muzyka:</div>
            <div class="toneka-meta-value">
                <?php echo esc_html(implode(', ', array_column($muzycy, 'imie_nazwisko'))); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="toneka-meta-row toneka-meta-row-split">
            <?php if ($wydawcy && is_array($wydawcy)): ?>
            <div class="toneka-meta-half">
                <span class="toneka-meta-label">wydawca:</span>
                <span class="toneka-meta-value"><?php echo esc_html(implode(', ', array_column($wydawcy, 'imie_nazwisko'))); ?></span>
            </div>
            <?php endif; ?>
            <div class="toneka-meta-half">
                <span class="toneka-meta-label">data wydania:</span>
                <span class="toneka-meta-value"><?php echo get_the_date('d.m.Y'); ?></span>
            </div>
        </div>
        
        <!-- Opis produktu -->
        <?php if ($product->get_short_description()): ?>
        <div class="toneka-description-row">
            <div class="toneka-product-description">
                <p><span class="toneka-meta-label">Opis:</span> <?php echo wp_kses_post($product->get_short_description()); ?></p>
            </div>
            <?php if ($product->get_description()): ?>
            <div class="toneka-more-link animated-arrow-button">
                <span>WIĘCEJ</span>
                <div class="button-arrow">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/>
                        <path d="M11 9.67383L11 0.836618" stroke="white" stroke-linecap="round"/>
                        <path d="M11 0.836426L2.04334 0.836427" stroke="white" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Tagi -->
        <?php 
        $tags = get_the_terms($product_id, 'product_tag');
        if ($tags && !is_wp_error($tags)): 
        ?>
        <div class="toneka-meta-row toneka-tags-row">
            <div class="toneka-meta-label">
                tagi: <?php echo implode(', ', array_map(function($tag) { return $tag->name; }, $tags)); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * =================================================================================
 * Produkty powiązane
 * =================================================================================
 */

/**
 * Wyświetla produkty powiązane w siatce 3x2
 */
function toneka_display_related_products() {
    global $product;
    
    if (!$product) return;
    
    // Pobierz produkty powiązane
    $related_ids = wc_get_related_products($product->get_id(), 6);
    
    if (empty($related_ids)) {
        // Jeśli brak produktów powiązanych, pokaż losowe produkty
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 6,
            'orderby' => 'rand',
            'post__not_in' => array($product->get_id()),
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                )
            )
        );
        $related_products = get_posts($args);
    } else {
        $related_products = array_map('wc_get_product', $related_ids);
    }
    
    if (empty($related_products)) return;
    
    ?>
    <div class="toneka-products-grid">
        <?php 
        // Ograniczamy do 6 produktów i wyświetlamy w siatce 3x2
        $displayed = 0;
        foreach ($related_products as $related_product) {
            if ($displayed >= 6) break;
            
            if (is_object($related_product)) {
                $product_obj = $related_product;
            } else {
                $product_obj = wc_get_product($related_product->ID);
            }
            
            if (!$product_obj) continue;
            
            // Określ kategorię produktu
            $categories = get_the_terms($product_obj->get_id(), 'product_cat');
            $category_name = 'PRODUKT';
            if ($categories && !is_wp_error($categories)) {
                $category_name = strtoupper($categories[0]->name);
            }
            
            // Pobierz główny obraz produktu
            $image_id = $product_obj->get_image_id();
            $image_url = wp_get_attachment_image_url($image_id, 'large');
            
            ?>
            <div class="toneka-product-card">
                <div class="toneka-product-category">
                    <?php echo esc_html($category_name); ?>
                </div>
                
                <div class="toneka-product-image">
                    <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product_obj->get_name()); ?>">
                    <?php else: ?>
                        <div class="toneka-product-placeholder">
                            <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                                <rect width="200" height="200" fill="#333"/>
                                <text x="100" y="100" text-anchor="middle" fill="white" font-size="14">BRAK ZDJĘCIA</text>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="toneka-product-footer">
                    <div class="toneka-product-buy">
                        <a href="<?php echo esc_url($product_obj->get_permalink()); ?>">KUP</a>
                    </div>
                    <div class="toneka-product-price">
                        <?php echo $product_obj->get_price_html(); ?>
                    </div>
                </div>
            </div>
            <?php
            $displayed++;
        }
        ?>
    </div>
    <?php
}

/**
 * =================================================================================
 * AJAX funkcje dla header'a
 * =================================================================================
 */

/**
 * Zwraca aktualną liczbę produktów w koszyku
 */
function toneka_get_cart_count() {
    if (class_exists('WooCommerce')) {
        wp_send_json_success(array(
            'count' => WC()->cart->get_cart_contents_count()
        ));
    } else {
        wp_send_json_error('WooCommerce not active');
    }
}
add_action('wp_ajax_toneka_get_cart_count', 'toneka_get_cart_count');
add_action('wp_ajax_nopriv_toneka_get_cart_count', 'toneka_get_cart_count');

/**
 * Aktualizuje licznik koszyka po dodaniu produktu
 */
function toneka_update_cart_count_fragment($fragments) {
    if (class_exists('WooCommerce')) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $fragments['.cart-count'] = '<span class="cart-count">' . esc_html($cart_count) . '</span>';
    }
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'toneka_update_cart_count_fragment');

/**
 * =================================================================================
 * Usuwanie domyślnych WooCommerce elementów
 * =================================================================================
 */

// Usuń domyślne WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Usuń breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Customowe breadcrumbs - zmień "Strona główna" na "WSZYSTKO"
function custom_woocommerce_breadcrumbs() {
    woocommerce_breadcrumb(array(
        'delimiter' => ' / ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb">',
        'wrap_after' => '</nav>',
        'before' => '',
        'after' => '',
        'home' => 'WSZYSTKO'
    ));
}

// Filtr do zmiany linku home w breadcrumbs na stronę sklepu
add_filter('woocommerce_breadcrumb_home_url', function($url) {
    return wc_get_page_permalink('shop');
});

// Filtr do zmiany tekstu home w breadcrumbs
add_filter('woocommerce_breadcrumb_defaults', function($defaults) {
    $defaults['home'] = 'WSZYSTKO';
    return $defaults;
});

// Usuń domyślne zakładki produktu
add_filter( 'woocommerce_product_tabs', '__return_empty_array' );

// Usuń domyślne informacje o produkcie
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
