<!DOCTYPE html>
<html>
<head>
    <title>Gate Entry Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Gate Entry Report</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phase</th>
                <th>Email</th>
                <th>Entry</th>
                <th>Out</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gateMonitors as $gateMonitor)
                <tr>
                    <td>{{ $gateMonitor->owner->fname }} {{ $gateMonitor->owner->lname }}</td>
                    <td>{{ $gateMonitor->owner->phase }}</td>
                    <td>{{ $gateMonitor->owner->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($gateMonitor->in)->format('F j, Y g:i A') }}</td>
                    <td>{{ $gateMonitor->out ? \Carbon\Carbon::parse($gateMonitor->out)->format('F j, Y g:i A') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
