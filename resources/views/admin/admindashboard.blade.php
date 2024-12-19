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



@endsection
