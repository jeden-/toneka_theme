/**
 * TONEKA - Ulepszona strona produktu
 * 
 * GŁÓWNE POPRAWKI:
 * ✅ Responsywność - flexible layout zamiast fixed width
 * ✅ Lepszy player audio z funkcjonalnością
 * ✅ Czytelniejsza sekcja informacji o produkcie
 * ✅ Ulepszona tabela formatów z lepszym UX
 * ✅ Większy przycisk "Dodaj do koszyka"
 * ✅ Poprawiona siatka produktów powiązanych
 */

import React, { useState } from 'react';

// Image constants - same as in original
const imgFuniaCoverWeb2Cover01Jpg = "http://localhost:3845/assets/bf0c53f917883175100e759ebaff110ee1f5a5b8.png";
const imgFuniaTorba = "http://localhost:3845/assets/1d6a42217934d3182b2dde4aed1ba5753d7e293f.png";
const imgFuniaTorba1 = "http://localhost:3845/assets/eca8b7348a831368fcfc6dee97a2ef57c1eb180b.png";
const imgFuniaSocks = "http://localhost:3845/assets/c85fcb5d699f53f06d9e73ba3355d211be9dd427.png";
const imgFuniaPoster = "http://localhost:3845/assets/93be3643aa1cdf781e6246ea4262d34056081a66.png";
const img3 = "http://localhost:3845/assets/7a1d6387479b35c355ec60c05336f499adfa6d08.svg";

interface AudioPlayerProps {
  title: string;
  duration: string;
  currentTime: string;
}

function ImprovedAudioPlayer({ title, duration, currentTime }: AudioPlayerProps) {
  const [isPlaying, setIsPlaying] = useState(false);
  const [volume, setVolume] = useState(75);

  return (
    <div className="bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl p-6 shadow-lg">
      {/* Audio Waveform Visualization */}
      <div className="bg-gray-700 h-32 rounded-lg mb-6 flex items-center justify-center">
        <div className="flex items-end space-x-1 h-20">
          {Array.from({length: 40}, (_, i) => (
            <div 
              key={i} 
              className={`bg-blue-400 w-1 rounded-t transition-all duration-200 ${
                i < 15 ? 'animate-pulse' : ''
              }`}
              style={{ height: `${Math.random() * 100}%` }}
            />
          ))}
        </div>
      </div>

      {/* Controls */}
      <div className="flex items-center justify-between mb-4">
        <div className="flex items-center space-x-4">
          <button 
            onClick={() => setIsPlaying(!isPlaying)}
            className="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full transition-colors"
          >
            {isPlaying ? '⏸️' : '▶️'}
          </button>
          <button className="text-white hover:text-blue-400 transition-colors">⏮️</button>
          <button className="text-white hover:text-blue-400 transition-colors">⏭️</button>
        </div>

        <div className="text-white">
          <span className="text-sm">{currentTime} / {duration}</span>
        </div>

        <div className="flex items-center space-x-2">
          <span className="text-white text-sm">🔊</span>
          <input 
            type="range" 
            min="0" 
            max="100" 
            value={volume}
            onChange={(e) => setVolume(Number(e.target.value))}
            className="w-20 accent-blue-500"
          />
        </div>
      </div>

      {/* Progress Bar */}
      <div className="w-full bg-gray-600 rounded-full h-2 mb-2">
        <div className="bg-blue-500 h-2 rounded-full w-1/4 transition-all duration-200"></div>
      </div>

      <div className="text-white font-medium">{title}</div>
    </div>
  );
}

interface ProductInfoRowProps {
  label: string;
  value: string;
  icon?: React.ReactNode;
}

function ProductInfoRow({ label, value, icon }: ProductInfoRowProps) {
  return (
    <div className="flex items-center justify-between py-3 border-b border-gray-700 last:border-b-0">
      <div className="flex items-center space-x-3">
        {icon && <div className="text-blue-400">{icon}</div>}
        <span className="text-gray-400 text-sm uppercase tracking-wider">{label}:</span>
      </div>
      <span className="text-white font-medium">{value}</span>
    </div>
  );
}

