@extends('layouts.guardlayout')

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="display-4">Service Providers</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createOutsiderModal">Create New</button>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Search and Filter</h5>
            <a href="{{ route('guard.generateOutsiderPdf', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>
        <div class="card-body">

                  <!-- Search Form -->
                  <form method="GET" action="{{ route('outsiders.index') }}" id="searchForm">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ $search }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <form method="GET" action="{{ route('outsiders.index') }}" id="dateFilterForm">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" name="from_date" class="form-control" value="{{ $from_date }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="to_date" class="form-control" value="{{ $to_date }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

            <!-- Outsider Entries Table -->
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Entry</th>
                        <th>Out</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outsiders as $outsider)
                        <tr>
                            <td>{{ $outsider->name }}</td>
                            <td>{{ $outsider->type }}</td>
                            <td>{{ \Carbon\Carbon::parse($outsider->in)->format('F j, Y g:i A') }}</td>
                            <td>{{ $outsider->out ? \Carbon\Carbon::parse($outsider->out)->format('F j, Y g:i A') : 'N/A' }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewOutsiderModal{{ $outsider->id }}">
                                    View Details
                                </button>
                                {{-- <button
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editOutsiderModal{{ $outsider->id }}"
                                    @if($outsider->out) disabled @endif
                                >
                                    Edit
                                </button> --}}
                                <form action="{{ route('outsiders.updateOut', $outsider->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="btn btn-info btn-sm"
                                        onclick="return confirm('Are you sure you want to set the out time?')"
                                        @if($outsider->out) disabled @endif
                                    >
                                        Set Out
                                    </button>
                                </form>
                                <form action="{{ route('outsiders.destroy', $outsider) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" @if($outsider->out) disabled @endif>Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal for Editing Outsider -->
                        <div class="modal fade" id="editOutsiderModal{{ $outsider->id }}" tabindex="-1" aria-labelledby="editOutsiderModalLabel{{ $outsider->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('outsiders.update', $outsider->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editOutsiderModalLabel{{ $outsider->id }}">Edit Outsider</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name{{ $outsider->id }}">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $outsider->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="type{{ $outsider->id }}">Type</label>
                                                <input type="text" name="type" class="form-control" value="{{ $outsider->type }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="vehicle_type{{ $outsider->id }}">Vehicle Type</label>
                                                <input type="text" name="vehicle_type" class="form-control" value="{{ $outsider->vehicle_type }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="brand{{ $outsider->id }}">Brand</label>
                                                <input type="text" name="brand" class="form-control" value="{{ $outsider->brand }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="color{{ $outsider->id }}">Color</label>
                                                <input type="text" name="color" class="form-control" value="{{ $outsider->color }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="model{{ $outsider->id }}">Model</label>
                                                <input type="text" name="model" class="form-control" value="{{ $outsider->model }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="plate_number{{ $outsider->id }}">Plate Number</label>
                                                <input type="text" name="plate_number" class="form-control" value="{{ $outsider->plate_number }}">
                                            </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="viewOutsiderModal{{ $outsider->id }}" tabindex="-1" aria-labelledby="viewOutsiderModalLabel{{ $outsider->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewOutsiderModalLabel{{ $outsider->id }}">Service Provider Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Service Provider Details -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6><strong>Name:</strong></h6>
                                                <p>{{ $outsider->name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><strong>Type:</strong></h6>
                                                <p>{{ $outsider->type }}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6><strong>Vehicle Type:</strong></h6>
                                                <p>{{ $outsider->vehicle_type ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><strong>Brand:</strong></h6>
                                                <p>{{ $outsider->brand ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6><strong>Color:</strong></h6>
                                                <p>{{ $outsider->color ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><strong>Model:</strong></h6>
                                                <p>{{ $outsider->model ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6><strong>Plate Number:</strong></h6>
                                                <p>{{ $outsider->plate_number ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><strong>Entry Time:</strong></h6>
                                                <p>{{ $outsider->in }}</p>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6><strong>Exit Time:</strong></h6>
                                                <p>{{ $outsider->out ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <!-- Loop through the associated outsiderGroups -->
                                        <h5 class="mt-4 mb-3">
                                            Group Details <span class="badge bg-primary">{{ $outsider->outsiderGroups->count() }}</span>
                                        </h5>
                                        <div class="d-flex flex-wrap gap-5">
                                        @foreach ($outsider->outsiderGroups as $group)
                                            <div class="mb-4">
                                                <h6><strong>Member Name:</strong></h6>
                                                <p>{{ $group->name }}</p>

                                                <h6><strong>Type ID:</strong></h6>
                                                <p>{{ $group->type_id }}</p>

                                                <h6><strong>Valid ID:</strong></h6>
                                                <img src="{{ Storage::url($group->valid_id) }}" alt="Valid ID" class="img-fluid mb-2" style="max-width: 100px;">

                                                <h6><strong>Profile Image:</strong></h6>
                                                <img src="{{ Storage::url($group->profile_img) }}" alt="Profile Image" class="img-fluid mb-2" style="max-width: 100px;">
                                            </div>
                                        @endforeach
                                    </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>

<div class="d-flex justify-content-center">
    {{ $outsiders->links() }}
</div>

           <!-- Modal for Creating Outsider -->
<div class="modal fade" id="createOutsiderModal" tabindex="-1" aria-labelledby="createOutsiderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('outsiders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createOutsiderModalLabel">Create New Service Providers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Name Input -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type Input -->
                   <!-- Type Input -->
<div class="form-group">
    <label for="type">Type</label>
    <select name="type" id="type" class="form-control" required onchange="toggleOtherTypeInput(this)">
        <option value="" disabled selected>Select a type</option>
        <option value="Construction" {{ old('type') == 'Construction' ? 'selected' : '' }}>Construction</option>
        <option value="Wifi Installation" {{ old('type') == 'Wifi Installation' ? 'selected' : '' }}>Wifi Installation</option>
        <option value="Delivery" {{ old('type') == 'Delivery' ? 'selected' : '' }}>Delivery</option>
        <option value="Vendor" {{ old('type') == 'Vendor' ? 'selected' : '' }}>Vendor</option>
        <option value="Plumber" {{ old('type') == 'Plumber' ? 'selected' : '' }}>Plumber</option>
        <option value="Electrician" {{ old('type') == 'Electrician' ? 'selected' : '' }}>Electrician</option>
        <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>
    @error('type')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Other Type Input -->
<div class="form-group" id="otherTypeInput" style="display: none;">
    <label for="other_type">Specify Other Type</label>
    <input type="text" name="other_type" id="other_type" class="form-control" value="{{ old('other_type') }}">
    @error('other_type')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>


                    <!-- Vehicle Type Input -->
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type</label>
                        <input type="text" name="vehicle_type" class="form-control" value="{{ old('vehicle_type') }}">
                        @error('vehicle_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Brand Input -->
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                        @error('brand')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Color Input -->
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color') }}">
                        @error('color')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Model Input -->
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model') }}">
                        @error('model')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Plate Number Input -->
                    <div class="form-group">
                        <label for="plate_number">Plate Number</label>
                        <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number') }}">
                        @error('plate_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type_id">Type Id</label>
                        <select name="type_id" class="form-control" required>
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
                        @error('type_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="valid_id">Valid ID</label>

                        <input type="file" name="valid_id" class="form-control" accept="image/*" required>
                        @error('valid_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="profile_img">Profile Image</label>
                        <input type="file" name="profile_img" class="form-control" accept="image/*" required>
                        @error('profile_img')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" id="addMemberButton">Add Member</button>

                    <!-- Member Fields Container -->
                    <div id="membersContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create </button>
                </div>
            </form>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

<script>
    let memberCount = 0;

    document.getElementById('addMemberButton').addEventListener('click', function() {
        memberCount++;

        // Create a new member fieldset
        const memberFieldset = document.createElement('div');
        memberFieldset.classList.add('memberFields');
        memberFieldset.innerHTML = `
            <div class="card mb-3">
                <div class="card-header">Member ${memberCount}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="member_name_${memberCount}">Member Name</label>
                        <input type="text" name="members[${memberCount}][name]" class="form-control" id="member_name_${memberCount}" required>
                    </div>
                    <div class="form-group">
    <label for="member_type_id_${memberCount}">Type ID</label>
    <select name="members[${memberCount}][type_id]" class="form-control" id="member_type_id_${memberCount}" required>
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
</div>

                    <div class="form-group">
                        <label for="member_valid_id_${memberCount}">Valid ID</label>
                        <input type="file" name="members[${memberCount}][valid_id]" class="form-control" id="member_valid_id_${memberCount}" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="member_profile_img_${memberCount}">Profile Image</label>
                        <input type="file" name="members[${memberCount}][profile_img]" class="form-control" id="member_profile_img_${memberCount}" accept="image/*">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm removeMemberButton" data-member-id="${memberCount}">Remove Member</button>
                </div>
            </div>
        `;

        document.getElementById('membersContainer').appendChild(memberFieldset);

        // Add remove functionality
        memberFieldset.querySelector('.removeMemberButton').addEventListener('click', function() {
            const memberId = this.getAttribute('data-member-id');
            memberCount--;  // Decrement the member count
            document.getElementById(`member_name_${memberId}`).closest('.memberFields').remove();
        });
    });

    function toggleOtherTypeInput(selectElement) {
        const otherTypeInput = document.getElementById('otherTypeInput');
        if (selectElement.value === 'Other') {
            otherTypeInput.style.display = 'block';
            document.getElementById('other_type').required = true;
        } else {
            otherTypeInput.style.display = 'none';
            document.getElementById('other_type').required = false;
        }
    }
</script>

@endsection
