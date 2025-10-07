#!/bin/bash
set -e

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🚀 Starting Railway Deployment"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# 1. Create SQLite database if doesn't exist
echo ""
echo "📦 Step 1: Setting up database..."
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chmod 666 database/database.sqlite
    echo "✅ SQLite database created"
else
    echo "✅ SQLite database exists"
fi

# 2. Run migrations
echo ""
echo "📦 Step 2: Running migrations..."
if php artisan migrate --force; then
    echo "✅ Migrations completed successfully"
else
    echo "❌ Migrations failed!"
    exit 1
fi

# 3. Seed templates (don't fail if already exists)
echo ""
echo "📦 Step 3: Seeding templates..."
if php artisan db:seed --force --class=TemplateSeeder 2>/dev/null; then
    echo "✅ Templates seeded successfully"
else
    echo "⚠️  Template seeding skipped (may already exist)"
fi

# 4. Create storage link
echo ""
echo "📦 Step 4: Creating storage link..."
if php artisan storage:link 2>/dev/null; then
    echo "✅ Storage link created"
else
    echo "⚠️  Storage link already exists or failed (continuing...)"
fi

# 5. Clear cache
echo ""
echo "📦 Step 5: Clearing cache..."
php artisan config:clear
php artisan cache:clear
echo "✅ Cache cleared"

# 6. Check Cloudinary configuration
echo ""
echo "📦 Step 6: Checking Cloudinary configuration..."
if [ -n "$CLOUDINARY_URL" ] || [ -n "$CLOUDINARY_CLOUD_NAME" ]; then
    echo "✅ Cloudinary is configured"
    if [ -n "$CLOUDINARY_URL" ]; then
        echo "   Using CLOUDINARY_URL"
    else
        echo "   Using individual variables"
    fi
else
    echo "⚠️  Cloudinary NOT configured - photos will use local storage"
    echo "   Add CLOUDINARY_URL to Railway Variables for photo upload support"
fi

# 7. Start the server
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🚀 Starting Laravel Server on port $PORT"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

exec php artisan serve --host=0.0.0.0 --port=$PORT

