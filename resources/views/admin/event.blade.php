@extends('layouts.adminlayout')

@section('styles')
{{-- You can add custom styles here if needed --}}
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="display-4 mb-4">Activites</h1>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createEventModal">Create New Activity</button>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@elseif(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Activties</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>{{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}</td>

                            <td>{{ $event->title }}</td>
                            <td>{{ $event->start }}</td>
                            <td>{{ $event->end }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEventModal" data-id="{{ $event->id }}" data-title="{{ $event->title }}" data-start="{{ $event->start }}">Edit</button>
                                <form action="{{ route('eventdos.destroy', $event) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('eventdos.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Create Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="start">Start Date & Time</label>
                        <input type="datetime-local" name="start" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end">End Date & Time</label>
                        <input type="datetime-local" name="end" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editEventForm" method="POST">
                @csrf
                @method('PUT')  <!-- This tells Laravel to treat the form as a PUT request -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editTitle">Title</label>
                        <input type="text" name="title" id="editTitle" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editStart">Start Date & Time</label>
                        <input type="datetime-local" name="start" id="editStart" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Populate the Edit Event modal with data
    var editEventModal = document.getElementById('editEventModal');
    editEventModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var eventId = button.getAttribute('data-id');
        var eventTitle = button.getAttribute('data-title');
        var eventStart = button.getAttribute('data-start');

        // Update form action to include the event ID
        var form = document.getElementById('editEventForm');
        form.action = '/eventdos/' + eventId; // Set the action to the correct route

        // Populate the form fields with the event data
        var titleInput = document.getElementById('editTitle');
        var startInput = document.getElementById('editStart');

        titleInput.value = eventTitle;
        startInput.value = eventStart;
    });
</script>
@endsection

