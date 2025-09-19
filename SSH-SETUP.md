# 🔑 SSH Key Setup Guide

## Generowanie klucza SSH

### Na macOS/Linux:
```bash
# Wygeneruj nowy klucz SSH
ssh-keygen -t rsa -b 4096 -C "deployment@toneka.pl"

# Lokalizacja: ~/.ssh/id_rsa (private) i ~/.ssh/id_rsa.pub (public)
```

### Na Windows:
```bash
# W PowerShell lub Git Bash
ssh-keygen -t rsa -b 4096 -C "deployment@toneka.pl"
```

## Konfiguracja na serwerze

### 1. Skopiuj klucz publiczny na serwer:
```bash
# Opcja A: Automatycznie
ssh-copy-id username@server-ip

# Opcja B: Ręcznie
cat ~/.ssh/id_rsa.pub | ssh username@server-ip "mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys"
```

### 2. Test połączenia:
```bash
ssh username@server-ip
# Powinno połączyć bez pytania o hasło
```

## Dodanie do GitHub Secrets

### 1. Skopiuj prywatny klucz:
```bash
# macOS/Linux
cat ~/.ssh/id_rsa | pbcopy

# Linux (bez pbcopy)
cat ~/.ssh/id_rsa
# Zaznacz i skopiuj całą zawartość
```

### 2. W GitHub:
- Settings > Secrets and Variables > Actions
- New repository secret
- Name: `PROD_SSH_KEY`
- Value: [wklej cały content klucza prywatnego]

## Przykład kompletnej konfiguracji secrets:

```
PROD_HOST=123.45.67.89
PROD_USERNAME=ubuntu
PROD_PORT=22
PROD_PATH=/var/www/html
PROD_SSH_KEY=-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA1234567890abcdef...
[cała zawartość klucza prywatnego]
...xyz789
-----END RSA PRIVATE KEY-----
```
