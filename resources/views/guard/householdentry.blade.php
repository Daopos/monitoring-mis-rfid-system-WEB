@extends('layouts.guardlayout')

@section('styles')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="p-2">
            <h1 class="display-4">Household Entry List</h1>
            <p class="lead">Total entries: {{ $totalEntries }}</p>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Search and Filter</h5>
                <a href="{{ route('guard.householdentry.pdf', request()->all()) }}" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>
            <div class="card-body">
                <div class="list">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('guard.householdentry') }}" id="searchForm">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>


                    <!-- Date Filter Form -->
                    <form method="GET" action="{{ route('guard.householdentry') }}" id="dateFilterForm">
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
                                <th scope="col">Head of the Familys</th>
                                <th scope="col">Entry</th>
                                <th scope="col">Exit</th>
                                <th scope="col">Actions</th>
                                {{-- <th scope="col">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gateMonitors as $gateMonitor)
                                <tr>
                                    <td>{{ $gateMonitor->household->name }}</td>
                                    <td>
                                        @if($gateMonitor->household->homeOwner)
                                            {{ $gateMonitor->household->homeOwner->fname }} {{ $gateMonitor->household->homeOwner->lname }}
                                        @else
                                            No homeowner information available
                                        @endif
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($gateMonitor->in)->format('F j, Y g:i A') }}</td>
                                    <td>{{ $gateMonitor->out ? \Carbon\Carbon::parse($gateMonitor->out)->format('F j, Y g:i A') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $gateMonitor->id }}">
                                            View Images
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="imageModal-{{ $gateMonitor->id }}" tabindex="-1" aria-labelledby="imageModalLabel-{{ $gateMonitor->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="imageModalLabel-{{ $gateMonitor->id }}">Entry and Exit Images</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 text-center">
                                                                <h6>In Image</h6>
                                                                @if($gateMonitor->in_img)
                                                                <img src="{{ asset('storage/'.$gateMonitor->in_img) }}" alt="In Image" class="img-fluid" width="500">
                                                            @else
                                                                <p>No image available</p>
                                                            @endif
                                                            </div>
                                                            <div class="col-md-6 text-center">
                                                                <h6>Out Image</h6>
                                                                @if($gateMonitor->out_img)
                                <img src="{{ asset('storage/'.$gateMonitor->out_img) }}" alt="Out Image" class="img-fluid" width="500">
                            @else
                                <p>No image available</p>
                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
