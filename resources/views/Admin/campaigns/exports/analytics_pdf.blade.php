<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Campaign Analytics</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; }
        table { width:100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #999; padding: 8px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h2>Campaign Analytics Report</h2>
<p><strong>Campaign:</strong> {{ $campaign->title }}</p>

<h4>Summary</h4>
<table>
    <tbody>
        <tr><th>Total Raised</th><td>{{ $data['summary']['total_raised'] }}</td></tr>
        <tr><th>Goal Amount</th><td>{{ $data['summary']['goal_amount'] }}</td></tr>
        <tr><th>Total Donors</th><td>{{ $data['summary']['total_donors'] }}</td></tr>
        <tr><th>Average Donation</th><td>{{ $data['summary']['average_donation'] }}</td></tr>
    </tbody>
</table>

<h4 style="margin-top:25px">Donations</h4>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Donor</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['donations'] as $row)
            <tr>
                <td>{{ $row->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $row->user->name ?? 'Anonymous' }}</td>
                <td>{{ $row->amount }}</td>
                <td>{{ $row->payment_method }}</td>
                <td>{{ $row->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
