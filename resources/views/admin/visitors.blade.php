@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h1 class="display-4 mb-4">Visitors</h1>

    <div class="col-md-4">
        <!-- Search Form -->
        <form action="{{ route('visitors.index') }}" method="GET" class="d-flex mb-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by Visitor or homeowner name" aria-label="Search" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Visitor Status</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Visitor Name</th>
                            <th>Homeowner Name</th>
                            <th>Date of Visit</th>
                            <th>RFID Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>


                            <!-- Details Modal -->
                           <!-- Details Modal -->
                           @foreach ($visitors as $visitor)
                           <tr>
                               <td>{{ $loop->iteration + ($visitors->currentPage() - 1) * $visitors->perPage() }}</td>
                               <td>{{ $visitor->name }}</td>
                               <td>{{ $visitor->homeowner ? $visitor->homeowner->fname . ' ' . $visitor->homeowner->lname : 'N/A' }}</td>
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
                                        <h6>Representative:</h6>
                                           <ul class="list-group">
                                            <li class="list-group-item">
                                                <strong>Valid ID:</strong>
                                                <img src="{{ asset('storage/' . $visitor->valid_id) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Profile Image:</strong>
                                                <img src="{{ asset('storage/' . $visitor->profile_img) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                            </li>
                                               <li class="list-group-item"><strong>Relationship:</strong> {{ $visitor->relationship ?? 'N/A' }}</li>
                                               @if($visitor->brand)
                                               <li class="list-group-item"><strong>Brand:</strong> {{ $visitor->brand }}</li>
                                           @endif

                                           @if($visitor->color)
                                               <li class="list-group-item"><strong>Color:</strong> {{ $visitor->color }}</li>
                                           @endif

                                           @if($visitor->model)
                                               <li class="list-group-item"><strong>Model:</strong> {{ $visitor->model }}</li>
                                           @endif

                                           @if($visitor->plate_number)
                                               <li class="list-group-item"><strong>Plate Number:</strong> {{ $visitor->plate_number }}</li>
                                           @endif

                                               <li class="list-group-item"><strong>Date of Visit:</strong> {{ $visitor->date_visit ?? 'N/A' }}</li>
                                               <li class="list-group-item"><strong>Id Type:</strong> {{ $visitor->type_id ?? 'N/A' }}</li>
                                               <li class="list-group-item"><strong>RFID:</strong> {{ $visitor->rfid ?? 'N/A' }}</li>
                                               <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($visitor->status) }}</li>
                                               <li class="list-group-item"><strong>Guard Approval:</strong> {{ $visitor->guard ? 'Approved' : 'Pending' }}</li>
                                           </ul>
                                           <hr />
                                           <h6>Members:</h6>
                                           <ul class="list-group">
                                               @foreach ($visitor->visitorGroups as $group)
                                                   <li class="list-group-item">
                                                       <strong>Name:</strong> {{ $group->name ?? 'N/A' }}<br />
                                                       <strong>ID Type:</strong> {{ $group->type_id ?? 'N/A' }}<br />
                                                       <strong>Valid ID:</strong>
                                                       @if ($group->valid_id)
                                                           <img src="{{ asset('storage/' . $group->valid_id) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                                       @else
                                                           N/A
                                                       @endif
                                                       <br />
                                                       <strong>Profile Image:</strong>
                                                       @if ($group->profile_img)
                                                           <img src="{{ asset('storage/' . $group->profile_img) }}" alt="Profile Image" class="img-thumbnail" width="100" />
                                                       @else
                                                           N/A
                                                       @endif
                                                   </li>
                                               @endforeach
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
                <div class="mt-3 d-flex justify-content-center">
                    {{ $visitors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
