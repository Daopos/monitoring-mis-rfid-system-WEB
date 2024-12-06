@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Visitor Requests</h1>

    <div class="p-2 w-25">
        <!-- Search Form -->
        <form action="{{ route('admin.households') }}" method="GET" class="d-flex mb-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by household or homeowner name" aria-label="Search" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                            <th>Homeowner Name</th>
                            <th>Number of Visitors</th>
                            <th>Date of Visit</th>
                            <th>RFID Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visitors as $visitor)
                            <tr>
                                <td>{{ $visitor->name }}</td>
                                {{-- <td>{{ $visitor->plate_number }}</td> --}}
                                <td>{{ $visitor->homeowner ? $visitor->homeowner->fname . ' ' . $visitor->homeowner->lname : 'N/A' }}</td>
                                <td>{{ $visitor->number_visitors ?? 'N/A' }}</td>
                                <td>{{ $visitor->date_visit ?? 'N/A' }}</td>

                                <td>{{ $visitor->status ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $visitor->id }}">
                                        View Details
                                    </button>
                                </td>
                            </tr>

                            <!-- Details Modal -->
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
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Name:</strong> {{ $visitor->name }}</li>
                                                <li class="list-group-item"><strong>Relationship:</strong> {{ $visitor->relationship ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Brand:</strong> {{ $visitor->brand ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Color:</strong> {{ $visitor->color ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Model:</strong> {{ $visitor->model ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Plate Number:</strong> {{ $visitor->plate_number ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Number of Visitors:</strong> {{ $visitor->number_visitors ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Date of Visit:</strong> {{ $visitor->date_visit ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>RFID:</strong> {{ $visitor->rfid ?? 'N/A' }}</li>
                                                <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($visitor->status) }}</li>
                                                <li class="list-group-item"><strong>Guard Approval:</strong> {{ $visitor->guard ? 'Approved' : 'Pending' }}</li>
                                            </ul>
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
            </div>
        </div>
    </div>
</div>
@endsection
