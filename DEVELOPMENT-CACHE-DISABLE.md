# Wyłączenie cachowania dla developmentu

## Zaimplementowane rozwiązania

### 1. Konfiguracja Nginx
- Dodano agresywne cache headers dla wszystkich plików statycznych
- Wyłączono cachowanie dla HTML, CSS, JS, obrazów i fontów
- Dodano headers: `Cache-Control`, `Pragma`, `Expires`, `ETag`, `Last-Modified`

### 2. WordPress Functions
- Dodano funkcje do wyłączania cachowania w `functions.php`
- Cache-busting dla CSS i JS z timestamp
- Meta tags w HTML head
- HTTP headers dla wszystkich stron

## Dodatkowe kroki dla przeglądarek

### Safari
1. **Otwórz Developer Tools** (Cmd+Option+I)
2. **Preferences** → **Advanced** → zaznacz "Show Develop menu"
3. **Develop** → **Disable Caches** (zaznacz)
4. **Develop** → **Empty Caches** (regularnie)

### Chrome
1. **Developer Tools** (F12)
2. **Network** tab → zaznacz "Disable cache"
3. **Settings** (F1) → zaznacz "Disable cache (while DevTools is open)"

### Firefox
1. **Developer Tools** (F12)
2. **Settings** (koło zębate) → zaznacz "Disable HTTP Cache"

## Restart serwera

Po zmianach w nginx należy zrestartować serwer:

```bash
# W Local by Flywheel lub podobnym środowisku
# Restart przez panel administracyjny

# Lub przez terminal (jeśli masz dostęp)
sudo nginx -s reload
```

## Weryfikacja

1. Otwórz Developer Tools
2. Sprawdź Network tab
3. Odśwież stronę (Cmd+R / Ctrl+R)
4. Sprawdź Response Headers - powinny zawierać:
   - `Cache-Control: no-cache, no-store, must-revalidate`
   - `Pragma: no-cache`
   - `Expires: 0`

## Hard Refresh

Używaj hard refresh podczas developmentu:
- **Safari**: Cmd+Option+R
- **Chrome**: Cmd+Shift+R / Ctrl+Shift+R
- **Firefox**: Cmd+Shift+R / Ctrl+Shift+R

## Uwagi

- Te ustawienia są przeznaczone TYLKO dla developmentu
- Przed wdrożeniem na produkcję należy przywrócić normalne cache headers
- Może wpłynąć na wydajność - używaj tylko podczas developmentu
