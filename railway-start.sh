#!/bin/sh
set -e

echo "Starting Railway deployment..."

# Create SQLite database
touch database/database.sqlite
chmod 666 database/database.sqlite

# Run migrations
php artisan migrate --force

# Seed templates (ignore errors if already exists)
php artisan db:seed --force --class=TemplateSeeder || true

# Create storage link (ignore errors if already exists)
php artisan storage:link || true

# Clear cache
php artisan config:clear || true
php artisan cache:clear || true

echo "Starting server on port $PORT..."

# Start the server
exec php artisan serve --host=0.0.0.0 --port=$PORT

