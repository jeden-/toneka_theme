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
            const selectedRadio = radios.filter(':checked');
            if (!selectedRadio.length) {
                e.preventDefault();
                alert('Proszę wybrać wariant produktu.');
                return false;
            }
            
            // Sprawdź czy formularz ma wszystkie potrzebne dane
            const variationId = hiddenInput.val();
            if (!variationId) {
                e.preventDefault();
                alert('Błąd: brak ID wariantu.');
                return false;
            }
        });
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
        
        // Obsługa przycisków ilości
        quantityMinus.on('click', function(e) {
            e.preventDefault();
            const currentValue = parseInt(quantityInput.val()) || 1;
            const newValue = Math.max(1, currentValue - 1);
            quantityInput.val(newValue);
            updateSelectedVariation();
        });
        
        quantityPlus.on('click', function(e) {
            e.preventDefault();
            const currentValue = parseInt(quantityInput.val()) || 1;
            const newValue = currentValue + 1;
            quantityInput.val(newValue);
            updateSelectedVariation();
        });
        
        // Obsługa formularza
        cartForm.on('submit', function(e) {
            console.log('TONEKA: Przesyłanie formularza dwuetapowego selektora');
            
            const primarySelected = primaryRadios.filter(':checked').length > 0;
            const secondarySelected = !twoStepData.secondary_attribute || secondaryRadios.filter(':checked').length > 0;
            
            console.log('TONEKA: Primary selected:', primarySelected);
            console.log('TONEKA: Secondary selected:', secondarySelected);
            console.log('TONEKA: Secondary attribute exists:', !!twoStepData.secondary_attribute);
            
            if (!primarySelected || !secondarySelected) {
                e.preventDefault();
                alert('Proszę wybrać wszystkie opcje produktu.');
                console.log('TONEKA: Zatrzymano - nie wszystkie opcje wybrane');
                return false;
            }
            
            const variationId = hiddenInput.val();
            console.log('TONEKA: Variation ID:', variationId);
            
            if (!variationId) {
                e.preventDefault();
                alert('Błąd: brak ID wariantu.');
                console.log('TONEKA: Zatrzymano - brak variation ID');
                return false;
            }
            
            // Debug - sprawdź wszystkie pola formularza
            console.log('TONEKA: Pola formularza:');
            cartForm.find('input').each(function() {
                console.log('- ' + this.name + ':', this.value);
            });
            
            console.log('TONEKA: Formularz przeszedł walidację, wysyłanie...');
        });
        
        // Inicjalizacja - ustaw pierwszy wariant
        updateSelectedVariation();
    }

    // Inicjalizuj widget po załadowaniu strony
    initCarrierSelection();
    
    // Reinicjalizuj po aktualizacjach AJAX (np. po zmianie atrybutów)
    $(document).on('updated_wc_div', initCarrierSelection);
});
