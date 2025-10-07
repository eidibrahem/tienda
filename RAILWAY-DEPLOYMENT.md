# 🚂 نشر Tienda على Railway

## ✅ مراجعة إعدادات `.env` الخاصة بك

### 📋 الإعدادات الحالية:
```env
APP_NAME="Tienda"
APP_ENV="production"
APP_DEBUG="false"
APP_URL="https://tienda-production-76fc.up.railway.app/"
APP_KEY="base64:1PmGtHWoQ+cbMdnmVNWiT1SjeUsleapEFISr4/Ug5uA="
DB_CONNECTION="mysql"
DB_HOST="${{MySQL.MYSQLHOST}}"
DB_PORT="${{MySQL.MYSQLPORT}}"
DB_DATABASE="${{MySQL.MYSQLDATABASE}}"
DB_USERNAME="${{MySQL.MYSQLUSER}}"
DB_PASSWORD="${{MySQL.MYSQLPASSWORD}}"
LOG_CHANNEL="errorlog"
LOG_LEVEL="info"
SESSION_DRIVER="file"
SESSION_SECURE_COOKIE="true"
CACHE_STORE="file"
QUEUE_CONNECTION="sync"
FILESYSTEM_DISK="public"
FORCE_HTTPS="true"
VITE_APP_NAME="${APP_NAME}"
```

---

## 🔧 التعديلات المقترحة:

### ✅ **1. تغيير LOG_LEVEL**
```env
# من:
LOG_LEVEL="info"

# إلى:
LOG_LEVEL="error"
```
**السبب:** في الإنتاج، يفضل تسجيل الأخطاء فقط لتوفير المساحة وتحسين الأداء.

---

### ✅ **2. تغيير SESSION_DRIVER**
```env
# من:
SESSION_DRIVER="file"

# إلى:
SESSION_DRIVER="database"
```
**السبب:** على Railway، استخدام `file` قد يسبب مشاكل عند إعادة تشغيل الخادم. `database` أكثر استقراراً.

**⚠️ مهم:** بعد التغيير، تأكد من تشغيل:
```bash
php artisan session:table
php artisan migrate
```

---

### ✅ **3. تغيير CACHE_STORE**
```env
# من:
CACHE_STORE="file"

# إلى:
CACHE_STORE="database"
```
**السبب:** نفس السبب - `database` أكثر استقراراً على Railway.

**⚠️ مهم:** بعد التغيير، تأكد من تشغيل:
```bash
php artisan cache:table
php artisan migrate
```

---

### ✅ **4. إضافة إعدادات إضافية (اختيارية)**

أضف هذه الإعدادات في نهاية `.env`:

```env
# Asset URL
ASSET_URL="${APP_URL}"

# Session Settings
SESSION_LIFETIME="120"
SESSION_PATH="/"
SESSION_DOMAIN="null"
SESSION_SAME_SITE="lax"

# Cache Prefix
CACHE_PREFIX="tienda"

# Log Deprecations
LOG_DEPRECATIONS_CHANNEL="null"

# Broadcasting
BROADCAST_CONNECTION="log"
```

---

## 📝 إعدادات `.env` النهائية المقترحة:

```env
APP_NAME="Tienda"
APP_ENV="production"
APP_DEBUG="false"
APP_URL="https://tienda-production-76fc.up.railway.app"
APP_KEY="base64:1PmGtHWoQ+cbMdnmVNWiT1SjeUsleapEFISr4/Ug5uA="

DB_CONNECTION="mysql"
DB_HOST="${{MySQL.MYSQLHOST}}"
DB_PORT="${{MySQL.MYSQLPORT}}"
DB_DATABASE="${{MySQL.MYSQLDATABASE}}"
DB_USERNAME="${{MySQL.MYSQLUSER}}"
DB_PASSWORD="${{MySQL.MYSQLPASSWORD}}"

LOG_CHANNEL="errorlog"
LOG_LEVEL="error"
LOG_DEPRECATIONS_CHANNEL="null"

SESSION_DRIVER="database"
SESSION_LIFETIME="120"
SESSION_ENCRYPT="false"
SESSION_PATH="/"
SESSION_DOMAIN="null"
SESSION_SECURE_COOKIE="true"
SESSION_SAME_SITE="lax"

CACHE_STORE="database"
CACHE_PREFIX="tienda"

QUEUE_CONNECTION="sync"
FILESYSTEM_DISK="public"
FORCE_HTTPS="true"

BROADCAST_CONNECTION="log"

VITE_APP_NAME="${APP_NAME}"
ASSET_URL="${APP_URL}"
```

---

## 🚀 خطوات النشر على Railway:

### 1️⃣ **قبل النشر - إنشاء جداول Cache و Session**

في Railway Console، شغّل:

