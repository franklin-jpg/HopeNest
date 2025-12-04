<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .update-badge {
            background-color: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .update-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 20px 0;
        }
        .update-content {
            color: #4b5563;
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .campaign-info {
            background-color: #f9fafb;
            border-left: 4px solid #f97316;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .campaign-info h3 {
            margin: 0 0 10px 0;
            color: #1f2937;
            font-size: 18px;
        }
        .campaign-info p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }
        .progress-bar {
            background-color: #e5e7eb;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin: 15px 0;
        }
        .progress-fill {
            background: linear-gradient(90deg, #f97316 0%, #ea580c 100%);
            height: 100%;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #ffffff;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            color: #6b7280;
            font-size: 14px;
            margin: 5px 0;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #f97316;
            text-decoration: none;
            font-size: 20px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
            }
            .header, .content, .footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>📢 Campaign Update</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <span class="update-badge">New Update</span>
            
            <h2 class="update-title">{{ $update->title }}</h2>
            
            <div class="update-content">
                {!! nl2br(e($update->content)) !!}
            </div>

            <!-- Campaign Info Box -->
            <div class="campaign-info">
                <h3>{{ $campaign->title }}</h3>
                <p><strong>Location:</strong> {{ $campaign->location ?? 'Global' }}</p>
                
                @php
                    $progress = round(($campaign->raised_amount / max($campaign->goal_amount, 1)) * 100);
                @endphp
                
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $progress }}%">
                        {{ $progress }}%
                    </div>
                </div>
                
                <p><strong>Raised:</strong> ${{ number_format($campaign->raised_amount, 2) }} of ${{ number_format($campaign->goal_amount, 2) }}</p>
                <p><strong>Donors:</strong> {{ $campaign->donorsCount() }} amazing supporters</p>
            </div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('show.single', $campaign->slug) }}" class="button">
                    View Campaign Details
                </a>
            </center>

            <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                Thank you for being part of this journey! Your support means the world to us and the people we're helping.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-weight: bold; color: #1f2937;">HopeNest</p>
            <p>Building hope, transforming lives</p>
            
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
            </div>
            
            <p style="font-size: 12px; color: #9ca3af; margin-top: 20px;">
                You're receiving this email because you donated to this campaign.<br>
                If you no longer wish to receive updates, you can 
                <a href="#" style="color: #f97316;">unsubscribe here</a>.
            </p>
        </div>
    </div>
</body>
</html>