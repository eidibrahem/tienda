#!/bin/bash

# 🚂 Railway Setup Script for Tienda
# Run this after first deployment on Railway

echo "🚂 Setting up Tienda on Railway..."

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Step 1: Creating session table migration...${NC}"
php artisan session:table
echo -e "${GREEN}✅ Session table migration created${NC}"

echo -e "${YELLOW}Step 2: Creating cache table migration...${NC}"
php artisan cache:table
echo -e "${GREEN}✅ Cache table migration created${NC}"

echo -e "${YELLOW}Step 3: Running all migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✅ All migrations completed${NC}"

echo -e "${YELLOW}Step 4: Seeding database with 3 video templates...${NC}"
php artisan db:seed --force
echo -e "${GREEN}✅ Database seeded${NC}"

echo -e "${YELLOW}Step 5: Creating storage link...${NC}"
php artisan storage:link
echo -e "${GREEN}✅ Storage link created${NC}"

echo -e "${YELLOW}Step 6: Caching configuration...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Configuration cached${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Railway setup completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Your Tienda store is now ready on Railway!${NC}"
echo ""
echo -e "🎬 3 video templates have been added"
echo -e "🔐 Dashboard: /dashboard (password: admin123)"
echo ""

