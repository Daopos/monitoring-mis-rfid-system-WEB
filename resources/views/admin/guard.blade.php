@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Guards Management</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Add Guard Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add Guard</button>

    <!-- Search Form (Optional if needed) -->
    <form action="{{ route('admin.guard.index') }}" method="GET">
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Name or Username" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Tabs for Active and Archived Guards -->
    <ul class="nav nav-tabs" id="guardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="activeGuards-tab" data-bs-toggle="tab" href="#activeGuards" role="tab" aria-controls="activeGuards" aria-selected="true">Active Guards</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="archivedGuards-tab" data-bs-toggle="tab" href="#archivedGuards" role="tab" aria-controls="archivedGuards" aria-selected="false">Archived Guards</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="guardTabsContent">
        <!-- Active Guards Tab -->
        <div class="tab-pane fade show active" id="activeGuards" role="tabpanel" aria-labelledby="activeGuards-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Active Guards List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guards as $guard)
                                @if(!$guard->is_archived) <!-- Only show active guards -->
                                <tr>
                                    <td>{{ $guard->fname }} {{ $guard->mname[0] ?? '' }}. {{ $guard->lname }}</td>
                                    <td>{{ $guard->username }}</td>
                                    <td>{{ $guard->email }}</td>
                                    <td>{{ $guard->phone }}</td>
                                    <td>{{ $guard->active ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <form action="{{ route('admin.guard.assign', $guard->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning btn-sm">Assign</button>
                                        </form>
                                        <!-- Edit Guard Button -->
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="{{ $guard->id }}" data-username="{{ $guard->username }}"
                                            data-email="{{ $guard->email }}" data-phone="{{ $guard->phone }}">Edit</button>

                                        <!-- Archive Button -->
                                        <form action="{{ route('admin.guard.archive', $guard->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger btn-sm">Archive</button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Archived Guards Tab -->
        <div class="tab-pane fade" id="archivedGuards" role="tabpanel" aria-labelledby="archivedGuards-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Archived Guards List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guards as $guard)
                                @if($guard->is_archived) <!-- Only show archived guards -->
                                <tr>
                                    <td>{{ $guard->fname }} {{ $guard->mname[0] ?? '' }}. {{ $guard->lname }}</td>
                                    <td>{{ $guard->username }}</td>
                                    <td>{{ $guard->email }}</td>
                                    <td>{{ $guard->phone }}</td>
                                    <td>{{ $guard->active ? 'Active' : 'Inactive' }}</td>
                                    {{-- <td>
                                        <!-- Restore Button -->
                                        <form action="{{ route('admin.guard.restore', $guard->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning btn-sm">Restore</button>
                                        </form>
                                        <!-- Edit Guard Button -->
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="{{ $guard->id }}" data-username="{{ $guard->username }}"
                                            data-email="{{ $guard->email }}" data-phone="{{ $guard->phone }}">Edit</button>
                                    </td> --}}
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Guard Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.guard.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add Guard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_username" class="form-label">Username</label>
                        <input type="text" name="username" id="create_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_email" class="form-label">Email</label>
                        <input type="email" name="email" id="create_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="create_phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="create_phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="create_fname" class="form-label">First Name</label>
                        <input type="text" name="fname" id="create_fname" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="create_mname" class="form-label">Middle Name</label>
                        <input type="text" name="mname" id="create_mname" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="create_lname" class="form-label">Last Name</label>
                        <input type="text" name="lname" id="create_lname" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Guard Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editGuardForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Guard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
  document.getElementById('editModal').addEventListener('show.bs.modal', function(event) {
    let button = event.relatedTarget;
    let modal = this;
    let form = modal.querySelector('form');

    // Set the action URL for the form
    form.action = `/admin/guard/${button.getAttribute('data-id')}`;

    // Populate the form fields with the selected guard's data
    modal.querySelector('[name="username"]').value = button.getAttribute('data-username');
    modal.querySelector('[name="email"]').value = button.getAttribute('data-email');
    modal.querySelector('[name="phone"]').value = button.getAttribute('data-phone');
});
</script>

@endsection
