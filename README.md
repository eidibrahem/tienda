<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

# 🛍️ Tienda - Custom Video Store

A Laravel-based custom video ordering platform.

## 🚀 Deployment Instructions (Server Setup)

### 1️⃣ Upload Files to Server
Upload all project files to your server (e.g., `/public_html/tienda/`)

### 2️⃣ Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

### 3️⃣ Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4️⃣ Configure Database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 5️⃣ **Run Migrations & Seed Database** ⚡
This will create all tables AND automatically insert 3 video templates:
```bash
php artisan migrate --force
php artisan db:seed --force
```

The seeder will automatically create:
- ✅ `templates` table with 3 video templates
- ✅ Sample 1 - 5 AED (sample1.mp4)
- ✅ Sample 2 - 10 AED (sample2.mp4)
- ✅ Sample 3 - 15 AED (sample3.mp4)

### 6️⃣ Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7️⃣ Upload Video Files
Make sure these video files are uploaded to `public/videos/`:
- `sample1.mp4`
- `sample2.mp4`
- `sample3.mp4`

### 8️⃣ Configure Web Server
Point your web server document root to `/public` directory.

**For Apache (.htaccess):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**For Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 9️⃣ Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 🔟 Test Your Site
Visit your domain and you should see 3 video templates on the home page!

---

## 📋 Admin Access

**Dashboard URL:** `/dashboard`  
**Password:** `admin123`

---

## 🔄 Update Database After Deployment

If you need to re-seed the database (won't duplicate existing records):
```bash
php artisan db:seed --class=TemplateSeeder --force
```

---

## 🛠️ Troubleshooting

### Templates not showing?
```bash
# Re-run seeder
php artisan db:seed --force

# Clear cache
php artisan cache:clear
php artisan view:clear
```

### Videos not playing?
- Check that video files exist in `public/videos/`
- Verify file permissions: `chmod 644 public/videos/*.mp4`

### Database errors?
- Verify `.env` database credentials
- Run: `php artisan migrate:fresh --seed --force`

---

## 📱 Features

- 🎬 Video template browsing
- 📝 Custom video order requests
- 🖼️ Image upload (up to 5 images)
- 💳 Order management
- 📊 Admin dashboard with statistics
- 🔐 Password-protected admin area

---

## 🎯 Default Order Status

New orders are created with status: **`pending`**

Available statuses:
- `pending` - New order
- `processing` - Being worked on
- `completed` - Finished
- `delivered` - Sent to customer
