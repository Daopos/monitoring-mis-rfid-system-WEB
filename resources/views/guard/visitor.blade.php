@extends('layouts.guardlayout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Visitors</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addVisitorModal">
        Add Visitor
    </button>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('guard.visitor') }}" method="GET">
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or Homeowner name" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Visitors</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Visitor Name</th>
                            <th>Plate Number</th>
                            <th>Homeowner Name</th>
                            <th>Relationship</th>
                            <th>RFID Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visitors as $visitor)
                            <tr>
                                <td>{{ $loop->iteration + ($visitors->currentPage() - 1) * $visitors->perPage() }}</td>
                                <td>{{ $visitor->name }}</td>
                                <td>{{ $visitor->plate_number ?? 'N/A' }}</td>
                                <td>{{ $visitor->homeowner ? $visitor->homeowner->fname . ' ' . $visitor->homeowner->lname : 'N/A' }}</td>
                                <td>{{ $visitor->relationship ?? 'N/A' }}</td>
                                <td>{{ ucfirst($visitor->status) }}</td>
                                <td>
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $visitor->id }}">
                                        View Details
                                    </button>
                                    @if ($visitor->status === 'pending')
                                        <!-- Approve and Reject Actions -->
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $visitor->id }}">Approve</button>
                                        <form action="{{ route('guard.deny', $visitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    @elseif ($visitor->status === 'requested')
                                        <!-- Delete Action -->
                                        <form action="{{ route('guard.delete', $visitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Delete</button>
                                        </form>
                                    @elseif ($visitor->status === 'return')
                                        <!-- Approve New RFID for Returning Visitor -->
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal{{ $visitor->id }}">
                                            Assign New RFID
                                        </button>
                                    @else
                                        {{-- <form action="{{ route('guard.return', $visitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="badge bg-secondary">Already Processed</button>
                                        </form> --}}
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="detailsModal{{ $visitor->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $visitor->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailsModalLabel{{ $visitor->id }}">
                                                Visitor Details: {{ $visitor->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                         <h6>Representative:</h6>
                                            <ul class="list-group">
                                             <li class="list-group-item">
                                                 <strong>Valid ID:</strong>
                                                 <img src="{{ asset('storage/' . $visitor->valid_id) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                             </li>
                                             <li class="list-group-item">
                                                 <strong>Profile Image:</strong>
                                                 <img src="{{ asset('storage/' . $visitor->profile_img) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                             </li>
                                                <li class="list-group-item"><strong>Relationship:</strong> {{ $visitor->relationship ?? 'N/A' }}</li>
                                                @if($visitor->brand)
                                                <li class="list-group-item"><strong>Brand:</strong> {{ $visitor->brand }}</li>
                                            @endif

                                            @if($visitor->color)
                                                <li class="list-group-item"><strong>Color:</strong> {{ $visitor->color }}</li>
                                            @endif

                                            @if($visitor->model)
                                                <li class="list-group-item"><strong>Model:</strong> {{ $visitor->model }}</li>
                                            @endif

                                            @if($visitor->plate_number)
                                                <li class="list-group-item"><strong>Plate Number:</strong> {{ $visitor->plate_number }}</li>
                                            @endif

                                                <li class="list-group-item"><strong>Date of Visit:</strong> {{ $visitor->date_visit ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Id Type:</strong> {{ $visitor->type_id ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>RFID:</strong> {{ $visitor->rfid ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($visitor->status) }}</li>
                                                <li class="list-group-item"><strong>Guard Approval:</strong> {{ $visitor->guard ? 'Approved' : 'Pending' }}</li>
                                            </ul>
                                            <hr />
                                            <h6>Members:</h6>
                                            <ul class="list-group">
                                                @foreach ($visitor->visitorGroups as $group)
                                                    <li class="list-group-item">
                                                        <strong>Name:</strong> {{ $group->name ?? 'N/A' }}<br />
                                                        <strong>ID Type:</strong> {{ $group->type_id ?? 'N/A' }}<br />
                                                        <strong>Valid ID:</strong>
                                                        @if ($group->valid_id)
                                                            <img src="{{ asset('storage/' . $group->valid_id) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                                        @else
                                                            N/A
                                                        @endif
                                                        <br />
                                                        <strong>Profile Image:</strong>
                                                        @if ($group->profile_img)
                                                            <img src="{{ asset('storage/' . $group->profile_img) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                                        @else
                                                            N/A
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Approve/Assign RFID Modal -->
                            <div class="modal fade" id="approveModal{{ $visitor->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $visitor->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel{{ $visitor->id }}">
                                                @if ($visitor->status === 'return')
                                                    Assign New RFID for {{ $visitor->name }}
                                                @else
                                                    Approve RFID for {{ $visitor->name }}
                                                @endif
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('guard.approve', $visitor->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="rfid" class="form-label">RFID</label>
                                                    <input type="text" name="rfid" class="form-control" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">
                                                    @if ($visitor->status === 'return')
                                                        Assign RFID
                                                    @else
                                                        Approve
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $visitors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Visitor Modal -->
<div class="modal fade" id="addVisitorModal" tabindex="-1" aria-labelledby="addVisitorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVisitorModalLabel">Add New Visitors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('guard.storeVisitor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Representative Section -->
                    <!-- Representative Section -->
<div class="mb-3">
    <h6>Representative</h6>
  <!-- Search Input -->
    <!-- Homeowner Selection -->
    <label for="homeownerSearch" class="form-label">Homeowner</label>
    <!-- Searchable Homeowner Input -->
    <div class="position-relative">
        <input type="text" id="homeownerSearch" class="form-control" placeholder="Search Homeowner..." autocomplete="off" onkeyup="filterHomeowners()">

        <!-- Dropdown list container -->
        <div id="homeownerDropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;">
            @foreach ($homeowners as $homeowner)
                <div class="dropdown-item" onclick="selectHomeowner(this)" data-value="{{ $homeowner->id }}">
                    {{ $homeowner->fname }} {{ $homeowner->lname }}
                </div>
            @endforeach
        </div>
    </div>

    <!-- Hidden input to store selected homeowner's ID -->
    <input type="hidden" name="home_owner_id" id="selectedHomeownerId" required>

    <!-- Representative Name -->
    <label for="representative_name" class="form-label">Name</label>
    <input type="text" name="representative[name]" class="form-control" required>

    <!-- Relationship Dropdown -->
    <label for="representative_relationship" class="form-label">Relationship</label>
    <select name="representative[relationship]" class="form-control" required>
        <option value="" disabled selected>Select Relationship</option>
        <option value="Family">Family</option>
        <option value="Friend">Friend</option>
        <option value="Colleague">Colleague</option>
        <option value="Business Partner">Business Partner</option>
        <option value="Other">Other</option>
    </select>

    <!-- Other Fields -->
    <label for="representative_brand" class="form-label">Brand</label>
    <input type="text" name="representative[brand]" class="form-control">

    <label for="representative_color" class="form-label">Color</label>
    <input type="text" name="representative[color]" class="form-control">

    <label for="representative_model" class="form-label">Model</label>
    <input type="text" name="representative[model]" class="form-control">

    <label for="representative_plate_number" class="form-label">Plate Number</label>
    <input type="text" name="representative[plate_number]" class="form-control">

    <label for="representative_type_id" class="form-label">Type ID</label>
<select name="representative[type_id]" class="form-control" required>
    <option value="">Select ID</option>
    <option value="Driver License">Driver License</option>
    <option value="Postal ID">Postal ID</option>
    <option value="Voter's ID">Voter's ID</option>
    <option value="Senior Citizen ID">Senior Citizen ID</option>
    <option value="Student ID">Student ID</option>
    <option value="Employee ID">Employee ID</option>
    <option value="SSS ID">SSS ID</option>
    <option value="PRC ID">PRC ID</option>
</select>


    <label for="representative_reason" class="form-label">Reason</label>
    <textarea name="representative[reason]" class="form-control" rows="3" required></textarea>

    <label for="representative_profile_img" class="form-label">Profile Image</label>
    <input type="file" name="representative[profile_img]" class="form-control" accept="image/*" required>

    <label for="representative_valid_id" class="form-label">Valid ID</label>
    <input type="file" name="representative[valid_id]" class="form-control" accept="image/*" required>
</div>

                    <!-- Members Section -->
                    <div id="visitorGroupContainer">
                        <div class="visitor-group mb-3">
                            <h6>Member</h6>
                            <label for="visitor_name" class="form-label">Name</label>
                            <input type="text" name="members[0][name]" class="form-control" required>

                            <label for="type_id" class="form-label">Type ID</label>
                            <label for="type_id" class="form-label">Type ID</label>
                            <select name="members[0][type_id]" class="form-control" required>
                                <option value="">Select ID</option>
                                <option value="Driver License">Driver License</option>
                                <option value="Postal ID">Postal ID</option>
                                <option value="Voter's ID">Voter's ID</option>
                                <option value="Senior Citizen ID">Senior Citizen ID</option>
                                <option value="Student ID">Student ID</option>
                                <option value="Employee ID">Employee ID</option>
                                <option value="SSS ID">SSS ID</option>
                                <option value="PRC ID">PRC ID</option>
                            </select>

                            <label for="valid_id" class="form-label">Valid ID</label>
                            <input type="file" name="members[0][valid_id]" class="form-control" accept="image/*" required>

                            <label for="profile_img" class="form-label">Profile Image</label>
                            <input type="file" name="members[0][profile_img]" class="form-control" accept="image/*" required>

                            <button type="button" class="btn btn-danger mt-2 remove-visitor-group">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary mb-3" id="addVisitorGroup">Add Another Member</button>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                      </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
  let visitorIndex = 1;
document.getElementById('addVisitorGroup').addEventListener('click', function () {
    const container = document.getElementById('visitorGroupContainer');
    const newGroup = document.createElement('div');
    newGroup.classList.add('visitor-group', 'mb-3');
    newGroup.innerHTML = `
        <h6>Member</h6>
        <label for="visitor_name" class="form-label">Name</label>
        <input type="text" name="members[${visitorIndex}][name]" class="form-control" required>

        <label for="type_id" class="form-label">Type ID</label>
        <select name="members[${visitorIndex}][type_id]" class="form-control" required>
                <option value="">Select ID</option>
                <option value="Driver License">Driver License</option>
                <option value="Postal ID">Postal ID</option>
                <option value="Voter's ID">Voter's ID</option>
                <option value="Senior Citizen ID">Senior Citizen ID</option>
                <option value="Student ID">Student ID</option>
                <option value="Employee ID">Employee ID</option>
                <option value="SSS ID">SSS ID</option>
                <option value="PRC ID">PRC ID</option>
        </select>

        <label for="valid_id" class="form-label">Valid ID</label>
        <input type="file" name="members[${visitorIndex}][valid_id]" class="form-control" accept="image/*" required>

        <label for="profile_img" class="form-label">Profile Image</label>
        <input type="file" name="members[${visitorIndex}][profile_img]" class="form-control" accept="image/*" required>

        <button type="button" class="btn btn-danger mt-2 remove-visitor-group">Remove</button>
    `;
    container.appendChild(newGroup);
    visitorIndex++;
});


    document.getElementById('visitorGroupContainer').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-visitor-group')) {
            e.target.parentElement.remove();
        }
    });


    const inputField = document.getElementById('homeownerSearch');
const dropdown = document.getElementById('homeownerDropdown');
const hiddenInput = document.getElementById('selectedHomeownerId');

// Show dropdown when clicking the input
inputField.addEventListener('focus', () => {
    dropdown.style.display = 'block';
});

// Hide dropdown when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.position-relative')) {
        dropdown.style.display = 'none';
    }
});

// Filter homeowners based on input
function filterHomeowners() {
    const searchValue = inputField.value.toLowerCase();
    const items = dropdown.querySelectorAll('.dropdown-item');

    items.forEach(item => {
        if (item.textContent.toLowerCase().includes(searchValue)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });

    // Show dropdown if there's input
    dropdown.style.display = searchValue ? 'block' : 'none';
}

// Select homeowner from the dropdown
function selectHomeowner(element) {
    inputField.value = element.textContent.trim(); // Clean text
    hiddenInput.value = element.getAttribute('data-value');
    dropdown.style.display = 'none';
}


</script>

@endsection
