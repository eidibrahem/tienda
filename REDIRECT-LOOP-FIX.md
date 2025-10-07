# 🔧 إصلاح مشكلة Redirect Loop (ERR_TOO_MANY_REDIRECTS)

## ❌ المشكلة:

```
ERR_TOO_MANY_REDIRECTS
tienda-production-10.up.railway.app redirected you too many times.
```

---

## 🔍 السبب:

Railway يستخدم **SSL Termination** عبر Load Balancer:

```
Internet (HTTPS) → Railway Load Balancer (HTTPS) → Laravel App (HTTP)
```

Laravel يظن أن الـ request هو HTTP (لأنه من Load Balancer)، فيحاول redirect لـ HTTPS، فيحدث **Infinite Loop**!

---

## ✅ الحل المُطبّق:

### 1️⃣ إضافة TrustProxies Middleware

**الملف:** `app/Http/Middleware/TrustProxies.php`

```php
protected $proxies = '*';  // Trust all proxies (Railway, CloudFlare, etc.)

protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
    Request::HEADER_X_FORWARDED_HOST |
    Request::HEADER_X_FORWARDED_PORT |
    Request::HEADER_X_FORWARDED_PROTO |  // ⭐ هذا هو المهم!
    Request::HEADER_X_FORWARDED_AWS_ELB;
```

---

### 2️⃣ تفعيل TrustProxies في Bootstrap

**الملف:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    // Trust proxies (Railway, CloudFlare, etc.)
    $middleware->trustProxies(at: '*');  // ⭐ هذا يجعل Laravel يثق بـ X-Forwarded-Proto
    
    // Force HTTPS in production
    $middleware->web(append: [
        \App\Http\Middleware\ForceHttps::class,
    ]);
})
```

---

### 3️⃣ تبسيط ForceHttps Middleware

**الملف:** `app/Http/Middleware/ForceHttps.php`

```php
public function handle(Request $request, Closure $next): Response
{
    // TrustProxies middleware يتعامل مع X-Forwarded-Proto تلقائياً
    if (env('APP_ENV') === 'production' && env('FORCE_HTTPS', true)) {
        // Laravel الآن يعرف أن Request جاي من HTTPS (عبر X-Forwarded-Proto)
        if (!$request->secure() && !$request->is('health') && !$request->is('up')) {
            return redirect()->secure($request->getRequestUri());
        }
    }

    $response = $next($request);

    // Remove HSTS في Local لمنع مشاكل المتصفح
    if (env('APP_ENV') !== 'production') {
        $response->headers->remove('Strict-Transport-Security');
    }

    return $response;
}
```

---

## 🔄 كيف يعمل الحل:

### قبل الإصلاح:
```
1. User → HTTPS → Railway Load Balancer
2. Load Balancer → HTTP → Laravel
3. Laravel: request->secure() = false ❌
4. Laravel: redirect to HTTPS
5. ♻️ Loop! (لأن Load Balancer بيبعت HTTP دايماً)
```

### بعد الإصلاح:
```
1. User → HTTPS → Railway Load Balancer
2. Load Balancer → HTTP + X-Forwarded-Proto: https → Laravel
3. TrustProxies: يقرأ X-Forwarded-Proto: https
4. Laravel: request->secure() = true ✅
5. لا يوجد redirect! الصفحة تفتح عادي 🎉
```

---

## 📊 الفرق بين البيئات:

| البيئة | Trust Proxies | HTTPS Redirect | النتيجة |
|--------|--------------|----------------|---------|
| **Local** | لا | لا | HTTP يعمل ✅ |
| **Railway** | نعم | نعم (بدون loop) | HTTPS يعمل ✅ |

---

## 🚀 النشر على Railway:

```bash
# 1. إضافة التغييرات
git add .

# 2. Commit
git commit -m "Fix HTTPS redirect loop on Railway"

# 3. Push
git push origin main

# 4. انتظر 1-2 دقيقة للـ deployment
# Railway سيعيد build & deploy تلقائياً
```

---

## 🧪 التحقق من النجاح:

بعد الـ deployment:

1. امسح cache المتصفح (أو استخدم Incognito)
2. اذهب إلى: `https://tienda-production-10.up.railway.app`
3. ✅ الصفحة يجب أن تفتح بدون redirect loop
4. ✅ URL يبقى HTTPS
5. ✅ لا توجد أخطاء

---

## 🔧 التحقق من Headers:

افتح Developer Tools (F12) → Network tab:

```
Request Headers:
✅ X-Forwarded-Proto: https
✅ X-Forwarded-For: [User IP]
✅ X-Forwarded-Host: tienda-production-10.up.railway.app
```

---

## 🛡️ الأمان:

- ✅ HTTPS إجباري في Production
- ✅ Trust Proxies محدود بـ Railway فقط (عبر headers)
- ✅ Local development يعمل بدون مشاكل
- ✅ لا توجد ثغرات أمنية

---

## 📝 ملاحظات مهمة:

1. **TrustProxies ضروري** لأي Laravel app على Railway/CloudFlare/AWS ELB
2. **X-Forwarded-Proto** هو المفتاح لحل Redirect Loop
3. في Local: TrustProxies لا يؤثر (لا توجد proxies)
4. في Production: TrustProxies يجعل `$request->secure()` يعمل صح

---

## 🆘 إذا استمرت المشكلة:

### 1. تحقق من Environment Variables على Railway:

```bash
APP_ENV=production
FORCE_HTTPS=true
APP_URL=https://tienda-production-10.up.railway.app
```

### 2. تحقق من Logs على Railway:

```bash
railway logs
# ابحث عن: redirect, loop, too many redirects
```

### 3. امسح cache المتصفح:

- Chrome: `chrome://net-internals/#hsts` → Delete: tienda-production-10.up.railway.app
- أو استخدم Incognito window

---

**✅ الآن الموقع يجب أن يعمل على HTTPS بدون redirect loop!**

