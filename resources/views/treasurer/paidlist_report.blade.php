<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paid List Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            position: relative;
            margin-bottom: 100px; /* Ensure space for signature */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 100px
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

        .signature-section {
            position: absolute;
            right: 20px;
            text-align: center;
        }

        .signature-section img {
            width: 150px;
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
                    <td>{{ $reminder->homeOwner->fname }} {{ strtoupper(substr($reminder->homeOwner->mname, 0, 1)) }}. {{ $reminder->homeOwner->lname }}</td>
                    <td>{{ $reminder->title }}</td>
                    <td>{{ $reminder->amount }}</td>
                    <td>{{ $reminder->due_date }}</td>
                    <td>{{ $reminder->updated_at->format('F d, Y') }}</td>
                    <td>{{ $reminder->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Treasurer's signature or details -->
    <div class="signature-section">
        @if($treasurer)
            <p>Signed by: {{ $treasurer->homeowner->fname }} {{ $treasurer->homeowner->lname }} (Treasurer)</p>
            <img src="{{ asset('path_to_signature_image.png') }}" alt="Signature">
        @else
            <p>No treasurer found.</p>
        @endif
    </div>

</body>
</html>
