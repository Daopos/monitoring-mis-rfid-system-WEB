@extends('layouts.treasurerlayout')

@section('styles')
    <style>
.table-overdue {
    background-color: #f8d7da; /* Light red background */
    color: #721c24; /* Dark red text */
}

    </style>
@endsection
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Payment Reminders</h1>

    <!-- Search Form -->
    <form action="{{ route('payment_reminders.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Search by Homeowner Name -->
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Homeowner Name" value="{{ request('search') }}">
            </div>

            <div class="col-md-8">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Filter Form -->
    <form action="{{ route('payment_reminders.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Filter Dropdown -->
            <div class="col-md-4">
                <select name="filter" class="form-control">
                    <option value="">Filter by Due Date</option>
                    <option value="due_today" {{ request('filter') == 'due_today' ? 'selected' : '' }}>Due Today</option>
                    <option value="overdue" {{ request('filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>

            <div class="col-md-8">
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
        </div>
    </form>

    <!-- Display Total Homeowners for Due Today and Overdue -->
    <div class="row mb-4">
        <!-- Due Today Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-calendar-day fa-3x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0">Due Today</h5>
                        <p class="card-text h4 mb-0">{{ $dueTodayCount }} Homeowners</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-calendar-times fa-3x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0">Overdue</h5>
                        <p class="card-text h4 mb-0">{{ $overdueCount }} Homeowners</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createReminderModal">
        Create New Reminder
    </button>
    <!-- Table for Payment Reminders -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Payment Reminders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Homeowner</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reminders as $reminder)
                        @php
                        // Parse the due date and current date
                        $dueDate = \Carbon\Carbon::parse($reminder->due_date)->startOfDay(); // Remove the time
                        $currentDate = \Carbon\Carbon::now()->startOfDay(); // Remove the time
                        $tomorrow = \Carbon\Carbon::tomorrow()->startOfDay(); // Get tomorrow's date

                        // Debug output: format the dates for debugging
                        $dueDateFormatted = $dueDate->format('Y-m-d'); // Format for debugging
                        $currentDateFormatted = $currentDate->format('Y-m-d'); // Format for debugging

                        // Check if the due date is overdue (current date is after the due date)
                        $isOverdue = $currentDate->isAfter($dueDate);
                    @endphp
                        <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                            <td>{{ $loop->iteration + ($reminders->currentPage() - 1) * $reminders->perPage() }}</td>
                            <td>{{ $reminder->homeOwner->fname }} {{ $reminder->homeOwner->lname }}</td>
                            <td>{{ $reminder->title }}</td>
                            <td>{{ $reminder->amount }}</td>
                            <td>
                                {{ $reminder->due_date->format('F d, Y')}} <!-- Display due date for troubleshooting -->
                            </td>
                            <td>{{ $isOverdue ? 'Overdue' : 'Pending' }}</td>
                            <td>
                                <form action="{{ route('payment_reminders.markPaid', $reminder->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Mark as Paid</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $reminders->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Reminder Modal -->
    <div class="modal fade" id="createReminderModal" tabindex="-1" aria-labelledby="createReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReminderModalLabel">Create Payment Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createReminderForm" action="{{ route('payment_reminders.store') }}" method="POST">
                        @csrf
                        <div class="alert alert-info">
                            This will create a payment reminder for <strong>all homeowners</strong> with the title "Association Fee."
                        </div>
                        <div class="form-group mb-3">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" class="form-control" value="300" readonly>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
