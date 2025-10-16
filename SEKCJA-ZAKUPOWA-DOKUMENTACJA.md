# Dokumentacja Sekcji Zakupowej - Toneka Theme

## Przegląd
Zaimplementowana została kompletna sekcja zakupowa zgodna z designem Figma, która obsługuje zarówno produkty wariantowe (z różnymi opcjami) jak i produkty proste.

## Funkcjonalności

### ✅ Selektor Wariantów
- **Radio buttons** z custom stylingiem (białe kropki)
- **Automatyczne mapowanie** nazw wariantów:
  - `cd` → `CD`
  - `kaseta-magnetofonowa` → `Kaseta magnetofonowa`
  - `plyta-winylowa` → `Płyta winylowa`
  - `pliki-cyfrowe` → `Pliki cyfrowe`
- **Obsługa różnych atrybutów**: nośniki, rozmiary, kolory, itp.

### ✅ Dynamiczne Informacje
- **Opis wariantu** - zmienia się w zależności od wyboru
- **Cena** - aktualizuje się automatycznie (z obsługą promocji)
- **Status dostępności** - sprawdza czy wariant jest w magazynie

### ✅ Sekcja Zakupowa
- **Quantity selector** z przyciskami +/- w stylu designu
- **Przycisk "DODAJ DO KOSZYKA"** z hover effectami
- **Loading state** podczas dodawania do koszyka
- **Walidacja** - sprawdza czy wybrano wariant

### ✅ Responsywność
- **Mobile-first design**
- **Elastyczny layout** - na mobile quantity i przycisk układają się pionowo
- **Dostosowane rozmiary** tekstu i elementów

## Pliki

### PHP
- **`functions.php`** - funkcja `toneka_output_variable_product_selector()`
- **`single.php`** - wywołuje sekcję zakupową

### CSS
- **`style.css`** - sekcja "SEKCJA ZAKUPOWA" (linie ~976-1158)
- **Classes**: `.toneka-carrier-*`, `.toneka-variation-*`, `.toneka-cart-*`

### JavaScript
- **`js/variation-selector.js`** - obsługa selektora wariantów
- **`js/description-toggle.js`** - obsługa rozwijania opisu

## Jak Dodać Nowe Mapowania

W pliku `functions.php`, w funkcji `toneka_output_variable_product_selector()`:

```php
$value_mapping = array(
    'cd' => 'CD',
    'kaseta-magnetofonowa' => 'Kaseta magnetofonowa',
    'plyta-winylowa' => 'Płyta winylowa',
    'pliki-cyfrowe' => 'Pliki cyfrowe',
    // Dodaj nowe mapowania tutaj:
    'nowy-slug' => 'Nowa Nazwa',
);
```

## Struktura HTML

```html
<div class="toneka-carrier-selection-widget">
    <h3 class="toneka-carrier-title">Wybierz:</h3>
    
    <!-- Radio buttons -->
    <div class="toneka-carrier-options">
        <label class="toneka-carrier-option">
            <input type="radio" class="toneka-carrier-radio">
            <span class="toneka-carrier-radio-custom"></span>
            <span class="toneka-carrier-label">CD</span>
        </label>
    </div>
    
    <!-- Dynamiczne informacje -->
    <div class="toneka-variation-info-container">
        <div class="toneka-variation-description-display"></div>
        <div class="toneka-variation-price-display"></div>
    </div>
    
    <!-- Sekcja zakupowa -->
    <div class="toneka-cart-section">
        <div class="toneka-quantity-section">
            <div class="toneka-quantity-wrapper">
                <button class="toneka-quantity-minus">−</button>
                <input class="toneka-quantity-input" value="1">
                <button class="toneka-quantity-plus">+</button>
            </div>
        </div>
        <button class="toneka-add-to-cart-button">DODAJ DO KOSZYKA</button>
    </div>
</div>
```

## Kolory i Style

- **Tekst**: `#ffffff` (biały)
- **Akcenty**: `#969696` (szary)
- **Radio buttons**: Border `#969696`, fill `#ffffff`
- **Hover effects**: `opacity: 0.8`
- **Transitions**: `0.3s ease`

## Testowanie

System został przetestowany na:
- ✅ Produkty wariantowe (Funia rękawiczka - Kopia)
- ✅ Produkty proste (Funia rękawiczka)
- ✅ Różne typy wariantów (nośniki)
- ✅ Responsywność (mobile/desktop)
- ✅ Obsługa błędów i walidacja

## Zgodność z WooCommerce

- **Pełna integracja** z WooCommerce hooks
- **Zachowanie standardowych funkcji**: dodawanie do koszyka, inventory management
- **SEO-friendly**: właściwe meta tags i structured data
- **Accessibility**: keyboard navigation, screen readers

---

**Status**: ✅ KOMPLETNE - gotowe do użycia
**Ostatnia aktualizacja**: Wrzesień 2025




























