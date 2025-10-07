# 🚀 تعليمات النشر على السيرفر - Tienda

## ✅ الخطوات المطلوبة لرفع المشروع على السيرفر

### 1️⃣ رفع الملفات على السيرفر
ارفع جميع ملفات المشروع إلى السيرفر (مثلاً: `/public_html/tienda/`)

---

### 2️⃣ تثبيت المكتبات المطلوبة
```bash
# في مجلد المشروع، نفذ:
composer install --optimize-autoloader --no-dev

# إذا كنت تستخدم npm:
npm install && npm run build
```

---

### 3️⃣ إعداد ملف البيئة (.env)
```bash
# إذا لم يكن موجود، أنشئ ملف .env
cp .env.example .env

# أو أنشئ ملف .env جديد بالمحتوى التالي:
```

**محتوى ملف `.env`:**
```env
APP_NAME=Tienda
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tienda_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

SESSION_DRIVER=database
SESSION_LIFETIME=120

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

**ثم قم بتوليد مفتاح التطبيق:**
```bash
php artisan key:generate
```

---

### 4️⃣ **تشغيل قاعدة البيانات وإدخال البيانات التلقائية** ⚡

**هذه الخطوة الأهم!** ستنشئ جميع الجداول وتدخل 3 فيديوهات تلقائياً:

```bash
# إنشاء جميع الجداول
php artisan migrate --force

# إدخال بيانات الفيديوهات (3 templates)
php artisan db:seed --force
```

**ماذا سيحدث؟**
- ✅ سيتم إنشاء جدول `templates`
- ✅ سيتم إدخال 3 صفوف تلقائياً:
  - **Sample 1** - السعر 5 درهم - `/videos/sample1.mp4`
  - **Sample 2** - السعر 10 درهم - `/videos/sample2.mp4`
  - **Sample 3** - السعر 15 درهم - `/videos/sample3.mp4`

**إذا كانت البيانات موجودة بالفعل:**
لن يتم تكرارها! الـ Seeder يستخدم `firstOrCreate()` لتجنب التكرار.

---

### 5️⃣ ضبط الصلاحيات
```bash
# امنح الصلاحيات المطلوبة
chmod -R 775 storage bootstrap/cache

# إذا كان السيرفر يستخدم www-data:
chown -R www-data:www-data storage bootstrap/cache
```

---

### 6️⃣ رفع ملفات الفيديو
تأكد من وجود ملفات الفيديو في المسار: `public/videos/`

الملفات المطلوبة:
- `sample1.mp4`
- `sample2.mp4`
- `sample3.mp4`

```bash
# ضبط صلاحيات الفيديوهات
chmod 644 public/videos/*.mp4
```

---

### 7️⃣ إعدادات السيرفر (Apache/Nginx)

#### **لـ Apache:**
تأكد من وجود ملف `.htaccess` في المجلد الرئيسي:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

وفي `public/.htaccess`:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### **لـ Nginx:**
أضف في ملف التكوين:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/tienda/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

### 8️⃣ تحسين الأداء (Production)
```bash
# تخزين ملفات التكوين مؤقتاً
php artisan config:cache

# تخزين المسارات مؤقتاً
php artisan route:cache

# تخزين العروض مؤقتاً
php artisan view:cache
```

---

### 9️⃣ اختبار الموقع
1. افتح المتصفح واذهب إلى: `https://yourdomain.com`
2. يجب أن تشاهد **3 فيديوهات** في الصفحة الرئيسية
3. جرب الضغط على أي فيديو وطلب فيديو مخصص

---

## 🔐 الدخول إلى لوحة التحكم

**رابط لوحة التحكم:** `https://yourdomain.com/dashboard`  
**كلمة المرور:** `admin123`

---

## 🔄 إعادة تشغيل البيانات (في حال حدوث مشكلة)

### لإعادة إدخال الفيديوهات فقط:
```bash
php artisan db:seed --class=TemplateSeeder --force
```

### لحذف وإعادة إنشاء قاعدة البيانات بالكامل:
```bash
php artisan migrate:fresh --seed --force
```

⚠️ **تحذير:** الأمر أعلاه سيحذف جميع البيانات!

---

## 🛠️ حل المشاكل الشائعة

### المشكلة: الفيديوهات لا تظهر في الصفحة الرئيسية
**الحل:**
```bash
# تأكد من وجود البيانات في قاعدة البيانات
php artisan db:seed --force

# امسح الذاكرة المؤقتة
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

---

### المشكلة: الفيديوهات لا تعمل
**الحل:**
1. تأكد من وجود الملفات في `public/videos/`
2. تحقق من الصلاحيات: `chmod 644 public/videos/*.mp4`
3. تأكد من أن المسار صحيح في قاعدة البيانات

---

### المشكلة: خطأ في قاعدة البيانات
**الحل:**
1. تحقق من بيانات الاتصال في `.env`
2. تأكد من وجود قاعدة البيانات على السيرفر
3. جرب: `php artisan migrate:fresh --seed --force`

---

### المشكلة: خطأ "500 Internal Server Error"
**الحل:**
```bash
# تحقق من ملف اللوج
tail -f storage/logs/laravel.log

# تأكد من الصلاحيات
chmod -R 775 storage bootstrap/cache

# امسح الذاكرة المؤقتة
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

### المشكلة: الصور المرفوعة لا تظهر
**الحل:**
```bash
# أنشئ symbolic link
php artisan storage:link

# تأكد من الصلاحيات
chmod -R 775 storage/app/public
```

---

## 📋 الملفات المهمة

| الملف | الوظيفة |
|------|---------|
| `database/seeders/TemplateSeeder.php` | يحتوي على بيانات الفيديوهات الثلاثة |
| `database/seeders/DatabaseSeeder.php` | يستدعي TemplateSeeder تلقائياً |
| `public/videos/` | مجلد ملفات الفيديو |
| `.env` | إعدادات قاعدة البيانات والتطبيق |

---

## 🎯 حالات الطلبات المتاحة

عند إنشاء طلب جديد، الحالة الافتراضية: **`pending`**

الحالات المتاحة:
- `pending` - طلب جديد
- `processing` - قيد التنفيذ
- `completed` - مكتمل
- `delivered` - تم التسليم

---

## 📞 الدعم الفني

إذا واجهت أي مشكلة، تحقق من:
1. ملف اللوج: `storage/logs/laravel.log`
2. صلاحيات المجلدات
3. إعدادات قاعدة البيانات في `.env`

---

## ✅ خلاصة سريعة للنشر

```bash
# 1. رفع الملفات
# 2. تثبيت المكتبات
composer install --optimize-autoloader --no-dev

# 3. إنشاء ملف .env وضبط إعدادات قاعدة البيانات
php artisan key:generate

# 4. إنشاء قاعدة البيانات والجداول وإدخال البيانات التلقائية
php artisan migrate --force
php artisan db:seed --force

# 5. ضبط الصلاحيات
chmod -R 775 storage bootstrap/cache

# 6. رفع ملفات الفيديو إلى public/videos/

# 7. تحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ✅ جاهز! افتح الموقع في المتصفح
```

---

**🎉 الآن مشروعك جاهز على السيرفر مع 3 فيديوهات تلقائياً!**

