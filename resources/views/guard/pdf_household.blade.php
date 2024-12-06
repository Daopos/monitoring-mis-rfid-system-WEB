<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household Entry List</title>
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
    <h1>Household Entry List</h1>
    <p>Total Entries: {{ $gateMonitors->count() }}</p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Head of the Family</th>
                <th>Entry</th>
                <th>Exit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gateMonitors as $gateMonitor)
                <tr>
                    <td>{{ $gateMonitor->household->name }}</td>
                    <td>
                        @if ($gateMonitor->household->homeOwner)
                            {{ $gateMonitor->household->homeOwner->fname }} {{ $gateMonitor->household->homeOwner->lname }}
                        @else
                            No homeowner information available
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($gateMonitor->in)->format('F j, Y g:i A') }}</td>
                    <td>{{ $gateMonitor->out ? \Carbon\Carbon::parse($gateMonitor->out)->format('F j, Y g:i A') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
