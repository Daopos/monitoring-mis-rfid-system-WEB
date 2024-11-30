@extends('layouts.guardlayout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Visitor Requests</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addVisitorModal">
        Add Visitor
    </button>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('guard.visitor') }}" method="GET" >
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>


    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pending Visitor Requests</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
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
                                <td>{{ $visitor->name }}</td>
                                <td>{{ $visitor->plate_number ?? 'N/A' }}</td>
                                <td>{{ $visitor->homeowner ? $visitor->homeowner->fname . ' ' . $visitor->homeowner->lname : 'N/A' }}</td>
                                <td>{{ $visitor->relationship ?? 'N/A' }}</td>
                                <td>{{ $visitor->status ?? 'N/A' }}</td>
                                <td>
                                    @if ($visitor->status === 'pending')
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $visitor->id }}">Approve</button>

                                        <form action="{{ route('guard.deny', $visitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    @elseif ($visitor->status === 'requested')
                                        <form action="{{ route('guard.delete', $visitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Delete</button>
                                        </form>
                                    @else
                                        <form action="{{ route('guard.delete', $visitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="badge bg-secondary">Already Processed</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $visitor->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $visitor->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel{{ $visitor->id }}">Approve RFID for {{ $visitor->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('guard.approve', $visitor->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="rfid" class="form-label">RFID</label>
                                                    <input type="text" name="rfid" class="form-control" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Approve</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Visitor Modal -->
<div class="modal fade" id="addVisitorModal" tabindex="-1" aria-labelledby="addVisitorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVisitorModalLabel">Add New Visitor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('guard.storeVisitor') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="homeowner_id" class="form-label">Homeowner</label>
                        <select name="home_owner_id" id="homeowner_id" class="form-control" required>
                            <option value="">Select Homeowner</option>
                            @foreach ($homeowners as $homeowner)
                                <option value="{{ $homeowner->id }}">{{ $homeowner->fname }} {{ $homeowner->lname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="visitor_name" class="form-label">Visitor Name</label>
                        <input type="text" name="name" id="visitor_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="plate_number" class="form-label">Plate Number</label>
                        <input type="text" name="plate_number" id="plate_number" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="relationship" class="form-label">Relationship</label>
                        <select name="relationship" id="relationship" class="form-control" required>
                            <option value="">Select Relationship</option>
                            <option value="Family">Family</option>
                            <option value="Friend">Friend</option>
                            <option value="Colleague">Colleague</option>
                            <option value="Business Partner">Business Partner</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="number_vistiors" class="form-label">Number of Visitors</label>
                        <input type="number" name="number_vistiors" id="number_vistiors" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Visitor</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
