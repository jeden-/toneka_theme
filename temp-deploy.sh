#!/bin/bash

# TONEKA THEME - Production Deployment Script
# Uruchom ten skrypt bezpośrednio na serwerze przez SSH lub cPanel Terminal

echo "🚀 TONEKA THEME - Production Deployment"
echo "======================================="

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() { echo -e "${GREEN}✓${NC} $1"; }
print_info() { echo -e "${BLUE}ℹ${NC} $1"; }
print_warning() { echo -e "${YELLOW}⚠${NC} $1"; }
print_error() { echo -e "${RED}✗${NC} $1"; }

# Check if we're in the right directory
print_info "Checking current directory..."
if [ ! -f "wp-config.php" ]; then
    print_error "Not in WordPress root directory!"
    print_warning "Please navigate to: cd ~/shop.toneka.pl-at4c/public_html"
    print_warning "Then run: bash deploy-to-production.sh"
    exit 1
fi

print_status "Found WordPress installation"
print_info "Current directory: $(pwd)"

# Show current themes
print_info "Current themes:"
ls -la wp-content/themes/ | grep "^d" | awk '{print "  - " $9}'

# Backup existing theme if it exists
if [ -d "wp-content/themes/toneka-theme" ]; then
    BACKUP_NAME="toneka-theme-backup-$(date +%Y%m%d-%H%M%S)"
    print_warning "Backing up existing theme to: $BACKUP_NAME"
    cp -r wp-content/themes/toneka-theme wp-content/themes/$BACKUP_NAME
    print_status "Backup created successfully"
fi

# Clean up any previous deployment
if [ -d "temp-toneka-deployment" ]; then
    print_info "Cleaning up previous deployment..."
    rm -rf temp-toneka-deployment
fi

# Clone repository
print_info "Cloning Toneka Theme repository..."
print_info "Repository: https://github.com/jeden-/toneka_theme.git"

if git clone https://github.com/jeden-/toneka_theme.git temp-toneka-deployment; then
    print_status "Repository cloned successfully"
else
    print_error "Failed to clone repository"
    print_warning "Possible solutions:"
    print_warning "1. Check internet connection"
    print_warning "2. Try: git config --global http.sslverify false"
    print_warning "3. Use manual download method"
    exit 1
fi

# Verify theme structure
THEME_PATH="temp-toneka-deployment/app/public/wp-content/themes/toneka-theme"
if [ ! -d "$THEME_PATH" ]; then
    print_error "Theme directory not found in repository!"
    print_error "Expected: $THEME_PATH"
    print_info "Repository structure:"
    find temp-toneka-deployment -type d -maxdepth 3
    exit 1
fi

# Copy theme files
print_info "Installing theme files..."
cp -r "$THEME_PATH" wp-content/themes/
print_status "Theme files copied successfully"

# Set permissions
print_info "Setting file permissions..."
chmod -R 755 wp-content/themes/toneka-theme/
print_status "Permissions set to 755"

# Verify installation
if [ -f "wp-content/themes/toneka-theme/style.css" ]; then
    print_status "Theme installation verified"
    
    # Count files
    FILE_COUNT=$(find wp-content/themes/toneka-theme -type f | wc -l)
    print_info "Files installed: $FILE_COUNT"
    
    # Show theme info
    print_info "Theme details:"
    head -10 wp-content/themes/toneka-theme/style.css | grep -E "(Theme Name|Description|Version|Author)"
    
else
    print_error "Theme installation failed - style.css not found"
    exit 1
fi

# Clean up
print_info "Cleaning up temporary files..."
rm -rf temp-toneka-deployment
print_status "Cleanup completed"

# Success message
echo ""
print_status "🎉 TONEKA THEME INSTALLED SUCCESSFULLY!"
echo ""
print_info "📋 Next Steps:"
echo "  1. Go to WordPress Admin: https://shop.toneka.pl/wp-admin"
echo "  2. Navigate to: Appearance → Themes"  
echo "  3. Activate: 'Toneka Theme'"
echo "  4. Install WooCommerce plugin if not already installed"
echo "  5. Configure permalinks: Settings → Permalinks → Post name"
echo ""
print_info "🎯 Theme Location: wp-content/themes/toneka-theme/"
print_info "📊 Repository: https://github.com/jeden-/toneka_theme"
print_info "🌐 Website: https://shop.toneka.pl"
echo ""
print_status "Deployment completed successfully! 🚀"
