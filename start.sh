#!/bin/bash

echo "🚀 Uruchamianie aplikacji Toneka..."
echo ""

# Sprawdź czy jesteśmy w odpowiednim katalogu
if [ ! -f "package.json" ]; then
    echo "❌ Błąd: Uruchom skrypt z katalogu TonekaApp"
    exit 1
fi

# Sprawdź czy node_modules istnieje
if [ ! -d "node_modules" ]; then
    echo "📦 Instalowanie zależności..."
    npm install
fi

# Sprawdź czy Metro bundler działa
if ! pgrep -f "metro" > /dev/null; then
    echo "🔄 Uruchamianie Metro bundler..."
    npm run start &
    sleep 3
fi

echo "✅ Aplikacja gotowa do testowania!"
echo ""
echo "📱 Aby przetestować na iPhone:"
echo "1. Zainstaluj Expo Go z App Store"
echo "2. Zeskanuj QR kod z terminala"
echo "3. Aplikacja załaduje się na telefonie"
echo ""
echo "🔗 Alternatywnie możesz otworzyć:"
echo "http://localhost:8081"
echo ""
echo "⏹️  Aby zatrzymać: Ctrl+C"
echo ""

# Uruchom Metro bundler
npm run start
