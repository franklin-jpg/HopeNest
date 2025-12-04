<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donation Receipt</title>

    <style>
        @page { margin: 40px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 120px;
            margin-bottom: 10px;
        }

        h2 {
            margin: 0;
            font-size: 22px;
            color: #1a73e8;
        }

        .meta-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 8px 0;
            font-size: 14px;
        }

        .receipt-box {
            margin-top: 20px;
            padding: 18px 20px;
            background: #f1f8ff;
            border-left: 4px solid #1a73e8;
            border-radius: 6px;
        }

        .amount-box {
            background: #1a73e8;
            color: #fff;
            margin-top: 20px;
            padding: 18px;
            text-align: center;
            border-radius: 8px;
            font-size: 22px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }

        .divider {
            height: 1px;
            margin: 25px 0;
            background: #eaeaea;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <!-- Replace with your logo -->
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        <h2>Donation Receipt</h2>
        <p style="font-size: 12px; color: #555;">
            Thank you for supporting our mission.
        </p>
    </div>

    <div class="receipt-box">
        Receipt Number: <strong>{{ $donation->receipt_number }}</strong><br>
        Date: <strong>{{ $donation->created_at->format('F j, Y') }}</strong>
    </div>

    <table class="meta-table">

        <tr>
            <td><strong>Donor Name:</strong></td>
            <td>{{ $donation->is_anonymous ? 'Anonymous Donor' : $donation->donor_name }}</td>
        </tr>

        <tr>
            <td><strong>Email:</strong></td>
            <td>{{ $donation->donor_email }}</td>
        </tr>

        <tr>
            <td><strong>Phone:</strong></td>
            <td>{{ $donation->donor_phone }}</td>
        </tr>

        <tr>
            <td><strong>Campaign:</strong></td>
            <td>{{ $donation->campaign->title }}</td>
        </tr>

        @if ($donation->message)
        <tr>
            <td><strong>Message:</strong></td>
            <td>{{ $donation->message }}</td>
        </tr>
        @endif

    </table>

    <div class="amount-box">
        ₦{{ number_format($donation->total_amount, 2) }}
    </div>

    <div class="divider"></div>

    <table class="meta-table">
        <tr>
            <td><strong>Amount Donated:</strong></td>
            <td>₦{{ number_format($donation->amount, 2) }}</td>
        </tr>

        <tr>
            <td><strong>Processing Fee Covered:</strong></td>
            <td>{{ $donation->cover_fee ? 'Yes' : 'No' }}</td>
        </tr>

        <tr>
            <td><strong>Payment Method:</strong></td>
            <td>{{ ucfirst($donation->payment_method) }}</td>
        </tr>

        <tr>
            <td><strong>Status:</strong></td>
            <td>{{ ucfirst($donation->status) }}</td>
        </tr>

        <tr>
            <td><strong>Transaction Reference:</strong></td>
            <td>{{ $donation->payment_reference }}</td>
        </tr>
    </table>

    <div class="footer">
        If you have questions about this receipt, please contact us.<br>
        &copy; {{ date('Y') }} HOPENEST — All rights reserved.
    </div>
</div>

</body>
</html>
