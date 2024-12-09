@extends('layouts.adminlayout')

@section('styles')
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">List of Homeowner Pending Accounts</h1>
        <a class="btn btn-primary mb-3" href="{{ route('admin.homeownerform') }}">Add Homeowner</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
 <!-- Search Form -->
 <form action="{{ route('admin.homeownerpending') }}" method="GET">
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by Homeowner name" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Homeowner Pending Accounts</h5>

            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            {{-- <th scope="col">Position</th> --}}
                            <th scope="col">Phase</th>
                            <th scope="col">Email</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($homeowners as $homeowner)
                        <tr>
                            <td>{{ $loop->iteration + ($homeowners->currentPage() - 1) * $homeowners->perPage() }}</td>
                            <td>{{ $homeowner->fname }} {{ $homeowner->lname }}</td>
                            {{-- <td>{{ $homeowner->position }}</td> --}}
                            <td>{{ $homeowner->phase }}</td>
                            <td>{{ $homeowner->email }}</td>
                            <td>{{ $homeowner->phone }}</td>
                            <td>
                                <button data-bs-toggle="modal" class="btn btn-secondary" data-bs-target="#modal{{ $homeowner->id }}" data-product-id="{{ $homeowner->id }}">View</button>
                                <!-- Confirm Form -->
                                <form action="{{ route('homeowner.confirm', $homeowner->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to confirm this homeowner?');">Confirm</button>
                                </form>
                                <!-- Delete Form -->
                                <form action="{{ route('homeowner.delete', $homeowner->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this homeowner?');">Reject</button>
                                </form>
                            </td>
                        </tr>

                       <!-- Modal for Homeowner Details -->
<div class="modal fade" id="modal{{ $homeowner->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $homeowner->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalLabel{{ $homeowner->id }}">Homeowner Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="details-section">
                    @if ($homeowner->document_image)
                    <img src="{{ asset('storage/' . $homeowner->document_image) }}" alt="Document Image" width="100">
                @else
                    <p>No document image available.</p>
                @endif

                <!-- Display image -->
                @if ($homeowner->image)
                    <img src="{{ asset('storage/' . $homeowner->image) }}" alt="Homeowner Image" width="100">
                @else
                    <p>No image available.</p>
                @endif
                    <div class="detail-row"><strong>Name:</strong> {{ $homeowner->fname }} {{ $homeowner->mname }} {{ $homeowner->lname }}</div>
                    <div class="detail-row"><strong>Contact:</strong> {{ $homeowner->phone }}</div>
                    <div class="detail-row"><strong>Email:</strong> {{ $homeowner->email }}</div>
                    <div class="detail-row"><strong>Phase:</strong> {{ $homeowner->phase }}</div>
                    <div class="detail-row"><strong>Block:</strong> {{ $homeowner->block }}</div>
                    <div class="detail-row"><strong>Lot:</strong> {{ $homeowner->lot }}</div>
                    <div class="detail-row"><strong>Birthdate:</strong> {{ $homeowner->birthdate }}</div>
                    <div class="detail-row"><strong>Gender:</strong> {{ $homeowner->gender == 1 ? 'Male' : 'Female' }}</div>
                    {{-- <div class="detail-row"><strong>RFID:</strong> {{ $homeowner->rfid }}</div>
                    <div class="detail-row"><strong>Position:</strong> {{ $homeowner->position }}</div> --}}
                    {{-- <div class="detail-row"><strong>Plate:</strong> {{ $homeowner->plate ?? 'N/A' }}</div> --}}
                    <div class="detail-row"><strong>Extension:</strong> {{ $homeowner->extension ?? 'N/A' }}</div>
                    <div class="detail-row"><strong>Status:</strong> {{ $homeowner->status }}</div>
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
                <div class="mt-3 d-flex justify-content-center">
                    {{ $homeowners->links() }}
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <script>
        toastr.success('{{ session('success') }}', 'Success!');
    </script>
    @endif
@endsection
