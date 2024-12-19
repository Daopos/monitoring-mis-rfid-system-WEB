@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h2>Requesting permit</h2>

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

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="permitTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="requests-tab" data-bs-toggle="tab" href="#requests" role="tab" aria-controls="requests" aria-selected="true">Requests</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approved-tab" data-bs-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">Approved</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="rejected-tab" data-bs-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">Rejected</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="permitTabsContent">
        <!-- Requests Tab -->
        <div class="tab-pane fade show active" id="requests" role="tabpanel" aria-labelledby="requests-tab">
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
                    @foreach($applicants->where('status', 'request') as $applicant)
                        <tr>
                            <td>{{ $applicant->homeowner->fname ?? 'N/A' }} {{ $applicant->homeowner->lname ?? 'N/A' }}</td>
                    <td>{{ $applicant->project_description }}</td>
                            <td>{{ $applicant->application_date }}</td>
                            <td>{{ $applicant->mobilization_date }}</td>
                            <td>{{ $applicant->completion_date }}</td>
                            <td>{{ $applicant->status }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#neighborsModal" onclick="viewNeighbors({{ json_encode($applicant->neighbors) }})">
                                    View details
                                </button>
                                <a href="{{ route('applicant.approve', $applicant->id) }}" class="btn btn-success btn-sm ms-2" onclick="return confirm('Are you sure you want to approve this applicant?')">
                                    Approve
                                </a>
                                <a href="{{ route('applicant.reject', $applicant->id) }}" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Are you sure you want to reject this applicant?')">
                                    Reject
                                </a>
                                <a href="{{ route('applicant.print', $applicant->id) }}" class="btn btn-warning btn-sm ms-2" target="_blank">
                                    Print
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Rejected Tab -->
        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
            <table class="table table-bordered" id="rejectedTable">
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
                    @foreach($applicants->where('status', 'Rejected') as $applicant)
                        <tr>
                            <td>{{ $applicant->homeowner->fname ?? 'N/A' }} {{ $applicant->homeowner->lname ?? 'N/A' }}</td>
                    <td>{{ $applicant->project_description }}</td>

                            <td>{{ $applicant->application_date }}</td>
                            <td>{{ $applicant->mobilization_date }}</td>
                            <td>{{ $applicant->completion_date }}</td>
                            <td>{{ $applicant->status }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#neighborsModal" onclick="viewNeighbors({{ json_encode($applicant->neighbors) }})">
                                    View details
                                </button>
                                {{-- <a href="{{ route('applicant.print', $applicant->id) }}" class="btn btn-warning btn-sm ms-2" target="_blank">
                                    Print
                                </a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Approved Tab -->
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            <table class="table table-bordered" id="approvedTable">
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
                    @foreach($applicants->where('status', 'Approved') as $applicant)
                        <tr>
                            <td>{{ $applicant->homeowner->fname ?? 'N/A' }} {{ $applicant->homeowner->lname ?? 'N/A' }}</td>
                    <td>{{ $applicant->project_description }}</td>

                            <td>{{ $applicant->application_date }}</td>
                            <td>{{ $applicant->mobilization_date }}</td>
                            <td>{{ $applicant->completion_date }}</td>
                            <td>{{ $applicant->status }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#neighborsModal" onclick="viewNeighbors({{ json_encode($applicant->neighbors) }})">
                                    View details
                                </button>
                                <a href="{{ route('applicant.print', $applicant->id) }}" class="btn btn-warning btn-sm ms-2" target="_blank">
                                    Print
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
