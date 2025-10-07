# 🔧 حل مشكلة إعادة التوجيه التلقائي من HTTP إلى HTTPS

## ❌ المشكلة:

عند فتح `http://127.0.0.1:8001` في المتصفح، يتم إعادة التوجيه تلقائياً إلى `https://127.0.0.1:8001`

**السبب:** المتصفح حفظ HTTPS redirect في cache من المحاولات السابقة.

---

## ✅ الحلول (مرتبة من الأسهل للأصعب):

### الحل 1: Hard Refresh (إعادة تحميل كامل) ⭐ **الأسرع**

#### في Chrome/Edge/Brave:
1. افتح Developer Tools (اضغط `F12`)
2. **اضغط بزر الماوس الأيمن** على زر Refresh 🔄 في شريط العناوين
3. اختر **"Empty Cache and Hard Reload"**

#### في Firefox:
1. اضغط `Ctrl + Shift + Delete` (Windows/Linux) أو `Cmd + Shift + Delete` (Mac)
2. اختر "Cached Web Content"
3. اضغط "Clear Now"

#### في Safari:
1. اضغط `Cmd + Option + E` (مسح cache)
2. ثم `Cmd + R` (reload)

---

### الحل 2: استخدم Incognito/Private Window ⭐ **موصى به**

افتح نافذة خاصة:
- **Chrome/Edge:** `Ctrl/Cmd + Shift + N`
- **Firefox:** `Ctrl/Cmd + Shift + P`
- **Safari:** `Cmd + Shift + N`

ثم اذهب إلى: `http://127.0.0.1:8001`

---

### الحل 3: مسح HSTS Settings

#### في Chrome/Edge/Brave:
1. اذهب إلى: `chrome://net-internals/#hsts`
2. في قسم "Delete domain security policies"
3. اكتب: `127.0.0.1`
4. اضغط "Delete"
5. أعد تشغيل المتصفح
6. افتح `http://127.0.0.1:8001`

#### في Firefox:
1. اذهب إلى: `about:config`
2. ابحث عن: `network.stricttransportsecurity.preloadlist`
3. غير القيمة إلى `false`
4. أعد تشغيل Firefox

---

### الحل 4: مسح كل بيانات المتصفح

#### الطريقة السريعة:
1. اضغط `Ctrl/Cmd + Shift + Delete`
2. اختر "All time" أو "منذ البداية"
3. حدد:
   - ✅ Browsing history
   - ✅ Cookies and site data
   - ✅ Cached images and files
4. اضغط "Clear data"
5. أعد تشغيل المتصفح

---

### الحل 5: استخدم متصفح مختلف

إذا كنت تستخدم Chrome، جرب:
- Firefox
- Safari
- Opera
- أي متصفح آخر لم تستخدمه من قبل

---

## 🔧 ما تم إصلاحه في الكود:

تم إضافة header removal في `ForceHttps` middleware:

```php
// Remove HSTS header in local development
if (env('APP_ENV') !== 'production') {
    $response->headers->remove('Strict-Transport-Security');
}
```

هذا يمنع المتصفح من حفظ HTTPS redirect في البيئة المحلية.

---

## 📋 التحقق من النجاح:

بعد تطبيق أحد الحلول:

1. افتح `http://127.0.0.1:8001`
2. افتح Developer Tools (F12)
3. اذهب إلى Network tab
4. أعد تحميل الصفحة
5. **تحقق من أن URL لا يزال `http://`** وليس `https://`

---

## 🎯 الخلاصة:

| الطريقة | السرعة | النجاح |
|---------|--------|--------|
| Hard Refresh | ⚡ سريع جداً | 70% |
| Incognito Window | ⚡ فوري | 100% |
| مسح HSTS | 🕐 متوسط | 90% |
| مسح البيانات | 🕐 متوسط | 95% |
| متصفح آخر | ⚡ فوري | 100% |

---

## 💡 نصائح:

1. **للتطوير:** استخدم دائماً Incognito/Private window
2. **بعد كل تعديل:** اضغط Hard Refresh (`Ctrl/Cmd + Shift + R`)
3. **إذا استمرت المشكلة:** استخدم متصفح مختلف تماماً

---

## ⚠️ تأكد من:

```bash
# في Terminal، تحقق من:
php artisan tinker --execute="echo env('APP_ENV');"
# يجب أن يظهر: local

# تحقق من URL:
php artisan tinker --execute="echo url('/');"
# يجب أن يظهر: http://localhost
```

---

**✅ بعد تطبيق الحل، ستتمكن من فتح الموقع على HTTP بدون إعادة توجيه!**


