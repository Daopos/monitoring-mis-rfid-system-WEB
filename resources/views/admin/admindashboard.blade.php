@extends('layouts.adminlayout')

{{-- @section('title', 'Custom Orders') --}}

@section('styles')
    <link href="{{ asset('/css/admindashboard.css') }}" rel="stylesheet">
@endsection
@section('content')
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
                    <img src="/img/w.png" alt="">
                    <div>
                        <h2>Homeowners</h2>
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
                <a href="{{ route('admin.homeownerlist') }}">VIEW ALL</a>

            </div>


            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/e.png" alt="">
                    <div>
                        <h5>Homeowners without RFID</h5>
                        <h2>{{ $homeownersWithoutRFID }}</h2>
                    </div>
                </div>
                <a href="{{ route('admin.homeownerlist') }}">VIEW ALL</a>

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
                <a href="{{ route('admin.homeownerlist') }}">VIEW ALL</a>

            </div>

            <div class="dashboardcard">
                <div class="d-flex gap-3">
                    <img src="/img/y.png" alt="">
                    <div>
                        <h5>Visitors</h5>
                        <h2>{{ $visitor }}</h2>

                    </div>
                </div>
                <a href="{{ route('admin.homeownerlist') }}">VIEW ALL</a>
            </div>
        </div>
    </div>
@endsection
