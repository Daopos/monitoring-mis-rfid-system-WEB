@extends('layouts.adminlayout')

@section('styles')
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">List of Blocks</h1>

        <!-- Search Form -->
        <div class="p-2 w-25">
            <form action="{{ route('blocks.index') }}" method="GET" class="d-flex mb-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by block or number" aria-label="Search" value="{{ request('search') }}">
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
                <h5 class="mb-0">Blocks</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                    Create Block
                </button>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Block</th>
                            <th>Number</th>
                            <th>Lot</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blocks as $block)
                            <tr>
                                <td>{{ $block->block }}</td>
                                <td>{{ $block->number }}</td>
                                <td>{{ $block->lot }}</td>
                                <td>{{ $block->details }}</td>
                                <td>
                                    <!-- View Button -->
                                    <button data-bs-toggle="modal" class="btn btn-secondary" data-bs-target="#modal{{ $block->id }}">View</button>

                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $block->id }}">Edit</button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('blocks.destroy', $block->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this block?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- View Block Modal -->
                            <div class="modal fade" id="modal{{ $block->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $block->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="modalLabel{{ $block->id }}">Block Details</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="details-section">
                                                <div class="detail-row"><strong>Block:</strong> {{ $block->block }}</div>
                                                <div class="detail-row"><strong>Number:</strong> {{ $block->number }}</div>
                                                <div class="detail-row"><strong>Lot:</strong> {{ $block->lot }}</div>
                                                <div class="detail-row"><strong>Details:</strong> {{ $block->details }}</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Block Modal -->
                            <div class="modal fade" id="editModal{{ $block->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $block->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('blocks.update', $block->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $block->id }}">Edit Block</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="block">Block</label>
                                                    <input type="text" name="block" class="form-control" value="{{ $block->block }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="number">Number</label>
                                                    <input type="text" name="number" class="form-control" value="{{ $block->number }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lot">Lot</label>
                                                    <input type="text" name="lot" class="form-control" value="{{ $block->lot }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="details">Details</label>
                                                    <textarea name="details" class="form-control">{{ $block->details }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update Block</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Block Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('blocks.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create Block</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="block">Block</label>
                            <input type="text" name="block" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Number</label>
                            <input type="text" name="number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="lot">Lot</label>
                            <input type="text" name="lot" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="details">Details</label>
                            <textarea name="details" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Block</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
