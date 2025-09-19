#!/bin/bash

# Toneka Production Deployment Script
# Usage: ./deploy.sh [environment]
# Environments: staging, production

set -e  # Exit on any error

ENVIRONMENT=${1:-staging}
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

echo "🚀 Starting deployment to $ENVIRONMENT..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
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
if [ ! -f "app/public/wp-content/themes/toneka-theme/style.css" ]; then
    print_error "Not in the correct project directory!"
    exit 1
fi

# Check if git is clean
if [ -n "$(git status --porcelain)" ]; then
    print_warning "You have uncommitted changes. Please commit or stash them first."
    git status --short
    exit 1
fi

print_status "Git repository is clean"

# Run local tests
print_status "Running PHP syntax check..."
find app/public/wp-content/themes/toneka-theme -name "*.php" -exec php -l {} \; > /dev/null
print_status "PHP syntax check passed"

# Check if on main branch for production
if [ "$ENVIRONMENT" = "production" ]; then
    CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
    if [ "$CURRENT_BRANCH" != "main" ]; then
        print_error "Production deployments must be from 'main' branch. Currently on: $CURRENT_BRANCH"
        exit 1
    fi
fi

# Push to GitHub (triggers GitHub Actions)
print_status "Pushing to GitHub..."
git push origin $(git rev-parse --abbrev-ref HEAD)

print_status "Deployment initiated! Check GitHub Actions for progress."
print_status "GitHub Actions URL: https://github.com/[your-username]/toneka/actions"

# Optional: Wait for deployment to complete
read -p "Do you want to monitor the deployment? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_status "Opening GitHub Actions in browser..."
    open "https://github.com/[your-username]/toneka/actions"
fi

echo
print_status "Deployment script completed!"
echo "📋 Next steps:"
echo "  1. Monitor GitHub Actions for deployment status"
echo "  2. Test the $ENVIRONMENT website"
echo "  3. Check logs for any issues"
echo "  4. Notify team of deployment"
