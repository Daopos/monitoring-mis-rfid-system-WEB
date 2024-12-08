@extends('layouts.treasurerlayout')

@section('styles')
    <link href="{{ asset('/css/admindashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="dashboard-body p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Dashboard Overview</h3>

            <!-- Year Dropdown -->
            <form method="GET" action="{{ route('treasurer.dashboard') }}" class="d-inline">
                <label for="year" class="me-2">Select Year:</label>
                <select name="year" id="year" class="form-select d-inline w-auto" onchange="this.form.submit()">
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="mt-4">
            <h4>Monthly Collections for {{ $selectedYear }}</h4>
            <canvas id="collectionChart" width="400" height="200"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('collectionChart').getContext('2d');

            // Data from the controller
            const collections = @json($monthlyCollections);

            // Prepare labels (month names) and data
            const labels = Array.from({ length: 12 }, (_, i) => new Date(0, i).toLocaleString('default', { month: 'long' }));
            const data = Array(12).fill(0);

            collections.forEach(({ month, total_collected }) => {
                data[month - 1] = total_collected; // Map month to data array (0-indexed)
            });

            // Create the bar chart
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Amount Collected (in PHP)',
                        data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Amount Collected',
                            },
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month',
                            },
                        },
                    },
                },
            });
        });
    </script>
@endsection
