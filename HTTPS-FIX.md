# 🔒 إصلاح مشكلة HTTPS على Railway

## ❌ المشكلة:

عند إرسال النموذج على الموقع المباشر، تظهر الرسالة:

```
This page was loaded over a secure connection, but contains a form that targets 
an insecure endpoint 'http://tienda-production-1.up.railway.app/request/2'. 
This endpoint should be made available over a secure connection.
```

**السبب:** Laravel يولد URLs بـ `http://` بدلاً من `https://` في الإنتاج.

---

## ✅ الحل:

تم إضافة 3 إصلاحات لضمان استخدام HTTPS دائماً:

### 1️⃣ **إجبار HTTPS في `AppServiceProvider`**

تم تعديل `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    // Force HTTPS in production
    if (env('FORCE_HTTPS', false) || env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
}
```

**الوظيفة:** يجبر جميع URLs المُولدة (من `route()`, `url()`, `asset()`, إلخ) على استخدام `https://`.

---

### 2️⃣ **إنشاء Middleware لإعادة التوجيه التلقائي**

تم إنشاء `app/Http/Middleware/ForceHttps.php`:

```php
public function handle(Request $request, Closure $next): Response
{
    // Force HTTPS in production
    if (env('FORCE_HTTPS', false) || env('APP_ENV') === 'production') {
        if (!$request->secure() && !$request->is('health') && !$request->is('up')) {
            return redirect()->secure($request->getRequestUri());
        }
    }

    return $next($request);
}
```

**الوظيفة:** يُعيد توجيه أي طلب HTTP إلى HTTPS تلقائياً.

---

### 3️⃣ **تسجيل Middleware في التطبيق**

تم تعديل `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    // Force HTTPS in production
    $middleware->web(append: [
        \App\Http\Middleware\ForceHttps::class,
    ]);
})
```

**الوظيفة:** يُفعّل Middleware على جميع طلبات الويب.

---

## 🚀 النشر:

بعد دفع التغييرات إلى Railway:

1. **سيتم إعادة البناء تلقائياً**
2. **تحقق من أن `.env` يحتوي على:**
   ```env
   FORCE_HTTPS=true
   APP_ENV=production
   ```

3. **اختبر الموقع:**
   - افتح `https://tienda-production-76fc.up.railway.app`
   - جرب طلب فيديو مخصص
   - تحقق من أن النموذج يُرسل إلى HTTPS

---

## 🔍 التحقق من الحل:

### في المتصفح:

1. افتح Developer Tools (F12)
2. اذهب إلى Console tab
3. لا يجب أن تظهر أي تحذيرات HTTPS

### في Railway Console:

```bash
# تحقق من أن URLs تستخدم HTTPS
php artisan tinker
>>> route('orders.create', 1)
=> "https://tienda-production-76fc.up.railway.app/request/1"
```

---

## 📋 ملخص التغييرات:

| الملف | التغيير | الوظيفة |
|------|---------|---------|
| `app/Providers/AppServiceProvider.php` | إضافة `URL::forceScheme('https')` | إجبار جميع URLs على HTTPS |
| `app/Http/Middleware/ForceHttps.php` | إنشاء middleware جديد | إعادة توجيه HTTP → HTTPS |
| `bootstrap/app.php` | تسجيل middleware | تفعيل الحماية |

---

## ⚙️ إعدادات `.env` المطلوبة:

تأكد من وجود هذه الإعدادات في Railway Variables:

```env
FORCE_HTTPS=true
APP_ENV=production
APP_URL=https://tienda-production-76fc.up.railway.app
```

⚠️ **مهم:** تأكد من أن `APP_URL` يبدأ بـ `https://`

---

## 🛠️ إذا استمرت المشكلة:

### الخطوة 1: مسح Cache

في Railway Console:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan config:cache
```

---

### الخطوة 2: التحقق من Trusted Proxies

إذا لم يعمل الحل، قد تحتاج لإضافة Railway proxies.

أنشئ `app/Http/Middleware/TrustProxies.php`:

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies = '*';
    
    protected $headers = 
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
```

ثم أضفه في `bootstrap/app.php`:

```php
$middleware->web(append: [
    \App\Http\Middleware\TrustProxies::class,
    \App\Http\Middleware\ForceHttps::class,
]);
```

---

## ✅ النتيجة المتوقعة:

بعد التطبيق:
- ✅ جميع URLs تستخدم `https://`
- ✅ النماذج تُرسل إلى endpoints آمنة
- ✅ لا توجد تحذيرات في Console
- ✅ الموقع يعمل بشكل كامل وآمن
- ✅ يتم حفظ الطلبات في قاعدة البيانات

---

**🔒 الآن موقعك آمن بالكامل على Railway!**


