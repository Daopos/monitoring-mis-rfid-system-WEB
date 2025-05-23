<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
    <title>Admin</title>
    <link rel="shortcut icon" href="/img/agllogo.png" type="image/x-icon">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/admin.css">
    @yield('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
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
            <h5 class="mt-2 text-white">Admin</h5>
            <p class="m-0 text-white">admin@gmail.com</p>

        </div>
        <hr class="text-light">
        <nav class="px-3">
            <a href="{{ route('admin.dashboard') }}"
            class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
             <i class="fa-solid fa-house"></i>
             Dashboard
             <span class="ms-auto dropdown-arrow">&#9656;</span>
         </a>
        <!-- Dropdown -->
       <!-- Homeowner List Dropdown -->
<div class="sidebar-dropdown {{ Request::routeIs('admin.homeownerlist', 'admin.homeownerpending', 'admin.homeownerlistarchived') ? 'open' : '' }}">
    <button class="sidebar-dropdown-btn d-flex align-items-center gap-2">
        <i class="fa-solid fa-users"></i>
        Homeowner List
        <span class="ms-auto dropdown-arrow">&#9662;</span>
    </button>
    <ul class="sidebar-dropdown-menu">
        <li>
            <a href="{{ route('admin.homeownerlist') }}"
               class="dropdown-item {{ Request::routeIs('admin.homeownerlist') ? 'active' : '' }}">
               Homeowners
            </a>
        </li>
        <li>
            <a href="{{ route('admin.homeownerpending') }}"
               class="dropdown-item {{ Request::routeIs('admin.homeownerpending') ? 'active' : '' }}">
               New Homeowners
            </a>
        </li>
        <li>
            <a href="{{ route('admin.homeownerlistarchived') }}"
               class="dropdown-item {{ Request::routeIs('admin.homeownerlistarchived') ? 'active' : '' }}">
               Transferred Homeowners
            </a>
        </li>
    </ul>
</div>


       <a href="{{ route('admin.households') }}"
       class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('admin.households') ? 'active' : '' }}">
       <i class="fa-solid fa-user-group"></i>
        Households
        <span class="ms-auto dropdown-arrow">&#9656;</span>
    </a>
        <!-- Dropdown -->
        <div class="sidebar-dropdown {{ Request::routeIs('admin.gatelist', 'admin.outsiders', 'admin.visitors', 'admin.householdentry') ? 'open' : '' }}">
            <button class="sidebar-dropdown-btn d-flex align-items-center gap-2">
                <i class="fa-solid fa-door-open"></i>
                Gate Entry/Exit
                <span class="ms-auto dropdown-arrow">&#9662;</span>
            </button>
            <ul class="sidebar-dropdown-menu">
                <li>
                    <a href="{{ route('admin.gatelist') }}"
                       class="dropdown-item {{ Request::routeIs('admin.gatelist') ? 'active' : '' }}">
                       Homeowners
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.outsiders') }}"
                       class="dropdown-item {{ Request::routeIs('admin.outsiders') ? 'active' : '' }}">
                       Service Providers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.visitors') }}"
                       class="dropdown-item {{ Request::routeIs('admin.visitors') ? 'active' : '' }}">
                       Visitors
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.householdentry') }}"
                       class="dropdown-item {{ Request::routeIs('admin.householdentry') ? 'active' : '' }}">
                       Households
                    </a>
                </li>

            </ul>
        </div>
        <a href="{{ route('visitors.index') }}"
        class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('visitors.index') ? 'active' : '' }}">
        <i class="fa-solid fa-people-group"></i>
         Visitors
         <span class="ms-auto dropdown-arrow">&#9656;</span>
     </a>

     <a href="{{ route('admin.messages') }}"
     class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('admin.messages') ? 'active' : '' }}">
     <i class="fa-solid fa-message"></i>
     Messages
      <span class="ms-auto dropdown-arrow">&#9656;</span>
  </a>
        <a href="{{ route('eventdos.index') }}"
         class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('eventdos.index') ? 'active' : '' }}">
         <i class="fa-solid fa-calendar"></i>
          Activities
          <span class="ms-auto dropdown-arrow">&#9656;</span>
      </a>

    <a href="{{ route('admin.guard.index') }}"
    class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('admin.guard.index') ? 'active' : '' }}">
    <i class="fa-solid fa-user-group"></i>
    Guards

     <span class="ms-auto dropdown-arrow">&#9656;</span>
 </a>
 <a href="{{ route('officers.index') }}"
 class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('officers.index') ? 'active' : '' }}">
 <i class="fa-solid fa-people-group"></i>
 Officers

  <span class="ms-auto dropdown-arrow">&#9656;</span>
</a>
<a href="{{ route('admin.applicant') }}"
class="nav-link text-white d-flex align-items-center gap-2 {{ Request::routeIs('admin.applicant') ? 'active' : '' }}">
<i class="fa-solid fa-clone"></i>
Permit

 <span class="ms-auto dropdown-arrow">&#9656;</span>
</a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="mt-3">
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
