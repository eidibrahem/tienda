#!/bin/bash

# 🔧 Fix Local Environment Script
# Run this if you get "Unsupported SSL request" error locally

echo "🔧 Fixing local environment..."

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Check if .env exists
if [ ! -f ".env" ]; then
    echo -e "${RED}❌ .env file not found!${NC}"
    echo "Please create .env file first."
    exit 1
fi

# Check current APP_ENV
CURRENT_ENV=$(grep "^APP_ENV=" .env | cut -d'=' -f2)
echo -e "${YELLOW}Current APP_ENV: $CURRENT_ENV${NC}"

if [ "$CURRENT_ENV" = "production" ]; then
    echo -e "${YELLOW}⚠️  Warning: APP_ENV is set to 'production' in local .env${NC}"
    echo "For local development, it should be 'local'"
    echo ""
    read -p "Do you want to change it to 'local'? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        # Backup .env
        cp .env .env.backup
        echo -e "${GREEN}✅ Backed up .env to .env.backup${NC}"
        
        # Change APP_ENV to local
        sed -i.tmp 's/^APP_ENV=production/APP_ENV=local/' .env
        rm .env.tmp 2>/dev/null
        echo -e "${GREEN}✅ Changed APP_ENV to 'local'${NC}"
        
        # Change APP_DEBUG to true
        sed -i.tmp 's/^APP_DEBUG=false/APP_DEBUG=true/' .env
        rm .env.tmp 2>/dev/null
        echo -e "${GREEN}✅ Changed APP_DEBUG to 'true'${NC}"
        
        # Change APP_URL to http
        sed -i.tmp 's|^APP_URL=https://|APP_URL=http://localhost:8000|' .env
        rm .env.tmp 2>/dev/null
        echo -e "${GREEN}✅ Changed APP_URL to 'http://localhost:8000'${NC}"
    fi
else
    echo -e "${GREEN}✅ APP_ENV is already set to '$CURRENT_ENV' (not production)${NC}"
fi

echo ""
echo -e "${YELLOW}Clearing cache...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo -e "${GREEN}✅ Cache cleared${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Local environment fixed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Now restart your local server:${NC}"
echo "  • If using 'php artisan serve': Press Ctrl+C and run again"
echo "  • If using XAMPP: Restart Apache from XAMPP Control Panel"
echo ""
echo -e "${YELLOW}Your local .env should have:${NC}"
echo "  APP_ENV=local"
echo "  APP_DEBUG=true"
echo "  APP_URL=http://localhost:8000"
echo ""


