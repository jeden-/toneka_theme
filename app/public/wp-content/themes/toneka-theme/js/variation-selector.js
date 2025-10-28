document.addEventListener('DOMContentLoaded', function() {
    const variationRadios = document.querySelectorAll('.toneka-carrier-radio');
    const descriptionDisplay = document.querySelector('.toneka-variation-description-display');
    const priceDisplay = document.querySelector('.toneka-variation-price-display');
    const variationIdInput = document.querySelector('.variation_id');
    const quantityMinus = document.querySelector('.toneka-quantity-minus');
    const quantityPlus = document.querySelector('.toneka-quantity-plus');
    const quantityInput = document.querySelector('.toneka-quantity-input');
    const addToCartButton = document.querySelector('.toneka-add-to-cart-button');
    
    // Pobierz dane wariantów z JSON
    const variationsDataElement = document.querySelector('.toneka-variations-data');
    let variationsData = [];
    
    if (variationsDataElement) {
        try {
            variationsData = JSON.parse(variationsDataElement.textContent);
        } catch (e) {
            console.error('Błąd parsowania danych wariantów:', e);
        }
    }
    
    // Funkcja aktualizująca wyświetlane informacje
    function updateVariationInfo(variationId) {
        const variation = variationsData.find(v => v.variation_id == variationId);
        
        if (variation) {
            // Ukryj wszystkie opisy inline
            const allInlineDescriptions = document.querySelectorAll('.toneka-variation-description-inline');
            allInlineDescriptions.forEach(desc => {
                desc.style.display = 'none';
            });
            
            // Pokaż opis dla wybranego wariantu
            const selectedDescription = document.querySelector(`.toneka-variation-description-inline[data-variation-id="${variationId}"]`);
            if (selectedDescription && variation.variation_description) {
                selectedDescription.style.display = 'block';
            }
            
            // Aktualizuj cenę
            if (priceDisplay && variation.price_html) {
                priceDisplay.innerHTML = variation.price_html;
            }
            
            // Aktualizuj ukryte pole variation_id
            if (variationIdInput) {
                variationIdInput.value = variationId;
            }
            
            // Aktualizuj przycisk dodaj do koszyka
            if (addToCartButton) {
                addToCartButton.value = variationId;
                addToCartButton.disabled = !variation.is_purchasable;
                
                if (variation.is_in_stock) {
                    addToCartButton.textContent = 'DODAJ DO KOSZYKA';
                } else {
                    addToCartButton.textContent = 'BRAK W MAGAZYNIE';
                    addToCartButton.disabled = true;
                }
            }
        }
    }
    
    // Obsługa zmiany wariantu
    variationRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateVariationInfo(this.value);
            }
        });
    });
    
    // Obsługa quantity selector (usunięte - duplikat z carrier-selection-new.js)
    // if (quantityMinus && quantityPlus && quantityInput) {
    //     quantityMinus.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         const currentValue = parseInt(quantityInput.value) || 1;
    //         if (currentValue > 1) {
    //             quantityInput.value = currentValue - 1;
    //         }
    //     });
        
    //     quantityPlus.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         const currentValue = parseInt(quantityInput.value) || 1;
    //         quantityInput.value = currentValue + 1;
    //     });
        
    //     // Walidacja input
    //     quantityInput.addEventListener('change', function() {
    //         const value = parseInt(this.value);
    //         if (isNaN(value) || value < 1) {
    //             this.value = 1;
    //         }
    //     });
    // }
    
    // Inicjalizuj z pierwszym zaznaczonym wariantem
    const checkedRadio = document.querySelector('.toneka-carrier-radio:checked');
    if (checkedRadio) {
        updateVariationInfo(checkedRadio.value);
    } else if (variationsData.length > 0) {
        // Jeśli nie ma zaznaczonego wariantu, wybierz pierwszy i wyświetl jego cenę
        const firstRadio = document.querySelector('.toneka-carrier-radio');
        if (firstRadio) {
            firstRadio.checked = true;
            updateVariationInfo(firstRadio.value);
        }
    }
    
    // Obsługa formularza dodawania do koszyka
    const cartForm = document.querySelector('.toneka-cart-form');
    if (cartForm) {
        cartForm.addEventListener('submit', function(e) {
            const variationId = variationIdInput ? variationIdInput.value : '';
            
            // Dla produktów wariantowych sprawdź czy wybrano wariant
            if (variationsData.length > 0 && !variationId) {
                e.preventDefault();
                alert('Proszę wybrać wariant produktu.');
                return false;
            }
            
            // Dodaj efekt loading do przycisku
            if (addToCartButton) {
                addToCartButton.disabled = true;
                addToCartButton.textContent = 'DODAWANIE...';
                
                // Przywróć normalny stan po 3 sekundach (fallback)
                setTimeout(() => {
                    addToCartButton.disabled = false;
                    addToCartButton.textContent = 'DODAJ DO KOSZYKA';
                }, 3000);
            }
        });
    }
});
