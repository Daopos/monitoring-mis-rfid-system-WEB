@extends('layouts.treasurerlayout')

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
                            <tr>
                                <td>{{ $loop->iteration + ($reminders->currentPage() - 1) * $reminders->perPage() }}</td>
                                <td>{{ $reminder->homeOwner->fname }} {{ $reminder->homeOwner->lname }}</td>
                                <td>{{ $reminder->title }}</td>
                                <td>{{ $reminder->amount }}</td>
                                <td>{{ $reminder->due_date }}</td>
                                <td>{{ $reminder->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-reminder="{{ json_encode(['id' => $reminder->id, 'title' => $reminder->title, 'amount' => $reminder->amount, 'due_date' => $reminder->due_date]) }}">Edit</button>

                                    @if($reminder->status !== 'paid')
                                        <form action="{{ route('payment_reminders.markPaid', $reminder->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm">Mark as Paid</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('payment_reminders.destroy', $reminder->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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

    <!-- Edit Reminder Modal -->
    <div class="modal fade" id="editReminderModal" tabindex="-1" aria-labelledby="editReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReminderModalLabel">Edit Payment Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editReminderForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="reminder_id" id="edit_reminder_id">
                        <div class="form-group mb-3">
                            <label for="edit_title">Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_amount">Amount</label>
                            <input type="number" name="amount" id="edit_amount" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_due_date">Due Date</label>
                            <input type="date" name="due_date" id="edit_due_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Handle Edit Button Click
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const reminder = JSON.parse(this.dataset.reminder);
                document.getElementById('edit_reminder_id').value = reminder.id;
                document.getElementById('edit_title').value = reminder.title;
                document.getElementById('edit_amount').value = reminder.amount;
                document.getElementById('edit_due_date').value = reminder.due_date;
                document.getElementById('editReminderForm').action = `/payment_reminders/${reminder.id}`;
                const editModal = new bootstrap.Modal(document.getElementById('editReminderModal'));
                editModal.show();
            });
        });

        // Reset Create Reminder Form when Modal is Hidden
        document.getElementById('createReminderModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('createReminderForm').reset();
        });
    </script>
</div>
@endsection
