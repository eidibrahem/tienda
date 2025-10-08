# 📧 Email Setup Guide

## Overview
The system now automatically sends an email to customers when their order status is changed to "delivered".

## Email Configuration

### For Development (Local Testing)
The easiest way for development is to use **Mailtrap** or **log** driver.

#### Option 1: Using Log (Simplest for testing)
Add these to your `.env` file:
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@yourstore.com
MAIL_FROM_NAME="Your Store Name"
```
Emails will be saved to `storage/logs/laravel.log` file.

#### Option 2: Using Mailtrap (Recommended for testing)
1. Sign up for free at [Mailtrap.io](https://mailtrap.io)
2. Get your credentials from the inbox settings
3. Add to your `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourstore.com
MAIL_FROM_NAME="Your Store Name"
```

### For Production (Real Emails)

#### Option 1: Gmail SMTP
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password: [Google App Passwords](https://myaccount.google.com/apppasswords)
3. Add to your `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Your Store Name"
```

#### Option 2: SendGrid (Recommended for production)
1. Sign up at [SendGrid.com](https://sendgrid.com)
2. Create an API key
3. Add to your `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourstore.com
MAIL_FROM_NAME="Your Store Name"
```

#### Option 3: Mailgun
1. Sign up at [Mailgun.com](https://mailgun.com)
2. Add and verify your domain
3. Add to your `.env` file:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-api-key
MAIL_FROM_ADDRESS=noreply@yourstore.com
MAIL_FROM_NAME="Your Store Name"
```

#### Option 4: Amazon SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-aws-access-key
AWS_SECRET_ACCESS_KEY=your-aws-secret-key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@yourstore.com
MAIL_FROM_NAME="Your Store Name"
```

## Testing Email Delivery

After configuring your `.env` file:

1. **Clear config cache:**
```bash
php artisan config:clear
php artisan cache:clear
```

2. **Test by changing an order status to "delivered"** in the dashboard

3. **Check logs:**
   - If using `log` driver: Check `storage/logs/laravel.log`
   - If using Mailtrap: Check your Mailtrap inbox
   - If using real email: Check the customer's inbox

## Email Template

The email sent to customers includes:
- ✅ Delivery confirmation message
- 📋 Complete order details
- 💰 Order price
- 📅 Order date
- Professional design matching your brand colors

## Troubleshooting

### Email not sending?
1. Check your `.env` configuration
2. Clear cache: `php artisan config:clear`
3. Check logs: `storage/logs/laravel.log`
4. Verify email credentials are correct
5. Check if port is blocked by firewall

### Common Issues:
- **"Connection could not be established"**: Wrong host or port
- **"Authentication failed"**: Wrong username/password
- **"535 Authentication failed"**: For Gmail, make sure you're using App Password, not regular password
- **Emails going to spam**: Add SPF and DKIM records to your domain

## Railway Deployment

For Railway, add these environment variables in the Railway dashboard:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Your Store Name"
```

## Security Notes

⚠️ **IMPORTANT:**
- Never commit `.env` file to Git
- Use environment variables for sensitive data
- Use App Passwords for Gmail (not your account password)
- Consider using a dedicated email service for production (SendGrid, Mailgun, etc.)

## Support

If you need help with email configuration, check:
- Laravel Mail Documentation: https://laravel.com/docs/mail
- Your email provider's SMTP settings
- Laravel logs: `storage/logs/laravel.log`

