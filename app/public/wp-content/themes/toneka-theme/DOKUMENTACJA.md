# Dokumentacja Motywu Toneka Theme

Ten dokument opisuje kluczowe, niestandardowe funkcjonalności zaimplementowane w motywie `toneka-theme`.

## 1. System Twórców

"System Twórców" to centralna funkcjonalność serwisu, która pozwala na automatyczne tworzenie stron poświęconych poszczególnym twórcom oraz dynamiczne powiązywanie ich z produktami (słuchowiskami), w których brali udział.

### a. Struktura Danych

- **Custom Post Type (CPT) `creator`**:
  - Slug: `creator` (URL: `/tworca/imie-nazwisko/`)
  - Główne etykiety: "Twórcy" / "Twórca"
  - Przechowuje informacje o poszczególnych osobach: biografię, zdjęcie wyróżniające itp.

- **Niestandardowe Pola Meta w Produktach WooCommerce**:
  - Do każdego produktu dodano szereg niestandardowych pól (tzw. "repeater fields"), które pozwalają na przypisanie wielu osób do konkretnych ról.
  - Kluczowe pola meta: `_autors`, `_obsada`, `_rezyserzy`, `_muzycy`, `_tlumacze`, `_adaptatorzy`, `_wydawcy`, `_grafika`.
  - Każde pole przechowuje tablicę z imionami i nazwiskami twórców. Pole `_obsada` przechowuje dodatkowo rolę.

### b. Automatyzacja

- **Hook `save_post`**: Po zapisaniu dowolnego posta (w praktyce filtrowane do typu `product`), uruchamiana jest funkcja `toneka_auto_create_creator_pages`.
- **Logika działania**:
  1. Funkcja skanuje wszystkie pola meta twórców (`_autors`, `_obsada` itd.) w zapisanym produkcie.
  2. Dla każdego imienia i nazwiska na liście wywoływana jest funkcja `toneka_get_or_create_creator_page`.
  3. Funkcja ta sprawdza, czy wpis typu `creator` dla danej osoby już istnieje w bazie danych.
  4. Jeśli nie istnieje, tworzy go automatycznie, przypisując imię i nazwisko jako tytuł oraz domyślną treść biografii.

### c. Wyświetlanie na Stronie (Frontend)

- **Strona Produktu (`single-product.php`)**:
  - Dane z pól meta twórców są pobierane i wyświetlane w dedykowanej sekcji.
  - Imię i nazwisko każdego twórcy jest automatycznie zamieniane na link, który prowadzi do jego osobistej strony (`/tworca/...`).

- **Archiwum Twórców (`archive-creator.php`)**:
  - Wyświetla listę wszystkich twórców zapisanych w bazie danych.
  - Adres URL: `/tworcy/` (lub `/tworca/` w zależności od struktury permalinków).

- **Strona Pojedynczego Twórcy (`single-creator.php`)**:
  - Wyświetla biografię twórcy.
  - Zawiera sekcję "Portfolio", która dynamicznie wykonuje zapytanie `WP_Query` do bazy danych, aby znaleźć i wyświetlić wszystkie produkty, z którymi dany twórca jest powiązany.

## 2. Strona Produktu (Single Product)

Strona produktu została zaprojektowana zgodnie z projektem Figma i składa się z kilku kluczowych sekcji:

### a. Struktura Layout'u

- **Hero Section**: Dwukolumnowy layout z tytułem produktu po lewej i obrazem okładki po prawej
- **Product Info Section**: Szczegółowe informacje o produkcie w formie uporządkowanych wierszy z borderami
- **Audio Player**: Sekcja z odtwarzaczem próbek audio
- **Related Products**: Siatka produktów powiązanych

### b. Kluczowe Pliki

- **`woocommerce/single-product.php`**: Główny szablon strony produktu
- **`style.css`**: Kompletne style CSS zgodne z projektem Figma
- **`functions.php`**: Niestandardowy selektor wariantów produktu

### c. Funkcjonalności

- **Automatyczne linkowanie twórców**: Imiona i nazwiska w polach meta są automatycznie linkowane do stron twórców
- **Niestandardowy selektor wariantów**: Zastępuje domyślny formularz WooCommerce eleganckim selektorem w formie tabeli
- **Responsywny design**: Layout dostosowuje się do urządzeń mobilnych
- **Integracja z lokalnymi fontami**: Używa fontu Figtree zgodnie z projektem

### d. Kolorystyka i Typografia

- **Tło**: #000000 (czarny)
- **Tekst**: #ffffff (biały)
- **Bordery**: #ffffff i rgba(255, 255, 255, 0.4)
- **Fonty**: Figtree (główny font dla całej strony)
