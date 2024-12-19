@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h2>Officers Management</h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="col-md-3 mt-3">
    <form method="GET" action="{{ route('officers.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search by Name" value="{{ request()->get('search') }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>
</div>

    <!-- Add Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#officerModal" onclick="clearForm()">Add Officer</button>

    <!-- Officers Table -->
    <table class="table table-bordered" id="officerTable">
        <thead>
            <tr>
                <th>Homeowner Name</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($officers as $officer)
<tr>
    <td>{{ $officer->homeowner->fname }} {{ $officer->homeowner->lname }}</td>
    <td>{{ $officer->position }}</td>
    <td>
        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOfficerModal"
   onclick="editOfficer({{ $officer->id }}, {{ $officer->homeowner_id }}, '{{ $officer->position }}')">Edit</a>

        <form action="{{ route('officers.destroy', $officer->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to Remove this officer?')">Remove</button>
        </form>
    </td>
</tr>
@endforeach

        </tbody>
    </table>
</div>

<!-- Officer Modal -->
<div class="modal fade" id="officerModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Officer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="officerForm" method="POST" action="{{ route('officers.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="officer_id" name="officer_id">
                    <div class="mb-3 position-relative">
                        <label for="homeowner_search" class="form-label">Homeowner</label>
                        <input type="text" class="form-control" id="homeowner_search" name="homeowner_search" placeholder="Search Homeowner" onkeyup="filterHomeowners('add')" autocomplete="off">
                        <ul class="list-group position-absolute w-100 mt-2 d-none" id="homeowner_list_add">
                            @foreach($homeowners as $homeowner)
                                <li class="list-group-item list-group-item-action" onclick="selectHomeowner('add', {{ $homeowner->id }}, '{{ $homeowner->fname }} {{ $homeowner->lname }}')">
                                    {{ $homeowner->fname }} {{ $homeowner->lname }}
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" id="homeowner_id" name="homeowner_id">
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <select class="form-select" id="position" name="position" required>
                            <option value="">Select Position</option>
                            <!-- Positions -->
                            @foreach(['President', 'Vice President', 'Secretary', 'Asst. Secretary', 'Treasurer', 'Asst. Treasurer', 'Auditors', 'Sgt. at Arms', 'P.R.O', 'Business Managers'] as $position)
                                <option value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Officer Modal -->
<div class="modal fade" id="editOfficerModal" tabindex="-1" aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Edit Officer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editOfficerForm" method="POST" action="{{ route('officers.update', 'officer_id') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_officer_id" name="officer_id">
                    <div class="mb-3 position-relative">
                        <label for="edit_homeowner_search" class="form-label">Homeowner</label>
                        <input type="text" class="form-control" id="edit_homeowner_search" name="edit_homeowner_search" placeholder="Search Homeowner" onkeyup="filterHomeowners('edit')" autocomplete="off">
                        <ul class="list-group position-absolute w-100 mt-2 d-none" id="homeowner_list_edit">
                            @foreach($homeowners as $homeowner)
                                <li class="list-group-item list-group-item-action" onclick="selectHomeowner('edit', {{ $homeowner->id }}, '{{ $homeowner->fname }} {{ $homeowner->lname }}')">
                                    {{ $homeowner->fname }} {{ $homeowner->lname }}
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" id="edit_homeowner_id" name="homeowner_id">
                    </div>
                    <div class="mb-3">
                        <label for="edit_position" class="form-label">Position</label>
                        <select class="form-select" id="edit_position" name="position" required>
                            <option value="">Select Position</option>
                            @foreach(['President', 'Vice President', 'Secretary', 'Asst. Secretary', 'Treasurer', 'Asst. Treasurer', 'Auditors', 'Sgt. at Arms', 'P.R.O', 'Business Managers'] as $position)
                                <option value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
function clearForm() {
    $('#officerForm')[0].reset();
    $('#officer_id').val('');
    $('#modalTitle').text('Add Officer');
}

function editOfficer(id, homeowner_id, position) {
    document.getElementById('editModalTitle').textContent = 'Edit Officer';

    // Populate the form with the officer's data
    document.getElementById('edit_officer_id').value = id;
    document.getElementById('edit_position').value = position;

    // Set the correct homeowner ID in the dropdown
    document.getElementById('edit_homeowner_id').value = homeowner_id;

    // Set the form action for the update route
    document.getElementById('editOfficerForm').setAttribute('action', `/officers/${id}`);

    // Show the Edit Officer modal
    var editModal = new bootstrap.Modal(document.getElementById('editOfficerModal'));
    editModal.show();
}
function filterHomeowners(modalType) {
    const searchInput = document.getElementById(modalType === 'add' ? 'homeowner_search' : 'edit_homeowner_search');
    const list = document.getElementById(modalType === 'add' ? 'homeowner_list_add' : 'homeowner_list_edit');
    const filter = searchInput.value.toLowerCase();
    const items = list.querySelectorAll('li');

    // Show the list if there is input
    if (filter) {
        list.classList.remove('d-none');
    } else {
        list.classList.add('d-none');
    }

    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function selectHomeowner(modalType, id, name) {
    const searchInput = document.getElementById(modalType === 'add' ? 'homeowner_search' : 'edit_homeowner_search');
    const hiddenInput = document.getElementById(modalType === 'add' ? 'homeowner_id' : 'edit_homeowner_id');
    const list = document.getElementById(modalType === 'add' ? 'homeowner_list_add' : 'homeowner_list_edit');

    // Set the selected homeowner's name in the search box
    searchInput.value = name;

    // Set the selected homeowner's ID in the hidden input
    hiddenInput.value = id;

    // Hide the dropdown list
    list.classList.add('d-none');
}
</script>
@endsection
