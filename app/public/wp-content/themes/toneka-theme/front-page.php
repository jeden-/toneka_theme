<?php
/**
 * Template for displaying the front page (home page)
 */

get_header(); ?>

<!-- Hero Slider Section -->
<div class="toneka-hero-slider" 
     data-autoplay="<?php echo get_theme_mod('toneka_slider_autoplay', true) ? 'true' : 'false'; ?>"
     data-duration="<?php echo get_theme_mod('toneka_slider_duration', 5000); ?>"
     data-transition="<?php echo get_theme_mod('toneka_slider_transition', 'fade'); ?>">
    
    <!-- Slider Container -->
    <div class="toneka-slider-container">
        <?php
        // Pobierz ustawienia slajdów
        $slider_autoplay = get_theme_mod('toneka_slider_autoplay', true);
        $slider_duration = get_theme_mod('toneka_slider_duration', 5000);
        $slider_transition = get_theme_mod('toneka_slider_transition', 'fade');
        
        // Slider 1
        $slide1_image = get_theme_mod('toneka_slider_1_image');
        $slide1_title = get_theme_mod('toneka_slider_1_title', 'Melodie jak fale, co w stronę marzeń płyną');
        $slide1_subtitle = get_theme_mod('toneka_slider_1_subtitle', 'a dźwięki to słowa, co w ciszy z duszą śpiewają');
        $slide1_button_text = get_theme_mod('toneka_slider_1_button_text', 'ostatnio dodane');
        $slide1_button_url = get_theme_mod('toneka_slider_1_button_url', wc_get_page_permalink('shop'));
        
        // Slider 2
        $slide2_image = get_theme_mod('toneka_slider_2_image');
        $slide2_title = get_theme_mod('toneka_slider_2_title', 'Odkryj magiczny świat słuchowisk');
        $slide2_subtitle = get_theme_mod('toneka_slider_2_subtitle', 'gdzie każda historia ma swój unikalny głos');
        $slide2_button_text = get_theme_mod('toneka_slider_2_button_text', 'przeglądaj');
        $slide2_button_url = get_theme_mod('toneka_slider_2_button_url', wc_get_page_permalink('shop'));
        
        // Slider 3
        $slide3_image = get_theme_mod('toneka_slider_3_image');
        $slide3_title = get_theme_mod('toneka_slider_3_title', 'Toneka - gdzie muzyka spotyka się z opowieścią');
        $slide3_subtitle = get_theme_mod('toneka_slider_3_subtitle', 'dołącz do naszej społeczności melomanów');
        $slide3_button_text = get_theme_mod('toneka_slider_3_button_text', 'dowiedz się więcej');
        $slide3_button_url = get_theme_mod('toneka_slider_3_button_url', wc_get_page_permalink('shop'));
        
        // Wyświetl slajdy tylko jeśli mają zdjęcia
        $slides = array();
        if ($slide1_image) $slides[] = array('image' => $slide1_image, 'title' => $slide1_title, 'subtitle' => $slide1_subtitle, 'button_text' => $slide1_button_text, 'button_url' => $slide1_button_url);
        if ($slide2_image) $slides[] = array('image' => $slide2_image, 'title' => $slide2_title, 'subtitle' => $slide2_subtitle, 'button_text' => $slide2_button_text, 'button_url' => $slide2_button_url);
        if ($slide3_image) $slides[] = array('image' => $slide3_image, 'title' => $slide3_title, 'subtitle' => $slide3_subtitle, 'button_text' => $slide3_button_text, 'button_url' => $slide3_button_url);
        
        // Jeśli nie ma żadnych slajdów, pokaż domyślny hero
        if (empty($slides)) {
            // Fallback do starego hero
            $hero_image = get_theme_mod('toneka_homepage_hero_image');
            if ($hero_image) {
                echo '<div class="toneka-hero-section">';
                echo '<div class="toneka-hero-left">';
                echo '<div class="toneka-hero-content">';
                echo '<div class="woocommerce-breadcrumb"><a href="' . esc_url(home_url('/')) . '">WSZYSTKO</a> / STRONA GŁÓWNA</div>';
                echo '<div class="toneka-product-title"><h1>' . esc_html(strtoupper(get_theme_mod('toneka_homepage_hero_text', 'Melodie jak fale, co w stronę marzeń płyną, a dźwięki to słowa, co w ciszy z duszą śpiewają'))) . '</h1></div>';
                echo '<a href="' . esc_url(get_theme_mod('toneka_homepage_button_url', wc_get_page_permalink('shop'))) . '" class="toneka-listen-button toneka-filter-button animated-arrow-button">';
                echo '<span class="button-text">' . esc_html(strtoupper(get_theme_mod('toneka_homepage_button_text', 'ostatnio dodane'))) . '</span>';
                echo '<div class="button-arrow"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/><path d="M11 9.67383L11 0.836618" stroke="white" stroke-linecap="round"/><path d="M11 0.836426L2.04334 0.836427" stroke="white" stroke-linecap="round"/></svg></div>';
                echo '</a></div></div>';
                echo '<div class="toneka-hero-right"><img src="' . esc_url($hero_image) . '" alt="Hero Image" class="toneka-hero-image" style="width: 100%; height: 100%; object-fit: cover;" /></div>';
                echo '</div>';
            } else {
                echo '<div class="toneka-hero-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #666666; border: 2px dashed #333333;">Dodaj zdjęcie w Dostosuj → Hero Slider</div>';
            }
        } else {
            // Wyświetl slajdy z oryginalnym layoutem 50/50
            foreach ($slides as $index => $slide) {
                $active_class = $index === 0 ? ' active' : '';
                echo '<div class="toneka-slide toneka-hero-section' . $active_class . '" data-slide="' . $index . '">';
                echo '<div class="toneka-hero-left">';
                echo '<div class="toneka-hero-content">';
                echo '<div class="woocommerce-breadcrumb"><a href="' . esc_url(home_url('/')) . '">WSZYSTKO</a> / STRONA GŁÓWNA</div>';
                echo '<div class="toneka-product-title"><h1>' . esc_html(strtoupper($slide['title'])) . '</h1></div>';
                if ($slide['subtitle']) {
                    echo '<div class="toneka-slide-subtitle"><p>' . esc_html($slide['subtitle']) . '</p></div>';
                }
                echo '<a href="' . esc_url($slide['button_url']) . '" class="toneka-listen-button toneka-filter-button animated-arrow-button">';
                echo '<span class="button-text">' . esc_html(strtoupper($slide['button_text'])) . '</span>';
                echo '<div class="button-arrow"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/><path d="M11 9.67383L11 0.836618" stroke="white" stroke-linecap="round"/><path d="M11 0.836426L2.04334 0.836427" stroke="white" stroke-linecap="round"/></svg></div>';
                echo '</a></div></div>';
                echo '<div class="toneka-hero-right">';
                echo '<img src="' . esc_url($slide['image']) . '" alt="' . esc_attr($slide['title']) . '" class="toneka-hero-image" style="width: 100%; height: 100%; object-fit: cover;" />';
                echo '</div></div>';
            }
        }
        ?>
    </div>
    
    <!-- Navigation Controls -->
    <?php if (!empty($slides) && count($slides) > 1): ?>
    <div class="toneka-slider-controls">
        <button class="toneka-slider-prev" aria-label="Poprzedni slajd">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <button class="toneka-slider-next" aria-label="Następny slajd">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18L15 12L9 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
    
    <!-- Dots Navigation -->
    <div class="toneka-slider-dots">
        <?php foreach ($slides as $index => $slide): ?>
            <button class="toneka-slider-dot<?php echo $index === 0 ? ' active' : ''; ?>" data-slide="<?php echo $index; ?>" aria-label="Przejdź do slajdu <?php echo $index + 1; ?>"></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
    
