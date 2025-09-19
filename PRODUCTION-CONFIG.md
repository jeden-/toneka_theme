# 🚀 Konfiguracja Serwera Produkcyjnego - Toneka

## 📊 Dane serwera produkcyjnego:

```
Host: pressmatic3.ssh.dhosting.pl
Username: pressmatic3
Server: web03-s211
Path: ~/shop.toneka.pl-at4c/public_html
Password: [HIDDEN FOR SECURITY]
```

## 🔐 GitHub Secrets do dodania:

W GitHub repo: **Settings > Secrets and Variables > Actions**

Dodaj następujące secrets:

```
PROD_HOST=pressmatic3.ssh.dhosting.pl
PROD_USERNAME=pressmatic3
PROD_PORT=22
PROD_PATH=/home/pressmatic3/shop.toneka.pl-at4c/public_html
PROD_PASSWORD=[YOUR_SSH_PASSWORD]
```

## ⚠️ Uwaga - Deployment Strategy

Ponieważ używasz hasła zamiast klucza SSH, musimy zmodyfikować GitHub Actions workflow.

### Opcja 1: SSH Key (ZALECANE)
Wygeneruj klucz SSH i dodaj do serwera:

```bash
# Lokalnie
ssh-keygen -t rsa -b 4096 -C "deployment@toneka.pl"

# Skopiuj klucz publiczny na serwer
ssh-copy-id pressmatic3@pressmatic3.ssh.dhosting.pl

# Dodaj klucz prywatny do GitHub Secrets jako PROD_SSH_KEY
```

### Opcja 2: Password Authentication
Zmodyfikuj workflow żeby używał hasła (mniej bezpieczne).

## 🎯 Ścieżka do theme na serwerze:

```
/home/pressmatic3/shop.toneka.pl-at4c/public_html/wp-content/themes/toneka-theme
```

## 🚀 Deployment Commands:

```bash
# Połącz się z serwerem
ssh pressmatic3@pressmatic3.ssh.dhosting.pl

# Przejdź do WordPress directory
cd ~/shop.toneka.pl-at4c/public_html

# Sklonuj repo (pierwsza instalacja)
git clone https://github.com/jeden-/toneka_theme.git temp-toneka
cp -r temp-toneka/app/public/wp-content/themes/toneka-theme wp-content/themes/
rm -rf temp-toneka

# Ustaw uprawnienia
chmod -R 755 wp-content/themes/toneka-theme/
```

## 📋 Checklist przed pierwszym deploymentem:

- [ ] Dodaj secrets do GitHub
- [ ] Skonfiguruj SSH key (opcjonalnie)
- [ ] Zainstaluj theme na serwerze
- [ ] Aktywuj theme w WordPress
- [ ] Test połączenia SSH
- [ ] Test pierwszego deploymentu

## 🔧 WordPress Configuration:

Na serwerze w wp-admin:
1. Aktywuj theme "Toneka Theme"
2. Zainstaluj/aktywuj WooCommerce
3. Skonfiguruj permalinki na "Post name"
4. Zaimportuj produkty/content
