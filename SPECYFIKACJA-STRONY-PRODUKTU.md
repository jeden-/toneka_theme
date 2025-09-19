# Specyfikacja Strony Produktu WooCommerce - Toneka Theme

## 1. Informacje Ogólne

**Cel dokumentu:** Szczegółowa specyfikacja strony produktu dla słuchowisk w sklepie WooCommerce, zgodna z projektem Figma.

**Plik docelowy:** `woocommerce/single-product.php`

**Wymiary projektu:** 100% szerokość ekranu (desktop), podział 50/50

**Breakpoint mobile:** 768px i mniej

## 2. Struktura Layoutu Głównego

### 2.1 Hero Section (Sekcja główna)
**Layout:** Dwukolumnowy grid (50% + 50% szerokości ekranu)
**Wysokość:** Idealne kwadraty - wysokość = 50vw (50% szerokości viewport)

#### Kolumna lewa (50% szerokości):
- **Header/Navigation:** 
  - Wysokość: 82px
  - Logo Toneka (140px × 18px) w pozycji lewej (padding: 40px)
  - Border dolny: 1px solid #ffffff

- **Content Area:**
  - Padding: 140px (lewy/prawy), 0px (góra/dół)
  - Wysokość: 550px
  - Ikona kategorii: 92px × 92px
  - Tytuł produktu: font Lato, 80px, uppercase, biały
  - Przycisk "POSŁUCHAJ" z ikoną strzałki w dół

#### Kolumna prawa (50% szerokości):
- **Header/Navigation:**
  - Duplikacja nawigacji z lewej kolumny
  - Dodatkowe pozycje menu: Home, Słuchowiska, Design, Muzyka, Kontakt, Zaloguj się

- **Okładka produktu:**
  - Pozycja: wycentrowana w kolumnie
  - Wymiary: proporcjonalne do rozmiaru kolumny (max 600px × 600px)
  - Tło czarne z borderem dolnym

### 2.2 Product Info Section (Sekcja informacji o produkcie)
**Layout:** Dwukolumnowy grid (50% + 50% szerokości ekranu)

#### Kolumna lewa - Info Box (50% szerokości):
- **Padding:** responsywne (140px na desktop, mniej na mniejszych ekranach)
- **Wysokość:** auto (dostosowana do zawartości)
- **Border:** prawy 1px solid #ffffff

**Zawartość:**
1. **Tytuł produktu** (wysokość: 42.584px)
   - Tekst: Nazwa produktu
   - Font: Figtree, 20px, uppercase, biały
   - Border dolny: 1px solid #ffffff

2. **Typ produktu** (wysokość: 42.584px)
   - Ikona słuchawek (24px × 24px)
   - Tekst: "SŁUCHOWISKO"
   - Czas trwania: "124 min"
   - Border dolny: 1px solid rgba(255,255,255,0.4)

3. **Metadane produktu** (każdy wiersz 42.584px):
   - Autor: "autor: [nazwa]"
   - Tłumacz: "tłumacz: [nazwa]"
   - Adaptacja tekstu: "adaptacja tekstu: [nazwa]"
   - Reżyseria: "reżyseria: [nazwa]"
   - Obsada: "obsada: [nazwa]"
   - Muzyka: "muzyka: [nazwa]"
   - Wydawca i data: "wydawca: toneka" | "data wydania: [data]"

4. **Opis produktu** (wysokość: 153px)
   - Tekst opisu: 16px, normalny
   - Przycisk "WIĘCEJ" z ikoną strzałki
   - Border dolny: 1px solid rgba(255,255,255,0.4)

5. **Tagi** (wysokość: 42.584px)
   - Font: Poppins, 12px, uppercase
   - Kolor: rgba(255,255,255,0.4)
   - Format: "tagi: [lista tagów]"

6. **Tabela wariantów produktu** (wysokość: 144px)
   - Tło: rgba(54,54,54,0.12)
   - Szerokość: 254px
   - Bordery: 1px solid rgba(255,255,255,0.4)
   
   **Wiersze tabeli:**
   - CD | 12
   - Kaseta | Wybierz ile sztuk
   - Plik cyfrowy | Wybierz format
   - Winyl | Wybierz ile sztuk

7. **Przycisk koszyka**
   - Wymiary: 204px szerokość
   - Tekst: "DODAJ DO KOSZYKA"
   - Style: border 1px solid #ffffff, border-radius: 100px
   - Tło: czarne

