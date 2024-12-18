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
    <!-- Add Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#officerModal" onclick="clearForm()">Add Officer</button>

    <!-- Officers Table -->
    <table class="table table-bordered" id="officerTable">
        <thead>
            <tr>
                <th>Homeowner</th>
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
        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#officerModal"
           onclick="editOfficer(@json($officer))">Edit</a>
        <form action="{{ route('officers.destroy', $officer->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this officer?')">Delete</button>
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
                    <div class="mb-3">
                        <label for="homeowner_id" class="form-label">Homeowner</label>
                        <select class="form-select" id="homeowner_id" name="homeowner_id">
                            <option value="">Select Homeowner</option>
                            @foreach($homeowners as $homeowner)
                                <option value="{{ $homeowner->id }}">{{ $homeowner->fname }} {{ $homeowner->lname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <select class="form-select" id="position" name="position" required>
                            <option value="">Select Position</option>
                            <option value="President">President</option>
                            <option value="Vice President">Vice President</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Asst. Secretary">Asst. Secretary</option>
                            <option value="Treasurer">Treasurer</option>
                            <option value="Asst. Treasurer">Asst. Treasurer</option>
                            <option value="Auditors">Auditors</option>
                            <option value="Sgt. at Arms">Sgt. at Arms</option>
                            <option value="P.R.O">P.R.O</option>
                            <option value="Business Managers">Business Managers</option>
                            <!-- Add more positions as needed -->
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

<!-- Bootstrap 5 Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
function clearForm() {
    // Reset the form and change the modal title to 'Add Officer'
    $('#officerForm')[0].reset();
    $('#officer_id').val('');
    $('#modalTitle').text('Add Officer');
}

function editOfficer(officer) {
    // Populate the form with the officer's data and change the modal title
    $('#modalTitle').text('Edit Officer');
    $('#officer_id').val(officer.id);
    $('#homeowner_id').val(officer.homeowner_id);
    $('#position').val(officer.position);
    $('#officerForm').attr('action', `/officers/${officer.id}`);
}

</script>
@endsection
