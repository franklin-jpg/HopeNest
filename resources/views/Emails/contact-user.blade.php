
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting HopeNest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 20px;
        }
        .content {
            padding: 40px 30px;
        }
        .highlight-box {
            background: #fff5f2;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ff5722;
        }
        .footer {
            background: #f4f4f4;
            padding: 30px 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #ff5722;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 32px;">Thank You!</h1>
            <p style="margin: 10px 0 0 0; font-size: 18px;">We've received your message</p>
        </div>

        <div class="content">
            <p>Dear {{ $data['first_name'] }},</p>

            <p>Thank you for reaching out to HopeNest! We have received your message and our team will review it shortly.</p>

            <div class="highlight-box">
                <p style="margin: 0;"><strong>What happens next?</strong></p>
                <p style="margin: 10px 0 0 0;">Our support team typically responds within 24 hours during business days. We'll get back to you at <strong>{{ $data['email'] }}</strong></p>
            </div>

            <p><strong>Your message details:</strong></p>
            <p style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                <strong>Subject:</strong> {{ ucfirst($data['subject']) }}<br>
                <strong>Message:</strong> {{ $data['message'] }}
            </p>

            <p>In the meantime, feel free to:</p>
            <ul>
                <li>Browse our <a href="{{ url('/campaigns') }}" style="color: #ff5722;">active campaigns</a></li>
                <li>Learn more <a href="{{ url('/about') }}" style="color: #ff5722;">about HopeNest</a></li>
                <li>Start your own <a href="{{ url('/campaigns/create') }}" style="color: #ff5722;">fundraising campaign</a></li>
            </ul>

            <p>If you have any urgent concerns, please don't hesitate to call us at <strong>+234 801 234 5678</strong></p>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>The HopeNest Team</strong>
            </p>
        </div>

        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">Instagram</a> |
                <a href="#">LinkedIn</a>
            </div>
            <p><strong>HopeNest</strong><br>
            123 Hope Street, Benin City, Edo State, Nigeria</p>
            <p>Email: support@hopenest.ng | Phone: +234 801 234 5678</p>
            <p>&copy; {{ date('Y') }} HopeNest. All rights reserved.</p>
        </div>
    </div>
</body>
</html>