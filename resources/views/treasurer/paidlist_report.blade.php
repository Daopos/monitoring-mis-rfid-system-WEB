<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paid List Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Paid List Report</h1>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Homeowner</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Date Paid</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reminders as $reminder)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reminder->homeOwner->fname }} {{ $reminder->homeOwner->lname }}</td>
                    <td>{{ $reminder->title }}</td>
                    <td>{{ $reminder->amount }}</td>
                    <td>{{ $reminder->due_date }}</td>
                    <td>{{ $reminder->updated_at->format('F d, Y') }}</td>
                    <td>{{ $reminder->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
