#!/bin/bash

# Toneka Theme Installation Script for Production Server
# Run this script directly on the server: bash install-theme.sh

echo "🚀 Installing Toneka Theme on Production Server..."

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "wp-config.php" ]; then
    print_error "Not in WordPress root directory!"
    print_warning "Please run this script from: ~/shop.toneka.pl-at4c/public_html/"
    exit 1
fi

print_status "Found WordPress installation"

# Create backup of current theme if exists
if [ -d "wp-content/themes/toneka-theme" ]; then
    BACKUP_NAME="toneka-theme-backup-$(date +%Y%m%d-%H%M%S)"
    print_warning "Creating backup: $BACKUP_NAME"
    cp -r wp-content/themes/toneka-theme wp-content/themes/$BACKUP_NAME
    print_status "Backup created: wp-content/themes/$BACKUP_NAME"
fi

# Remove old temp directory if exists
if [ -d "temp-deployment" ]; then
    print_warning "Removing old temp-deployment directory"
    rm -rf temp-deployment
fi

# Clone repository
print_status "Cloning Toneka Theme repository..."
if git clone https://github.com/jeden-/toneka_theme.git temp-deployment; then
    print_status "Repository cloned successfully"
else
    print_error "Failed to clone repository"
    exit 1
fi

# Copy theme files
print_status "Installing theme files..."
if [ -d "temp-deployment/app/public/wp-content/themes/toneka-theme" ]; then
    cp -r temp-deployment/app/public/wp-content/themes/toneka-theme wp-content/themes/
    print_status "Theme files copied successfully"
else
    print_error "Theme directory not found in repository"
    exit 1
fi

# Set correct permissions
print_status "Setting file permissions..."
chmod -R 755 wp-content/themes/toneka-theme/
print_status "Permissions set to 755"

# Clean up
print_status "Cleaning up temporary files..."
rm -rf temp-deployment
print_status "Temporary files removed"

# Final check
if [ -d "wp-content/themes/toneka-theme" ] && [ -f "wp-content/themes/toneka-theme/style.css" ]; then
    print_status "✨ Toneka Theme installed successfully!"
    echo ""
    echo "📋 Next steps:"
    echo "  1. Go to WordPress Admin: https://shop.toneka.pl/wp-admin"
    echo "  2. Navigate to Appearance > Themes"
    echo "  3. Activate 'Toneka Theme'"
    echo "  4. Install/activate WooCommerce plugin"
    echo "  5. Configure permalinks (Settings > Permalinks > Post name)"
    echo ""
    echo "🎯 Theme location: wp-content/themes/toneka-theme/"
    echo "📊 Files installed: $(find wp-content/themes/toneka-theme -type f | wc -l) files"
else
    print_error "Installation failed - theme files not found"
    exit 1
fi
