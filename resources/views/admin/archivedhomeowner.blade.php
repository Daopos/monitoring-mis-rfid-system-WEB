@extends('layouts.adminlayout')

@section('styles')
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
<style>
    .vehicle-images .image-container {
        text-align: center;
        display: inline-block;
        margin-right: 10px;
    }

    .vehicle-img {
        max-width: 150px;
        max-height: 150px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Optional: Make images responsive and add some space between them */
    .vehicle-images {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }
</style>
    <div class="container mt-5">
        <h1 class="display-4 mb-4">List of Homeowner</h1>

        <div class="col-md-4">
                <form action="{{ route('admin.homeownerlist') }}" method="GET" class="d-flex mb-3">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" aria-label="Search" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Homeowner</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Phase</th>
                            <th>Email</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($homeowners as $homeowner)
                        <tr>
                            <td>{{ $loop->iteration + ($homeowners->currentPage() - 1) * $homeowners->perPage() }}</td>
                            <td>{{ $homeowner->fname }} {{ $homeowner->lname }}</td>
                            <td>{{ $homeowner->position }}</td>
                            <td>{{ $homeowner->phase }}</td>
                            <td>{{ $homeowner->email }}</td>
                            <td>{{ $homeowner->phone }}</td>


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
                                        {{-- <div class="detail-row"><strong>RFID:</strong> {{ $homeowner->rfid }}</div> --}}
                                        <div class="detail-row"><strong>Position:</strong> {{ $homeowner->position }}</div>
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

                       <!-- Modal for Vehicles -->
                       <div class="modal fade" id="vehiclesModal{{ $homeowner->id }}" tabindex="-1" aria-labelledby="vehiclesModalLabel{{ $homeowner->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="vehiclesModalLabel{{ $homeowner->id }}">Vehicles of {{ $homeowner->fname }} {{ $homeowner->lname }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($homeowner->vehicles->isEmpty())
                                        <p>No vehicles found for this homeowner.</p>
                                    @else
                                        <ul class="list-group">
                                            @foreach ($homeowner->vehicles as $vehicle)
                                                <li class="list-group-item">
                                                    <strong>Car Type:</strong> {{ $vehicle->car_type ?? 'N/A' }}<br>
                                                    <strong>Brand:</strong> {{ $vehicle->brand ?? 'N/A' }}<br>
                                                    <strong>Model:</strong> {{ $vehicle->model ?? 'N/A' }}<br>
                                                    <strong>Color:</strong> {{ $vehicle->color ?? 'N/A' }}<br>
                                                    <strong>Plate Number:</strong> {{ $vehicle->plate_number ?? 'N/A' }}

                                                    <!-- Vehicle Images -->
                                                    <div class="vehicle-images mt-3">
                                                        @if($vehicle->vehicle_img)
                                                            <div class="image-container mb-2">
                                                                <strong>Vehicle Image:</strong><br>
                                                                <img src="{{ asset('storage/' . $vehicle->vehicle_img) }}" alt="Vehicle Image" class="img-fluid vehicle-img">
                                                            </div>
                                                        @endif

                                                        @if($vehicle->or_img)
                                                            <div class="image-container mb-2">
                                                                <strong>OR Image:</strong><br>
                                                                <img src="{{ asset('storage/' . $vehicle->or_img) }}" alt="OR Image" class="img-fluid vehicle-img">
                                                            </div>
                                                        @endif

                                                        @if($vehicle->cr_img)
                                                            <div class="image-container mb-2">
                                                                <strong>CR Image:</strong><br>
                                                                <img src="{{ asset('storage/' . $vehicle->cr_img) }}" alt="CR Image" class="img-fluid vehicle-img">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                       <!-- Modal for Household Members -->
<div class="modal fade" id="householdModal{{ $homeowner->id }}" tabindex="-1" aria-labelledby="householdModalLabel{{ $homeowner->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="householdModalLabel{{ $homeowner->id }}">Household Members of {{ $homeowner->fname }} {{ $homeowner->lname }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Household Members List -->
                @if ($homeowner->households->isEmpty())
                    <p>No household members found for this homeowner.</p>
                @else
                    <ul class="list-group">
                        @foreach ($homeowner->households as $household)
                            <li class="list-group-item">
                                <strong>Name:</strong> {{ $household->name }}<br>
                                <strong>Relationship:</strong> {{ $household->relationship }}<br>
                                <strong>Age:</strong> {{ $household->age }}<br>
                                <strong>Gender:</strong> {{ $household->gender }}<br>
                                <strong>RFID:</strong> {{ $household->rfid ?? 'Not Assigned' }}
                                <form action="{{ route('household.updateRfid', $household->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="rfid" class="form-control mt-2" placeholder="Enter RFID">
                                    <button type="submit" class="btn btn-primary mt-2">Update RFID</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
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
@endsection
