<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/admin.css">
    @yield('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>


<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
</head>

<body>
    <aside class="sidebar">
        <div class="d-flex justify-content-center">
            <img class="" src="/img/user.png" alt="" width="100">

        </div>
        <div class="d-flex justify-content-between align-items-center">
            <h3 style="color: white">Admin</h2>
                <img src="/img/next.png" alt="" width="20px" height="20px">

        </div>
        <div>
            <p style="color: white">admin@gmail.com</p>
        </div>
        <div class="line mt-1"></div>
        <div class="mt-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/menu 8.png" alt="">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        {{-- <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/registration.png" alt="">
                    <a href="{{ route('admin.homeownerform') }}">Rfid registration</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div> --}}
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/inout.png" alt="">
                    <a href="{{ route('admin.homeownerlist') }}">Homeowner List</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/inout.png" alt="">
                    <a href="{{ route('admin.homeownerpending') }}">Homeowner Pending</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/inout.png" alt="">
                    <a href="{{ route('admin.gatelist') }}">Gate Entry / Exit </a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/inout.png" alt="">
                    <a href="{{ route('admin.outsiders') }}">Outsider Gate Entry / Exit </a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/inout.png" alt="">
                    <a href="{{ route('admin.visitors') }}">Visitor Gate Entry / Exit </a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        {{-- <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/reminder.png" alt="">
                    <a href="{{ route('admin.adminpaymentreminder') }}">Payment reminder</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div> --}}
        {{-- <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/reminder.png" alt="">
                    <a href="{{ route('visitors.index') }}">Visitors</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div> --}}
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/todo.png" alt="">
                    <a href="{{ route('eventdos.index') }}">Activites</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        {{-- <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/calendar.png" alt="">
                    <a href="Dashboard">Calendar</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div> --}}
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/message.png" alt="">
                    <a href="{{ route('admin.messages') }}">Messages</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>
        {{-- <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/approved.png" alt="">
                    <a href="{{ route('admin.rfidapproval') }}">Rfid approval</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div> --}}
        {{-- <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/settings.png" alt="">
                    <a href="Dashboard">Settings</a>
                </div>
                <img src="/img/next.png" alt="" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div> --}}
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/back.png" alt="Back Icon">

                    <!-- Logout form -->
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link p-0" style="color: white; text-decoration: none;">Log out</button>
                    </form>
                </div>
                <img src="/img/next.png" alt="Next Icon" width="20px" height="20px">
            </div>
            <div class="line"></div>
        </div>

    </aside>
    <section>
        @yield('content')
    </section>


</body>

</html>