#### Kolumna prawa - Audio Frame (50% szerokości):
- **Padding:** responsywne (140px na desktop, mniej na mniejszych ekranach)
- **Wysokość:** auto (dostosowana do zawartości)

**Zawartość:**
1. **Odtwarzacz audio** (430px × 432px)
   - Placeholder obszar: 429px × 273px, tło #3e3e3e, border-radius: 12px
   - Przyciski sterowania:
     - Play/Pause (24px × 24px)
     - Przewijanie (24px × 24px) 
     - Kontrolki głośności (24px × 24px)
   
2. **Informacje o fragmencie**
   - Tytuł: "KWIAT PAPROCI"
   - Czas: "0:10 /0:40"
   - Font: Poppins, 12px

3. **Sekcja "ODTWÓRZ FRAGMENT"**
   - Font: Figtree, 14px, uppercase, biały
   - Border górny: 1px solid #ffffff

## 3. Related Products Section (Produkty powiązane)

### 3.1 Nagłówek sekcji
- **Wysokość:** 82px
- **Tekst:** "SŁUCHOWISKA, KSIĄŻKI, SZTUKA"
- **Font:** Lato, 25px, uppercase, biały
- **Ikona:** strzałka obrócona o 90 stopni
- **Border:** górny i dolny 1px solid #ffffff

### 3.2 Siatka produktów
**Layout:** 3 kolumny × 2 wiersze (33.33% szerokości każda kolumna)

**Struktura każdego produktu:**
1. **Header produktu** (wysokość: 36px)
   - Kategoria produktu (np. "TORBA", "AUDIO BOOK", "SKARPETKI", "POSTER")
   - Font: Figtree, 14px, uppercase, biały
   - Padding: 12px

2. **Obszar zdjęcia** (wysokość: proporcjonalna)
   - Padding: responsywny
   - Zdjęcie produktu: kwadratowe, dopasowane do kolumny
   - Tło: czarne

3. **Footer produktu** (wysokość: 42px)
   - Lewa strona: "KUP" (Font: Poppins, 20px, uppercase)
   - Prawa strona: Cena "30 PLN" (Font: Poppins, 20px, uppercase)
   - Padding: 12px

### 3.3 Nagłówek "WIĘCEJ"
- **Wysokość:** 82px
- **Tekst:** "WIĘCEJ"
- **Font:** Lato, 25px, uppercase, biały
- **Border:** górny i dolny 1px solid #ffffff

## 4. Footer
**Wysokość:** 532px
**Struktura zgodna z ogólnym layoutem strony**

## 5. Kolorystyka

### 5.1 Kolory główne
- **Tło:** #000000 (czarny)
- **Tekst główny:** #ffffff (biały)
- **Tekst drugorzędny:** #969696 (szary)
- **Tekst wyblakły:** rgba(255,255,255,0.4)
- **Bordery główne:** #ffffff (białe)
- **Bordery drugorzędne:** rgba(255,255,255,0.4)
- **Tło elementów:** rgba(54,54,54,0.12)
- **Tło odtwarzacza:** #3e3e3e

### 5.2 Kolory interakcji
- **Hover:** (do zdefiniowania w implementacji)
- **Active:** (do zdefiniowania w implementacji)
- **Focus:** (do zdefiniowania w implementacji)

## 6. Typografia

### 6.1 Fonty
- **Główny:** Figtree (Regular, Normal)
- **Tytuły:** Lato (Regular, Not-italic)
- **Akcenty:** Poppins (Regular, Not-italic)
- **Ikony:** Font Awesome 6 Free (Solid)

### 6.2 Rozmiary fontów
- **Tytuł główny:** 80px, uppercase, line-height: 1.1
- **Tytuły sekcji:** 25px, uppercase, line-height: 1.4
- **Tytuły produktów:** 20px, uppercase, line-height: 1.4
- **Metadane:** 14.01px, uppercase, line-height: 1.4
- **Opis:** 16px, normal, line-height: 1.4
- **Tagi:** 12px, uppercase, line-height: 1.4
- **Przyciski:** 14px, uppercase, line-height: 1.4

### 6.3 Odstępy liter (letter-spacing)
- **Przyciski:** 1.44px
- **Tabela:** 0.28px

## 7. Komponenty i Funkcjonalności

### 7.1 Przycisk "POSŁUCHAJ"
- **Style:** gap: 12px, padding: 8px, border-radius: 8px
- **Zawartość:** tekst + ikona strzałki w dół
- **Stan:** Default/Variant2/down

