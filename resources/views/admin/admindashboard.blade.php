@extends('layouts.adminlayout')

{{-- @section('title', 'Custom Orders') --}}

@section('styles')
    <link href="{{ asset('/css/admindashboard.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="dashboard-body  p-3">
        <div class="d-flex justify-content-between ">
            <h3>Dashboard Overview</h3>
        </div>
        <div class="d-flex gap-5 mt-4 flex-wrap">
            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/w.png" alt="">
                    <div>
                        <h5>Homeowners</h5>
                        <h2>{{ $totalHomeowners }}</h2>
                    </div>
                </div>
                <a href="{{ route('admin.homeownerlist') }}">VIEW ALL</a>
            </div>
            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/q.png" alt="">
                    <div>
                        <h5>Homeowners with RFID</h5>
                        <h2>{{ $homeownersWithRFID }}</h2>
                    </div>
                </div>
                <a href="{{ route('admin.homeownerlist', ['rfid_filter' => 'with_rfid']) }}">VIEW ALL</a>

            </div>


            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/e.png" alt="">
                    <div>
                        <h5>Homeowners without RFID</h5>
                        <h2>{{ $homeownersWithoutRFID }}</h2>
                    </div>
                </div>
                <a href="{{ route('admin.homeownerlist', ['rfid_filter' => 'without_rfid']) }}">VIEW ALL</a>

            </div>

            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/r.png" alt="">
                    <div>
                        <h5>Activities</h5>
                        <h2>{{ $totalEvents }}</h2>

                    </div>
                </div>
                <a href="{{ route('eventdos.index') }}">VIEW ALL</a>
            </div>
            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/t.png" alt="">
                    <div>
                        <h5>Households</h5>
                        <h2>{{ $household }}</h2>

                    </div>
                </div>
                <a href="{{ route('admin.households') }}">VIEW ALL</a>

            </div>

            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/y.png" alt="">
                    <div>
                        <h5>Visitors</h5>
                        <h2>{{ $visitor }}</h2>

                    </div>
                </div>
                <a href="{{ route('admin.visitors') }}">VIEW ALL</a>
            </div>
        </div>

        <div class="dashboard-section mt-5">
            <h5>Unread Messages</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($unreadMessages as $message)
                            <tr>
                                <td> {{ $message->homeOwner ? $message->homeOwner->fname . ' ' . $message->homeOwner->lname : 'Unknown Sender' }}</td>
                                <td>{{ Str::limit($message->message, 50) }}</td> <!-- Limit message length -->
                                <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message->home_owner_id) }}" class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No unread messages</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.messages') }}" class="btn btn-link mt-3">View All Messages</a>
        </div>


        <div class="dashboard-section mt-5">
            <h5>PDF Management</h5>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Upload PDF</button>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pdfs as $pdf)
                        <tr>
                            <td>{{ $pdf->name }}</td>
                            <td>
                                <!-- View PDF -->
                                <a href="{{ route('pdfs.show', $pdf) }}" class="btn btn-info btn-sm">View</a>

                                <!-- Edit PDF Modal Trigger -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="{{ $pdf->id }}" data-name="{{ $pdf->name }}">Edit</button>

                                <!-- Delete PDF Modal Trigger -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="{{ $pdf->id }}" data-name="{{ $pdf->name }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Create PDF Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pdfs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Upload New PDF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">PDF Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Select PDF</label>
                            <input type="file" class="form-control" name="file" accept="application/pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit PDF Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit PDF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">PDF Name</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete PDF Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete PDF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this PDF?</p>
                        <p id="deletePdfName"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Edit Modal
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var pdfId = button.getAttribute('data-id');
            var pdfName = button.getAttribute('data-name');

            var formAction = '/pdfs/' + pdfId;
            document.getElementById('editForm').action = formAction;
            document.getElementById('editName').value = pdfName;
        });

        // Delete Modal
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var pdfId = button.getAttribute('data-id');
            var pdfName = button.getAttribute('data-name');

            var formAction = '/pdfs/' + pdfId;
            document.getElementById('deleteForm').action = formAction;
            document.getElementById('deletePdfName').innerText = pdfName;
        });
    </script>
@endsection
