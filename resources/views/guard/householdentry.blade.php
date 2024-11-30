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
            <div class="card-header">
                <h5 class="mb-0">Search and Filter</h5>
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
