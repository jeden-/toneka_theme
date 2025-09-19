<?php
/**
 * TONEKA THEME - WOOCOMMERCE IMPROVEMENTS
 * 
 * Dodatkowe funkcje i ulepszenia dla WooCommerce
 * - Ulepszone pola produktu
 * - Dodatkowe metadane dla słuchowisk
 * - Ulepszony player audio
 * - Responsywne style
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// ==================================================================
// IMPROVED PRODUCT FIELDS FOR AUDIOBOOKS/PODCASTS
// ==================================================================

/**
 * Dodaje dodatkowe pola meta dla produktów audio
 */
function toneka_add_enhanced_product_fields() {
    global $post;

    echo '<div class="options_group">';
    echo '<h3>' . __('Informacje o słuchowisku', 'tonekatheme') . '</h3>';

    // Gatunek/Kategoria
    woocommerce_wp_select([
        'id' => '_audio_genre',
        'label' => __('Gatunek', 'tonekatheme'),
        'options' => [
            '' => __('Wybierz gatunek', 'tonekatheme'),
            'sluchowisko' => __('Słuchowisko', 'tonekatheme'),
            'audiobook' => __('Audiobook', 'tonekatheme'),
            'podcast' => __('Podcast', 'tonekatheme'),
            'muzyka' => __('Muzyka', 'tonekatheme'),
            'poezja' => __('Poezja', 'tonekatheme'),
        ],
        'value' => get_post_meta($post->ID, '_audio_genre', true)
    ]);

    // Czas trwania (w minutach)
    woocommerce_wp_text_input([
        'id' => '_duration_minutes',
        'label' => __('Czas trwania (minuty)', 'tonekatheme'),
        'type' => 'number',
        'custom_attributes' => ['min' => '1', 'step' => '1'],
        'value' => get_post_meta($post->ID, '_duration_minutes', true)
    ]);

    // Rok produkcji
    woocommerce_wp_text_input([
        'id' => '_production_year',
        'label' => __('Rok produkcji', 'tonekatheme'),
        'type' => 'number',
        'custom_attributes' => ['min' => '1900', 'max' => date('Y') + 5],
        'value' => get_post_meta($post->ID, '_production_year', true)
    ]);

    // Język nagrania
    woocommerce_wp_select([
        'id' => '_audio_language',
        'label' => __('Język nagrania', 'tonekatheme'),
        'options' => [
            '' => __('Wybierz język', 'tonekatheme'),
            'pl' => __('Polski', 'tonekatheme'),
            'en' => __('Angielski', 'tonekatheme'),
            'de' => __('Niemiecki', 'tonekatheme'),
            'fr' => __('Francuski', 'tonekatheme'),
            'es' => __('Hiszpański', 'tonekatheme'),
            'other' => __('Inny', 'tonekatheme'),
        ],
        'value' => get_post_meta($post->ID, '_audio_language', true)
    ]);

    // Ocena treści
    woocommerce_wp_select([
        'id' => '_content_rating',
        'label' => __('Ocena treści', 'tonekatheme'),
        'options' => [
            '' => __('Nie określono', 'tonekatheme'),
            'all' => __('Dla wszystkich', 'tonekatheme'),
            '7+' => __('7+', 'tonekatheme'),
            '12+' => __('12+', 'tonekatheme'),
            '16+' => __('16+', 'tonekatheme'),
            '18+' => __('18+', 'tonekatheme'),
        ],
        'value' => get_post_meta($post->ID, '_content_rating', true)
    ]);

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'toneka_add_enhanced_product_fields', 15);

/**
 * Zapisuje dodatkowe pola produktu
 */
