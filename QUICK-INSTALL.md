# 🚀 Quick Install Guide

## Instalacja Theme na Serwerze Produkcyjnym

### Metoda 1: Automatyczny Skrypt

1. **Połącz się z serwerem:**
```bash
ssh pressmatic3@pressmatic3.ssh.dhosting.pl
```

2. **Przejdź do WordPress:**
```bash
cd ~/shop.toneka.pl-at4c/public_html
```

3. **Pobierz i uruchom skrypt instalacyjny:**
```bash
curl -s https://raw.githubusercontent.com/jeden-/toneka_theme/main/install-theme.sh | bash
```

### Metoda 2: Manualna Instalacja

```bash
# Połącz się z serwerem
ssh pressmatic3@pressmatic3.ssh.dhosting.pl

# Przejdź do WordPress
cd ~/shop.toneka.pl-at4c/public_html

# Sklonuj repozytorium
git clone https://github.com/jeden-/toneka_theme.git temp-deployment

# Skopiuj theme
cp -r temp-deployment/app/public/wp-content/themes/toneka-theme wp-content/themes/

# Ustaw uprawnienia
chmod -R 755 wp-content/themes/toneka-theme/

# Posprzątaj
rm -rf temp-deployment

# Gotowe!
echo "✅ Theme zainstalowany!"
```

### Metoda 3: One-liner

```bash
ssh pressmatic3@pressmatic3.ssh.dhosting.pl "cd ~/shop.toneka.pl-at4c/public_html && git clone https://github.com/jeden-/toneka_theme.git temp && cp -r temp/app/public/wp-content/themes/toneka-theme wp-content/themes/ && chmod -R 755 wp-content/themes/toneka-theme/ && rm -rf temp && echo '✅ Toneka Theme installed!'"
```

## Po Instalacji

1. **WordPress Admin:** https://shop.toneka.pl/wp-admin
2. **Appearance > Themes** → Aktywuj "Toneka Theme"
3. **Plugins** → Zainstaluj/aktywuj WooCommerce
4. **Settings > Permalinks** → Wybierz "Post name"

## Weryfikacja

Sprawdź czy theme jest zainstalowany:
```bash
ls -la wp-content/themes/toneka-theme/
```

Powinno pokazać wszystkie pliki theme (style.css, functions.php, etc.)

---

**Po instalacji theme będzie dostępny do aktywacji w WordPress Admin!** 🎉