```bash
# إنشاء جدول session
php artisan session:table

# إنشاء جدول cache
php artisan cache:table

# تشغيل migrations (سيشمل session و cache)
php artisan migrate --force

# إدخال بيانات الفيديوهات الثلاثة
php artisan db:seed --force
```

---

### 2️⃣ **ضبط المتغيرات في Railway Dashboard**

في Railway Dashboard، اذهب إلى **Variables** وأضف:

```
APP_NAME=Tienda
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tienda-production-76fc.up.railway.app
APP_KEY=base64:1PmGtHWoQ+cbMdnmVNWiT1SjeUsleapEFISr4/Ug5uA=

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

LOG_CHANNEL=errorlog
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

CACHE_STORE=database
CACHE_PREFIX=tienda

QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
FORCE_HTTPS=true

BROADCAST_CONNECTION=log
```

⚠️ **ملاحظة:** في Railway Variables، استخدم `${{` بدلاً من `${{` للمتغيرات الديناميكية.

---

### 3️⃣ **إنشاء Procfile لـ Railway**

أنشئ ملف `Procfile` في جذر المشروع:

```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

أو استخدم:

```
web: php -S 0.0.0.0:$PORT -t public/
```

---

### 4️⃣ **إنشاء Build Script**

أنشئ ملف `railway.json` في جذر المشروع:

```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "composer install --optimize-autoloader --no-dev && npm install && npm run build"
  },
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

---

### 5️⃣ **رفع الملفات المهمة**

تأكد من رفع:

```
public/videos/
├── sample1.mp4
├── sample2.mp4
└── sample3.mp4

public/assets/
└── logo.webp
```

⚠️ **مهم:** Railway قد لا يحفظ الملفات المرفوعة من المستخدمين بعد إعادة التشغيل. فكر في استخدام:
- **Railway Volume** (للتخزين الدائم)
- **AWS S3** أو **Cloudinary** (للصور والفيديوهات)

---

### 6️⃣ **أوامر بعد النشر**

في Railway Console:

```bash
# 1. تشغيل migrations
php artisan migrate --force

# 2. إدخال بيانات الفيديوهات
php artisan db:seed --force

# 3. تحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. إنشاء symbolic link للتخزين
php artisan storage:link
```

---

## ⚠️ مشاكل محتملة على Railway:

### 1. **ملفات الفيديو لا تظهر**
**الحل:** استخدم Railway Volume أو خدمة تخزين خارجية.

### 2. **الصور المرفوعة تختفي بعد إعادة التشغيل**
**الحل:** استخدم Railway Volume:
```bash
# في Railway Dashboard → Volumes
Mount Path: /app/storage/app/public
```

### 3. **خطأ "Session file not found"**
**الحل:** استخدم `SESSION_DRIVER=database` كما هو مذكور أعلاه.

### 4. **خطأ "SQLSTATE[HY000] [2002] Connection refused"**
**الحل:** تأكد من أن MySQL service موجود ومتصل بالتطبيق في Railway.

---

## 🔍 التحقق من التثبيت:

بعد النشر، تحقق من:

1. ✅ افتح `https://tienda-production-76fc.up.railway.app`
2. ✅ يجب أن تشاهد 3 فيديوهات في الصفحة الرئيسية
3. ✅ جرب طلب فيديو مخصص
4. ✅ افتح `/dashboard` وأدخل كلمة المرور `admin123`
5. ✅ تحقق من أن الطلبات تظهر في Dashboard

---

## 📊 مراقبة Logs:

في Railway Console:

```bash
# عرض logs
railway logs

# أو في Railway Dashboard → Logs
```

---

## 🔄 إعادة إدخال بيانات الفيديوهات:

إذا لم تظهر الفيديوهات:

```bash
php artisan db:seed --class=TemplateSeeder --force
```

---

## 📝 ملخص التعديلات المطلوبة:

| الإعداد | القيمة الحالية | القيمة المقترحة | السبب |
|--------|----------------|-----------------|-------|
| `LOG_LEVEL` | `info` | `error` | توفير المساحة |
| `SESSION_DRIVER` | `file` | `database` | استقرار أفضل |
| `CACHE_STORE` | `file` | `database` | استقرار أفضل |

**✅ باقي الإعدادات ممتازة ولا تحتاج تعديل!**

---

## 🎯 الخلاصة:

إعداداتك الحالية **جيدة جداً** ✅، لكن:

1. **غيّر** `LOG_LEVEL` من `info` إلى `error`
2. **غيّر** `SESSION_DRIVER` من `file` إلى `database`
3. **غيّر** `CACHE_STORE` من `file` إلى `database`
4. **شغّل** migrations لإنشاء جداول session و cache
5. **شغّل** seeder لإدخال الفيديوهات الثلاثة

---

**🚀 بعدها مشروعك جاهز 100% للعمل على Railway!**

