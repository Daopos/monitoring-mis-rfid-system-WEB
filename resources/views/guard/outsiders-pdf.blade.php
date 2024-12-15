<!DOCTYPE html>
<html>
<head>
    <title>Service Providers List</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h1 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Service Providers List</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Vehicle Type</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Plate Number</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($outsiders as $outsider)
                <tr>
                    <td>{{ $outsider->name }}</td>
                    <td>{{ $outsider->type }}</td>
                    <td>{{ $outsider->vehicle_type ?? 'N/A' }}</td>
                    <td>{{ $outsider->brand ?? 'N/A' }}</td>
                    <td>{{ $outsider->model ?? 'N/A' }}</td>
                    <td>{{ $outsider->plate_number ?? 'N/A' }}</td>
                    <td>{{ $outsider->in }}</td>
                    <td>{{ $outsider->out ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
