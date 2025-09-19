jQuery(document).ready(function($) {
    'use strict';

    // Otwieranie minikoszyka
    // Potrzebujemy selektora, który będzie otwierał koszyk. Na razie załóżmy, że będzie to link z klasą .cart-contents
    $(document).on('click', '.cart-contents', function(e) {
        e.preventDefault();
        $('.toneka-minicart').addClass('is-active');
        $('.minicart-overlay').addClass('is-active');
    });

    // Zamykanie minikoszyka
    $(document).on('click', '.minicart-close, .minicart-overlay', function(e) {
        e.preventDefault();
        $('.toneka-minicart').removeClass('is-active');
        $('.minicart-overlay').removeClass('is-active');
    });

    // Aktualizacja minikoszyka po dodaniu produktu przez AJAX
    $(document.body).on('added_to_cart', function() {
        // Opcjonalnie: automatycznie otwórz minikoszyk po dodaniu produktu
        // $('.toneka-minicart').addClass('is-active');
        // $('.minicart-overlay').addClass('is-active');
    });

});

