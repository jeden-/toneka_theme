jQuery(document).ready(function($) {
    'use strict';
    
    console.log('TONEKA: Nowy skrypt carrier-selection-new.js załadowany!');

    // Główna funkcja inicjalizująca widget
    function initCarrierSelection() {
        const widget = $('.toneka-carrier-selection-widget');
        console.log('TONEKA: Szukam widgetów, znaleziono:', widget.length);
        if (!widget.length) return;

        // Sprawdź tryb widgetu
        const widgetMode = widget.data('mode');
        console.log('TONEKA: Tryb widgetu:', widgetMode);
        
        if (widgetMode === 'two-step') {
            console.log('TONEKA: Inicjalizuję dwuetapowy selektor');
            initTwoStepSelector(widget);
            return;
        } else if (widgetMode === 'all-variations') {
            console.log('TONEKA: Inicjalizuję standardowy selektor');
            // Kontynuuj z obecną logiką
        } else {
            console.log('Widget nie jest w obsługiwanym trybie');
            return;
        }

        const radios = widget.find('.toneka-carrier-radio');
        const variationDetails = widget.find('.toneka-variation-details');
        const variationDescription = widget.find('.toneka-variation-description');
        const variationPrice = widget.find('.toneka-variation-price');
        const hiddenInput = widget.find('input[name="variation_id"]');
        const cartForm = widget.find('.toneka-cart-form');
        const quantityInput = widget.find('.toneka-quantity-input');
        const quantityMinus = widget.find('.toneka-quantity-minus');
        const quantityPlus = widget.find('.toneka-quantity-plus');
        
        // Kontenery dla zawsze widocznych informacji
        const variationPriceDisplay = widget.find('.toneka-variation-price-display');
        const variationDescriptionDisplay = widget.find('.toneka-variation-description-display');

        // Funkcja do aktualizacji informacji o wariancie
        function updateVariationInfo(variationData, quantity = 1) {
            if (!variationData) return;
            
            console.log('Selected Variation Data:', variationData);
            console.log('Price Display Container:', variationPriceDisplay.length);
            console.log('Description Display Container:', variationDescriptionDisplay.length);
            
            // Pobierz opis wariantu
            const description = variationData.variation_description || 
                               variationData.description || 
                               '';
            
            // Pobierz cenę wariantu
            let priceHtml = variationData.price_html || '';
            const regularPrice = parseFloat(variationData.display_regular_price) || 0;
            const salePrice = parseFloat(variationData.display_price) || regularPrice;
            
            console.log('Description:', description);
            console.log('Price HTML:', priceHtml);
            console.log('Regular Price:', regularPrice);
            console.log('Sale Price:', salePrice);
            
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
            
            // Aktualizuj zawsze widoczne kontenery
            variationDescriptionDisplay.html(description);
            variationPriceDisplay.html(priceHtml);

            // Pokaż lub ukryj kontener z detalami
            if (description || priceHtml) {
                variationDetails.show();
            } else {
                variationDetails.hide();
            }

            // Zaktualizuj ukryte pole z ID wariantu
            hiddenInput.val(variationData.variation_id);
            
            // Dodaj wszystkie atrybuty wariantu jako ukryte pola w formularzu
            cartForm.find('input[name^="attribute_"]').remove(); // Usuń stare pola atrybutów
            
            if (variationData.attributes) {
                $.each(variationData.attributes, function(attributeName, attributeValue) {
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', attributeName)
                        .val(attributeValue)
                        .appendTo(cartForm);
                });
            }

            // Zaktualizuj przycisk dodawania do koszyka
            const addToCartButton = cartForm.find('.toneka-add-to-cart-button');
            addToCartButton.data('variation_id', variationData.variation_id);
            
            // Zaktualizuj ceny w przycisku jeśli potrzeba
            if (variationData.is_in_stock === false) {
                addToCartButton.prop('disabled', true).text('Brak na stanie');
            } else {
                addToCartButton.prop('disabled', false).text(addToCartButton.data('original-text') || 'Dodaj do koszyka');
            }
        }

        // Funkcja formatowania ceny
        function formatPrice(price) {
            return new Intl.NumberFormat('pl-PL', {
                style: 'currency',
                currency: 'PLN'
            }).format(price);
        }

        // Obsługa kliknięcia w radio button
        radios.on('change', function() {
            const selectedRadio = $(this);
            const variationId = selectedRadio.val();
            const variationDataString = selectedRadio.data('variation-data');
            
            let variationData;
            try {
                variationData = typeof variationDataString === 'string' ? 
                    JSON.parse(variationDataString) : 
                    variationDataString;
            } catch (e) {
                console.error('Błąd parsowania danych wariantu:', e);
                return;
            }
            
            const currentQuantity = parseInt(quantityInput.val()) || 1;
            updateVariationInfo(variationData, currentQuantity);
        });

        // Obsługa przycisków ilości
        quantityMinus.on('click', function(e) {
            e.preventDefault();
            const currentValue = parseInt(quantityInput.val()) || 1;
            const newValue = Math.max(1, currentValue - 1);
            quantityInput.val(newValue);
            
            // Zaktualizuj informacje o wariancie z nową ilością
            const selectedRadio = radios.filter(':checked');
            if (selectedRadio.length) {
                const variationDataString = selectedRadio.data('variation-data');
                let variationData;
                try {
                    variationData = typeof variationDataString === 'string' ? 
                        JSON.parse(variationDataString) : 
                        variationDataString;
                    updateVariationInfo(variationData, newValue);
                } catch (e) {
                    console.error('Błąd parsowania danych wariantu:', e);
                }
            }
        });

        quantityPlus.on('click', function(e) {
            e.preventDefault();
            const currentValue = parseInt(quantityInput.val()) || 1;
            const newValue = currentValue + 1;
            quantityInput.val(newValue);
            
            // Zaktualizuj informacje o wariancie z nową ilością
            const selectedRadio = radios.filter(':checked');
            if (selectedRadio.length) {
                const variationDataString = selectedRadio.data('variation-data');
                let variationData;
                try {
                    variationData = typeof variationDataString === 'string' ? 
                        JSON.parse(variationDataString) : 
                        variationDataString;
                    updateVariationInfo(variationData, newValue);
                } catch (e) {
                    console.error('Błąd parsowania danych wariantu:', e);
                }
            }
        });

        // Obsługa zmiany ilości w polu input
        quantityInput.on('input change', function() {
            const newValue = Math.max(1, parseInt($(this).val()) || 1);
            $(this).val(newValue);
            
            // Zaktualizuj informacje o wariancie z nową ilością
            const selectedRadio = radios.filter(':checked');
            if (selectedRadio.length) {
                const variationDataString = selectedRadio.data('variation-data');
                let variationData;
                try {
                    variationData = typeof variationDataString === 'string' ? 
                        JSON.parse(variationDataString) : 
                        variationDataString;
                    updateVariationInfo(variationData, newValue);
                } catch (e) {
                    console.error('Błąd parsowania danych wariantu:', e);
                }
            }
        });

        // Inicjalizacja - załaduj pierwszy wariant
        const firstRadio = radios.first();
        if (firstRadio.length) {
            const variationDataString = firstRadio.data('variation-data');
            let variationData;
            try {
                variationData = typeof variationDataString === 'string' ? 
                    JSON.parse(variationDataString) : 
                    variationDataString;
                updateVariationInfo(variationData, parseInt(quantityInput.val()) || 1);
            } catch (e) {
                console.error('Błąd parsowania danych wariantu:', e);
            }
        }

        // Obsługa formularza dodawania do koszyka
        cartForm.on('submit', function(e) {
            e.preventDefault();
            
            const currentQuantity = parseInt(quantityInput.val()) || 1;
            console.log('🔍 TONEKA DEBUG: Form submitted with quantity:', currentQuantity);
            console.log('🔍 TONEKA DEBUG: Quantity is even?', currentQuantity % 2 === 0);
            console.log('🔍 TONEKA DEBUG: Quantity is odd?', currentQuantity % 2 === 1);
            
            const selectedRadio = radios.filter(':checked');
            if (!selectedRadio.length) {
                alert('Proszę wybrać wariant produktu.');
                return false;
            }
            
            // Sprawdź czy formularz ma wszystkie potrzebne dane
            const variationId = hiddenInput.val();
            if (!variationId) {
                alert('Błąd: brak ID wariantu.');
                return false;
            }
            
            const productId = wc_add_to_cart_params.product_id;
            const quantity = parseInt(quantityInput.val()) || 1;
            
            console.log('TONEKA JS: Adding to cart - Product ID:', productId, 'Quantity:', quantity, 'Variation ID:', variationId);
            console.log('TONEKA JS: AJAX URL:', wc_add_to_cart_params.ajax_url);
            console.log('TONEKA JS: Nonce:', wc_add_to_cart_params.wc_ajax_nonce);
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'toneka_ajax_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    variation_id: variationId,
                    security: wc_add_to_cart_params.wc_ajax_nonce
                },
                success: function(response) {
                    console.log('🔍 TONEKA JS: AJAX Success - Raw response:', response);
                    console.log('🔍 TONEKA JS: Response type:', typeof response);
                    console.log('🔍 TONEKA JS: Response keys:', Object.keys(response));
                    console.log('🔍 TONEKA JS: Response.success:', response.success);
                    console.log('🔍 TONEKA JS: Response.data:', response.data);
                    console.log('🔍 TONEKA JS: JSON stringify:', JSON.stringify(response));
                    
                    if (response.success) {
                        console.log('🎉 TONEKA JS: Product successfully added to cart');
                        console.log('🎉 TONEKA JS: Fragments received:', response.data.fragments);
                        console.log('🎉 TONEKA JS: Cart count:', response.data.cart_count);
                        // Product successfully added to cart
                        
                        // Update UI fragments (WooCommerce way)
                        if (response.data && response.data.fragments) {
                            console.log('TONEKA JS: Processing fragments:', Object.keys(response.data.fragments));
                            $.each(response.data.fragments, function(key, value) {
                                console.log('TONEKA JS: Looking for element:', key);
                                var $element = $(key);
                                console.log('TONEKA JS: Found elements:', $element.length);
                                console.log('TONEKA JS: Current content:', $element.html());
                                console.log('TONEKA JS: New content:', value);
                                
                                if ($element.length > 0) {
                                    $element.replaceWith(value);
                                    console.log('TONEKA JS: Fragment updated successfully');
                                } else {
                                    console.warn('TONEKA JS: Element not found for selector:', key);
                                }
                            });
                        } else {
                            console.warn('TONEKA JS: No fragments in response');
                        }
                        
                        // Update cart count manually as backup
                        if (response.data.cart_count !== undefined) {
                            console.log('TONEKA JS: Manual cart count update:', response.data.cart_count);
                            var $cartCount = $('.cart-count');
                            console.log('TONEKA JS: Cart count elements found:', $cartCount.length);
                            $cartCount.text(response.data.cart_count);
                            if (response.data.cart_count > 0) {
                                $cartCount.show();
                            } else {
                                $cartCount.hide();
                            }
                        }
                        
                        // Show success message
                        showNotification('Produkt został dodany do koszyka!');
                        
                        // Trigger WooCommerce event - multiple approaches
                        console.log('TONEKA JS: Triggering added_to_cart event on body:', $(document.body));
                        console.log('TONEKA JS: Body length check:', $('body').length);
                        console.log('TONEKA JS: About to trigger with fragments:', response.data.fragments);
                        
                        // Method 1: jQuery trigger on document.body
                        $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash]);
                        console.log('TONEKA JS: Method 1 triggered');
                        
                        // Method 2: jQuery trigger on 'body' selector  
                        $('body').trigger('added_to_cart', [response.data.fragments, response.data.cart_hash]);
                        console.log('TONEKA JS: Method 2 triggered');
                        
                        // Method 3: Native event
                        var nativeEvent = new CustomEvent('added_to_cart', {
                            detail: { fragments: response.data.fragments, cart_hash: response.data.cart_hash }
                        });
                        document.body.dispatchEvent(nativeEvent);
                        console.log('TONEKA JS: Method 3 (native) triggered');
                        
                        // Also trigger our custom event for navigation.js
                        setTimeout(function() {
                            console.log('TONEKA JS: Triggering custom added_to_cart event');
                            document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                                detail: { fragments: response.data.fragments, cart_hash: response.data.cart_hash }
                            }));
                        }, 50);
                        
                        // Direct call to navigation update function as backup
                        setTimeout(function() {
                            console.log('TONEKA JS: Direct cart count update backup');
                            if (typeof window.updateCartCount === 'function') {
                                window.updateCartCount();
                            }
                        }, 100);
                        
                        // Immediate minicart opening (don't wait for events)
                        setTimeout(function() {
                            console.log('TONEKA JS: === IMMEDIATE MINICART OPENING ===');
                            var $minicart = $('.toneka-minicart');
                            var $overlay = $('.toneka-minicart-overlay');
                            var $body = $('body');
                            
                            console.log('TONEKA JS: Minicart element:', $minicart.length, $minicart);
                            console.log('TONEKA JS: Overlay element:', $overlay.length, $overlay);
                            console.log('TONEKA JS: Body element:', $body.length);
                            
                            if ($minicart.length > 0 && $overlay.length > 0) {
                                console.log('TONEKA JS: Adding is-active classes...');
                                
                                // Check current state
                                console.log('TONEKA JS: Minicart current classes:', $minicart.attr('class'));
                                console.log('TONEKA JS: Overlay current classes:', $overlay.attr('class'));
                                console.log('TONEKA JS: Body current classes:', $body.attr('class'));
                                
                                // Add classes
                                $minicart.addClass('is-active');
                                $overlay.addClass('is-active');
                                $body.addClass('minicart-open');
                                
                                // Check after adding
                                console.log('TONEKA JS: Minicart after adding:', $minicart.attr('class'));
                                console.log('TONEKA JS: Overlay after adding:', $overlay.attr('class'));
                                console.log('TONEKA JS: Body after adding:', $body.attr('class'));
                                
                                console.log('TONEKA JS: Is minicart now active?', $minicart.hasClass('is-active'));
                                
                            } else {
                                console.error('TONEKA JS: MINICART ELEMENTS NOT FOUND!');
                                console.log('TONEKA JS: Available elements with "minicart":', $('[class*="minicart"]'));
                            }
                        }, 200);
                        
                        // Manual fallback check after longer delay
                        setTimeout(function() {
                            console.log('TONEKA JS: Manual fallback check - is minicart open?', $('.toneka-minicart').hasClass('is-active'));
                            if (!$('.toneka-minicart').hasClass('is-active')) {
                                console.log('TONEKA JS: WARNING - Minicart still not open after 800ms!');
                            } else {
                                console.log('TONEKA JS: SUCCESS - Minicart is open!');
                            }
                        }, 800);
                        
                    } else {
                        console.log('TONEKA JS: Response indicates failure');
                        console.log('TONEKA JS: Full response object:', response);
                        showNotification('Błąd podczas dodawania produktu!');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('TONEKA JS: AJAX Error:', xhr, status, error);
                    showNotification('Błąd podczas dodawania produktu!');
                }
            });
        });
    }

    // Funkcja powiadomień
    function showNotification(message) {
        const notification = $('<div class="toneka-notification">' + message + '</div>');
        $('body').append(notification);

        setTimeout(function() {
            notification.addClass('show');
        }, 100);

        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Funkcja dla dwuetapowego selektora
    function initTwoStepSelector(widget) {
        console.log('TONEKA: Inicjalizuję dwuetapowy selektor');
        
        const twoStepData = JSON.parse(widget.find('.toneka-two-step-data').text());
        const primaryRadios = widget.find('.toneka-primary-radio');
        const secondaryRadios = widget.find('.toneka-secondary-radio');
        const hiddenInput = widget.find('input[name="variation_id"]');
        const cartForm = widget.find('.toneka-cart-form');
        const quantityInput = widget.find('.toneka-quantity-input');
        const quantityMinus = widget.find('.toneka-quantity-minus');
        const quantityPlus = widget.find('.toneka-quantity-plus');
        
        // Kontenery dla informacji o wariancie
        const variationPriceDisplay = widget.find('.toneka-variation-price-display');
        const variationDescriptionDisplay = widget.find('.toneka-variation-description-display');
        
        console.log('TONEKA: Dane dwuetapowe:', twoStepData);
        console.log('TONEKA: Primary radios:', primaryRadios.length);
        console.log('TONEKA: Secondary radios:', secondaryRadios.length);
        
        // Funkcja do aktualizacji wariantu na podstawie wyboru
        function updateSelectedVariation() {
            const primaryValue = primaryRadios.filter(':checked').val();
            const secondaryValue = secondaryRadios.filter(':checked').val();
            
            console.log('TONEKA: Wybrane wartości - Primary:', primaryValue, 'Secondary:', secondaryValue);
            
            if (!primaryValue) {
                console.log('TONEKA: Brak primary value, przerywam');
                return;
            }
            
            // Znajdź odpowiedni wariant
            let variantKey;
            if (twoStepData.secondary_attribute) {
                if (!secondaryValue) {
                    console.log('TONEKA: Wymagany secondary value, ale nie wybrany');
                    return;
                }
                variantKey = primaryValue + '|' + secondaryValue;
            } else {
                variantKey = primaryValue;
            }
                
            const variationData = twoStepData.variations_map[variantKey];
            
            console.log('TONEKA: Klucz wariantu:', variantKey);
            console.log('TONEKA: Dane wariantu:', variationData);
            console.log('TONEKA: Dostępne klucze:', Object.keys(twoStepData.variations_map));
            
            if (variationData) {
                const currentQuantity = parseInt(quantityInput.val()) || 1;
                updateVariationInfoTwoStep(variationData, currentQuantity);
                
                // Aktywuj drugi krok jeśli istnieje
                if (twoStepData.secondary_attribute) {
                    widget.find('.toneka-step-container').removeClass('active');
                    widget.find('.toneka-step-container').addClass('active');
                }
            } else {
                console.log('TONEKA: Nie znaleziono wariantu dla klucza:', variantKey);
            }
        }
        
        // Funkcja do aktualizacji informacji o wariancie (uproszczona wersja)
        function updateVariationInfoTwoStep(variationData, quantity = 1) {
            console.log('TONEKA: Aktualizuję informacje o wariancie:', variationData);
            
            // Pobierz opis wariantu
            const description = variationData.variation_description || 
                               variationData.description || 
                               '';
            
            // Pobierz cenę wariantu
            let priceHtml = variationData.price_html || '';
            const regularPrice = parseFloat(variationData.display_regular_price) || 0;
            const salePrice = parseFloat(variationData.display_price) || regularPrice;
            
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
            
            // Aktualizuj kontenery z informacjami
            variationDescriptionDisplay.html(description);
            variationPriceDisplay.html(priceHtml);
            
            // Zaktualizuj ukryte pole z ID wariantu
            hiddenInput.val(variationData.variation_id);
            
            // Dodaj wszystkie atrybuty wariantu jako ukryte pola w formularzu
            cartForm.find('input[name^="attribute_"]').remove();
            
            if (variationData.attributes) {
                $.each(variationData.attributes, function(attributeName, attributeValue) {
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', attributeName)
                        .val(attributeValue)
                        .appendTo(cartForm);
                });
            }
            
            // Zaktualizuj przycisk dodawania do koszyka
            const addToCartButton = cartForm.find('.toneka-add-to-cart-button');
            addToCartButton.data('variation_id', variationData.variation_id);
            
            if (variationData.is_in_stock === false) {
                addToCartButton.prop('disabled', true).text('Brak na stanie');
            } else {
                addToCartButton.prop('disabled', false).text(addToCartButton.data('original-text') || 'Dodaj do koszyka');
            }
        }
        
        // Funkcja formatowania ceny
        function formatPrice(price) {
            return new Intl.NumberFormat('pl-PL', {
                style: 'currency',
                currency: 'PLN'
            }).format(price);
        }
        
        // Obsługa zmian w pierwszym kroku
        primaryRadios.on('change', function() {
            console.log('TONEKA: Zmiana w primary radio');
            updateSelectedVariation();
        });
        
        // Obsługa zmian w drugim kroku
        secondaryRadios.on('change', function() {
            console.log('TONEKA: Zmiana w secondary radio');
            updateSelectedVariation();
        });
        
        // Obsługa przycisków ilości (usunięte - duplikat z initCarrierSelection)
        // quantityMinus.on('click', function(e) {
        //     e.preventDefault();
        //     const currentValue = parseInt(quantityInput.val()) || 1;
        //     const newValue = Math.max(1, currentValue - 1);
        //     quantityInput.val(newValue);
        //     updateSelectedVariation();
        // });
        
        // quantityPlus.on('click', function(e) {
        //     e.preventDefault();
        //     const currentValue = parseInt(quantityInput.val()) || 1;
        //     const newValue = currentValue + 1;
        //     quantityInput.val(newValue);
        //     updateSelectedVariation();
        // });
        
        // Obsługa formularza
        cartForm.on('submit', function(e) {
            e.preventDefault();
            
            const currentQuantity = parseInt(quantityInput.val()) || 1;
            console.log('🔍 TONEKA DEBUG TWO-STEP: Form submitted with quantity:', currentQuantity);
            console.log('🔍 TONEKA DEBUG TWO-STEP: Quantity is even?', currentQuantity % 2 === 0);
            console.log('🔍 TONEKA DEBUG TWO-STEP: Quantity is odd?', currentQuantity % 2 === 1);
            
            const primarySelected = primaryRadios.filter(':checked').length > 0;
            const secondarySelected = !twoStepData.secondary_attribute || secondaryRadios.filter(':checked').length > 0;
            
            if (!primarySelected || !secondarySelected) {
                alert('Proszę wybrać wszystkie opcje produktu.');
                return false;
            }
            
            const variationId = hiddenInput.val();
            if (!variationId) {
                alert('Błąd: brak ID wariantu.');
                return false;
            }
            
            const productId = wc_add_to_cart_params.product_id;
            const quantity = parseInt(quantityInput.val()) || 1;
            
            console.log('TONEKA JS: Adding to cart - Product ID:', productId, 'Quantity:', quantity, 'Variation ID:', variationId);
            console.log('TONEKA JS: AJAX URL:', wc_add_to_cart_params.ajax_url);
            console.log('TONEKA JS: Nonce:', wc_add_to_cart_params.wc_ajax_nonce);
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'toneka_ajax_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    variation_id: variationId,
                    security: wc_add_to_cart_params.wc_ajax_nonce
                },
                success: function(response) {
                    console.log('🔍 TONEKA JS: AJAX Success - Raw response:', response);
                    console.log('🔍 TONEKA JS: Response type:', typeof response);
                    console.log('🔍 TONEKA JS: Response keys:', Object.keys(response));
                    console.log('🔍 TONEKA JS: Response.success:', response.success);
                    console.log('🔍 TONEKA JS: Response.data:', response.data);
                    console.log('🔍 TONEKA JS: JSON stringify:', JSON.stringify(response));
                    
                    if (response.success) {
                        console.log('🎉 TONEKA JS: Product successfully added to cart');
                        console.log('🎉 TONEKA JS: Fragments received:', response.data.fragments);
                        console.log('🎉 TONEKA JS: Cart count:', response.data.cart_count);
                        // Product successfully added to cart
                        
                        // Update UI fragments (WooCommerce way)
                        if (response.data && response.data.fragments) {
                            console.log('TONEKA JS: Processing fragments:', Object.keys(response.data.fragments));
                            $.each(response.data.fragments, function(key, value) {
                                console.log('TONEKA JS: Looking for element:', key);
                                var $element = $(key);
                                console.log('TONEKA JS: Found elements:', $element.length);
                                console.log('TONEKA JS: Current content:', $element.html());
                                console.log('TONEKA JS: New content:', value);
                                
                                if ($element.length > 0) {
                                    $element.replaceWith(value);
                                    console.log('TONEKA JS: Fragment updated successfully');
                                } else {
                                    console.warn('TONEKA JS: Element not found for selector:', key);
                                }
                            });
                        } else {
                            console.warn('TONEKA JS: No fragments in response');
                        }
                        
                        // Update cart count manually as backup
                        if (response.data.cart_count !== undefined) {
                            console.log('TONEKA JS: Manual cart count update:', response.data.cart_count);
                            var $cartCount = $('.cart-count');
                            console.log('TONEKA JS: Cart count elements found:', $cartCount.length);
                            $cartCount.text(response.data.cart_count);
                            if (response.data.cart_count > 0) {
                                $cartCount.show();
                            } else {
                                $cartCount.hide();
                            }
                        }
                        
                        // Show success message
                        showNotification('Produkt został dodany do koszyka!');
                        
                        // Trigger WooCommerce event - multiple approaches
                        console.log('TONEKA JS: Triggering added_to_cart event on body:', $(document.body));
                        console.log('TONEKA JS: Body length check:', $('body').length);
                        console.log('TONEKA JS: About to trigger with fragments:', response.data.fragments);
                        
                        // Method 1: jQuery trigger on document.body
                        $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash]);
                        console.log('TONEKA JS: Method 1 triggered');
                        
                        // Method 2: jQuery trigger on 'body' selector  
                        $('body').trigger('added_to_cart', [response.data.fragments, response.data.cart_hash]);
                        console.log('TONEKA JS: Method 2 triggered');
                        
                        // Method 3: Native event
                        var nativeEvent = new CustomEvent('added_to_cart', {
                            detail: { fragments: response.data.fragments, cart_hash: response.data.cart_hash }
                        });
                        document.body.dispatchEvent(nativeEvent);
                        console.log('TONEKA JS: Method 3 (native) triggered');
                        
                        // Also trigger our custom event for navigation.js
                        setTimeout(function() {
                            console.log('TONEKA JS: Triggering custom added_to_cart event');
                            document.body.dispatchEvent(new CustomEvent('added_to_cart', {
                                detail: { fragments: response.data.fragments, cart_hash: response.data.cart_hash }
                            }));
                        }, 50);
                        
                        // Direct call to navigation update function as backup
                        setTimeout(function() {
                            console.log('TONEKA JS: Direct cart count update backup');
                            if (typeof window.updateCartCount === 'function') {
                                window.updateCartCount();
                            }
                        }, 100);
                        
                        // Immediate minicart opening (don't wait for events)
                        setTimeout(function() {
                            console.log('TONEKA JS: === IMMEDIATE MINICART OPENING ===');
                            var $minicart = $('.toneka-minicart');
                            var $overlay = $('.toneka-minicart-overlay');
                            var $body = $('body');
                            
                            console.log('TONEKA JS: Minicart element:', $minicart.length, $minicart);
                            console.log('TONEKA JS: Overlay element:', $overlay.length, $overlay);
                            console.log('TONEKA JS: Body element:', $body.length);
                            
                            if ($minicart.length > 0 && $overlay.length > 0) {
                                console.log('TONEKA JS: Adding is-active classes...');
                                
                                // Check current state
                                console.log('TONEKA JS: Minicart current classes:', $minicart.attr('class'));
                                console.log('TONEKA JS: Overlay current classes:', $overlay.attr('class'));
                                console.log('TONEKA JS: Body current classes:', $body.attr('class'));
                                
                                // Add classes
                                $minicart.addClass('is-active');
                                $overlay.addClass('is-active');
                                $body.addClass('minicart-open');
                                
                                // Check after adding
                                console.log('TONEKA JS: Minicart after adding:', $minicart.attr('class'));
                                console.log('TONEKA JS: Overlay after adding:', $overlay.attr('class'));
                                console.log('TONEKA JS: Body after adding:', $body.attr('class'));
                                
                                console.log('TONEKA JS: Is minicart now active?', $minicart.hasClass('is-active'));
                                
                            } else {
                                console.error('TONEKA JS: MINICART ELEMENTS NOT FOUND!');
                                console.log('TONEKA JS: Available elements with "minicart":', $('[class*="minicart"]'));
                            }
                        }, 200);
                        
                        // Manual fallback check after longer delay
                        setTimeout(function() {
                            console.log('TONEKA JS: Manual fallback check - is minicart open?', $('.toneka-minicart').hasClass('is-active'));
                            if (!$('.toneka-minicart').hasClass('is-active')) {
                                console.log('TONEKA JS: WARNING - Minicart still not open after 800ms!');
                            } else {
                                console.log('TONEKA JS: SUCCESS - Minicart is open!');
                            }
                        }, 800);
                        
                    } else {
                        console.log('TONEKA JS: Response indicates failure');
                        console.log('TONEKA JS: Full response object:', response);
                        showNotification('Błąd podczas dodawania produktu!');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('TONEKA JS: AJAX Error:', xhr, status, error);
                    showNotification('Błąd podczas dodawania produktu!');
                }
            });
        });
        
        // Inicjalizacja - ustaw pierwszy wariant
        updateSelectedVariation();
    }

    // Inicjalizuj widget po załadowaniu strony
    initCarrierSelection();
    
    // Reinicjalizuj po aktualizacjach AJAX (np. po zmianie atrybutów)
    $(document).on('updated_wc_div', initCarrierSelection);
});
