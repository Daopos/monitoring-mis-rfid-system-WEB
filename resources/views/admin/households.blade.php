@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 display-4">Households</h1>

    <div class="col-md-5">
        <!-- Search Form -->
        <form action="{{ route('admin.households') }}" method="GET" class="d-flex mb-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by household or homeowner name" aria-label="Search" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- RFID Filter Buttons -->
        <form action="{{ route('admin.households') }}" method="GET" class="d-flex mb-3">
            <input type="hidden" name="search" value="{{ request('search') }}"> <!-- Preserve search input -->

            <button type="submit" name="rfid_filter" value="with_rfid" class="btn btn-success me-2">
                With RFID
            </button>
            <button type="submit" name="rfid_filter" value="without_rfid" class="btn btn-danger">
                Without RFID
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Households</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Gender</th>
                            <th>Relationship</th>
                            <th>Homeowner Name</th>
                            <th>RFID Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($households as $household)
                            <tr>
                            <td>{{ $loop->iteration + ($households->currentPage() - 1) * $households->perPage() }}</td>
                                <td>{{ $household->name }}</td>
                                <td>{{ $household->birthdate }}</td>
                                <td>{{ $household->gender }}</td>
                                <td>{{ $household->relationship }}</td>
                                <td>{{ $household->homeowner ? $household->homeowner->fname . ' ' . $household->homeowner->lname : 'N/A' }}</td>
                                <td>{{ $household->rfid ? 'Registered' : 'Unregistered' }}</td>
                                <td>
                                    <!-- Update RFID Button -->
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rfidModal{{ $household->id }}">
                                        {{ $household->rfid ? 'Update RFID' : 'Add RFID' }}
                                    </button>
                                </td>
                            </tr>

                            <!-- RFID Modal -->
                            <div class="modal fade" id="rfidModal{{ $household->id }}" tabindex="-1" aria-labelledby="rfidModalLabel{{ $household->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rfidModalLabel{{ $household->id }}">
                                                {{ $household->rfid ? 'Update RFID' : 'Add RFID' }} for {{ $household->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('household.updateRfid', $household->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="rfid" class="form-label">RFID</label>
                                                    <input type="text" name="rfid" class="form-control" value="{{ $household->rfid }}" >
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $households->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
