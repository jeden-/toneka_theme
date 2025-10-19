#!/bin/bash

# Skrypt naprawiający konfigurację Nginx dla Local by Flywheel
# Problem: Local dodaje .php do bloku cache, co powoduje pobieranie plików zamiast renderowania

SITE_CONF="/Users/mariusz/Library/Application Support/Local/run/dYy_k7iF6/conf/nginx/site.conf"
MIME_CONF="/Users/mariusz/Library/Application Support/Local/run/dYy_k7iF6/conf/nginx/includes/mime-types.conf"

echo "🔧 Naprawiam konfigurację Nginx..."

# 1. Napraw site.conf - usuń .php z bloku cache
if grep -q "location ~\* \\.(html|htm|php)\$" "$SITE_CONF"; then
    sed -i '' 's/location ~\* \\.(html|htm|php)\$/location ~\* \\.(html|htm)\$/' "$SITE_CONF"
    echo "✅ Naprawiono site.conf - usunięto .php z bloku cache"
else
    echo "✅ site.conf jest już poprawny"
fi

# 2. Napraw mime-types.conf - dodaj definicję PHP
if ! grep -q "application/x-httpd-php" "$MIME_CONF"; then
    # Znajdź linię z application/octet-stream i dodaj przed nią definicję PHP
    sed -i '' '/application\/octet-stream/i\
    application/x-httpd-php                 php;\
' "$MIME_CONF"
    echo "✅ Naprawiono mime-types.conf - dodano definicję PHP"
else
    echo "✅ mime-types.conf jest już poprawny"
fi

# 3. Restartuj Nginx
echo "🔄 Restartuję Nginx..."
killall nginx 2>/dev/null
sleep 2

# Sprawdź czy port jest wolny
if lsof -ti:10023 >/dev/null 2>&1; then
    echo "⚠️  Port 10023 jest zajęty, zabijam procesy..."
    lsof -ti:10023 | xargs kill -9 2>/dev/null
    sleep 1
fi

echo "✅ Konfiguracja naprawiona!"
echo "💡 Teraz uruchom site w Local lub poczekaj na automatyczny restart"

