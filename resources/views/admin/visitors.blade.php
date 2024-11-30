@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Visitor Requests</h1>

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
                            <th>Plate Number</th>
                            <th>Homeowner Name</th>
                            <th>RFID Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visitors as $visitor)
                            <tr>
                                <td>{{ $visitor->name }}</td>
                                <td>{{ $visitor->plate_number }}</td>
                                <td>{{ $visitor->homeowner ? $visitor->homeowner->fname . ' ' . $visitor->homeowner->lname : 'N/A' }}</td>
                                <td>{{ $visitor->status ?? 'N/A' }}</td>
                                <td>
                                    @if ($visitor->status === 'pending')
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $visitor->id }}">Approve</button>

                                        <form action="{{ route('visitors.deny', $visitor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary">Already Processed</span>
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
                                            <form action="{{ route('visitors.approve', $visitor->id) }}" method="POST">
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
@endsection
