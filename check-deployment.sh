#!/bin/bash

# 🔍 Tienda Deployment Checker
# This script checks if everything is ready for deployment

echo "🔍 Checking Tienda Deployment Readiness..."
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

ISSUES=0

# Check if artisan exists
echo -n "Checking artisan file... "
if [ -f "artisan" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check if composer.json exists
echo -n "Checking composer.json... "
if [ -f "composer.json" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check if package.json exists
echo -n "Checking package.json... "
if [ -f "package.json" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check if .env exists
echo -n "Checking .env file... "
if [ -f ".env" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${YELLOW}⚠️  Not found (will be created during deployment)${NC}"
fi

# Check if vendor directory exists
echo -n "Checking vendor directory... "
if [ -d "vendor" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${YELLOW}⚠️  Not found (will be created during deployment)${NC}"
fi

# Check video files
echo ""
echo "Checking video files in public/videos/:"
echo -n "  - sample1.mp4... "
if [ -f "public/videos/sample1.mp4" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - sample2.mp4... "
if [ -f "public/videos/sample2.mp4" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - sample3.mp4... "
if [ -f "public/videos/sample3.mp4" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check logo file
echo ""
echo -n "Checking logo file (public/assets/logo.webp)... "
if [ -f "public/assets/logo.webp" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check important migrations
echo ""
echo "Checking database migrations:"
echo -n "  - create_templates_table... "
if [ -f "database/migrations/"*"_create_templates_table.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - create_orders_table... "
if [ -f "database/migrations/"*"_create_orders_table.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - add_video_url_to_templates_table... "
if [ -f "database/migrations/"*"_add_video_url_to_templates_table.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check seeders
echo ""
echo "Checking database seeders:"
echo -n "  - DatabaseSeeder.php... "
if [ -f "database/seeders/DatabaseSeeder.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - TemplateSeeder.php... "
if [ -f "database/seeders/TemplateSeeder.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check important views
echo ""
echo "Checking views:"
echo -n "  - home.blade.php... "
if [ -f "resources/views/home.blade.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - request.blade.php... "
if [ -f "resources/views/request.blade.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - dashboard.blade.php... "
if [ -f "resources/views/dashboard.blade.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check controllers
echo ""
echo "Checking controllers:"
echo -n "  - OrderController.php... "
if [ -f "app/Http/Controllers/OrderController.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check models
echo ""
echo "Checking models:"
echo -n "  - Template.php... "
if [ -f "app/Models/Template.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

echo -n "  - Order.php... "
if [ -f "app/Models/Order.php" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${RED}❌ Not found${NC}"
    ((ISSUES++))
fi

# Check deployment files
echo ""
echo "Checking deployment files:"
echo -n "  - deploy.sh... "
if [ -f "deploy.sh" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${YELLOW}⚠️  Not found${NC}"
fi

echo -n "  - DEPLOYMENT.md... "
if [ -f "DEPLOYMENT.md" ]; then
    echo -e "${GREEN}✅${NC}"
else
    echo -e "${YELLOW}⚠️  Not found${NC}"
fi

# Summary
echo ""
echo "========================================="
if [ $ISSUES -eq 0 ]; then
    echo -e "${GREEN}✅ All checks passed! Ready for deployment.${NC}"
    echo ""
    echo -e "${YELLOW}To deploy, run:${NC}"
    echo -e "${GREEN}./deploy.sh${NC}"
else
    echo -e "${RED}❌ Found $ISSUES issue(s). Please fix them before deploying.${NC}"
fi
echo "========================================="

