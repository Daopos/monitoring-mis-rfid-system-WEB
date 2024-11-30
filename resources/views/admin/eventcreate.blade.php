@extends('layouts.adminlayout')

@section('styles')
{{-- You can add custom styles here if needed --}}
@endsection

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Create Event</h1>
    <form action="{{ route('eventdos.store') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Event Details</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="start">Start Date & Time</label>
                    <input type="datetime-local" name="start" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end">End Date & Time</label>
                    <input type="datetime-local" name="end" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="InActive">InActive</option>
                        <option value="Active">Active</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Create Event</button>
            </div>
        </div>
    </form>
</div>
@endsection
