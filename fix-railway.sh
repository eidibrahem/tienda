#!/bin/bash

# 🔧 Railway Fix Script
# Run this in Railway Console if you have any issues

echo "🔧 Fixing Railway issues..."

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Step 1: Building assets...${NC}"
npm run build
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Assets built successfully${NC}"
else
    echo -e "${YELLOW}⚠️  Asset build failed or already built${NC}"
fi

echo -e "${YELLOW}Step 2: Clearing cache...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo -e "${GREEN}✅ Cache cleared${NC}"

echo -e "${YELLOW}Step 3: Running migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✅ Migrations completed${NC}"

echo -e "${YELLOW}Step 4: Seeding database...${NC}"
php artisan db:seed --force
echo -e "${GREEN}✅ Database seeded${NC}"

echo -e "${YELLOW}Step 5: Creating storage link...${NC}"
php artisan storage:link
echo -e "${GREEN}✅ Storage link created${NC}"

echo -e "${YELLOW}Step 6: Optimizing...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Optimization complete${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Railway fix completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Checking results:${NC}"
echo ""

echo -e "${YELLOW}1. Assets built:${NC}"
if [ -f "public/build/manifest.json" ]; then
    echo -e "${GREEN}   ✅ manifest.json exists${NC}"
else
    echo -e "${YELLOW}   ⚠️  manifest.json not found${NC}"
fi

echo -e "${YELLOW}2. Templates in database:${NC}"
php artisan tinker --execute="echo 'Templates: ' . \App\Models\Template::count() . PHP_EOL;"

echo -e "${YELLOW}3. Orders in database:${NC}"
php artisan tinker --execute="echo 'Orders: ' . \App\Models\Order::count() . PHP_EOL;"

echo ""
echo -e "${GREEN}Done! Your site should now work correctly.${NC}"

