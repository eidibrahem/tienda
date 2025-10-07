# 🔧 حل مشاكل السيرفر المحلي

## ❌ المشكلة الشائعة:

```
ERR_CONNECTION_CLOSED
127.0.0.1 unexpectedly closed the connection
```

**السبب:** السيرفر المحلي متوقف أو تعطل.

---

## ✅ الحلول السريعة:

### الحل 1: إعادة تشغيل Laravel Development Server

```bash
# 1. أوقف السيرفر القديم (إذا كان يعمل)
# اضغط Ctrl+C في Terminal

# 2. شغل السيرفر مرة أخرى
php artisan serve

# 3. يجب أن تشاهد:
# Starting Laravel development server: http://127.0.0.1:8000
```

---

### الحل 2: إذا كنت تستخدم XAMPP

#### الطريقة 1: من XAMPP Control Panel
1. افتح XAMPP Control Panel
2. اضغط **Stop** على Apache
3. انتظر ثانيتين
4. اضغط **Start** على Apache

#### الطريقة 2: من Terminal
```bash
# على macOS:
sudo /Applications/XAMPP/xamppfiles/xampp stopapache
sudo /Applications/XAMPP/xamppfiles/xampp startapache

# أو:
sudo apachectl restart
```

---

### الحل 3: التحقق من المنفذ (Port)

إذا كان المنفذ 8000 مشغول:

```bash
# تحقق من المنافذ المشغولة
lsof -ti:8000

# أوقف العملية التي تستخدم المنفذ
kill -9 $(lsof -ti:8000)

# ثم شغل السيرفر مرة أخرى
php artisan serve
```

أو استخدم منفذ آخر:

```bash
php artisan serve --port=8001
# ثم افتح: http://127.0.0.1:8001
```

---

### الحل 4: مسح الـ Cache

أحياناً، الـ cache يسبب مشاكل:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# ثم أعد تشغيل السيرفر
php artisan serve
```

---

## 🔍 التحقق من حالة السيرفر:

### تحقق من أن السيرفر يعمل:

```bash
# الطريقة 1: استخدم curl
curl http://127.0.0.1:8000

# الطريقة 2: تحقق من العمليات
ps aux | grep "php artisan serve"

# الطريقة 3: تحقق من المنفذ
lsof -i:8000
```

---

## 📋 الأخطاء الشائعة وحلولها:

### خطأ: "Address already in use"

**الحل:**
```bash
# أوقف العملية القديمة
kill -9 $(lsof -ti:8000)

# أو استخدم منفذ آخر
php artisan serve --port=8001
```

---

### خطأ: "Could not open input file: artisan"

**الحل:**
```bash
# تأكد أنك في مجلد المشروع
cd /Applications/XAMPP/xamppfiles/htdocs/tienda

# ثم شغل السيرفر
php artisan serve
```

---

### خطأ: "Unsupported SSL request" (تم حله)

**الحل:** تم إصلاح هذا الخطأ في التحديث الأخير.

تأكد من:
```env
# في .env المحلي:
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

---

## 🚀 أوامر مفيدة:

### تشغيل السيرفر مع خيارات:

```bash
# المنفذ الافتراضي (8000)
php artisan serve

# منفذ مخصص
php artisan serve --port=8080

# على جميع الواجهات (للوصول من أجهزة أخرى)
php artisan serve --host=0.0.0.0

# منفذ وواجهة محددة
php artisan serve --host=0.0.0.0 --port=8080
```

---

### تشغيل السيرفر في الخلفية (Background):

```bash
# شغل في الخلفية
nohup php artisan serve > /dev/null 2>&1 &

# أو احفظ الـ logs
nohup php artisan serve > storage/logs/server.log 2>&1 &

# للإيقاف
kill $(lsof -ti:8000)
```

---

## 📊 مراقبة السيرفر:

### عرض logs مباشرة:

```bash
# في terminal منفصل
tail -f storage/logs/laravel.log

# أو
php artisan pail
```

---

## ✅ خطوات التحقق السريع:

1. **هل السيرفر يعمل؟**
   ```bash
   ps aux | grep "php artisan serve"
   ```

2. **هل المنفذ مفتوح؟**
   ```bash
   lsof -i:8000
   ```

3. **هل الموقع يستجيب؟**
   ```bash
   curl http://127.0.0.1:8000
   ```

4. **هل التصميم يظهر؟**
   - افتح المتصفح: `http://127.0.0.1:8000`
   - افتح Developer Tools (F12) → Console
   - تحقق من عدم وجود أخطاء

---

## 🎯 الحل النهائي:

إذا لم يعمل أي شيء:

```bash
# 1. أوقف كل العمليات
killall php

# 2. مسح كل الـ cache
php artisan optimize:clear

# 3. إعادة البناء
composer dump-autoload
npm run build

# 4. تشغيل السيرفر
php artisan serve
```

---

## 🔧 سكريبت إعادة تشغيل سريع:

احفظ هذا في `restart-server.sh`:

```bash
#!/bin/bash
echo "🔄 Restarting server..."
killall php 2>/dev/null
php artisan config:clear
php artisan cache:clear
php artisan serve
```

ثم:
```bash
chmod +x restart-server.sh
./restart-server.sh
```

---

**✅ الآن السيرفر يعمل بشكل صحيح على `http://127.0.0.1:8000`**

افتح المتصفح واذهب إلى: `http://127.0.0.1:8000` 🎉


