@extends('layouts.adminlayout')

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Guest service</h1>

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
                  <form method="GET" action="{{ route('admin.outsiders') }}" id="searchForm">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ $search }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <form method="GET" action="{{ route('admin.outsiders') }}" id="dateFilterForm">
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
                            </td>
                        </tr>


                        <!-- Modal for Viewing Outsider Details -->
                        <div class="modal fade" id="viewOutsiderModal{{ $outsider->id }}" tabindex="-1" aria-labelledby="viewOutsiderModalLabel{{ $outsider->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewOutsiderModalLabel{{ $outsider->id }}">Outsider Details</h5>
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
        </div>
    </div>
</div>
@endsection
