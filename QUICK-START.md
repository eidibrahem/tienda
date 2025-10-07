# 🚀 دليل البدء السريع - Tienda

## ⚡ النشر على السيرفر في 5 دقائق

### الطريقة السريعة (استخدم السكريبت الجاهز):

```bash
# 1. ارفع جميع ملفات المشروع إلى السيرفر
# 2. اذهب إلى مجلد المشروع
cd /path/to/tienda

# 3. تحقق من أن كل شيء جاهز
./check-deployment.sh

# 4. قم بضبط إعدادات قاعدة البيانات في .env
nano .env
# أو استخدم محرر النصوص المفضل لديك

# 5. شغل سكريبت النشر
./deploy.sh

# ✅ انتهى! افتح موقعك في المتصفح
```

---

## 📝 ضبط ملف .env (مهم!)

قبل تشغيل `./deploy.sh`، تأكد من ضبط هذه الإعدادات في `.env`:

```env
APP_NAME=Tienda
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tienda_db          # ⬅️ غير هذا
DB_USERNAME=your_username      # ⬅️ غير هذا
DB_PASSWORD=your_password      # ⬅️ غير هذا
```

---

## ✅ ماذا سيفعل سكريبت النشر؟

عند تشغيل `./deploy.sh`، سيقوم بـ:

1. ✅ تثبيت مكتبات Composer
2. ✅ تثبيت مكتبات NPM وبناء Assets
3. ✅ إنشاء ملف `.env` إذا لم يكن موجوداً
4. ✅ توليد مفتاح التطبيق (`APP_KEY`)
5. ✅ تشغيل Migrations (إنشاء جميع الجداول)
6. ✅ **إدخال 3 فيديوهات تلقائياً في جدول templates**
7. ✅ ضبط صلاحيات المجلدات
8. ✅ إنشاء symbolic link للتخزين
9. ✅ تحسين الأداء (caching)

---

## 🎬 الفيديوهات الثلاثة التي سيتم إدخالها تلقائياً:

| الاسم | السعر | مسار الفيديو |
|------|------|-------------|
| Sample 1 | 5 AED | `/videos/sample1.mp4` |
| Sample 2 | 10 AED | `/videos/sample2.mp4` |
| Sample 3 | 15 AED | `/videos/sample3.mp4` |

⚠️ **تأكد من أن ملفات الفيديو موجودة في `public/videos/`**

---

## 🔐 الوصول إلى لوحة التحكم

بعد النشر:

- **الرابط:** `https://yourdomain.com/dashboard`
- **كلمة المرور:** `admin123`

---

## 🛠️ الأوامر المفيدة

### إعادة إدخال بيانات الفيديوهات فقط:
```bash
php artisan db:seed --class=TemplateSeeder --force
```

### حذف وإعادة إنشاء قاعدة البيانات:
```bash
php artisan migrate:fresh --seed --force
```
⚠️ **تحذير:** سيحذف جميع البيانات!

### مسح الذاكرة المؤقتة:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### إعادة تحسين الأداء:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📂 هيكل المشروع المهم

```
tienda/
├── app/
│   ├── Http/Controllers/
│   │   └── OrderController.php      # معالجة الطلبات والإحصائيات
│   └── Models/
│       ├── Template.php             # نموذج القوالب
│       └── Order.php                # نموذج الطلبات
├── database/
│   ├── migrations/                  # ملفات إنشاء الجداول
│   └── seeders/
│       ├── DatabaseSeeder.php       # المسؤول عن تشغيل جميع الـ seeders
│       └── TemplateSeeder.php       # يحتوي على بيانات الفيديوهات الثلاثة
├── public/
│   ├── videos/                      # ⚡ مجلد الفيديوهات (sample1-3.mp4)
│   └── assets/
│       └── logo.webp                # شعار التطبيق
├── resources/views/
│   ├── home.blade.php               # الصفحة الرئيسية (عرض الفيديوهات)
│   ├── request.blade.php            # صفحة طلب فيديو مخصص
│   └── dashboard.blade.php          # لوحة تحكم الإدارة
├── routes/
│   └── web.php                      # تعريف المسارات
├── .env                             # ⚡ إعدادات التطبيق وقاعدة البيانات
├── deploy.sh                        # 🚀 سكريبت النشر التلقائي
├── check-deployment.sh              # 🔍 فحص الاستعداد للنشر
└── DEPLOYMENT.md                    # تعليمات النشر التفصيلية
```

---

## 🔧 حل المشاكل السريع

### الفيديوهات لا تظهر؟
```bash
php artisan db:seed --force
php artisan cache:clear
```

### خطأ "500 Internal Server Error"؟
```bash
# تحقق من الصلاحيات
chmod -R 775 storage bootstrap/cache

# امسح الذاكرة المؤقتة
php artisan cache:clear
php artisan config:clear
```

### الصور المرفوعة لا تظهر؟
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

### خطأ في قاعدة البيانات؟
```bash
# تحقق من .env
cat .env | grep DB_

# جرب إعادة تشغيل migrations
php artisan migrate:fresh --seed --force
```

---

## 📞 الدعم

للمزيد من المعلومات التفصيلية، راجع:
- `DEPLOYMENT.md` - دليل النشر الشامل
- `README.md` - معلومات عامة عن المشروع
- `storage/logs/laravel.log` - ملف اللوجات للأخطاء

---

## ✅ قائمة التحقق قبل النشر

- [ ] رفع جميع ملفات المشروع
- [ ] ضبط إعدادات `.env` (خاصة قاعدة البيانات)
- [ ] التأكد من وجود ملفات الفيديو في `public/videos/`
- [ ] تشغيل `./check-deployment.sh` للتحقق
- [ ] تشغيل `./deploy.sh` للنشر
- [ ] فتح الموقع والتأكد من ظهور 3 فيديوهات
- [ ] تجربة طلب فيديو مخصص
- [ ] تجربة لوحة التحكم بكلمة المرور

---

**🎉 الآن مشروعك جاهز للعمل مع 3 فيديوهات تلقائياً!**

---

## 🎯 الميزات المتاحة

### للزوار:
- ✅ تصفح 3 قوالب فيديو
- ✅ طلب فيديو مخصص
- ✅ رفع حتى 5 صور
- ✅ إدخال اسم ووصف للفيديو

### للإدارة (Dashboard):
- ✅ عرض إحصائيات (إجمالي الطلبات، قيد الانتظار، المكتملة، الإيرادات)
- ✅ عرض جميع الطلبات
- ✅ تغيير حالة الطلب (pending, processing, completed, delivered)
- ✅ عرض وتحميل صور الطلبات
- ✅ حماية بكلمة مرور (admin123)

---

## 🔄 الحالات المتاحة للطلبات

- `pending` - طلب جديد (الحالة الافتراضية)
- `processing` - قيد التنفيذ
- `completed` - مكتمل
- `delivered` - تم التسليم

---

**مع تمنياتنا بنشر ناجح! 🚀**

