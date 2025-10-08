# TONEKA Mobile App

Aplikacja mobilna React Native dla platformy Toneka - sklep z słuchowiskami i merch.

## 🚀 Funkcjonalności

### ✅ Zaimplementowane
- **Design System** - Kompletny system designu bazowany na Figma
- **Navigation** - Bottom tabs + stack navigation
- **Authentication** - Logowanie i rejestracja przez WooCommerce
- **Home Screen** - Hero section z featured produktem
- **Library Screen** - Biblioteka kupionych utworów
- **Shop Screen** - Sklep z fragmentami i kategoriami
- **Player Screen** - Odtwarzacz audio z kontrolkami
- **Profile Screen** - Zarządzanie profilem użytkownika
- **WooCommerce API** - Pełna integracja z WooCommerce
- **Audio Service** - Odtwarzanie audio z TrackPlayer
- **UI Components** - Reużywalne komponenty (Button, Text, Input, Card)

### 🎨 Design
- **Dark Theme** - Czarne tło, białe teksty
- **Typography** - Figtree font (jak w web)
- **Components** - Spójne z designem Figma
- **Responsive** - Dostosowane do różnych rozmiarów ekranów

## 📱 Ekrany

### 1. Home Screen
- Hero section z featured produktem
- Najnowsze słuchowiska (3 produkty)
- Najnowszy merch (3 produkty)
- Hamburger menu

### 2. Library Screen
- Lista kupionych utworów
- Odtwarzanie playlisty
- Empty state z linkiem do sklepu

### 3. Shop Screen
- Filtry kategorii
- Lista produktów
- Fragmenty audio
- Przycisk kupna

### 4. Player Screen
- Album art
- Kontrolki odtwarzania
- Progress bar
- Informacje o utworze

### 5. Profile Screen
- Dane użytkownika
- Edycja profilu
- Historia zakupów
- Wylogowanie

## 🔧 Instalacja

### Wymagania
- Node.js 16+
- React Native CLI
- Android Studio (dla Android)
- Xcode (dla iOS)

### Kroki instalacji

1. **Klonowanie i instalacja zależności**
```bash
cd TonekaApp
npm install
```

2. **iOS (jeśli potrzebne)**
```bash
cd ios
pod install
cd ..
```

3. **Uruchomienie**
```bash
# Android
npm run android

# iOS
npm run ios
```

## 🔗 Integracja z WooCommerce

### Konfiguracja API
Edytuj plik `src/services/WooCommerceAPI.js`:

```javascript
this.baseURL = 'https://toneka.pl/wp-json/wc/v3';
this.consumerKey = 'your_consumer_key';
this.consumerSecret = 'your_consumer_secret';
```

### Wymagane pluginy WordPress
- WooCommerce
- JWT Authentication for WP REST API
- WooCommerce REST API

### Endpointy API
- `GET /wp-json/wc/v3/products` - Produkty
- `GET /wp-json/wc/v3/orders` - Zamówienia
- `POST /wp-json/jwt-auth/v1/token` - Logowanie
- `POST /wp-json/wp/v2/users` - Rejestracja

## 🎵 Audio

### TrackPlayer
- Background playback
- Notification controls
- Playlist management
- Seek functionality

### Formaty audio
- MP3, WAV, M4A
- Streaming z URL
- Offline playback (kupione utwory)

## 🎨 Design System

### Kolory
```javascript
primary: '#000000'      // Czarne tło
secondary: '#FFFFFF'    // Biały tekst
accent: '#404040'       // Szare elementy
```

### Typography
```javascript
h1: 42px, uppercase    // Hero titles
h2: 24px, uppercase    // Section titles
body: 16px             // Body text
caption: 12px          // Captions
```

### Spacing
```javascript
xs: 4px, sm: 8px, md: 12px, lg: 16px, xl: 20px, 2xl: 24px
```

## 📦 Struktura projektu

```
src/
├── components/          # Reużywalne komponenty
│   ├── common/         # Podstawowe komponenty
│   ├── audio/          # Audio komponenty
│   └── shop/           # Sklep komponenty
├── screens/            # Główne ekrany
│   ├── Home/           # Strona główna
│   ├── Library/        # Biblioteka
│   ├── Shop/           # Sklep
│   ├── Player/         # Odtwarzacz
│   ├── Profile/        # Profil
│   └── Auth/           # Autentykacja
├── services/           # API i serwisy
│   ├── WooCommerceAPI.js
│   ├── AudioService.js
│   └── AuthService.js
├── navigation/         # Nawigacja
├── theme/             # Design system
└── assets/            # Obrazy, ikony
```

## 🚀 Deployment

### Android
1. Generuj signed APK
2. Upload do Google Play Console
3. Konfiguruj release notes

### iOS
1. Archive w Xcode
2. Upload do App Store Connect
3. Submit for review

## 🔒 Bezpieczeństwo

- JWT tokens dla autentykacji
- Secure storage dla danych użytkownika
- HTTPS dla wszystkich API calls
- Input validation

## 📱 Testowanie

### Urządzenia testowe
- Android: API 21+ (Android 5.0+)
- iOS: iOS 11+

### Testowane funkcje
- ✅ Navigation
- ✅ Authentication
- ✅ Audio playback
- ✅ WooCommerce integration
- ✅ UI components

## 🐛 Znane problemy

1. **CocoaPods** - Problemy z architekturą na M1 Mac
2. **TrackPlayer** - Wymaga dodatkowej konfiguracji iOS
3. **Vector Icons** - Wymaga linkowania natywnego

## 📞 Support

W przypadku problemów:
1. Sprawdź logi w Metro bundler
2. Sprawdź logi w Xcode/Android Studio
3. Sprawdź konfigurację WooCommerce API

## 🎯 Następne kroki

- [ ] Implementacja płatności (Stripe)
- [ ] Push notifications
- [ ] Offline mode
- [ ] Social sharing
- [ ] Analytics
- [ ] Crash reporting

---

**Aplikacja gotowa do testowania!** 🎉