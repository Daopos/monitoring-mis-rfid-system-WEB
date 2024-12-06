<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Gate Entry List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Visitor Gate Entry List</h1>
    <p>Total Entries: {{ $gateMonitors->count() }}</p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Homeowner</th>
                <th>Email</th>
                <th>Entry</th>
                <th>Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gateMonitors as $gateMonitor)
                <tr>
                    <td>{{ $gateMonitor->visitor->name }}</td>
                    <td>{{ $gateMonitor->visitor->homeOwner->fname }} {{ $gateMonitor->visitor->homeOwner->lname }}</td>
                    <td>{{ $gateMonitor->visitor->homeOwner->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($gateMonitor->in)->format('F j, Y g:i A') }}</td>
                    <td>{{ $gateMonitor->out ? \Carbon\Carbon::parse($gateMonitor->out)->format('F j, Y g:i A') : 'N/A' }}</td>
                    <td>{{ $gateMonitor->visitor->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
