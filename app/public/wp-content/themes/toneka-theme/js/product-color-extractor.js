/**
 * Product Color Extractor
 * Rozpoznaje wiodący kolor zdjęcia okładki i ustawia go jako tło dla .toneka-product-info
 * 
 * PARAMETRY KONFIGURACYJNE:
 * ==========================
 * 
 * @config {number} CONFIG.sampleSize - Rozmiar próbki do analizy (domyślnie: 10)
 *   - Mniejsza wartość (1-5): Bardziej precyzyjna analiza, ale wolniejsza
 *   - Większa wartość (15-30): Szybsza analiza, ale mniej dokładna
 *   - Rekomendowane: 5-15 dla większości przypadków
 * 
 * @config {number} CONFIG.brightnessThreshold - Próg jasności (domyślnie: 150, zakres: 0-255)
 *   - Kolory powyżej tego progu będą przyciemniane
 *   - Niższa wartość (100-130): Więcej kolorów będzie przyciemnianych (lepszy kontrast tekstu)
 *   - Wyższa wartość (170-200): Tylko bardzo jasne kolory będą przyciemniane
 *   - Wzór jasności: brightness = (R*299 + G*587 + B*114) / 1000
 * 
 * @config {number} CONFIG.darkenPercentage - Procent przyciemnienia jasnych kolorów (domyślnie: -30)
 *   - Ujemne wartości: przyciemnianie (-10 do -50)
 *   - Dodatnie wartości: rozjaśnianie (10 do 50)
 *   - Wartość 0: brak zmiany
 * 
 * @config {number} CONFIG.alphaThreshold - Próg przezroczystości pikseli (domyślnie: 128)
 *   - Piksele z alpha poniżej tego progu są pomijane w analizie
 *   - Zakres: 0-255
 *   - 0 = pomija tylko całkowicie przezroczyste
 *   - 128 = pomija półprzezroczyste i przezroczyste
 * 
 * @config {string} CONFIG.colorMethod - Metoda ekstrakcji koloru (domyślnie: 'weighted-average')
 *   - 'weighted-average': Średnia ważona przez jasność (aktualna metoda)
 *   - 'simple-average': Prosta średnia wszystkich pikseli
 *   - 'most-common': Najczęściej występujący kolor (wymaga quantizacji)
 *   - 'vibrant': Najbardziej żywy/nasycony kolor
 *   - 'darkest': Najciemniejszy dominujący kolor
 *   - 'lightest': Najjaśniejszy dominujący kolor
 * 
 * @config {string} CONFIG.region - Region obrazu do analizy (domyślnie: 'all')
 *   - 'all': Cały obraz
 *   - 'center': Środek obrazu (40% x 40% w centrum)
 *   - 'top': Górna część (50% górnej części)
 *   - 'bottom': Dolna część (50% dolnej części)
 *   - 'edges': Tylko brzegi obrazu
 * 
 * CO MOŻEMY ROZPOZNAWAĆ NA ZDJĘCIU:
 * =================================
 * 
 * 1. KOLORY:
 *    - Dominujący kolor (główny kolor w obrazie)
 *    - Najbardziej nasycony kolor
 *    - Najjaśniejszy/najciemniejszy kolor
 *    - Paleta kolorów (top N kolorów)
 *    - Średni kolor całego obrazu
 * 
 * 2. CHARAKTERYSTYKI KOLORU:
 *    - Jasność (brightness/luminance)
 *    - Nasycenie (saturation)
 *    - Barwa (hue)
 *    - Kontrast (różnica między najjaśniejszym a najciemniejszym)
 * 
 * 3. ANALIZA OBRAZU:
 *    - Dominacja koloru (jak duży % obrazu zajmuje dany kolor)
 *    - Rozkład kolorów (czy obraz jest jednorodny czy zróżnicowany)
 *    - Gradient (czy kolory zmieniają się płynnie)
 * 
 * JAK ANALIZOWAĆ ZDJĘCIA:
 * =======================
 * 
 * METODA 1: Średnia ważona (aktualna - 'weighted-average')
 *   - Oblicza średnią kolorów ważoną przez jasność
 *   - Lepsze dla obrazów z dominującym kolorem
 *   - Wzór: color = Σ(pixel_color * brightness_weight) / Σ(brightness_weight)
 * 
 * METODA 2: Prosta średnia ('simple-average')
 *   - Oblicza prostą średnią wszystkich pikseli
 *   - Szybsza, ale mniej reprezentatywna
 * 
 * METODA 3: Najczęstszy kolor ('most-common')
 *   - Quantyzuje kolory (np. do 64 kolorów)
 *   - Wybiera najczęściej występujący
 *   - Najlepsza dla obrazów z wyraźnymi obszarami kolorów
 * 
 * METODA 4: K-medoids/Clustering
 *   - Grupuje podobne kolory w clustery
 *   - Wybiera najbardziej reprezentatywny kolor z największego clustera
 *   - Najbardziej zaawansowana, ale najwolniejsza
 * 
 * METODA 5: Analiza histogramu
 *   - Tworzy histogram kolorów
 *   - Wybiera kolor z najwyższego piku histogramu
 *   - Dobra dla obrazów z wyraźnymi dominacjami
 */

