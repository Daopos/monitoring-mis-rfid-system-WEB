@extends('layouts.guardlayout')

@section('content')
<div class="container mt-5">
    <h2>Approved Permit</h2>

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

    <!-- Applicants Table -->
    <table class="table table-bordered" id="applicantTable">
        <thead>
            <tr>
                <th>Homeowner Name</th>
                <th>Project Description</th>
                <th>Application Date</th>
                <th>Mobilization Date</th>
                <th>Completion Date </th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applicants as $applicant)
                <tr>
                    <td>{{ $applicant->homeowner->fname ?? 'N/A' }} {{ $applicant->homeowner->lname ?? 'N/A' }}</td>
                    <td>{{ $applicant->project_description }}</td>
                    <td>{{ $applicant->application_date }}</td>
                    <td>{{ $applicant->mobilization_date }}</td>
                    <td>{{ $applicant->completion_date }}</td>
                    <td>{{ $applicant->status }}</td>
                    <td>
                        <!-- View Neighbors Button -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#neighborsModal" onclick="viewNeighbors({{ json_encode($applicant->neighbors) }})">
                            View details
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Neighbors Modal -->
<div class="modal fade" id="neighborsModal" tabindex="-1" aria-labelledby="neighborsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="neighborsModalLabel">Neighbors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="neighborsList">
                <!-- Neighbor details will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
   function viewNeighbors(neighbors) {
    // Clear the modal body before adding new content
    const modalBody = document.getElementById('neighborsList');
    modalBody.innerHTML = '';

    // Check if neighbors exist
    if (neighbors && neighbors.length > 0) {
        // Loop through neighbors and display their details
        neighbors.forEach(neighbor => {
            const neighborItem = document.createElement('div');
            neighborItem.classList.add('neighbor-item', 'mb-3'); // Adding spacing between items

            // Create the neighbor's name
            const name = document.createElement('p');
            name.textContent = `Neighbor Homeowner: ${neighbor.homeowner.fname ?? 'N/A'} ${neighbor.homeowner.lname ?? 'N/A'}`;
            neighborItem.appendChild(name);

            // Create the block, lot, and phase
            const blockLotPhase = document.createElement('p');
            blockLotPhase.innerHTML = ` ${neighbor.homeowner.block ?? 'N/A'}, ${neighbor.homeowner.lot ?? 'N/A'}, ${neighbor.homeowner.phase ?? 'N/A'}`;
            neighborItem.appendChild(blockLotPhase);

            // Append to the modal body
            modalBody.appendChild(neighborItem);
        });
    } else {
        modalBody.innerHTML = '<p>No neighbors available.</p>';
    }
}
</script>
@endsection
