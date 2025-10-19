#!/bin/bash

# Skrypt monitorujący i naprawiający konfigurację Nginx

SITE_CONF="/Users/mariusz/Library/Application Support/Local/run/dYy_k7iF6/conf/nginx/site.conf"

echo "🔍 Uruchamiam monitoring konfiguracji Nginx..."
echo "💡 Skrypt będzie działał w tle i automatycznie naprawiał konfigurację"
echo "💡 Aby zatrzymać, naciśnij Ctrl+C"

while true; do
    # Sprawdź czy problematyczny blok istnieje
    if grep -q "location ~\* \\.(html|htm|php)\$" "$SITE_CONF"; then
        echo "⚠️  [$(date '+%H:%M:%S')] Wykryto problematyczny blok - naprawiam..."
        
        # Usuń problematyczny blok
        sed -i '' '/# DEVELOPMENT: Disable all caching for HTML pages/,/^    }$/d' "$SITE_CONF"
        
        # Zrestartuj Nginx
        killall -HUP nginx 2>/dev/null
        
        echo "✅ [$(date '+%H:%M:%S')] Konfiguracja naprawiona"
    fi
    
    # Czekaj 5 sekund przed następnym sprawdzeniem
    sleep 5
done

