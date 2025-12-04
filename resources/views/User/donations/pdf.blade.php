<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            background: #f5f5f5;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #ff5722;
            border-radius: 10px;
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #ff5722 0%, #ff7043 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .receipt-header h1 {
            font-size: 32px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .receipt-header p {
            font-size: 16px;
            opacity: 0.95;
        }

        .receipt-body {
            padding: 40px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #ff5722;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ff5722;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            padding: 10px 0;
            font-weight: 600;
            color: #666;
            width: 40%;
        }

        .info-value {
            display: table-cell;
            padding: 10px 0;
            color: #333;
        }

        .amount-box {
            background: #f8f9fa;
            border: 2px dashed #ff5722;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: bold;
            color: #ff5722;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .thank-you-section {
            background: #fff8e1;
            border-left: 4px solid #ff5722;
            padding: 20px;
            margin-top: 30px;
            border-radius: 5px;
        }

        .thank-you-section h3 {
            color: #ff5722;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .thank-you-section p {
            color: #666;
            line-height: 1.8;
        }

        .receipt-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
        }

        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 20px 0;
        }

        .campaign-info {
            background: #f1f3f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .campaign-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .campaign-category {
            font-size: 13px;
            color: #666;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt-container {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h1>HopeNest</h1>
            <p>Donation Receipt</p>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Receipt Info Section -->
            <div class="section">
                <div class="section-title">Receipt Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Receipt Number:</div>
                        <div class="info-value"><strong>{{ $donation->transaction_id ?? 'N/A' }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date of Donation:</div>
                        <div class="info-value">{{ isset($donation->created_at) ? $donation->created_at->format('F d, Y - h:i A') : 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Payment Method:</div>
                        <div class="info-value">{{ isset($donation->payment_method) ? ucfirst($donation->payment_method) : 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status:</div>
                        <div class="info-value">
                            @php
                                $status = $donation->status ?? 'pending';
                                $statusClass = $status == 'completed' ? 'status-success' : ($status == 'pending' ? 'status-pending' : 'status-failed');
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Info -->
            @if(isset($donation->campaign) && $donation->campaign)
            <div class="section">
                <div class="section-title">Campaign Details</div>
                <div class="campaign-info">
                    <div class="campaign-title">{{ $donation->campaign->title ?? 'N/A' }}</div>
                    <div class="campaign-category">
                        <i>Category:</i> {{ $donation->campaign->category->name ?? 'N/A' }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Donor Information -->
            <div class="section">
                <div class="section-title">Donor Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Name:</div>
                        <div class="info-value">
                            {{ $donation->donor_name ?? ($donation->user->name ?? 'Anonymous') }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">
                            {{ $donation->donor_email ?? ($donation->user->email ?? 'N/A') }}
                        </div>
                    </div>
                    @if(isset($donation->donor_phone) && $donation->donor_phone)
                    <div class="info-row">
                        <div class="info-label">Phone:</div>
                        <div class="info-value">{{ $donation->donor_phone }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Amount Section -->
            <div class="section">
                <div class="section-title">Donation Amount</div>
                <div class="amount-box">
                    <div class="amount-label">Total Donated</div>
                    <div class="amount-value">₦{{ number_format($donation->amount ?? 0, 2) }}</div>
                </div>
            </div>

            <!-- Thank You Section -->
            <div class="thank-you-section">
                <h3>Thank You for Your Generosity!</h3>
                <p>
                    Your donation makes a real difference in the lives of those we serve. 
                    We are deeply grateful for your support and commitment to our mission. 
                    This receipt serves as proof of your charitable contribution.
                </p>
            </div>

            @if(isset($donation->message) && $donation->message)
            <div class="section">
                <div class="section-title">Your Message</div>
                <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; font-style: italic; color: #555;">
                    "{{ $donation->message }}"
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p><strong>HopeNest Foundation</strong></p>
            <p>Email: support@hopenest.org | Phone: +234 XXX XXX XXXX</p>
            <p>This is an electronically generated receipt and does not require a signature.</p>
            <p style="margin-top: 10px;">Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>