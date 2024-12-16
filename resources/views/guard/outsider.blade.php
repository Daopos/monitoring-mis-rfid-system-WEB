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
                                <button
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editOutsiderModal{{ $outsider->id }}"
                                    @if($outsider->out) disabled @endif
                                >
                                    Edit
                                </button>
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
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for Viewing Outsider Details -->
                        <div class="modal fade" id="viewOutsiderModal{{ $outsider->id }}" tabindex="-1" aria-labelledby="viewOutsiderModalLabel{{ $outsider->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewOutsiderModalLabel{{ $outsider->id }}">Service Providers Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6>Name:</h6>
                                                <p>{{ $outsider->name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Type:</h6>
                                                <p>{{ $outsider->type }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6>Vehicle Type:</h6>
                                                <p>{{ $outsider->vehicle_type ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Brand:</h6>
                                                <p>{{ $outsider->brand ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6>Color:</h6>
                                                <p>{{ $outsider->color ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Model:</h6>
                                                <p>{{ $outsider->model ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6>Plate Number:</h6>
                                                <p>{{ $outsider->plate_number ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Entry Time:</h6>
                                                <p>{{ $outsider->in }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6>Exit Time:</h6>
                                                <p>{{ $outsider->out ?? 'N/A' }}</p>
                                            </div>
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

            {{ $outsiders->links() }}


           <!-- Modal for Creating Outsider -->
<div class="modal fade" id="createOutsiderModal" tabindex="-1" aria-labelledby="createOutsiderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('outsiders.store') }}" method="POST">
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
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select name="type" class="form-control" required>
                            <option value="" disabled selected>Select a type</option>
                            <option value="Type1" {{ old('type') == 'Type1' ? 'selected' : '' }}>Construction</option>
                            <option value="Type2" {{ old('type') == 'Type2' ? 'selected' : '' }}>Type2</option>
                            <option value="Type3" {{ old('type') == 'Type3' ? 'selected' : '' }}>Type3</option>
                        </select>
                        @error('type')
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
@endsection
