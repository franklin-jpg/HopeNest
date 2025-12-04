<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Performance Report - {{ now()->format('d M Y') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px 50px;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            padding: 45px 55px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 4px solid #3b82f6;
        }
        h1 {
            color: #1e40af;
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .subtitle {
            color: #64748b;
            font-size: 17px;
            margin-top: 10px;
            font-weight: 500;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            background: #f1f5f9;
            padding: 16px 24px;
            border-radius: 12px;
            margin: 30px 0;
            font-size: 15px;
            color: #475569;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 40px 0;
        }
        .stat-card {
            background: #3b82f6;
            color: white;
            padding: 24px;
            border-radius: 14px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }
        .stat-card.success { background: #10b981; }
        .stat-card.time   { background: #6366f1; }
        .stat-card.raised { background: #f59e0b; }
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 15px;
            opacity: 0.95;
            font-weight: 600;
        }
        .stat-card p {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e40af;
            margin: 45px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #dbeafe;
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
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 14px 12px;
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
        .font-bold { font-weight: 700; }
        .text-green { color: #16a34a; }
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        footer {
            margin-top: 80px;
            padding-top: 25px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }
        .text-muted { color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Campaign Performance Report</h1>
            <div class="subtitle">
                Comprehensive Analytics • {{ now()->format('d F Y \a\t g:i A') }}
            </div>
        </header>

        <div class="meta">
            <div><strong>Total Campaigns:</strong> {{ $report['total_campaigns'] }}</div>
            <div><strong>Goal Achieved:</strong> {{ $report['goal_achieved_count'] }} of {{ $report['total_campaigns'] }}</div>
            <div><strong>Overall Success Rate:</strong> {{ $report['success_rate'] }}%</div>
        </div>

        <!-- Summary Stats -->
        <div class="summary-grid">
            <div class="stat-card success">
                <h3>Success Rate</h3>
                <p>{{ $report['success_rate'] }}%</p>
            </div>
            <div class="stat-card time">
                <h3>Avg Time to Goal</h3>
                <p>{{ $report['avg_time_to_goal_days'] }} <small>days</small></p>
            </div>
            <div class="stat-card">
                <h3>Total Raised</h3>
                <p>₦{{ number_format($report['total_raised'], 0) }}</p>
            </div>
            <div class="stat-card raised">
                <h3>Goal Achievement</h3>
                <p>{{ $report['goal_achieved_count'] }} / {{ $report['total_campaigns'] }}</p>
            </div>
        </div>

        <!-- Top Performing Campaigns -->
        <div class="section-title">Top Performing Campaigns</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Campaign Title</th>
                    <th>Category</th>
                    <th class="text-right">Goal</th>
                    <th class="text-right">Raised</th>
                    <th class="text-center">Progress</th>
                    <th class="text-center">Donations</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['top_performers'] as $index => $c)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ Str::limit($c['title'], 50) }}</td>
                    <td class="text-muted">{{ $c['category'] ?? 'Uncategorized' }}</td>
                    <td class="text-right">₦{{ number_format($c['goal']) }}</td>
                    <td class="text-right text-green font-bold">₦{{ number_format($c['raised']) }}</td>
                    <td class="text-center">
                        <strong>{{ $c['progress'] }}%</strong>
                    </td>
                    <td class="text-center">{{ $c['donations'] }}</td>
                    <td class="text-center">
                        <span class="badge {{ $c['goal_achieved'] ? 'badge-success' : 'badge-warning' }}">
                            {{ $c['goal_achieved'] ? 'Achieved' : 'In Progress' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-muted">
                        No campaign data available for this period.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <footer>
            <p><strong>{{ config('app.name') }}</strong> • {{ config('app.url') }}</p>
            <p class="text-muted">
                This is an automatically generated report • Page 1 of 1
            </p>
        </footer>
    </div>
</body>
</html>