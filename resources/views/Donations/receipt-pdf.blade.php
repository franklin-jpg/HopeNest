<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Receipt - HopeNest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        @page {
            margin: 40px 30px;
            size: A4;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #2d3748;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(255, 87, 34, 0.1);
            border: 1px solid #fed7aa;
        }

        /* Header Gradient */
        .header {
            background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%);
            color: white;
            padding: 40px 50px;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 10px;
            background: linear-gradient(to right, #ff8a50, #ff5722);
        }

        .logo {
            width: 90px;
            height: auto;
            margin-bottom: 16px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .header h1 {
            font-size: 36px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 18px;
            opacity: 0.95;
            margin-top: 8px;
        }

        .receipt-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.25);
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 20px;
            backdrop-filter: blur(10px);
        }

        /* Content */
        .content {
            padding: 50px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #ff5722;
            margin: 30px 0 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            background: #fff8f5;
            padding: 24px;
            border-radius: 14px;
            border: 1px dashed #ff8a50;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 13px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-top: 4px;
        }

        /* Amount Highlight */
        .amount-highlight {
            background: linear-gradient(135deg, #fff0eb 0%, #ffe4d6 100%);
            padding: 30px;
            border-radius: 18px;
            text-align: center;
            border: 3px solid #ff8a50;
            margin: 30px 0;
        }

        .amount-label {
            font-size: 16px;
            color: #e65100;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .amount-value {
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, #ff5722, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .fee-note {
            margin-top: 12px;
            font-size: 15px;
            color: #16a34a;
            font-weight: 600;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        td {
            padding: 12px 0;
            border-bottom: 1px solid #fee2e2;
        }

        td:first-child {
            color: #718096;
            font-weight: 600;
            width: 40%;
        }

        td:last-child {
            color: #1a1a1a;
            font-weight: 500;
        }

        /* Impact Message */
        .impact-box {
            background: linear-gradient(to right, #fff8f5, #ffffff);
            border-left: 6px solid #ff5722;
            padding: 24px;
            border-radius: 0 12px 12px 0;
            margin: 30px 0;
            font-size: 16px;
            line-height: 1.7;
        }

        .impact-box strong {
            color: #c2410c;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 30px 50px;
            background: #fff8f5;
            color: #64748b;
            font-size: 13px;
        }

        .footer a {
            color: #ff5722;
            text-decoration: none;
            font-weight: 600;
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #ff8a50, transparent);
            margin: 30px 0;
        }

        @media print {
            body { background: white; }
            .container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        @if(file_exists(public_path('images/logo-white.png')))
            <img src="{{ public_path('images/logo-white.png') }}" class="logo" alt="HopeNest Logo">
        @elseif(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="HopeNest Logo" style="filter: brightness(0) invert(1);">
        @else
            <div style="font-size: 50px; font-weight: 900; letter-spacing: -2px;">HOPE<span style="color:#fff176">NEST</span></div>
        @endif

        <h1>Donation Receipt</h1>
        <p>Thank you for making hope possible</p>

        <div class="receipt-badge">
            <span>Receipt #{{ $donation->receipt_number }}</span>
            <span>•</span>
            <span>{{ $donation->paid_at->format('F j, Y') }}</span>
        </div>
    </div>

    <div class="content">

        <!-- Donor & Transaction Info -->
        <h2 class="section-title">Donor Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Donor Name</div>
                <div class="info-value">{{ $donation->is_anonymous ? 'Anonymous Hero' : $donation->display_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ $donation->donor_email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Phone Number</div>
                <div class="info-value">{{ $donation->donor_phone ?? 'Not provided' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Campaign Supported</div>
                <div class="info-value">{{ $donation->campaign->title }}</div>
            </div>
        </div>

        <!-- Amount Highlight -->
        <div class="amount-highlight">
            <div class="amount-label">Total Amount Donated</div>
            <div class="amount-value">₦{{ number_format($donation->total_amount, 2) }}</div>
            @if($donation->cover_fee)
                <div class="fee-note">You generously covered the processing fee</div>
            @endif
        </div>

        <!-- Details Table -->
        <h2 class="section-title">Transaction Details</h2>
        <table>
            <tr>
                <td>Base Donation Amount</td>
                <td>₦{{ number_format($donation->amount, 2) }}</td>
            </tr>
            @if($donation->cover_fee)
            <tr>
                <td>Processing Fee (Covered by you)</td>
                <td>₦{{ number_format($donation->processing_fee, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>Payment Method</td>
                <td>{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</td>
            </tr>
            <tr>
                <td>Frequency</td>
                <td>{{ ucfirst(str_replace('-', ' ', $donation->frequency)) }}</td>
            </tr>
            <tr>
                <td>Transaction Reference</td>
                <td style="font-family: monospace; font-size: 13px;">{{ $donation->paystack_reference ?? $donation->payment_reference }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td><strong style="color: #16a34a;">Completed Successfully</strong></td>
            </tr>
        </table>

        @if($donation->message)
        <div class="divider"></div>
        <h2 class="section-title">Your Message</h2>
        <p style="background:#fff8f5; padding:20px; border-radius:12px; font-style:italic; color:#555;">
            "{{ $donation->message }}"
        </p>
        @endif

        <!-- Impact Message -->
        <div class="impact-box">
            <strong>Your Impact:</strong><br>
            {{ $donation->campaign->custom_thank_you ?? 'Your generous donation helps us continue transforming lives, building hope, and creating lasting change in communities across Nigeria. Every contribution brings us closer to a world where no one is left behind. Thank you for being the reason someone smiles today.' }}
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>HopeNest</strong> • Building Hope, Transforming Lives</p>
        <p>Need help? Contact us at <a href="mailto:support@hopenest.org">support@hopenest.org</a></p>
        <p>This is an official tax-deductible receipt • &copy; {{ date('Y') }} HopeNest. All rights reserved.</p>
    </div>

</div>

</body>
</html>