function toneka_save_enhanced_product_fields($post_id) {
    $fields = [
        '_audio_genre',
        '_duration_minutes', 
        '_production_year',
        '_audio_language',
        '_content_rating'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('woocommerce_process_product_meta', 'toneka_save_enhanced_product_fields');

// ==================================================================
// ENHANCED PRODUCT DISPLAY FUNCTIONS
// ==================================================================

/**
 * Ulepszony wyświetlacz informacji o produkcie
 */
function toneka_display_enhanced_product_info() {
    global $product;
    
    $product_id = $product->get_id();
    
    // Zbieranie danych
    $duration = get_post_meta($product_id, '_duration_minutes', true);
    $year = get_post_meta($product_id, '_production_year', true);
    
    // Custom fields from existing system
    $autor_title = get_post_meta($product_id, '_autor_title', true) ?: __('Autorzy', 'tonekatheme');
    $autor = get_post_meta($product_id, '_autors', true);
    
    // Nowe pola do pobrania
    $tlumaczenie_title = get_post_meta($product_id, '_tlumaczenie_title', true) ?: __('Tłumaczenie', 'tonekatheme');
    $tlumaczenie = get_post_meta($product_id, '_tlumaczenie', true);
    
    $adaptacja_title = get_post_meta($product_id, '_adaptacja_title', true) ?: __('Adaptacja tekstu', 'tonekatheme');
    $adaptacja = get_post_meta($product_id, '_adaptacja', true);
    
    $rezyser_title = get_post_meta($product_id, '_rezyser_title', true) ?: __('Reżyseria', 'tonekatheme');
    $rezyser = get_post_meta($product_id, '_rezyserzy', true);
    
    $obsada_title = get_post_meta($product_id, '_obsada_title', true) ?: __('Obsada', 'tonekatheme');
    $obsada = get_post_meta($product_id, '_obsada', true);

    $muzyka_title = get_post_meta($product_id, '_muzyka_title', true) ?: __('Muzyka', 'tonekatheme');
    $muzyka = get_post_meta($product_id, '_muzyka', true);

    $grafika_title = get_post_meta($product_id, '_grafika_title', true) ?: __('Grafika', 'tonekatheme');
    $grafika = get_post_meta($product_id, '_grafika', true);

    $wydawca_title = get_post_meta($product_id, '_wydawca_title', true) ?: __('Wydawca', 'tonekatheme');
    $wydawca = get_post_meta($product_id, '_wydawca', true);
    
    $rating_info = get_post_meta($product_id, '_content_rating', true);
    $genre_info = get_post_meta($product_id, '_audio_genre', true);

    ?>
    <div class="product-details-wrapper">
        <div class="product-header">
            <h1 class="product-title"><?php echo $product->get_name(); ?></h1>
            <?php if ($duration): ?>
                <span class="product-duration"><?php echo esc_html($duration); ?> MIN</span>
            <?php endif; ?>
        </div>
        
        <div class="product-meta-list">
            <?php if ($rating_info || $genre_info): ?>
            <div class="meta-row meta-summary">
                <?php 
                $summary = [];
                if ($rating_info) $summary[] = $rating_info;
                if ($genre_info) $summary[] = ucfirst($genre_info);
                echo implode(', ', $summary);
                ?>
            </div>
            <?php endif; ?>

            <?php
            // Helper function to render a meta row
            $render_meta_row = function($label, $people) {
                if (!empty($people) && is_array($people)) {
                    $names = array_map(function($p) {
                        $name = $p['imie_nazwisko'] ?? '';
                        $role = $p['rola'] ?? '';
                        return $role ? "{$name} - {$role}" : $name;
                    }, $people);
                    $value = implode(', ', array_filter($names));
                    if (!empty($value)) {
                        echo '<div class="meta-row">';
                        echo '<span class="meta-label">' . esc_html(strtoupper($label)) . ':</span> ';
                        echo '<span class="meta-value">' . esc_html($value) . '</span>';
                        echo '</div>';
                    }
                }
            };

            $render_meta_row($autor_title, $autor);
            $render_meta_row($tlumaczenie_title, $tlumaczenie);
            $render_meta_row($adaptacja_title, $adaptacja);
            $render_meta_row($rezyser_title, $rezyser);
            $render_meta_row($obsada_title, $obsada);
            $render_meta_row($muzyka_title, $muzyka);
            $render_meta_row($grafika_title, $grafika);
            ?>
            
            <div class="meta-row-split">
                <div class="meta-row">
                    <span class="meta-label"><?php echo esc_html(strtoupper($wydawca_title)); ?>:</span>
                    <span class="meta-value">
                         <?php 
                         if (!empty($wydawca) && is_array($wydawca)) {
                             $wydawca_names = array_map(function($w) { return $w['imie_nazwisko'] ?? ''; }, $wydawca);
                             echo esc_html(implode(', ', array_filter($wydawca_names))); 
                         }
                         ?>
                    </span>
                </div>
                <?php if ($year): ?>
                <div class="meta-row">
                    <span class="meta-label"><?php _e('ROK WYDANIA', 'tonekatheme'); ?>:</span>
                    <span class="meta-value"><?php echo esc_html($year); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-description-wrapper">
            <div class="product-description-content">
                <?php echo apply_filters('the_content', $product->get_description()); ?>
            </div>
            <button class="toggle-description">WIĘCEJ <span class="arrow">↓</span></button>
        </div>
    </div>
    <?php
}

// ==================================================================
// ENHANCED VARIATION SELECTOR
// ==================================================================

/**
 * Ulepszony selektor wariantów produktu
 */
function toneka_enhanced_variation_selector() {
    global $product;
    
    if (!$product->is_type('variable')) {
        // Handle simple products
        ?>
        <div class="toneka-purchase-section">
            <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                <div class="price-wrapper">
                    <p class="price"><?php echo $product->get_price_html(); ?></p>
                </div>
                <div class="actions-wrapper">
                    <?php woocommerce_quantity_input(['min_value' => 1, 'max_value' => $product->get_stock_quantity()], $product, true); ?>
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
                </div>
            </form>
        </div>
        <?php
        return;
    }
    
    $variations = $product->get_available_variations();
    
    if (empty($variations)) {
        return;
    }
    
    ?>
    <div class="toneka-purchase-section">
        <h3 class="purchase-title"><?php _e('WYBIERZ:', 'tonekatheme'); ?></h3>
        
        <form class="variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo htmlspecialchars(wp_json_encode($variations)); ?>">
            
            <div class="variation-options">
                <?php foreach ($variations as $variation): ?>
                    <?php
                    $variation_obj = wc_get_product($variation['variation_id']);
                    if (!$variation_obj) continue;
                    $attribute_name = array_values($variation['attributes'])[0];
                    ?>
                    <div class="variation-radio">
                        <input 
                            type="radio" 
                            name="variation_id_radio" 
                            value="<?php echo esc_attr($variation['variation_id']); ?>" 
                            id="variation-<?php echo esc_attr($variation['variation_id']); ?>"
                            data-price_html="<?php echo esc_attr($variation['price_html']); ?>"
                            <?php checked(1, 2); ?>
                        >
                        <label for="variation-<?php echo esc_attr($variation['variation_id']); ?>"><?php echo esc_html($attribute_name); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="variation-info">
                <p class="variation-description-text"></p>
                <div class="woocommerce-variation-price"></div>
            </div>

            <div class="woocommerce-variation-add-to-cart variations_button">
                <div class="quantity-wrapper">
                    <?php
                    woocommerce_quantity_input([
                        'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                        'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                        'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                    ]);
                    ?>
                </div>
                <button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
            </div>
            
            <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
            <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
            <input type="hidden" name="variation_id" class="variation_id" value="0" />

        </form>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $form = $('.variations_form');
        var $variationRadios = $form.find('input[name="variation_id_radio"]');
        var $priceContainer = $form.find('.woocommerce-variation-price');
        var $descriptionContainer = $form.find('.variation-description-text');
        var $hiddenVariationInput = $form.find('input.variation_id');

        // Initial state from first available variation
        var initialVariationId = $variationRadios.first().val();
        $variationRadios.first().prop('checked', true);
        $hiddenVariationInput.val(initialVariationId).trigger('change');
        
        var variationsData = $form.data('product_variations');

        function updateDisplay(variationId) {
            var variation = variationsData.find(v => v.variation_id == variationId);
            if (variation) {
                $priceContainer.html(variation.price_html);
                
                var desc = variation.variation_description;
                if(desc) {
                    $descriptionContainer.html(desc).show();
                } else {
                    $descriptionContainer.empty().hide();
                }
            }
        }
        
        updateDisplay(initialVariationId);

        $variationRadios.on('change', function() {
            var variationId = $(this).val();
            $hiddenVariationInput.val(variationId).trigger('change');
            updateDisplay(variationId);
        });

    });
    </script>
    <?php
}

// ==================================================================
// ENHANCED SAMPLES PLAYER
// ==================================================================

/**
 * Ulepszony player próbek audio
 */
function toneka_enhanced_samples_player() {
    global $product;
    
    $samples = get_post_meta($product->get_id(), '_product_samples', true);
    
    if (empty($samples)) {
        return;
    }
    
    ?>
    <div id="toneka-enhanced-player" class="toneka-enhanced-player">
        <h3 class="player-title"><?php _e('ODSŁUCH FRAGMENTU', 'tonekatheme'); ?></h3>
        
        <div class="player-main-window">
            <div class="waveform-container">
                <div class="waveform-placeholder">
                    <div class="wave-bars">
                        <?php for ($i = 0; $i < 20; $i++): ?>
                            <div class="wave-bar" style="animation-delay: <?php echo $i * 0.1; ?>s;"></div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            
            <div class="player-controls">
                <button class="control-btn play-pause-btn" disabled>
                    <span class="play-icon">▶</span>
                    <span class="pause-icon" style="display: none;">⏸</span>
                </button>
                <button class="control-btn prev-btn" disabled>⏮</button>
                <button class="control-btn next-btn" disabled>⏭</button>
                
                <div class="time-display">
                    <span class="current-time">0:00</span>
                    <span class="separator">/</span>
                    <span class="total-time">0:00</span>
                </div>
                
                <div class="volume-control">
                    <span class="volume-icon">🔊</span>
                    <input type="range" class="volume-slider" min="0" max="100" value="75">
                </div>
            </div>
            
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                    <div class="progress-handle"></div>
                </div>
            </div>
            
            <audio class="hidden-audio" preload="none"></audio>
        </div>
        
        <div class="samples-playlist">
            <?php foreach ($samples as $index => $sample): ?>
                <div class="sample-item" 
                     data-src="<?php echo esc_url($sample['file']); ?>" 
                     data-index="<?php echo $index; ?>">
                    <div class="sample-info">
                        <div class="sample-name"><?php echo esc_html($sample['name']); ?></div>
                        <div class="sample-meta">
                            <span class="sample-type"><?php echo pathinfo($sample['file'], PATHINFO_EXTENSION); ?></span>
                            <span class="sample-duration">--:--</span>
                        </div>
                    </div>
                    <div class="sample-controls">
                        <button class="sample-play-btn">▶</button>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="playlist-counter">
                <span class="current-track">1</span>
                <span class="separator">/</span>
                <span class="total-tracks"><?php echo count($samples); ?></span>
            </div>
        </div>
    </div>
    
    <style>
    .toneka-enhanced-player {
        background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }
    
    .player-title {
        padding: 20px;
        margin: 0;
        font-size: 1.1rem;
        color: #fff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .waveform-container {
        height: 120px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #1a1a1a;
    }
    
    .wave-bars {
        display: flex;
        align-items: end;
        gap: 2px;
        height: 60px;
    }
    
    .wave-bar {
        width: 3px;
        background: linear-gradient(to top, #007cba, #00a0e6);
        border-radius: 2px;
        animation: wave 1.5s ease-in-out infinite;
        opacity: 0.6;
    }
    
    @keyframes wave {
        0%, 100% { height: 10%; }
        50% { height: 100%; }
    }
    
    .player-controls {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        gap: 15px;
        background: #1a1a1a;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .control-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .control-btn:hover:not(:disabled) {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .control-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    
    .time-display {
        margin-left: auto;
        font-size: 0.9rem;
        color: #fff;
        font-family: monospace;
    }
    
    .volume-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .volume-slider {
        width: 80px;
        accent-color: #007cba;
    }
    
    .progress-container {
        padding: 0 20px 15px;
        background: #1a1a1a;
    }
    
    .progress-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
        position: relative;
        cursor: pointer;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(to right, #007cba, #00a0e6);
        border-radius: 3px;
        width: 0%;
        transition: width 0.1s;
    }
    
    .hidden-audio {
        display: none;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        var $player = $('#toneka-enhanced-player');
        var $audio = $player.find('.hidden-audio')[0];
        var $playPauseBtn = $player.find('.play-pause-btn');
        var $prevBtn = $player.find('.prev-btn');
        var $nextBtn = $player.find('.next-btn');
        var $progressFill = $player.find('.progress-fill');
        var $currentTime = $player.find('.current-time');
        var $totalTime = $player.find('.total-time');
        var $volumeSlider = $player.find('.volume-slider');
        var $sampleItems = $player.find('.sample-item');
        var $currentTrack = $player.find('.current-track');
        var $waveBars = $player.find('.wave-bar');
        
        var currentSampleIndex = -1;
        
        function loadSample(index) {
            if (index < 0 || index >= $sampleItems.length) return;
            
            currentSampleIndex = index;
            var $item = $sampleItems.eq(index);
            var src = $item.data('src');
            
            $audio.src = src;
            $audio.load();
            
            $sampleItems.removeClass('active');
            $item.addClass('active');
            
            $currentTrack.text(index + 1);
            
            $playPauseBtn.prop('disabled', false);
            $prevBtn.prop('disabled', index === 0);
            $nextBtn.prop('disabled', index === $sampleItems.length - 1);
        }
        
        function updateWaveform(playing) {
            $waveBars.each(function(i) {
                $(this).css('animation-play-state', playing ? 'running' : 'paused');
            });
        }
        
        function formatTime(seconds) {
            var minutes = Math.floor(seconds / 60);
            var secs = Math.floor(seconds % 60);
            return minutes + ':' + (secs < 10 ? '0' : '') + secs;
        }
        
        // Event listeners
        $sampleItems.on('click', function() {
            var index = $(this).index();
            loadSample(index);
        });
        
        $playPauseBtn.on('click', function() {
            if ($audio.paused) {
                $audio.play();
            } else {
                $audio.pause();
            }
        });
        
        $prevBtn.on('click', function() {
            loadSample(currentSampleIndex - 1);
        });
        
        $nextBtn.on('click', function() {
            loadSample(currentSampleIndex + 1);
        });
        
        $volumeSlider.on('input', function() {
            $audio.volume = this.value / 100;
        });
        
        $player.find('.progress-bar').on('click', function(e) {
            if (!$audio.src) return;
            var rect = this.getBoundingClientRect();
            var percent = (e.clientX - rect.left) / rect.width;
            $audio.currentTime = $audio.duration * percent;
        });
        
        // Audio events
        $($audio).on('play', function() {
            $playPauseBtn.find('.play-icon').hide();
            $playPauseBtn.find('.pause-icon').show();
            updateWaveform(true);
        });
        
        $($audio).on('pause', function() {
            $playPauseBtn.find('.play-icon').show();
            $playPauseBtn.find('.pause-icon').hide();
            updateWaveform(false);
        });
        
        $($audio).on('loadedmetadata', function() {
            $totalTime.text(formatTime($audio.duration));
        });
        
        $($audio).on('timeupdate', function() {
            if ($audio.duration) {
                var percent = ($audio.currentTime / $audio.duration) * 100;
                $progressFill.css('width', percent + '%');
                $currentTime.text(formatTime($audio.currentTime));
            }
        });
        
        $($audio).on('ended', function() {
            if (currentSampleIndex < $sampleItems.length - 1) {
                loadSample(currentSampleIndex + 1);
                $audio.play();
            }
        });
        
        // Set initial volume
        $audio.volume = 0.75;
    });
    </script>
    <?php
}

// ==================================================================
// ENQUEUE STYLES AND SCRIPTS
// ==================================================================

/**
 * Dodaje CSS i JS dla ulepszeń WooCommerce
 */
function toneka_enqueue_woocommerce_improvements() {
    if (is_product()) {
        wp_enqueue_style(
            'toneka-wc-improvements',
            get_template_directory_uri() . '/css/product-responsive.css',
            [],
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'toneka_enqueue_woocommerce_improvements');

// ==================================================================
// REGISTER IMPROVED FUNCTIONS
// ==================================================================

// Replace default functions with improved versions
remove_action('woocommerce_single_product_summary', 'toneka_display_product_creators', 15);
remove_action('woocommerce_single_product_summary', 'toneka_output_variable_product_selector', 20);
remove_action('woocommerce_single_product_summary', 'toneka_display_product_samples_player', 25);

// Hook improved functions
add_action('toneka_product_info_section', 'toneka_display_enhanced_product_info', 10);
add_action('toneka_product_purchase_section', 'toneka_enhanced_variation_selector', 10);
add_action('toneka_product_samples_section', 'toneka_enhanced_samples_player', 10);

?>
