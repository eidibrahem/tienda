# 🔧 إصلاح خطأ "Unsupported SSL request" في البيئة المحلية

## ❌ المشكلة:

عند تشغيل الموقع محلياً، يظهر الخطأ:

```
Invalid request (Unsupported SSL request)
```

**السبب:** الكود الجديد كان يجبر استخدام HTTPS حتى في البيئة المحلية.

---

## ✅ الحل:

تم تعديل الكود ليطبق HTTPS **فقط في الإنتاج**، وليس في البيئة المحلية.

### التعديلات المُطبقة:

#### 1️⃣ **تعديل `AppServiceProvider.php`**

```php
// قبل:
if (env('FORCE_HTTPS', false) || env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}

// بعد:
if (env('APP_ENV') === 'production' && env('FORCE_HTTPS', true)) {
    URL::forceScheme('https');
}
```

#### 2️⃣ **تعديل `ForceHttps` Middleware**

```php
// قبل:
if (env('FORCE_HTTPS', false) || env('APP_ENV') === 'production') {
    // redirect to HTTPS
}

// بعد:
if (env('APP_ENV') === 'production' && env('FORCE_HTTPS', true)) {
    // redirect to HTTPS
}
```

---

## 🚀 خطوات الإصلاح:

### الخطوة 1: تحديث `.env` المحلي

في ملف `.env` الخاص بالبيئة المحلية، تأكد من:

```env
# للبيئة المحلية (Local Development)
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# أو إذا كنت تستخدم XAMPP:
APP_URL=http://localhost/tienda/public
```

⚠️ **مهم:** لا تستخدم `APP_ENV=production` في البيئة المحلية!

---

### الخطوة 2: مسح الـ Cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

### الخطوة 3: إعادة تشغيل السيرفر

#### إذا كنت تستخدم `php artisan serve`:
```bash
# أوقف السيرفر (Ctrl+C)
# ثم شغله مرة أخرى
php artisan serve
```

#### إذا كنت تستخدم XAMPP:
- أعد تشغيل Apache من لوحة تحكم XAMPP

---

## 📋 ملخص إعدادات `.env`:

### للبيئة المحلية (Local):
```env
APP_NAME=Tienda
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# أو mysql للـ local
```

### للإنتاج (Production - Railway):
```env
APP_NAME=Tienda
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tienda-production-76fc.up.railway.app
FORCE_HTTPS=true

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
# ... بقية إعدادات MySQL
```

---

## ✅ التحقق من الحل:

1. افتح المتصفح على `http://localhost:8000` (أو المسار المحلي الخاص بك)
2. يجب أن يعمل الموقع بدون أخطاء
3. يجب أن تتمكن من تصفح الصفحة الرئيسية وطلب فيديو

---

## 🎯 الآن:

- ✅ **البيئة المحلية:** تعمل على HTTP بدون مشاكل
- ✅ **الإنتاج (Railway):** يعمل على HTTPS بشكل إجباري
- ✅ **كلاهما:** يعملان بشكل صحيح بدون تعارض

---

## 🔍 إذا استمرت المشكلة:

### تحقق من قيمة `APP_ENV`:

```bash
php artisan tinker
>>> env('APP_ENV')
=> "local"  # ✅ يجب أن تكون "local" في البيئة المحلية
```

### تحقق من الـ URL المُولد:

```bash
php artisan tinker
>>> url('/')
=> "http://localhost:8000"  # ✅ يجب أن يكون HTTP في البيئة المحلية
```

---

**✅ الآن يمكنك تطوير المشروع محلياً بدون مشاكل، وسيعمل على HTTPS تلقائياً عند النشر على Railway!**


