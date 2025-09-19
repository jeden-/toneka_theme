# 🚀 Instalacja Theme na Produkcji - Wszystkie Opcje

## 📋 Status
- ✅ **Repository**: https://github.com/jeden-/toneka_theme (publiczne)
- ✅ **Server**: pressmatic3.ssh.dhosting.pl
- ✅ **WordPress Path**: ~/shop.toneka.pl-at4c/public_html
- ✅ **ZIP Package**: toneka-theme-production.zip (gotowy)

---

## 🎯 OPCJA 1: GitHub Clone (NAJLEPSZE)

Jeśli masz dostęp SSH do serwera:

```bash
# Połącz się z serwerem
ssh pressmatic3@pressmatic3.ssh.dhosting.pl

# Przejdź do WordPress
cd ~/shop.toneka.pl-at4c/public_html

# Sklonuj theme (one-liner)
git clone https://github.com/jeden-/toneka_theme.git temp && cp -r temp/app/public/wp-content/themes/toneka-theme wp-content/themes/ && chmod -R 755 wp-content/themes/toneka-theme/ && rm -rf temp && echo "✅ Theme zainstalowany!"
```

---

## 🎯 OPCJA 2: cPanel File Manager

Jeśli masz dostęp do cPanel:

### Krok 1: Pobierz ZIP
- Pobierz: `toneka-theme-production.zip` (w katalogu projektu)
- Lub pobierz z GitHub: https://github.com/jeden-/toneka_theme/archive/refs/heads/main.zip

### Krok 2: Upload przez cPanel
1. **cPanel** → **File Manager**
2. Przejdź do: `shop.toneka.pl-at4c/public_html/wp-content/themes/`
3. **Upload** → Wybierz `toneka-theme-production.zip`
4. **Extract** archiwum
5. Sprawdź czy folder `toneka-theme` jest w `wp-content/themes/`

---

## 🎯 OPCJA 3: FTP Upload

Jeśli masz dostęp FTP:

### Dane FTP (prawdopodobnie):
```
Host: ftp.dhosting.pl (lub shop.toneka.pl)
Username: pressmatic3
Password: PRessmatic3@!2025
Path: /shop.toneka.pl-at4c/public_html/wp-content/themes/
```

### Kroki:
1. **Rozpakuj** lokalnie `toneka-theme-production.zip`
2. **Upload** folder `toneka-theme` do `wp-content/themes/`
3. **Sprawdź** uprawnienia (755)

---

## 🎯 OPCJA 4: WordPress Admin Upload

### Krok 1: Przygotuj ZIP dla WordPress
Potrzebujemy ZIP z głównym folderem theme:

```bash
# Lokalnie
cd "/Users/mariusz/Local Sites/tonekacursor/app/public/wp-content/themes"
zip -r toneka-theme-wp.zip toneka-theme/
```

### Krok 2: Upload przez WP Admin
1. **WordPress Admin** → https://shop.toneka.pl/wp-admin
2. **Appearance** → **Themes**
3. **Add New** → **Upload Theme**
4. Wybierz `toneka-theme-wp.zip`
5. **Install Now**

---

## 🎯 OPCJA 5: Hosting Panel

Jeśli dhosting ma panel zarządzania:

1. **Panel hostingu** → **File Manager**
2. Przejdź do WordPress: `shop.toneka.pl-at4c/public_html/`
3. **Upload & Extract** `toneka-theme-production.zip`
4. **Move** folder do `wp-content/themes/`

---

## ✅ Po Instalacji - ZAWSZE

Niezależnie od metody instalacji:

### 1. 🎨 Aktywuj Theme
- **WordPress Admin**: https://shop.toneka.pl/wp-admin
- **Appearance** → **Themes**
- **Activate** "Toneka Theme"

### 2. 🛒 Zainstaluj WooCommerce
- **Plugins** → **Add New**
- Szukaj "WooCommerce" → **Install & Activate**

### 3. ⚙️ Ustawienia
- **Settings** → **Permalinks** → **Post name** → **Save**
- **WooCommerce** → **Settings** → Przejdź przez setup

### 4. 🧪 Test
- Sprawdź stronę: https://shop.toneka.pl
- Sprawdź czy theme się ładuje
- Sprawdź funkcjonalność

---

## 🔧 Weryfikacja Instalacji

Sprawdź czy theme jest poprawnie zainstalowany:

```bash
# SSH
ssh pressmatic3@pressmatic3.ssh.dhosting.pl
cd ~/shop.toneka.pl-at4c/public_html/wp-content/themes/toneka-theme
ls -la

# Powinno pokazać:
# style.css, functions.php, header.php, footer.php, js/, woocommerce/, etc.
```

Lub sprawdź przez **cPanel File Manager** czy folder `toneka-theme` zawiera wszystkie pliki.

---

## 🚨 Troubleshooting

### Problem: Theme nie pojawia się w WordPress
- Sprawdź czy folder jest w `wp-content/themes/toneka-theme/`
- Sprawdź czy istnieje plik `style.css` z header theme

### Problem: Błędy po aktywacji
- Sprawdź czy wszystkie pliki się przesłały
- Sprawdź uprawnienia folderów (755)
- Sprawdź error log WordPress

### Problem: Brak funkcjonalności
- Zainstaluj WooCommerce
- Sprawdź czy wszystkie pliki JS się załadowały

---

## 🎯 Rekomendacja

**NAJLEPSZA OPCJA**: GitHub Clone (Opcja 1) - najszybsza i najpewniejsza.

**ALTERNATYWA**: cPanel Upload (Opcja 2) - jeśli nie ma SSH.

**Która opcja jest dla Ciebie dostępna?**
