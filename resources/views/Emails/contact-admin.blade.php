<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
            padding: 30px 20px;
            text-align: center;
        }
        .content {
            padding: 30px 20px;
        }
        .info-row {
            margin-bottom: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #ff5722;
            margin-bottom: 5px;
        }
        .message-box {
            background: #f9f9f9;
            padding: 20px;
            border-left: 4px solid #ff5722;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #ff5722;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">New Contact Form Submission</h1>
            <p style="margin: 10px 0 0 0;">HopeNest Contact Form</p>
        </div>

        <div class="content">
            <p>You have received a new contact form submission from your website:</p>

            <div class="info-row">
                <div class="info-label">Name:</div>
                <div>{{ $data['first_name'] }} {{ $data['last_name'] }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email:</div>
                <div><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></div>
            </div>

            @if(!empty($data['phone']))
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div><a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a></div>
            </div>
            @endif

            <div class="info-row">
                <div class="info-label">Subject:</div>
                <div>{{ ucfirst($data['subject']) }}</div>
            </div>

            <div class="message-box">
                <div class="info-label">Message:</div>
                <div>{{ $data['message'] }}</div>
            </div>

            <p style="text-align: center;">
                <a href="{{ url('/admin/contacts') }}" class="btn">View in Dashboard</a>
            </p>
        </div>

        <div class="footer">
            <p>This email was sent from HopeNest Contact Form</p>
            <p>&copy; {{ date('Y') }} HopeNest. All rights reserved.</p>
        </div>
    </div>
</body>
</html>