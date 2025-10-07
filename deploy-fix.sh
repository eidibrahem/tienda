#!/bin/bash

echo "🔧 Deploying HTTPS Redirect Loop Fix to Railway..."
echo ""

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in git repo
if [ ! -d .git ]; then
    echo -e "${RED}❌ Error: Not a git repository${NC}"
    exit 1
fi

# Show what changed
echo -e "${BLUE}📝 Files changed:${NC}"
echo "   - app/Http/Middleware/TrustProxies.php (NEW)"
echo "   - app/Http/Middleware/ForceHttps.php (UPDATED)"
echo "   - bootstrap/app.php (UPDATED)"
echo ""

# Check git status
echo -e "${BLUE}📊 Git status:${NC}"
git status --short
echo ""

# Ask for confirmation
read -p "$(echo -e ${YELLOW}Continue with deployment? [y/N]: ${NC})" -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}❌ Deployment cancelled${NC}"
    exit 1
fi

# Add all changes
echo -e "${BLUE}➕ Adding files...${NC}"
git add app/Http/Middleware/TrustProxies.php
git add app/Http/Middleware/ForceHttps.php
git add bootstrap/app.php
git add REDIRECT-LOOP-FIX.md
git add deploy-fix.sh

# Commit
echo -e "${BLUE}💾 Creating commit...${NC}"
git commit -m "Fix HTTPS redirect loop on Railway

- Add TrustProxies middleware to handle X-Forwarded-Proto
- Update ForceHttps middleware to work with proxies
- Configure trustProxies in bootstrap/app.php
- This fixes ERR_TOO_MANY_REDIRECTS on Railway"

# Push
echo ""
echo -e "${BLUE}🚀 Pushing to Railway...${NC}"
git push origin main

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}✅ Deployment successful!${NC}"
    echo ""
    echo -e "${YELLOW}⏳ Wait 1-2 minutes for Railway to build & deploy${NC}"
    echo ""
    echo "Then visit: https://tienda-production-10.up.railway.app"
    echo ""
    echo -e "${BLUE}💡 If still having issues:${NC}"
    echo "   1. Clear browser cache (Ctrl+Shift+Delete)"
    echo "   2. Or use Incognito window"
    echo "   3. Check Railway logs: railway logs"
else
    echo ""
    echo -e "${RED}❌ Push failed!${NC}"
    echo "Please check your git configuration and try again."
    exit 1
fi