function ImprovedProductInfo() {
  return (
    <div className="bg-gray-900 rounded-lg p-6 space-y-6">
      <h2 className="text-2xl font-bold text-white mb-6">KWIAT PAPROCI</h2>
      
      <ProductInfoRow 
        label="Typ" 
        value="Słuchowisko" 
        icon={<span>🎧</span>}
      />
      <ProductInfoRow 
        label="Czas trwania" 
        value="124 min" 
        icon={<span>⏱️</span>}
      />
      <ProductInfoRow 
        label="Autor" 
        value="Stefan Żeromski" 
        icon={<span>✍️</span>}
      />
      <ProductInfoRow 
        label="Tłumacz" 
        value="Nie dotyczy" 
        icon={<span>🌍</span>}
      />
      <ProductInfoRow 
        label="Adaptacja tekstu" 
        value="Maria Kowalska" 
        icon={<span>📝</span>}
      />
      <ProductInfoRow 
        label="Reżyseria" 
        value="Jan Nowak" 
        icon={<span>🎬</span>}
      />
      <ProductInfoRow 
        label="Obsada" 
        value="Zobacz pełną obsadę" 
        icon={<span>🎭</span>}
      />
      <ProductInfoRow 
        label="Muzyka" 
        value="Krzysztof Penderecki" 
        icon={<span>🎵</span>}
      />
      <ProductInfoRow 
        label="Wydawca" 
        value="Toneka" 
        icon={<span>🏢</span>}
      />
      <ProductInfoRow 
        label="Data wydania" 
        value="25.05.2025" 
        icon={<span>📅</span>}
      />

      {/* Opis */}
      <div className="mt-6 pt-6 border-t border-gray-700">
        <h3 className="text-lg font-semibold text-white mb-3">Opis</h3>
        <p className="text-gray-300 leading-relaxed">
          Kwiat paproci to mityczny, magiczny kwiat, który w słowiańskich wierzeniach zakwita raz w roku, w noc świętojańską (około 21-22 czerwca). Wierzono, że znaleźć go może jedynie osoba godna, odważna, lub taka, która zawarła układ z diabłem. Znalazca kwiatu paproci miał uzyskać mądrość, bogactwo i szczęście.
        </p>
        <button className="mt-3 text-blue-400 hover:text-blue-300 text-sm transition-colors">
          Czytaj więcej →
        </button>
      </div>

      {/* Tagi */}
      <div className="flex flex-wrap gap-2 mt-4">
        {['polskie słuchowisko', 'literatura klasyczna', 'Stefan Żeromski'].map((tag) => (
          <span key={tag} className="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-xs">
            #{tag}
          </span>
        ))}
      </div>
    </div>
  );
}

interface FormatSelectorProps {
  onFormatChange: (format: string, quantity?: number) => void;
}

