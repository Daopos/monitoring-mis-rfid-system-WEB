@extends('layouts.adminlayout')

@section('styles')
    <link href="{{ asset('/css/adminlist.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <h1 class="mb-4">Homeowners with RFID</h1>

    <!-- Table displaying homeowners with RFID -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">RFID</th>
                <th scope="col">Position</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($homeowners as $homeowner)
                <tr>
                    <td>{{ $homeowner->fname }}</td>
                    <td>{{ $homeowner->lname }}</td>
                    <td>{{ $homeowner->phone }}</td>
                    <td>{{ $homeowner->email }}</td>
                    <td>{{ $homeowner->rfid }}</td>
                    <td>{{ $homeowner->position }}</td>
                    <td>{{ $homeowner->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
