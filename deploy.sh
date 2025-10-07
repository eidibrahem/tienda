#!/bin/bash

# 🚀 Tienda Deployment Script
# This script automates the deployment process

echo "🚀 Starting Tienda Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the project directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: artisan file not found. Are you in the project root?${NC}"
    exit 1
fi

echo -e "${YELLOW}📦 Step 1: Installing Composer dependencies...${NC}"
composer install --optimize-autoloader --no-dev
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Composer dependencies installed${NC}"
else
    echo -e "${RED}❌ Failed to install Composer dependencies${NC}"
    exit 1
fi

echo -e "${YELLOW}📦 Step 2: Installing NPM dependencies and building assets...${NC}"
if command -v npm &> /dev/null; then
    npm install && npm run build
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ NPM dependencies installed and assets built${NC}"
    else
        echo -e "${RED}❌ Failed to install NPM dependencies${NC}"
        exit 1
    fi
else
    echo -e "${YELLOW}⚠️  NPM not found, skipping asset build${NC}"
fi

echo -e "${YELLOW}🔑 Step 3: Checking .env file...${NC}"
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${GREEN}✅ Created .env from .env.example${NC}"
    else
        echo -e "${RED}❌ No .env or .env.example file found${NC}"
        echo -e "${YELLOW}Please create .env file with your database credentials${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}✅ .env file exists${NC}"
fi

echo -e "${YELLOW}🔑 Step 4: Generating application key...${NC}"
php artisan key:generate --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Application key generated${NC}"
else
    echo -e "${RED}❌ Failed to generate application key${NC}"
    exit 1
fi

echo -e "${YELLOW}🗄️  Step 5: Running database migrations...${NC}"
php artisan migrate --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Migrations completed${NC}"
else
    echo -e "${RED}❌ Failed to run migrations${NC}"
    exit 1
fi

echo -e "${YELLOW}🌱 Step 6: Seeding database with 3 video templates...${NC}"
php artisan db:seed --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Database seeded successfully${NC}"
    echo -e "${GREEN}   - Sample 1: 5 AED (sample1.mp4)${NC}"
    echo -e "${GREEN}   - Sample 2: 10 AED (sample2.mp4)${NC}"
    echo -e "${GREEN}   - Sample 3: 15 AED (sample3.mp4)${NC}"
else
    echo -e "${RED}❌ Failed to seed database${NC}"
    exit 1
fi

echo -e "${YELLOW}🔒 Step 7: Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Permissions set${NC}"
else
    echo -e "${RED}❌ Failed to set permissions${NC}"
fi

echo -e "${YELLOW}🔗 Step 8: Creating storage link...${NC}"
php artisan storage:link
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Storage link created${NC}"
else
    echo -e "${YELLOW}⚠️  Storage link already exists or failed${NC}"
fi

echo -e "${YELLOW}⚡ Step 9: Optimizing for production...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Application optimized${NC}"
else
    echo -e "${RED}❌ Failed to optimize application${NC}"
fi

echo ""
echo -e "${GREEN}✅ ========================================${NC}"
echo -e "${GREEN}✅  Deployment Completed Successfully!${NC}"
echo -e "${GREEN}✅ ========================================${NC}"
echo ""
echo -e "${YELLOW}📋 Next Steps:${NC}"
echo -e "1. Upload video files to: ${GREEN}public/videos/${NC}"
echo -e "   - sample1.mp4"
echo -e "   - sample2.mp4"
echo -e "   - sample3.mp4"
echo ""
echo -e "2. Configure your web server to point to: ${GREEN}public/${NC} directory"
echo ""
echo -e "3. Access your site and verify 3 videos are displayed"
echo ""
echo -e "4. Admin dashboard: ${GREEN}https://yourdomain.com/dashboard${NC}"
echo -e "   Password: ${GREEN}admin123${NC}"
echo ""
echo -e "${GREEN}🎉 Your Tienda store is ready!${NC}"

