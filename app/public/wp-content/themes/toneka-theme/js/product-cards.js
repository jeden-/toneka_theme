/**
 * Obsługa klikania kart produktów
 */
document.addEventListener('DOMContentLoaded', function() {
    attachProductCardListeners();
});

function attachProductCardListeners() {
    const productCards = document.querySelectorAll('.toneka-product-card[data-url]');
    
    productCards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            // Sprawdź czy kliknięto w link - jeśli tak, pozwól na domyślne działanie
            if (e.target.tagName === 'A' || e.target.closest('a')) {
                return;
            }
            
            // W przeciwnym razie przekieruj na stronę produktu
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    });
}

// Ponownie dodaj listenery po AJAX (dla dynamicznie ładowanych produktów)
document.addEventListener('toneka_products_updated', function() {
    attachProductCardListeners();
});
