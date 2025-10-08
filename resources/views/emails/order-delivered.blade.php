<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Delivered</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #1a4241 0%, #0d9488 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #1a4241;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .email-body p {
            margin-bottom: 15px;
            color: #57534e;
        }
        .order-details {
            background: #fdfcf9;
            border: 2px solid #f8ecd6;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .order-details h3 {
            color: #1a4241;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f8ecd6;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item strong {
            color: #292524;
        }
        .order-item span {
            color: #57534e;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(135deg, #0d9488, #1a4241);
            color: #ffffff;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .email-footer {
            background: #fdfcf9;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #f8ecd6;
        }
        .email-footer p {
            margin: 5px 0;
            color: #57534e;
            font-size: 14px;
        }
        .email-footer a {
            color: #1a4241;
            text-decoration: none;
            font-weight: 600;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">🎉</div>
            <h1>Order Delivered!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello {{ $order->name }},</h2>
            
            <p>Great news! Your order has been delivered successfully.</p>
            
            <p>We hope you're satisfied with your order. If you have any questions or concerns, please don't hesitate to reach out to us.</p>

            <!-- Order Details -->
            <div class="order-details">
                <h3>📋 Order Details</h3>
                
                <div class="order-item">
                    <strong>Order ID:</strong>
                    <span>#{{ $order->id }}</span>
                </div>
                
                <div class="order-item">
                    <strong>Customer Name:</strong>
                    <span>{{ $order->name }}</span>
                </div>
                
                <div class="order-item">
                    <strong>Email:</strong>
                    <span>{{ $order->email }}</span>
                </div>
                
                @if($order->country)
                <div class="order-item">
                    <strong>Country:</strong>
                    <span>{{ $order->country }}</span>
                </div>
                @endif
                
                @if($order->description)
                <div class="order-item">
                    <strong>Description:</strong>
                    <span>{{ $order->description }}</span>
                </div>
                @endif
                
                <div class="order-item">
                    <strong>Price:</strong>
                    <span>AED {{ number_format($order->price, 2) }}</span>
                </div>
                
                <div class="order-item">
                    <strong>Status:</strong>
                    <span class="status-badge">✅ Delivered</span>
                </div>
                
                <div class="order-item">
                    <strong>Order Date:</strong>
                    <span>{{ $order->created_at->format('F d, Y') }}</span>
                </div>
            </div>

            <p>Thank you for choosing our services! We look forward to serving you again.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>Need help?</strong></p>
            <p>Contact us if you have any questions or concerns.</p>
            <p style="margin-top: 20px; color: #999; font-size: 12px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>

