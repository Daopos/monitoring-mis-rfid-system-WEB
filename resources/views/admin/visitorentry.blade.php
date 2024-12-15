@extends('layouts.adminlayout')

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="p-2">
            <h1 class="display-4">Visitor Gate Entry List</h1>
            <p class="lead">Total entries: {{ $totalEntries }}</p>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Search and Filter</h5>
                <a href="{{ route('guard.visitorgatelist.pdf', request()->all()) }}" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>
            <div class="card-body">
                <div class="list">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.visitors') }}" id="searchForm">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>

                    <!-- Status Filter Form -->
                    <form method="GET" action="{{ route('admin.visitors') }}" id="statusFilterForm">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select name="status" class="form-control" onchange="document.getElementById('statusFilterForm').submit();">
                                    <option value="">All</option>
                                    <option value="in" {{ request('status') == 'in' ? 'selected' : '' }}>Currently In</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Date Filter Form -->
                    <form method="GET" action="{{ route('admin.visitors') }}" id="dateFilterForm">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Entry Table -->
                    <table class="table table-bordered table-striped mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Homeowner</th>
                                <th scope="col">Email</th>
                                <th scope="col">Entry</th>
                                <th scope="col">Out</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gateMonitors as $gateMonitor)
                                <tr>
                                    <td>{{ $gateMonitor->visitor->name }}</td>
                                    <td>{{ $gateMonitor->visitor->homeOwner->fname }} {{ $gateMonitor->visitor->homeOwner->lname }}</td>
                                    <td>{{ $gateMonitor->visitor->homeOwner->email }}</td>
                                    <td>{{ \Carbon\Carbon::parse($gateMonitor->in)->format('F j, Y g:i A') }}</td>
                                    <td>{{ $gateMonitor->out ? \Carbon\Carbon::parse($gateMonitor->out)->format('F j, Y g:i A') : 'N/A' }}</td>
                                    <td>
                                        <!-- View Details Button -->
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#visitorModal-{{ $gateMonitor->visitor->id }}">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $gateMonitors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Template for each visitor -->
    @foreach ($gateMonitors as $gateMonitor)
        <div class="modal fade" id="visitorModal-{{ $gateMonitor->visitor->id }}" tabindex="-1" role="dialog" aria-labelledby="visitorModalLabel-{{ $gateMonitor->visitor->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visitorModalLabel-{{ $gateMonitor->visitor->id }}">Visitor Details - {{ $gateMonitor->visitor->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Brand:</strong> {{ $gateMonitor->visitor->brand }}</p>
                        <p><strong>Color:</strong> {{ $gateMonitor->visitor->color }}</p>
                        <p><strong>Model:</strong> {{ $gateMonitor->visitor->model }}</p>
                        <p><strong>Plate Number:</strong> {{ $gateMonitor->visitor->plate_number }}</p>
                        <p><strong>RFID:</strong> {{ $gateMonitor->visitor->rfid }}</p>
                        <p><strong>Relationship:</strong> {{ $gateMonitor->visitor->relationship }}</p>
                        <p><strong>Date of Visit:</strong> {{ \Carbon\Carbon::parse($gateMonitor->visitor->date_visit)->format('F j, Y') }}</p>
                        <p><strong>Number of Visitors:</strong> {{ $gateMonitor->visitor->number_vistiors }}</p>
                        <p><strong>Status:</strong> {{ $gateMonitor->visitor->status }}</p>
                    </div>
                    <div class="row p-1">
                        <div class="col-md-6 text-center">
                            <h6>In Image</h6>
                            <img src="{{ asset('storage/' . $gateMonitor->in_img) }}" alt="In Image" class="img-fluid" width="500">
                        </div>
                        <div class="col-md-6 text-center">
                            <h6>Out Image</h6>
                            <img src="{{ asset('storage/' . $gateMonitor->out_img) }}" alt="Out Image" class="img-fluid" width="500">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
