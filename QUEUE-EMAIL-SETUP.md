# 📧 Queue Email Setup - حل مشكلة Timeout

## 🔴 المشكلة
عند تغيير حالة الطلب إلى "delivered" على Railway، يحدث timeout error:
```
Maximum execution time of 30 seconds exceeded
```

## ✅ الحل: استخدام Queue System

تم تعديل النظام ليرسل الإيميلات في الخلفية (background) بدلاً من الإرسال المباشر.

## 📋 ما تم تعديله:

### 1. **OrderController** ✅
- تم تغيير `Mail::send()` إلى `Mail::queue()`
- الإيميلات الآن تُضاف إلى queue وتُرسل في الخلفية

### 2. **Procfile** ✅
- تمت إضافة `worker` process لمعالجة الـ queue

### 3. **Queue Configuration** ✅
- `QUEUE_CONNECTION=database` في `.env`
- الـ jobs تُحفظ في قاعدة البيانات

## 🚀 خطوات التفعيل على Railway:

### الطريقة الأولى: استخدام Railway Worker (موصى بها)

⚠️ **مهم:** Railway يحتاج خطة Pro لتشغيل multiple services (web + worker)

**الخطوات:**

1. **تأكد من وجود الـ Procfile:**
```
web: ./railway-start.sh
worker: php artisan queue:work --verbose --tries=3 --timeout=90
```

2. **في Railway Dashboard:**
   - افتح مشروعك
   - اذهب إلى Settings
   - في قسم Deploys، تأكد من تفعيل Worker process

3. **متغيرات البيئة في Railway:**
```
QUEUE_CONNECTION=database
```

### الطريقة الثانية: استخدام Cron Job (مجانية)

إذا كنت على Free Plan، يمكنك استخدام cron job خارجي:

**1. سجل في أحد المواقع المجانية:**
- [cron-job.org](https://cron-job.org)
- [EasyCron](https://www.easycron.com)
- [UptimeRobot](https://uptimerobot.com)

**2. اضبط Cron Job:**
- URL: `https://your-railway-app.up.railway.app/queue-process`
- Interval: كل دقيقة أو كل 5 دقائق

**3. أضف Route في `routes/web.php`:**
```php
Route::get('/queue-process', function() {
    Artisan::call('queue:work', [
        '--stop-when-empty' => true,
        '--tries' => 3,
        '--timeout' => 90
    ]);
    return 'Queue processed';
});
```

### الطريقة الثالثة: تبسيط الإعدادات (الأسرع)

إذا كنت تريد حل سريع بدون queue worker:

**1. استخدم `sync` driver في `.env`:**
```env
QUEUE_CONNECTION=sync
```

**2. زيادة timeout في `php.ini` أو Railway:**
```env
MAX_EXECUTION_TIME=60
```

لكن **هذا ليس الحل الأمثل** لأنه قد يؤدي لتأخير في الـ response.

## 🧪 اختبار محلي:

### 1. تشغيل Queue Worker محلياً:
```bash
php artisan queue:work
```

### 2. غير حالة طلب إلى "delivered"

### 3. راقب الـ Queue Worker - سترى:
```
[2025-01-01 10:00:00][1] Processing: App\Mail\OrderDeliveredMail
[2025-01-01 10:00:05][1] Processed:  App\Mail\OrderDeliveredMail
```

### 4. تحقق من الـ Logs:
```bash
tail -f storage/logs/laravel.log
```

## 📊 مراقبة Queue Jobs

### عرض Jobs في الانتظار:
```bash
php artisan queue:listen --verbose
```

### عرض Failed Jobs:
```bash
php artisan queue:failed
```

### إعادة محاولة Failed Jobs:
```bash
php artisan queue:retry all
```

### مسح Failed Jobs:
```bash
php artisan queue:flush
```

## 🔧 إعدادات SMTP الموصى بها لـ Railway

لتجنب مشاكل timeout، استخدم:

### Gmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
```

### SendGrid (الأفضل للإنتاج):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### Mailgun:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-api-key
```

## 🚨 استكشاف الأخطاء

### Problem: Queue Worker لا يعمل على Railway
**الحل:**
- تأكد من أن Railway Plan يدعم Multiple Services
- أو استخدم Cron Job كما في الطريقة الثانية

### Problem: الإيميلات لا تُرسل
**الحل:**
1. تحقق من Queue Worker يعمل:
```bash
php artisan queue:listen
```

2. تحقق من Jobs في Database:
```sql
SELECT * FROM jobs;
```

3. تحقق من Failed Jobs:
```bash
php artisan queue:failed
```

### Problem: Timeout مستمر
**الحل:**
- استخدم `QUEUE_CONNECTION=database` و شغل Queue Worker
- أو استخدم خدمة email أسرع مثل SendGrid

## 📝 ملاحظات مهمة

✅ **الآن:**
- الإيميلات تُرسل في الخلفية
- لا يوجد timeout
- Dashboard يستجيب فوراً
- الإيميل يُرسل خلال ثوان

⚠️ **تذكر:**
- Queue Worker يجب أن يعمل دائماً على السيرفر
- راقب Failed Jobs بشكل دوري
- استخدم Logs لمتابعة الإرسال

## 📚 المزيد من المعلومات

- [Laravel Queue Documentation](https://laravel.com/docs/queues)
- [Railway Procfile](https://docs.railway.app/deploy/deployments)
- [Laravel Mail Documentation](https://laravel.com/docs/mail)

