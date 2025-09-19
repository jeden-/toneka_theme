# 🚀 Deployment Setup Guide

Instrukcje konfiguracji GitHub repository i automatycznego deploymentu na serwer produkcyjny.

## 📋 Wymagania

- Konto GitHub
- Serwer produkcyjny z SSH access
- WordPress hosting z Git support
- PHP 8.1+
- MySQL/MariaDB

## 🏗️ Krok 1: Stworzenie GitHub Repository

### 1.1 Stwórz nowe repo na GitHub
```bash
# Idź na github.com i stwórz nowe repository
# Nazwa: toneka (lub inna preferowana)
# Visibility: Private (zalecane)
# Nie dodawaj README, .gitignore, license (już mamy)
```

### 1.2 Połącz lokalne repo z GitHub
```bash
cd "/Users/mariusz/Local Sites/tonekacursor"

# Dodaj remote origin
git remote add origin https://github.com/jeden-/toneka_theme.git

# Zmień branch na main (jeśli potrzebne)
git branch -M main

# Wyślij kod na GitHub
git push -u origin main
```

## 🔐 Krok 2: Konfiguracja Secrets w GitHub

W GitHub repository, idź do **Settings > Secrets and Variables > Actions** i dodaj:

### 2.1 Secrets dla serwera produkcyjnego
```
PROD_HOST          = your-server-ip-or-domain.com
PROD_USERNAME      = your-ssh-username
PROD_SSH_KEY       = your-private-ssh-key
PROD_PORT          = 22 (lub inny port SSH)
PROD_PATH          = /path/to/your/wordpress/installation
```

### 2.2 Optional: Slack notifications
```
SLACK_WEBHOOK      = https://hooks.slack.com/services/YOUR/SLACK/WEBHOOK
```

## 🖥️ Krok 3: Przygotowanie Serwera Produkcyjnego

### 3.1 Zainstaluj Git na serwerze
```bash
# Ubuntu/Debian
sudo apt update && sudo apt install git

# CentOS/RHEL
sudo yum install git
```

### 3.2 Sklonuj repository na serwerze
```bash
# Przejdź do katalogu WordPress
cd /path/to/your/wordpress/

# Sklonuj repo (tylko theme folder)
git clone https://github.com/jeden-/toneka_theme.git temp-repo
cp -r temp-repo/app/public/wp-content/themes/toneka-theme wp-content/themes/
rm -rf temp-repo

# Lub sklonuj całe repo jeśli chcesz pełną kontrolę
git clone https://github.com/jeden-/toneka_theme.git .
```

### 3.3 Ustaw uprawnienia
```bash
# Właściciel: www-data (lub apache)
sudo chown -R www-data:www-data wp-content/themes/toneka-theme/
sudo chmod -R 755 wp-content/themes/toneka-theme/
```

### 3.4 Konfiguracja SSH Key
```bash
# Na serwerze, wygeneruj SSH key pair
ssh-keygen -t rsa -b 4096 -C "deployment@toneka.com"

# Dodaj public key do GitHub Deploy Keys
# Settings > Deploy keys > Add deploy key
cat ~/.ssh/id_rsa.pub

# Private key dodaj do GitHub Secrets jako PROD_SSH_KEY
cat ~/.ssh/id_rsa
```

## ⚙️ Krok 4: Konfiguracja WordPress

### 4.1 Aktywuj theme
```php
// W wp-admin lub przez WP-CLI
wp theme activate toneka-theme
```

### 4.2 Wymagane pluginy
- WooCommerce (najnowsza wersja)
- WooCommerce Blocks (dla checkout)

### 4.3 Permalinki
Ustaw permalinki na "Post name" w Settings > Permalinks

## 🧪 Krok 5: Testowanie Deploymentu

### 5.1 Test lokalny
```bash
cd "/Users/mariusz/Local Sites/tonekacursor"

# Uruchom lokalny test
./deploy.sh staging

# Sprawdź czy wszystko działa
php -l app/public/wp-content/themes/toneka-theme/functions.php
```

### 5.2 Test GitHub Actions
```bash
# Zrób małą zmianę i wyślij
echo "/* Test deployment */" >> app/public/wp-content/themes/toneka-theme/style.css
git add .
git commit -m "test: deployment test"
git push origin main

# Sprawdź GitHub Actions
# https://github.com/jeden-/toneka_theme/actions
```

## 📊 Krok 6: Monitoring i Logi

### 6.1 GitHub Actions logi
- Sprawdzaj status deploymentów w GitHub Actions
- Wszystkie błędy będą widoczne w logach

### 6.2 Serwer logi
```bash
# WordPress error log
tail -f wp-content/debug.log

# Apache/Nginx logi
sudo tail -f /var/log/apache2/error.log
sudo tail -f /var/log/nginx/error.log
```

### 6.3 Backup strategy
```bash
# Automatyczne backup przed każdym deploymentem
# Backup jest tworzony w: wp-content/themes/toneka-theme-backup-TIMESTAMP
```

## 🔄 Workflow Deploymentu

### Standardowy workflow:
1. **Rozwój lokalny** w Local by Flywheel
2. **Testowanie** funkcjonalności
3. **Commit & Push** do GitHub
4. **Automatyczny deployment** przez GitHub Actions
5. **Weryfikacja** na serwerze produkcyjnym

### Komendy:
```bash
# Lokalnie
git add .
git commit -m "feat: new feature description"
git push origin main

# Lub użyj skryptu
./deploy.sh production
```

## 🚨 Troubleshooting

### Problem: SSH connection failed
```bash
# Sprawdź SSH connection
ssh -i ~/.ssh/id_rsa user@server.com

# Sprawdź czy klucz jest poprawny w GitHub Secrets
```

### Problem: Permission denied
```bash
# Na serwerze
sudo chown -R www-data:www-data wp-content/themes/toneka-theme/
sudo chmod -R 755 wp-content/themes/toneka-theme/
```

### Problem: Git conflicts
```bash
# Na serwerze
git fetch origin main
git reset --hard origin/main
```

## 📈 Optymalizacje

### Performance
- Włącz caching (W3 Total Cache, WP Rocket)
- Optymalizuj obrazy (WebP, lazy loading)
- Minifikuj CSS/JS na produkcji

### Security
- Regularne aktualizacje WordPress/pluginów
- SSL certificate
- Firewall rules
- Regular backups

### Monitoring
- Uptime monitoring (UptimeRobot)
- Performance monitoring (GTmetrix)
- Error tracking (Sentry)

---

## 🎯 Następne kroki po setupie

1. ✅ Przetestuj pełny workflow
2. ✅ Skonfiguruj domain i SSL
3. ✅ Zaimportuj content i produkty
4. ✅ Skonfiguruj płatności WooCommerce
5. ✅ Ustaw Google Analytics
6. ✅ Skonfiguruj email templates
7. ✅ Performance optimization
8. ✅ SEO setup

**Potrzebujesz pomocy?** Sprawdź GitHub Issues lub skontaktuj się z zespołem.