### 7.2 Odtwarzacz audio
- **Komponenty:**
  - Obszar wizualizacji audio
  - Kontrolki play/pause/skip
  - Kontrolki głośności
  - Informacje o utworze i czasie

### 7.3 Tabela wariantów produktu
- **Struktura:** 2 kolumny (Typ | Opcje)
- **Integracja z WooCommerce:** custom selector dla wariantów
- **Responsive:** zachowanie na urządzeniach mobilnych

### 7.4 Przycisk rozwijania opisu
- **Funkcja:** toggle między skróconym a pełnym opisem
- **Ikona:** strzałka zmieniająca kierunek
- **Tekst:** "WIĘCEJ" / "MNIEJ"

## 8. Responsywność

### 8.1 Desktop (>1024px)
- **Layout:** dwukolumnowy grid 50/50%
- **Hero section:** idealne kwadraty (wysokość = 50vw)
- **Max-width:** opcjonalne ograniczenie dla bardzo szerokich ekranów

### 8.2 Tablet (769px - 1024px)
- **Hero section:** zachowanie kwadratów, ale mniejszy padding
- **Related products:** 2 kolumny zamiast 3
- **Zachowanie proporcji:** utrzymanie ogólnego layoutu

### 8.3 Breakpoint mobilny (≤768px)
- **Layout:** zmiana z dwukolumnowego na jednokolumnowy
- **Hero section:** układanie sekcji pionowo, wysokość auto
- **Product info:** pełna szerokość, jeden pod drugim
- **Related products:** 1 kolumna
- **Fonty:** dostosowanie rozmiarów dla lepszej czytelności
- **Padding:** znacznie zredukowany dla mobile

## 9. Integracja z WooCommerce

### 9.1 Hooks i funkcje
- **woocommerce_breadcrumb():** nawigacja okruchowa
- **the_title():** tytuł produktu
- **woocommerce_template_single_excerpt():** krótki opis
- **the_content():** pełny opis produktu

### 9.2 Custom funkcje (functions.php)
- **toneka_show_product_images_custom():** niestandardowe wyświetlanie galerii
- **toneka_display_product_samples_player():** odtwarzacz próbek
- **toneka_output_variable_product_selector():** selektor wariantów

### 9.3 Metadane produktu
- **Pola custom:** `_autors`, `_obsada`, `_rezyserzy`, `_muzycy`, `_tlumacze`, `_adaptatorzy`, `_wydawcy`, `_grafika`
- **Automatyczne linkowanie:** twórców do ich stron

## 10. Pliki i struktura

### 10.1 Główne pliki
- **Template:** `woocommerce/single-product.php`
- **Style:** `style.css` (sekcja single-product)
- **Funkcje:** `functions.php` (toneka custom functions)

### 10.2 Zależności
- **Google Fonts:** Figtree, Lato, Poppins
- **Font Awesome:** ikony (w przypadku braku natywnych)
- **JavaScript:** obsługa odtwarzacza i interakcji

### 10.3 Optymalizacja
- **Lazy loading:** obrazy produktów
- **Minifikacja:** CSS i JS
- **Cache:** optimalizacja WooCommerce

## 11. Notatki implementacyjne

### 11.1 Priorytet zgodności
1. **Pixel-perfect:** zgodność z projektem Figma
2. **Funkcjonalność:** pełna integracja z WooCommerce
3. **Performance:** optymalizacja ładowania
4. **Accessibility:** zgodność z WCAG

### 11.2 Testowanie
- **Browsers:** Chrome, Firefox, Safari, Edge
- **Devices:** Desktop, Tablet, Mobile
- **WooCommerce:** compatibility z najnowszą wersją
- **WordPress:** compatibility z najnowszą wersją

### 11.3 Uwagi techniczne
- **Grid system:** CSS Grid dla głównego layoutu (grid-template-columns: 1fr 1fr)
- **Viewport units:** vw dla idealnych kwadratów hero section
- **Flexbox:** dla komponentów wewnętrznych
- **Custom properties:** CSS variables dla kolorów i rozmiarów
- **BEM methodology:** dla nazewnictwa klas CSS
- **Responsive design:** mobile-first approach z media queries

### 11.4 CSS Hero Section - Kluczowe reguły
```css
.hero-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  height: 50vw; /* Idealne kwadraty */
  width: 100%;
}

@media (max-width: 768px) {
  .hero-section {
    grid-template-columns: 1fr;
    height: auto;
  }
}
```