(function() {
    'use strict';

    // ========================================
    // KONFIGURACJA - MOŻNA DOSTOSOWAĆ PARAMETRY
    // ========================================
    const CONFIG = {
        // Rozmiar próbki do analizy (mniejszy = dokładniejszy, większy = szybszy)
        sampleSize: 30,
        
        // Próg jasności - kolory powyżej tego będą przyciemniane (0-255)
        brightnessThreshold: 150,
        
        // Procent przyciemnienia jasnych kolorów (-100 do 100, ujemne = ciemniej)
        darkenPercentage: -30,
        
        // Próg przezroczystości - piksele z alpha poniżej są pomijane (0-255)
        alphaThreshold: 128,
        
        // Metoda ekstrakcji koloru
        // 'weighted-average' | 'simple-average' | 'most-common' | 'vibrant' | 'darkest' | 'lightest'
        colorMethod: 'vibrant',
        
        // Region obrazu do analizy
        // 'all' | 'center' | 'top' | 'bottom' | 'edges'
        region: 'center',
        
        // Opóźnienie między przetwarzaniem kart (ms) - dla wydajności
        processingDelay: 50,
        
        // Opóźnienie przy wykryciu nowych produktów (ms) - czeka na lazy loading
        newProductsDelay: 500,
        
        // Konwertuj kolor na pastelowy
        pastelize: true,
        
        // Intensywność pastelizacji (0-1, gdzie 1 = maksymalnie pastelowy)
        pastelIntensity: 0.6
    };

    /**
     * Ekstrakcja dominującego koloru z obrazu używając Canvas API
     * @param {HTMLImageElement} img - Element obrazu
     * @param {number} sampleSize - Rozmiar próbki do analizy (używa CONFIG.sampleSize jeśli nie podano)
     * @returns {Promise<string>} - Kolor w formacie hex (#RRGGBB)
     */
    function extractDominantColor(img, sampleSize = CONFIG.sampleSize) {
        return new Promise((resolve, reject) => {
            try {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                // Ustaw rozmiar canvas na rozmiar obrazu
                canvas.width = img.naturalWidth || img.width;
                canvas.height = img.naturalHeight || img.height;
                
                // Rysuj obraz na canvas
                ctx.drawImage(img, 0, 0);
                
                // Określ region do analizy
                let startX = 0, startY = 0, endX = canvas.width, endY = canvas.height;
                
                if (CONFIG.region === 'center') {
                    const centerWidth = canvas.width * 0.4;
                    const centerHeight = canvas.height * 0.4;
                    startX = (canvas.width - centerWidth) / 2;
                    startY = (canvas.height - centerHeight) / 2;
                    endX = startX + centerWidth;
                    endY = startY + centerHeight;
                } else if (CONFIG.region === 'top') {
                    endY = canvas.height * 0.5;
                } else if (CONFIG.region === 'bottom') {
                    startY = canvas.height * 0.5;
                }
                // 'edges' i 'all' używa pełnego obrazu
                
                // Pobierz dane obrazu dla określonego regionu
                const imageData = ctx.getImageData(startX, startY, endX - startX, endY - startY);
                const data = imageData.data;
                
                // Oblicz dominujący kolor (uproszczona metoda - średnia ważona)
                let r = 0, g = 0, b = 0;
                let count = 0;
                
                // Próbkuj co N-tego piksela dla wydajności
                const step = Math.max(1, Math.floor(Math.min(endX - startX, endY - startY) / sampleSize));
                
                for (let i = 0; i < data.length; i += 4 * step) {
                    const pixelR = data[i];
                    const pixelG = data[i + 1];
                    const pixelB = data[i + 2];
                    const pixelA = data[i + 3];
                    
                    // Pomiń przezroczyste piksele
                    if (pixelA < CONFIG.alphaThreshold) continue;
                    
                    // Różne metody ekstrakcji koloru
                    let weight = 1;
                    
                    if (CONFIG.colorMethod === 'weighted-average') {
                        // Średnia ważona przez jasność (domyślna)
                        const brightness = (pixelR + pixelG + pixelB) / 3;
                        weight = brightness / 255;
                    } else if (CONFIG.colorMethod === 'vibrant') {
                        // Preferuj bardziej nasycone kolory
                        const max = Math.max(pixelR, pixelG, pixelB);
                        const min = Math.min(pixelR, pixelG, pixelB);
                        const saturation = max === 0 ? 0 : (max - min) / max;
                        weight = saturation;
                    } else if (CONFIG.colorMethod === 'darkest') {
                        // Preferuj ciemniejsze kolory
                        const brightness = (pixelR + pixelG + pixelB) / 3;
                        weight = (255 - brightness) / 255;
                    } else if (CONFIG.colorMethod === 'lightest') {
                        // Preferuj jaśniejsze kolory
                        const brightness = (pixelR + pixelG + pixelB) / 3;
                        weight = brightness / 255;
                    }
                    // 'simple-average' używa weight = 1 (już ustawione)
                    
                    r += pixelR * weight;
                    g += pixelG * weight;
                    b += pixelB * weight;
                    count += weight;
                }
                
                if (count > 0) {
                    r = Math.round(r / count);
                    g = Math.round(g / count);
                    b = Math.round(b / count);
                }
                
                // Konwertuj na hex
                const hex = '#' + 
                    r.toString(16).padStart(2, '0') + 
                    g.toString(16).padStart(2, '0') + 
                    b.toString(16).padStart(2, '0');
                
                resolve(hex);
            } catch (error) {
                console.error('Błąd podczas ekstrakcji koloru:', error);
                reject(error);
            }
        });
    }

    /**
     * Przyciemnia lub rozjaśnia kolor
     * @param {string} hex - Kolor w formacie hex
     * @param {number} percent - Procent zmiany (-100 do 100, ujemne = ciemniej)
     * @returns {string} - Nowy kolor w formacie hex
     */
    function adjustColorBrightness(hex, percent) {
        const num = parseInt(hex.replace('#', ''), 16);
        const r = Math.min(255, Math.max(0, ((num >> 16) & 0xFF) + percent * 2.55));
        const g = Math.min(255, Math.max(0, ((num >> 8) & 0xFF) + percent * 2.55));
        const b = Math.min(255, Math.max(0, (num & 0xFF) + percent * 2.55));
        
        return '#' + 
            Math.round(r).toString(16).padStart(2, '0') + 
            Math.round(g).toString(16).padStart(2, '0') + 
            Math.round(b).toString(16).padStart(2, '0');
    }

    /**
     * Konwertuje kolor na pastelowy (zwiększa jasność, zmniejsza nasycenie)
     * @param {string} hex - Kolor w formacie hex
     * @param {number} intensity - Intensywność pastelizacji (0-1)
     * @returns {string} - Pastelowy kolor w formacie hex
     */
    function pastelizeColor(hex, intensity = 0.6) {
        const num = parseInt(hex.replace('#', ''), 16);
        let r = (num >> 16) & 0xFF;
        let g = (num >> 8) & 0xFF;
        let b = num & 0xFF;
        
        // Oblicz maksimum i minimum dla nasycenia
        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
        
        // Oblicz nasycenie (0-1)
        const saturation = max === 0 ? 0 : (max - min) / max;
        
        // Zmniejsz nasycenie (mieszaj z szarym)
        const desaturateAmount = saturation * intensity;
        const gray = (r + g + b) / 3;
        
        r = Math.round(r * (1 - desaturateAmount) + gray * desaturateAmount);
        g = Math.round(g * (1 - desaturateAmount) + gray * desaturateAmount);
        b = Math.round(b * (1 - desaturateAmount) + gray * desaturateAmount);
        
        // Zwiększ jasność (mieszaj z białym)
        const lightenAmount = intensity * 0.5; // Dodatkowe rozjaśnienie
        r = Math.round(r * (1 - lightenAmount) + 255 * lightenAmount);
        g = Math.round(g * (1 - lightenAmount) + 255 * lightenAmount);
        b = Math.round(b * (1 - lightenAmount) + 255 * lightenAmount);
        
        // Upewnij się, że wartości są w zakresie 0-255
        r = Math.min(255, Math.max(0, r));
        g = Math.min(255, Math.max(0, g));
        b = Math.min(255, Math.max(0, b));
        
        return '#' + 
            r.toString(16).padStart(2, '0') + 
            g.toString(16).padStart(2, '0') + 
            b.toString(16).padStart(2, '0');
    }

    /**
     * Sprawdza czy kolor jest zbyt jasny (potrzebujemy ciemniejszego tła)
     * @param {string} hex - Kolor w formacie hex
     * @returns {boolean}
     */
    function isTooLight(hex) {
        const num = parseInt(hex.replace('#', ''), 16);
        const r = (num >> 16) & 0xFF;
        const g = (num >> 8) & 0xFF;
        const b = num & 0xFF;
        const brightness = (r * 299 + g * 587 + b * 114) / 1000;
        return brightness > CONFIG.brightnessThreshold;
    }

    /**
     * Przetwarza pojedynczą kartę produktu
     * @param {HTMLElement} card - Element karty produktu
     */
    async function processProductCard(card) {
        // Znajdź obraz w karcie produktu
        const imageWrapper = card.querySelector('.toneka-product-image-wrapper');
        if (!imageWrapper) return;
        
        // Spróbuj znaleźć obraz (może być w lazy wrapper)
        let img = imageWrapper.querySelector('img.toneka-loaded');
        if (!img) {
            img = imageWrapper.querySelector('img[src]');
        }
        if (!img || !img.src) return;
        
        // Sprawdź czy obraz jest już załadowany (lub ładowany przez lazy loading)
        if (!img.complete || img.naturalHeight === 0) {
            // Poczekaj na załadowanie obrazu
            await new Promise((resolve) => {
                if (img.complete && img.naturalHeight > 0) {
                    resolve();
                } else {
                    // Poczekaj też na lazy loading (klasa toneka-loaded)
                    const checkLoaded = () => {
                        if (img.complete && img.naturalHeight > 0) {
                            resolve();
                        }
                    };
                    
                    img.addEventListener('load', checkLoaded, { once: true });
                    img.addEventListener('error', resolve, { once: true });
                    
                    // Sprawdź też czy lazy loading już załadował (sprawdź co 100ms)
                    const checkInterval = setInterval(() => {
                        if (img.classList.contains('toneka-loaded') && img.complete && img.naturalHeight > 0) {
                            clearInterval(checkInterval);
                            resolve();
                        }
                    }, 100);
                    
                    // Timeout po 5 sekundach
                    setTimeout(() => {
                        clearInterval(checkInterval);
                        resolve();
                    }, 5000);
                }
            });
        }
        
        // Znajdź element .toneka-product-info
        const productInfo = card.querySelector('.toneka-product-info');
        if (!productInfo) return;
        
        // Sprawdź czy już ma ustawiony kolor (unikaj ponownej analizy)
        if (productInfo.dataset.colorExtracted) return;
        
        try {
            // Wyciągnij dominujący kolor
            let backgroundColor = await extractDominantColor(img);
            
            // Konwertuj na pastelowy jeśli włączone
            if (CONFIG.pastelize) {
                backgroundColor = pastelizeColor(backgroundColor, CONFIG.pastelIntensity);
            }
            
            // Jeśli kolor jest zbyt jasny, przyciemnij go
            if (isTooLight(backgroundColor)) {
                backgroundColor = adjustColorBrightness(backgroundColor, CONFIG.darkenPercentage);
            }
            
            // Ustaw kolor tła
            productInfo.style.backgroundColor = backgroundColor;
            productInfo.dataset.colorExtracted = 'true';
            
        } catch (error) {
            console.error('Błąd podczas przetwarzania karty produktu:', error);
        }
    }

    /**
     * Inicjalizacja - przetwarzanie wszystkich kart produktów
     */
    function init() {
        // Poczekaj na załadowanie DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', processAllCards);
        } else {
            processAllCards();
        }
        
        // Obserwuj zmiany w DOM (dla dynamicznie ładowanych produktów, np. AJAX)
        const observer = new MutationObserver((mutations) => {
            let shouldProcess = false;
            let imageLoaded = false;
            
            mutations.forEach((mutation) => {
                // Sprawdź dodane węzły
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Element node
                        if (node.classList && node.classList.contains('toneka-product-card')) {
                            shouldProcess = true;
                        } else if (node.querySelector && node.querySelector('.toneka-product-card')) {
                            shouldProcess = true;
                        }
                    }
                });
                
                // Sprawdź zmiany atrybutów (lazy loading dodaje klasę toneka-loaded)
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const target = mutation.target;
                    if (target.tagName === 'IMG' && target.classList.contains('toneka-loaded')) {
                        const card = target.closest('.toneka-product-card');
                        if (card && !card.querySelector('.toneka-product-info')?.dataset.colorExtracted) {
                            processProductCard(card);
                            imageLoaded = true;
                        }
                    }
                }
            });
            
            if (shouldProcess && !imageLoaded) {
                // Opóźnienie dla załadowania obrazów przez lazy loading
                setTimeout(processAllCards, CONFIG.newProductsDelay);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'src']
        });
        
        // Nasłuchuj na custom eventy związane z aktualizacjami produktów
        document.addEventListener('toneka_products_updated', () => {
            setTimeout(processAllCards, CONFIG.newProductsDelay);
        });
    }

    /**
     * Przetwarza wszystkie karty produktów na stronie
     */
    async function processAllCards() {
        const productCards = document.querySelectorAll('.toneka-product-card');
        
        // Przetwarzaj karty sekwencyjnie, aby nie obciążać przeglądarki
        for (const card of productCards) {
            await processProductCard(card);
            // Małe opóźnienie między kartami
            await new Promise(resolve => setTimeout(resolve, CONFIG.processingDelay));
        }
    }

    // Uruchom inicjalizację
    init();
    
})();

