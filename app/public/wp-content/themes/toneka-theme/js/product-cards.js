/**
 * Obsługa klikania kart produktów i postów
 */
document.addEventListener('DOMContentLoaded', function() {
    attachProductCardListeners();
    attachPostCardListeners();
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

function attachPostCardListeners() {
    const postCards = document.querySelectorAll('.toneka-post-card[data-url]');
    
    postCards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            // Sprawdź czy kliknięto w link - jeśli tak, pozwól na domyślne działanie
            if (e.target.tagName === 'A' || e.target.closest('a')) {
                return;
            }
            
            // W przeciwnym razie przekieruj na stronę posta
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
