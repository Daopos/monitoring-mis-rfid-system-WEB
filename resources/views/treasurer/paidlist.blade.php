@extends('layouts.treasurerlayout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Paid List</h1>

    <!-- Display success message if exists -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Total Amount Box with Icon -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <!-- Icon for Total Amount -->
                    <i class="fas fa-money-bill-wave fa-3x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0">Total Amount</h5>
                        <p class="card-text h4 mb-0">₱{{ number_format($reminders->sum('amount'), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Payment Reminders Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Payment Reminders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Homeowner</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reminders as $reminder)
                            <tr>
                                <td>{{ $reminder->id }}</td>
                                <td>{{ $reminder->homeOwner->fname }} {{ $reminder->homeOwner->lname }}</td>
                                <td>{{ $reminder->title }}</td>
                                <td>{{ $reminder->amount }}</td>
                                <td>{{ $reminder->due_date }}</td>
                                <td>{{ $reminder->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        <div class="form-group mb-3">
                            <label for="home_owner_id">Homeowner</label>
                            <select name="home_owner_id" id="home_owner_id" class="form-control" required>
                                @foreach($homeOwners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->fname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('createReminderModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('createReminderForm').reset();
        });
    </script>
</div>
@endsection
