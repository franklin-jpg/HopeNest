<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Report - {{ ucfirst($period) }} ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 40px 60px;
            background: #f9f9f9;
            color: #333;
            line-height: 1.5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        h1 {
            color: #1e40af;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .subtitle {
            color: #64748b;
            font-size: 16px;
            margin-top: 8px;
        }
        .report-meta {
            display: flex;
            justify-content: space-between;
            background: #f1f5f9;
            padding: 15px 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 30px 0;
        }
        .summary-card {
            background: #3b82f6;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .summary-card.net { background: #10b981; }
        .summary-card.count { background: #8b5cf6; }
        .summary-card.avg { background: #f59e0b; }
        .summary-card h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .summary-card p {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
        }
        th {
            background: #1e40af;
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        tr:hover {
            background-color: #eff6ff;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-green { color: #16a34a; }
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
            margin: 35px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #dbeafe;
        }
        footer {
            text-align: center;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Donation Report</h1>
            <div class="subtitle">
                {{ ucfirst($period) }} Summary • {{ $startDate->format('d F Y') }} – {{ $endDate->format('d F Y') }}
            </div>
        </header>

        <div class="report-meta">
            <div><strong>Generated on:</strong> {{ now()->format('d F Y \a\t g:i A') }}</div>
            <div><strong>Report Period:</strong> {{ ucfirst($period) }}</div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Total Revenue</h3>
                <p>₦{{ number_format($report['summary']['total_revenue'], 2) }}</p>
            </div>
            <div class="summary-card net">
                <h3>Net Revenue</h3>
                <p>₦{{ number_format($report['summary']['net_revenue'], 2) }}</p>
            </div>
            <div class="summary-card count">
                <h3>Total Donations</h3>
                <p>{{ number_format($report['summary']['total_donations']) }}</p>
            </div>
            <div class="summary-card avg">
                <h3>Average Donation</h3>
                <p>₦{{ number_format($report['summary']['avg_donation'], 2) }}</p>
            </div>
        </div>

        <!-- Top Campaigns -->
        <div class="section-title">Top Performing Campaigns</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Campaign</th>
                    <th class="text-center">Donations</th>
                    <th class="text-right">Amount (₦)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['by_campaign'] as $index => $c)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ $c['campaign'] }}</td>
                    <td class="text-center">
                        {{ $c['count'] }}</td>
                    <td class="text-right text-green font-bold">
                        {{ number_format($c['total'], 2) }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">No campaign data available</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Payment Methods -->
        <div class="section-title">Payment Methods Distribution</div>
        <table>
            <thead>
                <tr>
                    <th>Method</th>
                    <th class="text-center">Transactions</th>
                    <th class="text-right">Total Amount (₦)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['by_payment_method'] as $pm)
                <tr>
                    <td class=" dark:text-gray-200">{{ $pm['method'] }}</td>
                    <td class="text-center">{{ $pm['count'] }}</td>
                    <td class="text-right font-bold text-green">
                        {{ number_format($pm['total'], 2) }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center">No payment data</td></tr>
                @endforelse
            </tbody>
        </table>

        <footer>
            <p>Generated by {{ config('app.name') }} • {{ config('app.url') }}</p>
            <p>This is an automated report • Page 1 of 1</p>
        </footer>
    </div>
</body>
</html>