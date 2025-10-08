# 🧪 Przewodnik testowania aplikacji Toneka

## 📱 Testowanie na urządzeniu fizycznym

### iPhone (iOS)

#### Opcja 1: Expo Go (Najłatwiejsza)
1. Zainstaluj **Expo Go** z App Store
2. Uruchom Metro bundler: `npm run start`
3. Zeskanuj QR kod w aplikacji Expo Go
4. Aplikacja załaduje się na telefonie

#### Opcja 2: TestFlight (Produkcyjna)
1. Zbuduj aplikację w Xcode
2. Upload do App Store Connect
3. Dodaj testerów do TestFlight
4. Testerzy otrzymają link do instalacji

#### Opcja 3: Development Build
1. Zainstaluj Xcode z App Store
2. Podłącz iPhone do Maca
3. Uruchom: `npx react-native run-ios --device`
4. Aplikacja zainstaluje się bezpośrednio na telefonie

### Android

#### Opcja 1: APK bezpośrednio
1. Zbuduj APK: `cd android && ./gradlew assembleRelease`
2. Przenieś APK na telefon
3. Zainstaluj z ustawień (zezwól na instalację z nieznanych źródeł)

#### Opcja 2: ADB (Android Debug Bridge)
1. Włącz tryb deweloperski na telefonie
2. Podłącz telefon do komputera
3. Uruchom: `npx react-native run-android`

## 🔧 Konfiguracja dla testów

### 1. WooCommerce API
Edytuj `src/services/WooCommerceAPI.js`:
```javascript
this.baseURL = 'https://toneka.pl/wp-json/wc/v3';
this.consumerKey = 'ck_your_key_here';
this.consumerSecret = 'cs_your_secret_here';
```

### 2. Testowe konto użytkownika
Utwórz testowe konto w WordPress:
- Email: test@toneka.pl
- Hasło: test123456

### 3. Testowe produkty
Dodaj produkty w WooCommerce:
- Kategoria: "słuchowiska"
- Kategoria: "merch"
- Dodaj meta fields: `_sample_audio`, `_audio_file`, `_duration_minutes`

## 🧪 Scenariusze testowe

### ✅ Testowanie autentykacji
1. **Rejestracja**
   - Otwórz aplikację
   - Przejdź do "PROFIL"
   - Kliknij "Zarejestruj się"
   - Wypełnij formularz
   - Sprawdź czy rejestracja się powiodła

2. **Logowanie**
   - Wprowadź dane testowe
   - Sprawdź czy logowanie działa
   - Sprawdź czy użytkownik jest zalogowany

### ✅ Testowanie sklepu
1. **Przeglądanie produktów**
   - Przejdź do "SKLEP"
   - Sprawdź czy produkty się ładują
   - Przetestuj filtry kategorii
   - Sprawdź czy fragmenty się odtwarzają

2. **Szczegóły produktu**
   - Kliknij na produkt
   - Sprawdź czy szczegóły się ładują
   - Przetestuj odtwarzanie fragmentu
   - Sprawdź przycisk kupna

### ✅ Testowanie odtwarzacza
1. **Odtwarzanie fragmentu**
   - Wybierz produkt ze sklepu
   - Kliknij "POSŁUCHAJ FRAGMENTU"
   - Sprawdź czy odtwarzacz się otwiera
   - Przetestuj kontrolki (play/pause/next/prev)

2. **Playlista**
   - Przejdź do "BIBLIOTEKA"
   - Kliknij "ODTWÓRZ WSZYSTKO"
   - Sprawdź czy playlist się odtwarza
   - Przetestuj przełączanie między utworami

### ✅ Testowanie UI/UX
1. **Nawigacja**
   - Przetestuj wszystkie zakładki
   - Sprawdź czy przejścia są płynne
   - Przetestuj przycisk "wstecz"

2. **Responsywność**
   - Obróć telefon (portrait/landscape)
   - Sprawdź czy layout się dostosowuje
   - Przetestuj na różnych rozmiarach ekranów

3. **Design**
   - Sprawdź czy kolory są zgodne z Figma
   - Przetestuj czcionki i rozmiary
   - Sprawdź czy animacje działają

## 🐛 Znane problemy i rozwiązania

### Problem: Aplikacja się nie ładuje
**Rozwiązanie:**
1. Sprawdź czy Metro bundler działa
2. Sprawdź połączenie internetowe
3. Sprawdź logi w konsoli

### Problem: Audio się nie odtwarza
**Rozwiązanie:**
1. Sprawdź czy URL audio jest prawidłowy
2. Sprawdź czy TrackPlayer jest skonfigurowany
3. Sprawdź uprawnienia do audio

### Problem: API nie działa
**Rozwiązanie:**
1. Sprawdź czy WooCommerce API jest aktywne
2. Sprawdź klucze API
3. Sprawdź czy endpointy są dostępne

### Problem: Nawigacja nie działa
**Rozwiązanie:**
1. Sprawdź czy React Navigation jest zainstalowane
2. Sprawdź konfigurację nawigacji
3. Sprawdź czy ekrany są poprawnie zarejestrowane

## 📊 Metryki do sprawdzenia

### Performance
- [ ] Czas ładowania aplikacji
- [ ] Czas ładowania ekranów
- [ ] Płynność animacji
- [ ] Zużycie pamięci

### Funkcjonalność
- [ ] Wszystkie przyciski działają
- [ ] Formularze się wysyłają
- [ ] Audio się odtwarza
- [ ] Nawigacja działa

### UX
- [ ] Intuicyjność interfejsu
- [ ] Spójność designu
- [ ] Responsywność
- [ ] Dostępność

## 🚀 Następne kroki po testach

1. **Zbierz feedback** od testerów
2. **Napraw błędy** znalezione podczas testów
3. **Zoptymalizuj performance** jeśli potrzeba
4. **Przygotuj do publikacji** w sklepach

## 📞 Kontakt

W przypadku problemów z testowaniem:
- Sprawdź logi w Metro bundler
- Sprawdź logi w przeglądarce (Expo Go)
- Sprawdź konfigurację API

---

**Powodzenia w testowaniu!** 🎉
