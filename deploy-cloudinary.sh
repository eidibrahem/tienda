#!/bin/bash

echo "🖼️ Deploying Cloudinary Image Storage Integration..."
echo ""

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Check if we're in git repo
if [ ! -d .git ]; then
    echo -e "${RED}❌ Error: Not a git repository${NC}"
    exit 1
fi

echo -e "${CYAN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║         Cloudinary Integration Deployment             ║${NC}"
echo -e "${CYAN}╚════════════════════════════════════════════════════════╝${NC}"
echo ""

echo -e "${BLUE}📝 Changes in this deployment:${NC}"
echo "   ✅ Installed Cloudinary PHP SDK"
echo "   ✅ Created CloudinaryService for uploads"
echo "   ✅ Updated OrderController to use Cloudinary"
echo "   ✅ Updated Dashboard to display Cloudinary images"
echo ""

echo -e "${YELLOW}⚠️  IMPORTANT: Before deploying, you MUST configure Cloudinary!${NC}"
echo ""
echo -e "${CYAN}Step 1: Create Cloudinary Account (FREE)${NC}"
echo "   → Visit: https://cloudinary.com/users/register_free"
echo ""
echo -e "${CYAN}Step 2: Get your credentials${NC}"
echo "   → Go to Dashboard"
echo "   → Copy: Cloud Name, API Key, API Secret"
echo ""
echo -e "${CYAN}Step 3: Add to Railway${NC}"
echo "   → Railway Dashboard → tienda-production-10 → Variables"
echo "   → Add these 3 variables:"
echo "      CLOUDINARY_CLOUD_NAME=your_cloud_name"
echo "      CLOUDINARY_API_KEY=your_api_key"
echo "      CLOUDINARY_API_SECRET=your_api_secret"
echo ""

read -p "$(echo -e ${YELLOW}Have you completed Cloudinary setup on Railway? [y/N]: ${NC})" -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo ""
    echo -e "${YELLOW}📖 Please read CLOUDINARY-SETUP.md for detailed instructions${NC}"
    echo ""
    echo -e "${CYAN}Quick setup:${NC}"
    echo "   1. Register at: https://cloudinary.com/users/register_free"
    echo "   2. Get credentials from Dashboard"
    echo "   3. Add to Railway Variables"
    echo "   4. Run this script again"
    echo ""
    echo -e "${RED}❌ Deployment cancelled${NC}"
    echo ""
    echo -e "${YELLOW}💡 You can still deploy now, but images won't upload to Cloudinary until you configure it!${NC}"
    read -p "$(echo -e ${YELLOW}Deploy anyway? [y/N]: ${NC})" -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo ""
echo -e "${BLUE}📊 Git status:${NC}"
git status --short
echo ""

read -p "$(echo -e ${YELLOW}Continue with deployment? [y/N]: ${NC})" -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}❌ Deployment cancelled${NC}"
    exit 1
fi

# Add all changes
echo -e "${BLUE}➕ Adding files...${NC}"
git add composer.json composer.lock
git add app/Services/CloudinaryService.php
git add app/Http/Controllers/OrderController.php
git add resources/views/dashboard.blade.php
git add CLOUDINARY-SETUP.md
git add deploy-cloudinary.sh
git add app/Http/Middleware/TrustProxies.php
git add app/Http/Middleware/ForceHttps.php
git add bootstrap/app.php
git add REDIRECT-LOOP-FIX.md
git add BROWSER-HTTPS-REDIRECT-FIX.md
git add deploy-fix.sh

# Commit
echo -e "${BLUE}💾 Creating commit...${NC}"
git commit -m "Add Cloudinary integration for persistent image storage

- Install Cloudinary PHP SDK
- Create CloudinaryService for cloud uploads
- Update OrderController to use Cloudinary when configured
- Update Dashboard to support both Cloudinary and local images
- Fix HTTPS redirect loop on Railway with TrustProxies
- Images now persist across deployments on Railway"

# Push
echo ""
echo -e "${BLUE}🚀 Pushing to Railway...${NC}"
git push origin main

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║           ✅ Deployment Successful! ✅                  ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${YELLOW}⏳ Wait 1-2 minutes for Railway to build & deploy${NC}"
    echo ""
    echo -e "${CYAN}📋 Post-Deployment Checklist:${NC}"
    echo ""
    echo "   1. ✅ Check Railway logs: railway logs"
    echo "   2. ✅ Verify Cloudinary variables are set"
    echo "   3. ✅ Test image upload: https://tienda-production-10.up.railway.app"
    echo "   4. ✅ Check Dashboard: https://tienda-production-10.up.railway.app/dashboard"
    echo "   5. ✅ Verify images open from Cloudinary"
    echo ""
    echo -e "${CYAN}🔍 How to verify Cloudinary is working:${NC}"
    echo ""
    echo "   • Upload a new order with a photo"
    echo "   • Go to Dashboard"
    echo "   • Click 'View Image'"
    echo "   • URL should be: https://res.cloudinary.com/..."
    echo ""
    echo -e "${GREEN}🎉 Images will now persist forever on Cloudinary!${NC}"
    echo ""
    echo -e "${YELLOW}💡 Need help? Read CLOUDINARY-SETUP.md${NC}"
else
    echo ""
    echo -e "${RED}❌ Push failed!${NC}"
    echo "Please check your git configuration and try again."
    exit 1
fi

