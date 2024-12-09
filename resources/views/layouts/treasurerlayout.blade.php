<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
    <title>Treasurer</title>
    <link rel="shortcut icon" href="/img/agllogo.png" type="image/x-icon">
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

     <!-- Header -->
     <header class="header bg-primary text-white">
        <div class="container-fluid d-flex justify-content-between align-items-center p-2">
            <button id="burger-menu" class="side-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h3 class="m-0">Admin Panel</h3>
        </div>
    </header>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="text-center py-3">
            <img src="/img/user.png" alt="User Image" class="rounded-circle" width="80">
            <h5 class="mt-2 text-white">Treasurer</h5>
            <p class="m-0 text-white">treasurer@gmail.com</p>

        </div>
        <hr class="text-light">
        <nav class="px-3">
            <a href="{{ route('treasurer.dashboard') }}"
            class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('treasurer.dashboard') ? 'active' : '' }}">
             <i class="fa-solid fa-house"></i>
             Dashboard
             <span class="ms-auto dropdown-arrow">&#9656;</span>
         </a>
         <a href="{{ route('payment_reminders.index') }}"
            class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('payment_reminders.index') ? 'active' : '' }}">
            <i class="fa-solid fa-message"></i>
            Payment Reminder
             <span class="ms-auto dropdown-arrow">&#9656;</span>
         </a><a href="{{ route('treasurer.paidlist') }}"
         class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('treasurer.paidlist') ? 'active' : '' }}">
         <i class="fa-solid fa-calendar"></i>
         Paid List
          <span class="ms-auto dropdown-arrow">&#9656;</span>
      </a>


            <form id="logout-form" action="{{ route('treasurer.logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-link text-white p-0">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <section id="content" class="p-3">
        @yield('content')
    </section>

    <script>
        // Toggle Sidebar on small screens
        document.getElementById('burger-menu').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('show-sidebar');
        });

        document.querySelectorAll('.sidebar-dropdown-btn').forEach((button) => {
    button.addEventListener('click', function () {
        const parent = this.closest('.sidebar-dropdown');
        parent.classList.toggle('open');
    });
});
    </script>


</body>

</html>
