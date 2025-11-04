# Lista wszystkich animacji w projekcie

## 1. fadeIn
**Definicja:** `@keyframes fadeIn` (linia 121-128)
- `from { opacity: 0; }`
- `to { opacity: 1; }`

**Użycia:**
- `.toneka-product-card:not(.toneka-ajax-loaded)` - `animation: fadeIn 0.3s ease-out;` (linia 154)
- `.toneka-category-filter-item` - `animation: fadeIn 0.3s ease-out;` (linia 154)
- `.woocommerce-pagination .page-numbers` - `animation: fadeIn 0.3s ease-out;` (linia 154)
- `.toneka-ajax-pagination .page-numbers` - `animation: fadeIn 0.3s ease-out;` (linia 154)
- `.toneka-minicart-checkbox-indicator` - `animation: fadeIn 0.2s ease;` (linia 2625)

**Status:** Używana w 5 miejscach

---

## 2. fadeInUp
**Definicja:** `@keyframes fadeInUp` (linia 130-137)
- `from { opacity: 0; }`
- `to { opacity: 1; }`
- **UWAGA:** Definicja nie zawiera transform: translateY, mimo nazwy "Up"

**Użycia:** BRAK UŻYĆ ❌

**Status:** Nieużywana - można usunąć

---

## 3. pulse
**Definicja:** `@keyframes pulse` (linia 139-146)
- `0%, 100% { opacity: 1; }`
- `50% { opacity: 0.7; }`

**Użycia:** BRAK UŻYĆ ❌

**Status:** Nieużywana - można usunąć

---

## 4. spin
**Definicja:** `@keyframes spin` - ZDEFINIOWANA 3 RAZY (duplikacja!)
- Linia 212-215: `0% { transform: rotate(0deg); }` → `100% { transform: rotate(360deg); }`
- Linia 4697-4700: Duplikat
- Linia 6874-6877: Duplikat

**Użycia:**
- `.toneka-spinner` - `animation: spin 1s linear infinite;` (linia 209)
- `.toneka-filter-loading-spinner` - `animation: spin 1s linear infinite;` (linia 4694)
- `.toneka-creators-loading-spinner` - `animation: spin 1s linear infinite;` (linia 6871)

**Status:** Używana w 3 miejscach - należy usunąć duplikaty, zostawić jedną definicję

---

## 5. arrow-move
**Definicja:** `@keyframes arrow-move` (linia 1543-1550)
- `0% { stroke-dashoffset: 20; }`
- `100% { stroke-dashoffset: 0; }`

**Użycia:** BRAK UŻYĆ ❌

**Status:** Nieużywana - można usunąć

---

## 6. fadeInCloseButton
**Definicja:** `@keyframes fadeInCloseButton` (linia 1662-1671)
- `from { opacity: 0; transform: scale(0.8); }`
- `to { opacity: 1; transform: scale(1); }`

**Użycia:**
- `.toneka-minicart.is-active .toneka-minicart-close` - `animation: fadeInCloseButton 0.3s ease-in-out 0.3s forwards;` (linia 1654)

**Status:** Używana w 1 miejscu

---

## 7. scaleIn (pierwsza definicja)
**Definicja:** `@keyframes scaleIn` (linia 2628-2641)
- `0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }`
- `50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }`
- `100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }`

**Użycia:** BRAK UŻYĆ ❌
- **UWAGA:** Użycie na linii 2625 zmienione na `fadeIn` przez użytkownika

**Status:** Nieużywana - można usunąć

---

## 8. scaleIn (druga definicja)
**Definicja:** `@keyframes scaleIn` (linia 3168-3177)
- `0% { transform: scale(0); opacity: 0; }`
- `100% { transform: scale(1); opacity: 1; }`

**Użycia:**
- `.toneka-product-card` - `animation: scaleIn var(--transition-slow) var(--ease-elastic);` (linia 3199)
- Z opóźnieniami dla kolejnych kart (animation-delay: 0s do 0.8s)

**Status:** Używana w 1 miejscu (z opóźnieniami dla 9 kart)

---

## 9. scaleOut
**Definicja:** `@keyframes scaleOut` (linia 3180-3189)
- `0% { transform: scale(1); opacity: 1; }`
- `100% { transform: scale(0); opacity: 0; }`

**Użycia:** BRAK UŻYĆ ❌

**Status:** Nieużywana - można usunąć

---

## 10. scroll-left
**Definicja:** `@keyframes scroll-left` (linia 3795-3802)
- `from { transform: translateX(0); }`
- `to { transform: translateX(-25%); }`

**Użycia:**
- `.toneka-scrolling-text` - `animation: scroll-left var(--scroll-duration, 50s) linear infinite;` (linia 3781)

**Status:** Używana w 1 miejscu

---

## 11. slideInRight
**Definicja:** `@keyframes slideInRight` (linia 4716-4725)
- `from { transform: translateX(100%); opacity: 0; }`
- `to { transform: translateX(0); opacity: 1; }`

**Użycia:**
- `.toneka-filter-error` - `animation: slideInRight 0.3s ease;` (linia 4713)

**Status:** Używana w 1 miejscu

---

## 12. toneka-loader-pulse
**Definicja:** `@keyframes toneka-loader-pulse` (linia 7206-7219)
- `0% { transform: translate(-50%, -50%) rotate(0deg) scale(1); opacity: 1; }`
- `50% { transform: translate(-50%, -50%) rotate(180deg) scale(0.9); opacity: 0.7; }`
- `100% { transform: translate(-50%, -50%) rotate(360deg) scale(1); opacity: 1; }`

**Użycia:**
- `.toneka-lazy-wrapper.toneka-loading::after` - `animation: toneka-loader-pulse 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;` (linia 7203)
- `.toneka-player-lazy-wrapper.toneka-loading::after` (pierwsza) - `animation: toneka-loader-pulse 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;` (linia 7250)
- `.toneka-player-lazy-wrapper.toneka-loading::after` (druga) - `animation: toneka-loader-pulse 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;` (linia 7273)

**Status:** Używana w 3 miejscach

---

## Podsumowanie

### Używane animacje (8):
1. ✅ **fadeIn** - 5 użyć
2. ✅ **spin** - 3 użycia (3 duplikaty definicji!)
3. ✅ **fadeInCloseButton** - 1 użycie
4. ✅ **scaleIn** (wersja 2) - 1 użycie (z opóźnieniami)
5. ✅ **scroll-left** - 1 użycie
6. ✅ **slideInRight** - 1 użycie
7. ✅ **toneka-loader-pulse** - 3 użycia
8. ✅ **animation: none** - 1 użycie (wyłączenie animacji)

### Nieużywane animacje (5):
1. ❌ **fadeInUp** - można usunąć
2. ❌ **pulse** - można usunąć
3. ❌ **arrow-move** - można usunąć
4. ❌ **scaleIn** (pierwsza definicja) - można usunąć
5. ❌ **scaleOut** - można usunąć

### Problemy do naprawienia:
1. ⚠️ **spin** - 3 duplikaty definicji (linie 212, 4697, 6874) - należy zostawić jedną
2. ⚠️ **scaleIn** - 2 różne definicje - pierwsza nieużywana, druga używana