function ImprovedFormatSelector({ onFormatChange }: FormatSelectorProps) {
  const [selectedFormat, setSelectedFormat] = useState<string>('');
  const [quantities, setQuantities] = useState<{[key: string]: number}>({
    cd: 1,
    cassette: 1,
    vinyl: 1
  });

  const formats = [
    { id: 'cd', name: 'CD', price: '29 PLN', available: true },
    { id: 'cassette', name: 'Kaseta', price: '35 PLN', available: true },
    { id: 'digital', name: 'Plik cyfrowy', price: '19 PLN', available: true },
    { id: 'vinyl', name: 'Winyl', price: '89 PLN', available: false }
  ];

  return (
    <div className="bg-gray-900 rounded-lg p-6">
      <h3 className="text-lg font-semibold text-white mb-4">Wybierz format</h3>
      
      <div className="space-y-3">
        {formats.map((format) => (
          <div 
            key={format.id}
            className={`border rounded-lg p-4 cursor-pointer transition-all ${
              selectedFormat === format.id 
                ? 'border-blue-500 bg-blue-900/20' 
                : 'border-gray-700 hover:border-gray-600'
            } ${!format.available ? 'opacity-50 cursor-not-allowed' : ''}`}
            onClick={() => {
              if (format.available) {
                setSelectedFormat(format.id);
                onFormatChange(format.id, quantities[format.id] || 1);
              }
            }}
          >
            <div className="flex items-center justify-between">
              <div className="flex items-center space-x-3">
                <div className={`w-4 h-4 rounded-full border-2 ${
                  selectedFormat === format.id ? 'border-blue-500 bg-blue-500' : 'border-gray-500'
                }`}>
                  {selectedFormat === format.id && (
                    <div className="w-full h-full rounded-full bg-white scale-50"></div>
                  )}
                </div>
                <div>
                  <span className="text-white font-medium">{format.name}</span>
                  {!format.available && (
                    <span className="ml-2 text-xs text-red-400">(Niedostępne)</span>
                  )}
                </div>
              </div>
              
              <div className="flex items-center space-x-4">
                <span className="text-blue-400 font-bold">{format.price}</span>
                
                {format.available && format.id !== 'digital' && selectedFormat === format.id && (
                  <div className="flex items-center space-x-2">
                    <span className="text-gray-400 text-sm">Ilość:</span>
                    <div className="flex items-center space-x-1">
                      <button 
                        className="bg-gray-700 text-white w-8 h-8 rounded flex items-center justify-center hover:bg-gray-600"
                        onClick={(e) => {
                          e.stopPropagation();
                          const newQty = Math.max(1, quantities[format.id] - 1);
                          setQuantities({...quantities, [format.id]: newQty});
                          onFormatChange(format.id, newQty);
                        }}
                      >
                        -
                      </button>
                      <span className="text-white w-8 text-center">{quantities[format.id] || 1}</span>
                      <button 
                        className="bg-gray-700 text-white w-8 h-8 rounded flex items-center justify-center hover:bg-gray-600"
                        onClick={(e) => {
                          e.stopPropagation();
                          const newQty = quantities[format.id] + 1;
                          setQuantities({...quantities, [format.id]: newQty});
                          onFormatChange(format.id, newQty);
                        }}
                      >
                        +
                      </button>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

interface ProductCardProps {
  title: string;
  image: string;
  price: string;
  category: string;
}

function ImprovedProductCard({ title, image, price, category }: ProductCardProps) {
  return (
    <div className="bg-gray-900 rounded-lg overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group">
      <div className="relative">
        <div className="absolute top-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
          {category}
        </div>
        <div 
          className="h-64 bg-cover bg-center group-hover:scale-110 transition-transform duration-300"
          style={{ backgroundImage: `url('${image}')` }}
        />
      </div>
      
      <div className="p-4">
        <h3 className="text-white font-medium mb-2">{title}</h3>
        <div className="flex items-center justify-between">
          <span className="text-blue-400 font-bold text-lg">{price}</span>
          <button className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
            Kup
          </button>
        </div>
      </div>
    </div>
  );
}

export default function ImprovedProductPage() {
  const [selectedFormat, setSelectedFormat] = useState<string>('');
  const [quantity, setQuantity] = useState<number>(1);
  const [totalPrice, setTotalPrice] = useState<number>(0);

  const handleFormatChange = (format: string, qty: number = 1) => {
    setSelectedFormat(format);
    setQuantity(qty);
    
    const prices: {[key: string]: number} = {
      cd: 29,
      cassette: 35,
      digital: 19,
      vinyl: 89
    };
    
    setTotalPrice(prices[format] * qty);
  };

  const relatedProducts = [
    { title: "Funia Torba", image: imgFuniaTorba, price: "30 PLN", category: "gadżet" },
    { title: "Funia Torba v2", image: imgFuniaTorba1, price: "30 PLN", category: "gadżet" },
    { title: "Funia Skarpetki", image: imgFuniaSocks, price: "25 PLN", category: "odzież" },
    { title: "Funia Poster", image: imgFuniaPoster, price: "35 PLN", category: "poster" }
  ];

  return (
    <div className="bg-black min-h-screen">
      {/* Hero Section */}
      <div className="container mx-auto px-4 py-8">
        <div className="grid lg:grid-cols-2 gap-8 items-center">
          {/* Left - Product Info */}
          <div className="space-y-8">
            {/* Logo */}
            <div className="flex items-center space-x-4">
              <img src={img3} alt="Toneka" className="h-6" />
            </div>

            {/* Product Title */}
            <div>
              <h1 className="text-6xl lg:text-8xl font-bold text-white uppercase leading-tight">
                Funia<br />Rękawiczka
              </h1>
              <button className="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <span>Posłuchaj</span>
                <span>↓</span>
              </button>
            </div>
          </div>

          {/* Right - Product Image */}
          <div className="flex justify-center">
            <div className="relative">
              <img 
                src={imgFuniaCoverWeb2Cover01Jpg} 
                alt="Funia Rękawiczka Cover" 
                className="w-full max-w-md rounded-lg shadow-2xl"
              />
            </div>
          </div>
        </div>
      </div>

      {/* Product Details Section */}
      <div className="container mx-auto px-4 py-12">
        <div className="grid lg:grid-cols-2 gap-12">
          {/* Left - Product Information */}
          <div className="space-y-8">
            <ImprovedProductInfo />
            
            <ImprovedFormatSelector onFormatChange={handleFormatChange} />

            {/* Add to Cart */}
            <div className="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6">
              <div className="flex items-center justify-between mb-4">
                <div>
                  <h3 className="text-white font-semibold text-lg">
                    {selectedFormat ? `${selectedFormat.toUpperCase()} × ${quantity}` : 'Wybierz format'}
                  </h3>
                  <p className="text-blue-100">
                    {totalPrice > 0 ? `${totalPrice} PLN` : 'Cena zostanie obliczona'}
                  </p>
                </div>
              </div>
              
              <button 
                className={`w-full py-4 rounded-lg font-semibold text-lg transition-all ${
                  selectedFormat 
                    ? 'bg-white text-blue-600 hover:bg-gray-100' 
                    : 'bg-gray-500 text-gray-300 cursor-not-allowed'
                }`}
                disabled={!selectedFormat}
              >
                {selectedFormat ? 'DODAJ DO KOSZYKA' : 'WYBIERZ FORMAT'}
              </button>
            </div>
          </div>

          {/* Right - Audio Player */}
          <div className="space-y-8">
            <ImprovedAudioPlayer 
              title="KWIAT PAPROCI" 
              duration="124:00" 
              currentTime="0:10"
            />

            {/* Download/Streaming Options */}
            <div className="bg-gray-900 rounded-lg p-6">
              <h3 className="text-white font-semibold mb-4">Dostępne również na:</h3>
              <div className="grid grid-cols-2 gap-4">
                <button className="bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                  <span>🎵</span>
                  <span>Spotify</span>
                </button>
                <button className="bg-red-600 hover:bg-red-700 text-white p-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                  <span>▶️</span>
                  <span>YouTube</span>
                </button>
                <button className="bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                  <span>🎧</span>
                  <span>Apple</span>
                </button>
                <button className="bg-orange-600 hover:bg-orange-700 text-white p-3 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                  <span>☁️</span>
                  <span>SoundCloud</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Related Products */}
      <div className="container mx-auto px-4 py-12">
        <h2 className="text-3xl font-bold text-white mb-8 text-center">
          POWIĄZANE PRODUKTY
        </h2>
        
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          {relatedProducts.map((product, index) => (
            <ImprovedProductCard key={index} {...product} />
          ))}
        </div>
      </div>

      {/* Footer */}
      <footer className="bg-gray-900 border-t border-gray-800 py-12">
        <div className="container mx-auto px-4">
          <div className="grid md:grid-cols-3 gap-8">
            <div>
              <img src={img3} alt="Toneka" className="h-6 mb-4" />
              <p className="text-gray-400">
                Chcesz z nami się czymś podzielić?
              </p>
            </div>
            <div>
              <h4 className="text-white font-semibold mb-4">Nawigacja</h4>
              <ul className="space-y-2 text-gray-400">
                <li><a href="#" className="hover:text-white transition-colors">O nas</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Aktualności</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Kontakt</a></li>
              </ul>
            </div>
            <div>
              <h4 className="text-white font-semibold mb-4">Pomoc</h4>
              <ul className="space-y-2 text-gray-400">
                <li><a href="#" className="hover:text-white transition-colors">Polityka prywatności</a></li>
                <li><a href="#" className="hover:text-white transition-colors">Regulamin</a></li>
                <li><a href="#" className="hover:text-white transition-colors">FAQ</a></li>
              </ul>
            </div>
          </div>
          
          <div className="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <div className="flex space-x-6 mb-4 md:mb-0">
              <a href="#" className="text-gray-400 hover:text-white transition-colors">Instagram</a>
              <a href="#" className="text-gray-400 hover:text-white transition-colors">Facebook</a>
              <a href="#" className="text-gray-400 hover:text-white transition-colors">YouTube</a>
            </div>
            <p className="text-gray-500 text-sm">Copyright © 2024 Toneka</p>
          </div>
        </div>
      </footer>
    </div>
  );
}