<!-- Main Content Area -->
<div class="toneka-category-page">
    <div class="toneka-category-content">
        
        <!-- Najnowsze Słuchowiska Section -->
        <div class="toneka-category-title">
            <h2>NAJNOWSZE SŁUCHOWISKA</h2>
        </div>
        
        <!-- Słuchowiska Grid - 3 produkty w jednym rzędzie -->
        <div class="toneka-products-grid toneka-category-products-grid toneka-homepage-audio-grid">
            <?php
            // Check if WooCommerce is active and get latest audio products
            if (function_exists('wc_get_products')) {
                $audio_products = wc_get_products(array(
                    'limit' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'status' => 'publish',
                    'category' => array('audio', 'słuchowiska', 'muzyka') // Assuming audio category exists
                ));
                
                // If no specific audio category, get latest products
                if (empty($audio_products)) {
                    $audio_products = wc_get_products(array(
                        'limit' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'status' => 'publish'
                    ));
                }
                
                if ($audio_products) {
                    foreach ($audio_products as $product) {
                        // Use new product card function
                        echo toneka_render_product_card($product->get_id());
                    }
                } else {
                    echo '<div class="toneka-no-products"><p>Brak słuchowisk do wyświetlenia.</p></div>';
                }
            } else {
                echo '<div class="toneka-no-products"><p>WooCommerce nie jest aktywne.</p></div>';
            }
            ?>
        </div>
        
        <!-- Merch Section -->
        <div class="toneka-category-title">
            <h2>MERCH</h2>
        </div>
        
        <!-- Merch Grid - 3 produkty w jednym rzędzie -->
        <div class="toneka-products-grid toneka-category-products-grid toneka-homepage-merch-grid">
            <?php
            // Check if WooCommerce is active and get merch products
            if (function_exists('wc_get_products')) {
                $merch_products = wc_get_products(array(
                    'limit' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'status' => 'publish',
                    'category' => array('merch', 'gadżety', 'ubrania') // Assuming merch category exists
                ));
                
                // If no specific merch category, get products with "merch" in name
                if (empty($merch_products)) {
                    $merch_products = wc_get_products(array(
                        'limit' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'status' => 'publish',
                        'search' => 'merch'
                    ));
                }
                
                // If still no merch products, get latest products as fallback
                if (empty($merch_products)) {
                    $merch_products = wc_get_products(array(
                        'limit' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'status' => 'publish'
                    ));
                }
                
                if ($merch_products) {
                    foreach ($merch_products as $product) {
                        // Use new product card function
                        echo toneka_render_product_card($product->get_id());
                    }
                } else {
                    echo '<div class="toneka-no-products"><p>Brak produktów merch do wyświetlenia.</p></div>';
                }
            } else {
                echo '<div class="toneka-no-products"><p>WooCommerce nie jest aktywne.</p></div>';
            }
            ?>
        </div>
        
    </div>
</div>

<?php get_footer(); ?>
