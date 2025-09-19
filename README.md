# Toneka - Sklep Internetowy

Nowoczesny sklep internetowy z produktami cyfrowymi (słuchowiska, merch) zbudowany na WordPress + WooCommerce.

## 🚀 Technologie

- **WordPress** 6.x
- **WooCommerce** 8.x
- **Custom Theme**: `toneka-theme`
- **CSS**: Custom styles z responsive design
- **JavaScript**: jQuery + vanilla JS
- **PHP**: 8.1+

## 🎨 Funkcjonalności

### ✅ Zrealizowane
- **Custom Theme** - Pełny custom design
- **Responsive Design** - Mobile-first approach
- **WooCommerce Integration** - Pełna integracja sklepu
- **Custom Post Types** - Twórcy (creators)
- **Advanced Cart** - Minicart z upsell produktami
- **Price Display** - Przeceny, oszczędności
- **AJAX Filtering** - Kategorie produktów
- **Custom Templates** - Product, category, creator pages
- **Adaptive Header** - Zmienia kolor w zależności od zdjęcia
- **Infinity Scroll** - Lista twórców
- **SEO Optimized** - Structured data, breadcrumbs

### 🛍️ E-commerce Features
- **Product Variants** - CD, kaseta, płyta winylowa, pliki cyfrowe
- **Sale Prices** - Przeceny z kalkulacją oszczędności
- **Cross-sell/Up-sell** - Podobne produkty
- **Cart Management** - AJAX updates, quantity changes
- **Checkout Process** - Custom styled checkout
- **Account Pages** - Login, register, my account

### 🎭 Content Types
- **Produkty** - Słuchowiska i Merch
- **Kategorie** - Hierarchiczne z custom hero sections
- **Twórcy** - Custom post type z portfolio
- **Tagi** - Klikalne tagi produktów

## 📁 Struktura Projektu

```
toneka-theme/
├── style.css              # Main stylesheet (4700+ lines)
├── functions.php          # Theme functions & hooks
├── header.php            # Site header
├── footer.php            # Site footer
├── index.php             # Main template
├── single-creator.php    # Creator page template
├── archive-creator.php   # Creators archive
├── taxonomy-product_cat.php  # Category pages
├── taxonomy-product_tag.php  # Tag pages
├── js/
│   ├── minicart.js       # Shopping cart functionality
│   ├── category-page.js  # AJAX filtering
│   ├── creator-page.js   # Creator page interactions
│   ├── creators-archive.js # Infinity scroll
│   ├── adaptive-header.js  # Header color adaptation
│   └── cart-checkout.js  # Cart & checkout functionality
└── woocommerce/
    ├── single-product.php    # Product page
    ├── cart/cart.php        # Cart page
    └── checkout/form-checkout.php # Checkout
```

## 🎯 Design System

### Kolory
- **Główny**: `#000000` (czarny)
- **Tekst**: `#ffffff` (biały)
- **Akcenty**: `#404040` (szary)
- **Przeceny**: `#666666` (jasnoszary)

### Typography
- **Font**: `'Figtree', sans-serif`
- **Headings**: Uppercase, różne wagi
- **Body**: 1rem base size
- **Responsive**: Skalowane dla mobile

### Components
- **Buttons**: Białe z hover effects
- **Forms**: Czarne tło, białe inputy
- **Cards**: Kwadratowe z hover animations
- **Modals**: Minicart, overlays

## 🛠️ Instalacja Lokalna

1. **Local by Flywheel** setup
2. **Clone repository**:
   ```bash
   git clone [repo-url] tonekacursor
   ```
3. **Import database** (jeśli dostępna)
4. **Update wp-config.php** z lokalnymi ustawieniami

## 🚀 Deployment

### Serwer Produkcyjny
- **Hosting**: [TBD]
- **Domain**: [TBD]
- **SSL**: Tak
- **CDN**: [TBD]

### GitHub Actions
Automatyczny deployment po push do `main` branch.

## 📱 Responsive Breakpoints

```css
/* Mobile First */
@media (max-width: 480px)  { /* Small mobile */ }
@media (max-width: 768px)  { /* Mobile/Tablet */ }
@media (max-width: 1024px) { /* Tablet */ }
@media (min-width: 1025px) { /* Desktop */ }
```

## 🧪 Testing

### Browser Support
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)

### Device Testing
- ✅ iPhone (Safari)
- ✅ Android (Chrome)
- ✅ iPad (Safari)
- ✅ Desktop (1920x1080+)

## 📈 Performance

### Optimizations
- **Lazy loading** obrazów
- **AJAX** dla dynamicznych treści
- **Minified** CSS/JS (production)
- **Optimized** images (WebP)
- **Caching** headers

## 🔧 Development

### Local Development
```bash
# Start Local by Flywheel site
# Make changes to theme files
# Test functionality
# Commit changes
git add .
git commit -m "feat: description of changes"
git push origin main
```

### Code Style
- **PHP**: WordPress Coding Standards
- **CSS**: BEM methodology / Utility classes
- **JS**: ES6+, jQuery compatibility
- **Comments**: Dokumentacja w kodzie

## 📋 TODO

- [ ] Automatyczny deployment
- [ ] Performance optimization
- [ ] SEO improvements
- [ ] Analytics integration
- [ ] Email templates
- [ ] Multi-language support

## 👥 Team

- **Developer**: Mariusz + AI Assistant
- **Design**: Based on Figma designs
- **Content**: TBD

## 📞 Support

W przypadku problemów:
1. Sprawdź logi WordPress (`/wp-content/debug.log`)
2. Sprawdź browser console
3. Sprawdź GitHub Issues

---

**Last Updated**: $(date)
**Version**: 1.0.0
