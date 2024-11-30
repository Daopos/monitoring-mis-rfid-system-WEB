@extends('layouts.adminlayout')

{{-- @section('title', 'Custom Orders') --}}

@section('styles')
    <link href="{{ asset('/css/admindashboard.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="greycard">
    </div>
    <div class="dashboard-body  p-3">
        <div class="d-flex justify-content-between ">
            <h3>Dashboard Overview</h3>

            {{-- <div class="d-flex gap-2">
                <input type="text" placeholder="category, services, customers, employee">
                <div>

                    <img src="/img/notification.png" alt="">
                    <img src="/img/human.png" alt="">
                </div>

            </div> --}}


        </div>
        <div class="d-flex gap-5 mt-4 flex-wrap">
            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/group.png" alt="">
                    <div>
                        <h2>Homeowners</h2>
                        <h2>{{ $totalHomeowners }}</h2>
                    </div>
                </div>
                <a href="">VIEW ALL</a>
            </div>
            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/owner.png" alt="">
                    <div>
                        <h5>Homeowners with RFID</h5>
                        <h2>{{ $homeownersWithRFID }}</h2>
                    </div>
                </div>
                <a href="">VIEW ALL</a>
            </div>
            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/renter.png" alt="">
                    <div>
                        <h5>Homeowners without RFID</h5>
                        <h2>{{ $homeownersWithoutRFID }}</h2>
                    </div>
                </div>
                <a href="">VIEW ALL</a>
            </div>
            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/pagibig.png" alt="">
                    <div>
                        <h5>Activities</h5>
                        <h2>{{ $totalEvents }}</h2>

                    </div>
                </div>
                <a href="">VIEW ALL</a>
            </div>
        </div>
    </div>
@endsection
