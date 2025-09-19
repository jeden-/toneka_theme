jQuery(document).ready(function($) {
    'use strict';

    // Główna funkcja inicjalizująca widget
    function initCarrierSelection() {
        const widget = $('.toneka-carrier-selection-widget');
        if (!widget.length) return;

        const radios = widget.find('.toneka-carrier-radio');
        const variationDetails = widget.find('.toneka-variation-details');
        const variationDescription = widget.find('.toneka-variation-description');
        const variationPrice = widget.find('.toneka-variation-price');
        const hiddenInput = widget.find('#toneka-selected-attribute');
        const cartForm = widget.find('.toneka-cart-form');
        const quantityInput = widget.find('.toneka-quantity-input');
        const quantityMinus = widget.find('.toneka-quantity-minus');
        const quantityPlus = widget.find('.toneka-quantity-plus');
        
        // Dodanie nowych selektorów dla zawsze widocznych kontenerów
        const variationPriceDisplay = widget.find('.toneka-variation-price-display');
        const variationDescriptionDisplay = widget.find('.toneka-variation-description-display');
        
        // Pobierz dane wariantów z JSON
        const variationsData = JSON.parse(widget.find('.toneka-variations-data').text() || '[]');
        const selectedAttribute = widget.data('attribute');

        // Dodaj log do konsoli przeglądarki, aby zobaczyć strukturę danych wariantów
        console.log('Variations Data:', variationsData);

        // Funkcja do znalezienia wariantu na podstawie wybranej opcji
        function findVariationByAttribute(attributeValue) {
            const attributeKey = `attribute_${selectedAttribute}`;
            return variationsData.find(variation => {
                return variation.attributes[attributeKey] === attributeValue;
            });
        }

        // Funkcja do aktualizacji informacji o wariancie
        function updateVariationInfo(attributeValue, quantity = 1) {
            const variation = findVariationByAttribute(attributeValue);
            
            if (variation) {
                console.log('Selected Variation:', variation);
                
                // Wyświetl opis wariantu jeśli istnieje
                // Sprawdź różne możliwe nazwy klucza dla opisu
                const description = variation.variation_description || 
                                   variation.description || 
                                   '';
                
                // Pobierz cenę wariantu
                let priceHtml = variation.price_html || '';
                const regularPrice = parseFloat(variation.display_regular_price) || 0;
                const salePrice = parseFloat(variation.display_price) || regularPrice;
                
                // Jeśli mamy ilość większą niż 1, zaktualizuj cenę
                if (quantity > 1) {
                    const totalRegularPrice = regularPrice * quantity;
                    const totalSalePrice = salePrice * quantity;
                    
                    // Formatuj ceny
                    if (salePrice < regularPrice) {
                        // Cena promocyjna
                        priceHtml = '<del>' + formatPrice(totalRegularPrice) + '</del> <ins>' + formatPrice(totalSalePrice) + '</ins>';
                    } else {
                        // Normalna cena
                        priceHtml = formatPrice(totalSalePrice);
                    }
                }

                // Aktualizuj opcjonalną tabelę
                variationDescription.html(description);
                variationPrice.html(priceHtml);
                
                // Aktualizuj stale widoczne kontenery
                variationDescriptionDisplay.html(description);
                variationPriceDisplay.html(priceHtml);

                // Pokaż lub ukryj kontener z detalami
                if (description || priceHtml) {
                    variationDetails.show();
                } else {
                    variationDetails.hide();
                }

                // Aktualizuj maksymalną ilość
                if (variation.max_qty && parseInt(variation.max_qty) > 0) {
                    quantityInput.attr('max', parseInt(variation.max_qty));
                } else {
                    quantityInput.attr('max', 999);
                }

                // Dodaj hidden input z ID wariantu
                updateHiddenInputs(variation.variation_id, attributeValue);
                
                // Zapisz aktualny wariant jako dane obiektu
                widget.data('current-variation', variation);
            } else {
                // Ukryj sekcję jeśli nie ma wariantu
                variationDetails.hide();
                variationDescription.html('');
                variationPrice.html('');
                
                // Wyczyść stale widoczne kontenery
                variationDescriptionDisplay.html('');
                variationPriceDisplay.html('');
            }
        }

        // Funkcja do formatowania ceny
        function formatPrice(price) {
            return `${parseFloat(price).toFixed(2)} zł`;
        }
        
        // Funkcja do aktualizacji ceny na podstawie ilości
        function updatePriceBasedOnQuantity() {
            const quantity = parseInt(quantityInput.val()) || 1;
            const selectedValue = radios.filter(':checked').val();
            
            if (selectedValue) {
                updateVariationInfo(selectedValue, quantity);
            }
        }

        // Funkcja do znajdowania term po slug
        function findTermBySlug(slug, taxonomy) {
            // To będzie działać tylko jeśli mamy dostęp do danych terms
            // Na razie zwracamy null, ale można by dodać AJAX call
            return null;
        }

        // Funkcja do aktualizacji ukrytych pól formularza
        function updateHiddenInputs(variationId, attributeValue) {
            // Usuń poprzednie hidden inputs
            cartForm.find('input[name^="attribute_"]').remove();
            cartForm.find('input[name="variation_id"]').remove();

            // Dodaj nowe hidden inputs
            cartForm.append(`<input type="hidden" name="variation_id" value="${variationId}">`);
            cartForm.append(`<input type="hidden" name="attribute_${selectedAttribute}" value="${attributeValue}">`);
            
            // Aktualizuj główne ukryte pole
            hiddenInput.val(attributeValue);
        }

        // Event handler dla zmiany radio buttonów
        radios.on('change', function() {
            const selectedValue = $(this).val();
            updateVariationInfo(selectedValue);
        });

        // Event handlers dla quantity buttons
        quantityMinus.on('click', function() {
            const currentValue = parseInt(quantityInput.val());
            const minValue = parseInt(quantityInput.attr('min')) || 1;
            if (currentValue > minValue) {
                quantityInput.val(currentValue - 1);
                updatePriceBasedOnQuantity();
            }
        });

        quantityPlus.on('click', function() {
            const currentValue = parseInt(quantityInput.val());
            const maxValue = parseInt(quantityInput.attr('max')) || 999;
            if (currentValue < maxValue) {
                quantityInput.val(currentValue + 1);
                updatePriceBasedOnQuantity();
            }
        });

        // Walidacja input quantity
        quantityInput.on('input', function() {
            let value = parseInt($(this).val()) || 1;
            const min = parseInt($(this).attr('min')) || 1;
            const max = parseInt($(this).attr('max')) || 999;
            
            // Upewnij się, że max jest większe od min
            const validMax = max > 0 ? max : 999;
            
            if (value < min) {
                value = min;
            } else if (value > validMax) {
                value = validMax;
            }
            
            $(this).val(value);
            updatePriceBasedOnQuantity();
        });

        // Event handler dla submitu formularza z obsługą AJAX
        cartForm.on('submit', function(e) {
            e.preventDefault(); // Zapobiegaj standardowemu submitowi formularza
            
            // Dodaj dodatkowe pole z wybranym atrybutem
            const selectedRadio = radios.filter(':checked');
            if (selectedRadio.length) {
                // Dodaj pole z nazwą wybranego atrybutu dla compatibility
                if (!cartForm.find('input[name="toneka_selected_attribute"]').length) {
                    cartForm.append(`<input type="hidden" name="toneka_selected_attribute" value="${selectedRadio.val()}">`);
                }
            }
            
            // Pokaż loader lub komunikat "Dodawanie do koszyka..."
            const addToCartButton = cartForm.find('.toneka-add-to-cart-button');
            const originalButtonText = addToCartButton.text();
            addToCartButton.text('Dodawanie...').prop('disabled', true);
            
            // Zbierz dane formularza
            const formData = new FormData(cartForm[0]);
            formData.append('action', 'toneka_ajax_add_to_cart');
            
            // Dodaj nonce do formularza, jeśli jest dostępny
            if (typeof toneka_ajax_object !== 'undefined' && toneka_ajax_object.nonce) {
                formData.append('nonce', toneka_ajax_object.nonce);
            }
            
            // Wyślij żądanie AJAX
            $.ajax({
                type: 'POST',
                url: typeof toneka_ajax_object !== 'undefined' ? toneka_ajax_object.ajax_url : ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('AJAX response:', response);
                    
                    // Przywróć oryginalny tekst przycisku
                    addToCartButton.text(originalButtonText).prop('disabled', false);
                    
                    if (response.success) {
                        // Pokaż komunikat sukcesu
                        if (response.data && response.data.message) {
                            // Opcjonalnie: pokaż komunikat w jakiś sposób
                            console.log('Success message:', response.data.message);
                        }
                        
                        // Zaktualizuj mini koszyk (jeśli istnieje)
                        if (response.data && response.data.fragments) {
                            $.each(response.data.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                        
                        // Zaktualizuj licznik produktów w koszyku (jeśli istnieje)
                        if (response.data && response.data.cart_count) {
                            $('.cart-count-number').text(response.data.cart_count);
                        }
                        
                        // Otwórz mini koszyk
                        setTimeout(function() {
                            // Sprawdź, czy istnieje funkcja do otwierania mini koszyka
                            if (typeof openMiniCart === 'function') {
                                openMiniCart();
                            } else if ($('.mini-cart-opener').length) {
                                // Kliknij w przycisk otwierający mini koszyk
                                $('.mini-cart-opener').trigger('click');
                            } else if ($('.cart-opener').length) {
                                $('.cart-opener').trigger('click');
                            } else if ($('.cart-contents').length) {
                                $('.cart-contents').trigger('click');
                            } else if ($('.header-cart').length) {
                                $('.header-cart').trigger('click');
                            } else {
                                // Spróbuj znaleźć przycisk koszyka na podstawie popularnych klas
                                const cartButtons = $('.cart-button, .cart-icon, .mini-cart, .header-cart-icon, .shopping-cart-icon');
                                if (cartButtons.length) {
                                    cartButtons.first().trigger('click');
                                }
                            }
                        }, 300);
                    } else {
                        // Pokaż komunikat błędu
                        if (response.data && response.data.message) {
                            alert(response.data.message);
                        } else {
                            alert('Wystąpił błąd podczas dodawania produktu do koszyka.');
                        }
                    }
                },
                error: function() {
                    // Przywróć oryginalny tekst przycisku
                    addToCartButton.text(originalButtonText).prop('disabled', false);
                    
                    // Pokaż komunikat błędu
                    alert('Wystąpił błąd podczas dodawania produktu do koszyka.');
                }
            });
        });

        // Inicjalizacja - pokaż pierwszy wariant
        const firstRadio = radios.first();
        if (firstRadio.length && firstRadio.is(':checked')) {
            updateVariationInfo(firstRadio.val());
        }
    }

    // Funkcja do usunięcia natywnych kontrolek WooCommerce
    function removeNativeQuantityControls() {
        // Ukryj natywne kontrolki WooCommerce
        $('.woocommerce .quantity, form.cart .quantity').not('.toneka-quantity-wrapper').hide();
        $('.woocommerce-variation-add-to-cart .quantity').hide();
        $('.woocommerce div.product form.cart div.quantity').hide();
        
        // Usuń elementy z DOM dla pewności
        setTimeout(function() {
            $('.woocommerce .quantity, form.cart .quantity').not('.toneka-quantity-wrapper').remove();
            $('.woocommerce-variation-add-to-cart .quantity').remove();
            $('.woocommerce div.product form.cart div.quantity').not('.toneka-quantity-wrapper').remove();
        }, 100);
        
        // Upewnij się, że nasze kontrolki mają odpowiednie style
        applyCustomStyles();
    }
    
    // Funkcja do zastosowania niestandardowych stylów
    function applyCustomStyles() {
        // Najpierw ukryj natywne kontrolki
        $('.woocommerce .quantity, form.cart .quantity').not('.toneka-quantity-wrapper').css({
            'display': 'none !important',
            'visibility': 'hidden !important',
            'opacity': '0 !important',
            'width': '0 !important',
            'height': '0 !important',
            'position': 'absolute !important',
            'left': '-9999px !important'
        });
        
        // Ustaw style dla przycisków minus i plus
        $('.toneka-quantity-minus, .toneka-quantity-plus').css({
            'width': '18px !important',
            'height': '18px !important',
            'background-color': '#fff !important',
            'color': '#000 !important',
            'border': 'none !important',
            'border-radius': '50% !important',
            'display': 'flex !important',
            'align-items': 'center !important',
            'justify-content': 'center !important',
            'font-weight': 'bold !important',
            'padding': '0 !important',
            'line-height': '1 !important',
            'font-size': '14px !important',
            'box-shadow': 'none !important',
            'margin': '0 !important',
            'outline': 'none !important',
            'text-shadow': 'none !important'
        });
        
        // Ustaw style dla pola input
        $('.toneka-quantity-input').css({
            'width': '40px !important',
            'height': '30px !important',
            'text-align': 'center !important',
            'border': 'none !important',
            'background-color': 'transparent !important',
            'color': '#fff !important',
            'margin': '0 10px !important',
            'font-weight': 'bold !important',
            'font-size': '16px !important',
            'box-shadow': 'none !important',
            '-webkit-appearance': 'none !important',
            'appearance': 'none !important',
            'outline': 'none !important',
            'padding': '0 !important'
        });
        
        // Ukryj natywne strzałki w polu input
        $('.toneka-quantity-input::-webkit-outer-spin-button, .toneka-quantity-input::-webkit-inner-spin-button').css({
            '-webkit-appearance': 'none !important',
            'margin': '0 !important'
        });
        
        // Ukryj wszystkie inne kontrolki ilości
        $('form.cart .quantity').not('.toneka-quantity-wrapper').hide();
        $('.woocommerce-variation-add-to-cart .quantity').hide();
        $('div.product form.cart div.quantity').not('.toneka-quantity-wrapper').hide();
    }

    // Inicjalizuj widget po załadowaniu strony
    initCarrierSelection();
    removeNativeQuantityControls();
    applyCustomStyles();
    
    // Zapisz oryginalny tekst wszystkich przycisków dodawania do koszyka
    function saveOriginalButtonTexts() {
        $('.ajax_add_to_cart, .single_add_to_cart_button, .toneka-add-to-cart-button').each(function() {
            var $button = $(this);
            if (!$button.data('original-text')) {
                $button.data('original-text', $button.text());
                console.log('Saved original text for button:', $button.text());
            }
        });
    }
    
    // Zapisz oryginalny tekst przycisków
    saveOriginalButtonTexts();

    // Re-inicjalizuj po załadowaniu Elementor (jeśli jesteśmy w edytorze)
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/toneka-carrier-selection.default', function() {
            initCarrierSelection();
            removeNativeQuantityControls();
            applyCustomStyles();
            saveOriginalButtonTexts();
        });
    }
    
    // Wykonaj ponownie po pełnym załadowaniu strony
    $(window).on('load', function() {
        removeNativeQuantityControls();
        applyCustomStyles();
        saveOriginalButtonTexts();
    });
    
    // Wykonaj co 500ms przez pierwsze 5 sekund, aby upewnić się, że style zostaną zastosowane
    let styleInterval = 0;
    const styleTimer = setInterval(function() {
        applyCustomStyles();
        styleInterval++;
        if (styleInterval > 10) {
            clearInterval(styleTimer);
        }
    }, 500);
    
    // Dodatkowy kod do przechwytywania kliknięć w przyciski dodawania do koszyka
    $(document).on('click', '.ajax_add_to_cart, .single_add_to_cart_button:not(.disabled), .toneka-add-to-cart-button:not(.disabled)', function(e) {
        // Debugowanie - pokaż, który przycisk został kliknięty
        console.log('Button clicked:', this, $(this).attr('class'));
        
        // Sprawdź, czy przycisk znajduje się w formularzu niestandardowym
        if ($(this).closest('.toneka-cart-form').length) {
            console.log('Button is in toneka-cart-form, will be handled by form submit handler');
            // Nie rób nic, formularz zostanie obsłużony przez inny handler
            return;
        }
        
        // Sprawdź, czy przycisk ma klasę 'product_type_variable'
        if ($(this).hasClass('product_type_variable')) {
            console.log('Button is for variable product, skipping');
            // Nie przechwytuj kliknięcia dla produktów wariantowych na liście produktów
            return;
        }
        
        // Sprawdź, czy przycisk ma klasę 'disabled'
        if ($(this).hasClass('disabled')) {
            console.log('Button is disabled, skipping');
            return;
        }
        
        // Sprawdź, czy jesteśmy na stronie produktu
        const $form = $(this).closest('form.cart');
        if ($form.length) {
            console.log('Button is in standard form.cart, will be handled by form submit handler');
            // Jesteśmy na stronie produktu, obsługa formularza będzie przez globalny handler
            return;
        }
        
        // Jesteśmy na liście produktów, przechwytujemy kliknięcie
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Intercepted add to cart click', this);
        
        const $button = $(this);
        // Zapisz oryginalny tekst przycisku jako atrybut data
        if (!$button.data('original-text')) {
            $button.data('original-text', $button.text());
        }
        const originalButtonText = $button.data('original-text');
        console.log('Original button text:', originalButtonText);
        
        // Pobierz ID produktu z różnych możliwych źródeł
        let productId = 0;
        
        // Sprawdź data-product_id
        if ($button.data('product_id')) {
            productId = $button.data('product_id');
            console.log('Found product ID from data-product_id:', productId);
        }
        
        // Sprawdź atrybut value przycisku
        if (productId == 0 && $button.attr('value')) {
            productId = $button.attr('value');
            console.log('Found product ID from button value:', productId);
        }
        
        // Sprawdź parametr w URL
        if (productId == 0 && $button.attr('href')) {
            const url = new URL($button.attr('href'), window.location.origin);
            const addToCartParam = url.searchParams.get('add-to-cart');
            if (addToCartParam) {
                productId = addToCartParam;
                console.log('Found product ID from URL parameter:', productId);
            }
        }
        
        if (productId == 0) {
            console.error('Could not determine product ID');
            alert('Nie można określić ID produktu');
            return;
        }
        
        const quantity = $button.data('quantity') || 1;
        
        // Pokaż loader
        console.log('Setting button text to "Dodawanie..."');
        $button.text('Dodawanie...').addClass('loading').prop('disabled', true);
        
        // Przygotuj dane
        const data = {
            action: 'toneka_ajax_add_to_cart',
            product_id: productId,
            quantity: quantity
        };
        
        // Dodaj nonce jeśli dostępny
        if (typeof toneka_ajax_object !== 'undefined' && toneka_ajax_object.nonce) {
            data.nonce = toneka_ajax_object.nonce;
        }
        
        console.log('Sending AJAX request with data:', data);
        
        // Wyślij żądanie AJAX
        $.ajax({
            type: 'POST',
            url: typeof toneka_ajax_object !== 'undefined' ? toneka_ajax_object.ajax_url : '/wp-admin/admin-ajax.php',
            data: data,
            success: function(response) {
                console.log('AJAX response:', response);
                
                if (response.success) {
                    // Zmień tekst na "Dodane"
                    console.log('Setting button text to "Dodane"');
                    $button.text('Dodane').removeClass('loading');
                    
                    // Po 2 sekundach przywróć oryginalny tekst
                    setTimeout(function() {
                        console.log('Restoring original button text:', originalButtonText);
                        $button.text(originalButtonText).prop('disabled', false);
                    }, 2000);
                    
                    // Zaktualizuj mini koszyk
                    if (response.data && response.data.fragments) {
                        $.each(response.data.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }
                    
                    // Zaktualizuj licznik produktów w koszyku
                    if (response.data && response.data.cart_count) {
                        $('.cart-count-number, .qodef-m-opener-count').text(response.data.cart_count);
                    }
                    
                    // Otwórz mini koszyk
                    setTimeout(function() {
                        if (typeof openMiniCart === 'function') {
                            openMiniCart();
                        } else {
                            // Spróbuj znaleźć przycisk koszyka
                            var cartOpeners = $('.qodef-m-opener, .qodef-woo-dropdown-cart .qodef-m-opener, .qodef-woo-side-area-cart .qodef-m-opener, .mini-cart-opener, .cart-opener, .cart-contents, .header-cart');
                            if (cartOpeners.length) {
                                cartOpeners.first().trigger('click');
                            }
                        }
                    }, 300);
                    
                    // Wywołaj zdarzenie dodania do koszyka
                    $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash, $button]);
                } else {
                    // Przywróć oryginalny tekst przycisku w przypadku błędu
                    console.log('Error response, restoring original button text:', originalButtonText);
                    $button.text(originalButtonText).removeClass('loading').prop('disabled', false);
                    
                    // Pokaż komunikat błędu
                    if (response.data && response.data.message) {
                        alert(response.data.message);
                    } else {
                        alert('Wystąpił błąd podczas dodawania produktu do koszyka.');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr, status, error);
                
                // Przywróć oryginalny tekst przycisku
                console.log('AJAX error, restoring original button text:', originalButtonText);
                $button.text(originalButtonText).removeClass('loading').prop('disabled', false);
                
                // Pokaż komunikat błędu
                alert('Wystąpił błąd podczas dodawania produktu do koszyka.');
            }
        });
    });
}); 