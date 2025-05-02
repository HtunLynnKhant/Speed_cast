<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('/images/logo_.png') }}" type="image/x-icon" />
    <title>Speedcast | @yield('title')</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @yield('bootstrap')
</head>

<body>
    <div class="main-wrapper">
        <!-- side_bar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
                    <img src="{{ asset('images/logo_.png') }}" alt="Logo" width="60" height="50" class="d-inline-block align-top mx-4" />
                </a>
                <div class="sidebar-toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <div class="sidebar-body">
                <ul class="nav" id="sidebarNav">
                    <li class="nav-item nav-category">Main</li>
                    <li class="nav-item {{ Route::currentRouteNamed(Auth::user()->hasRole('superadmin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('admin') ? 'admin.dashboard' : 'client.dashboard')) ? 'active' : '' }}">
                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('admin') ? 'admin.dashboard' : (Auth::user()->hasRole('client') ? 'client.dashboard' : 'fallback.route'))) }}" class="nav-link">
                            <i class="link-icon" data-feather="box"></i>
                            <span class="link-title">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item nav-category">PAGES</li>

                    @if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('client'))
                        <!-- Superadmin-specific links -->
                        <li class="nav-item {{ Route::is('superadmin.banner.*', 'admin.banner.*', 'client.banner.*') ? 'active' : '' }}">
                            <a href="{{ route(
                                Auth::user()->hasRole('superadmin') ? 'superadmin.banner.list' : 
                                (Auth::user()->hasRole('admin') ? 'admin.banner.list' : 
                                'client.banner.list')) }}" class="nav-link">
                                <i class="link-icon" data-feather="image"></i>
                                <span class="link-title">Main Banners</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.subbanner.*', 'superadmin.subbanner.*', 'client.subbanner.list') ? 'active' : '' }}">
                            <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.subbanner.list' : (Auth::user()->hasRole('client') ? 'client.subbanner.list' : 'admin.subbanner.list')) }}" class="nav-link">
                                <i class="link-icon" data-feather="image"></i>
                                <span class="link-title">Sub Banners</span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))
                        <li class="nav-item {{ Route::is('superadmin.category.*', 'admin.category.*', 'client.category.*') ? 'active' : '' }}">
                            <a href="{{ route(
                                Auth::user()->hasRole('superadmin') ? 'superadmin.category.list' : 
                                (Auth::user()->hasRole('admin') ? 'admin.category.list' : 
                                'client.category.list')) }}" class="nav-link">
                                <i class="link-icon" data-feather="grid"></i>
                                <span class="link-title">Categories</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('client'))
                        <!-- Common admin, superadmin, and client links -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#adsType" role="button" 
                            aria-expanded="{{ Route::is('admin.type.*', 'admin.project.*', 'admin.ads.*', 'superadmin.type.*', 'superadmin.project.*', 'superadmin.ads.*', 'client.ads.*', 'client.type.*', 'client.project.*') ? 'true' : 'false' }}" 
                            aria-controls="adsType">
                                <i class="link-icon" data-feather="folder"></i>
                                <span class="link-title">Ads Management</span>
                                <i class="link-arrow" data-feather="chevron-down"></i>
                            </a>
                            <div class="collapse {{ Route::is('admin.type.*', 'admin.project.*', 'admin.ads.*', 'superadmin.type.*', 'superadmin.project.*', 'superadmin.ads.*', 'client.ads.*', 'client.type.*', 'client.project.*') ? 'show' : '' }}" 
                                data-bs-parent="#sidebarNav" id="adsType">
                                <ul class="nav sub-menu">
                                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                                    <li class="nav-item">
                                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.type.list' : (Auth::user()->hasRole('client') ? 'client.type.list' : 'admin.type.list')) }}" class="nav-link {{ Route::is('admin.type.*', 'superadmin.type.*', 'client.type.list') ? 'active' : '' }}">Types</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.project.list' : (Auth::user()->hasRole('client') ? 'client.project.list' : 'admin.project.list')) }}" class="nav-link {{ Route::is('admin.project.*', 'superadmin.project.*', 'client.project.list') ? 'active' : '' }}">Projects</a>
                                    </li>
                                @endif
                                    <li class="nav-item">
                                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.ads.list' : (Auth::user()->hasRole('client') ? 'client.ads.list' : 'admin.ads.list')) }}" class="nav-link {{ Route::is('admin.ads.*', 'superadmin.ads.*', 'client.ads.list') ? 'active' : '' }}">ADS</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                    <li class="nav-item {{ Route::is('superadmin.drivers.*', 'admin.drivers.*') ? 'active' : '' }}">
                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.drivers.list' : 'admin.drivers.list') }}" class="nav-link">
                            <i class="link-icon" data-feather="truck"></i>
                            <span class="link-title">Drivers Management</span>
                        </a>
                    </li>


                    <li class="nav-item {{ Route::is('superadmin.clients.*', 'admin.clients.*') ? 'active' : '' }}">
                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.clients.list' : 'admin.clients.list') }}" class="nav-link">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">Clients Management</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                    <li class="nav-item {{ Route::is('superadmin.tablets.*', 'admin.tablets.*') ? 'active' : '' }}">
                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.tablets.list' : 'admin.tablets.list') }}" class="nav-link">
                            <i class="link-icon" data-feather="tablet"></i>
                            <span class="link-title">Tablets Management</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasRole('superadmin'))
                    <li class="nav-item {{ Route::is('superadmin.user.*', 'admin.user.*') ? 'active' : '' }}">
                        <a href="{{ route(Auth::user()->hasRole('superadmin') ? 'superadmin.user.list' : 'admin.user.list') }}" class="nav-link">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title">Admin</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>

        <div class="page-wrapper">
            <!-- top nav -->
            <nav class="navbar">
                <div class="navbar-content">
                    <div class="logo-mini-wrapper">
                        <img src="{{ asset('/images/logo_.png') }}" class="logo-mini logo-mini-light" alt="logo" />
                        <img src="{{ asset('/images/logo_.png') }}" class="logo-mini logo-mini-dark" alt="logo" />
                    </div>

                    <ul class="navbar-nav">
                        <li class="theme-switcher-wrapper nav-item">
                            <input type="checkbox" id="theme-switcher" />
                            <label for="theme-switcher">
                                <div class="box">
                                    <div class="ball"></div>
                                    <div class="icons">
                                        <i class="feather icon-sun"></i>
                                        <i class="feather icon-moon"></i>
                                    </div>
                                </div>
                            </label>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="false">
                                <img src="{{ asset('/images/profile.png') }}" alt="" class="w-30px h-30px ms-1 rounded-circle" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                                    <div class="mb-3">
                                        <img class="w-80px h-80px rounded-circle" src="{{ asset('/images/profile.png') }}" alt="" />
                                    </div>
                                    <div class="text-center">
                                        <p class="fs-16px fw-bolder">{{ Auth::user()->name }}</p>
                                        <p class="fs-12px text-secondary">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <div class="message-body">
                                    <!-- <a href="" class="d-flex align-items-center gap-2 dropdown-item">
                                        <i class="bi bi-person"></i>
                                        <p class="mb-0">My Profile</p>
                                    </a> -->

                                    <a href="" class="btn btn-sm btn-primary mx-3 mt-2 d-block" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <a href="#" class="sidebar-toggler">
                        <i data-feather="menu"></i>
                    </a>
                </div>
            </nav>
            <!-- end top nav -->

            <div class="page-content">
                @yield('content')
            </div>

            <footer class="footer d-flex flex-row align-items-center justify-content-between px-4 py-3 border-top small">
                <p class="text-secondary mb-1 mb-md-0">Copyright © 2024 <a href="" target="_blank">Startrick</a>.</p>
            </footer>
        </div>
    </div>

    <!-- model for logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    <form id="logoutForm" action="{{ route(
    Auth::user()->hasRole('superadmin') ? 'superadmin.sadminlogout' : 
    (Auth::user()->hasRole('admin') ? 'admin.logout' : 
    (Auth::user()->hasRole('client') ? 'client.clientogout' : 
    (Auth::user()->hasRole('driver') ? 'driver.driverlogout' : 'fallback.logout')))) 
}}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="loginsuccessModal" tabindex="-1" role="dialog" aria-labelledby="loginsuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-lg-4">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                        <circle class="path circle" fill="none" stroke="#198754" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                        <polyline class="path check" fill="none" stroke="#198754" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />
                    </svg>
                    <h4 class="text-success mt-3">Login Successful!</h4>
                    <p class="mt-3">{{ session('success') }}</p>
                    <button id="okButton" type="button" class="btn btn-sm mt-3 btn-success" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Success Modal -->

    @if (session('showpasswordModal'))
    <!-- Password Change Modal (if password is not changed) -->
    <div class="modal fade" id="passwordChangeModal" tabindex="-1" role="dialog" aria-labelledby="passwordChangeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-lg-4">
                    <h4 class="text-primary mt-3">Change Password</h4>
                    <form action="{{ route('client.password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="password">New Password:</label>
                            <input type="password" name="password" id="password">
                            @error('password')
                                <div class="error" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">Confirm New Password:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation">
                            @error('password_confirmation')
                                <div class="error" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-sm btn-primary">Change Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- driver password change model -->
    @if (session('showDpasswordModal'))
    <!-- Password Change Modal (if password is not changed) -->
    <div class="modal fade" id="showDpasswordModal" tabindex="-1" role="dialog" aria-labelledby="showDpasswordModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-lg-4">
                    <h4 class="text-primary mt-3">Change Password</h4>
                    <form action="{{ route('driver.password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="password">New Password:</label>
                            <input type="password" name="password" id="password">
                            @error('password')
                                <div class="error" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">Confirm New Password:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation">
                            @error('password_confirmation')
                                <div class="error" style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-sm btn-primary">Change Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
   
    <!-- bootstrap js -->
    <script src="{{ mix('js/dashboard.js') }}"></script>
    <script src="{{ mix('js/vendor.bundle.base.js') }}"></script>

    <script>
        // Replace feather icons after page load
        document.addEventListener("DOMContentLoaded", function() {
            const themeSwitcher = document.getElementById("theme-switcher");
            const box = document.querySelector(".box"); 
            const htmlElement = document.documentElement;

            // Check for saved theme  in localStorage
            const savedTheme = localStorage.getItem("theme");
            if (savedTheme) {
                htmlElement.setAttribute("data-bs-theme", savedTheme);
                box.classList.toggle("dark", savedTheme === "dark");
                themeSwitcher.checked = savedTheme === "dark"; 
            }

            themeSwitcher.addEventListener("change", function() {
                const newTheme = themeSwitcher.checked ? "dark" : "light";
                htmlElement.setAttribute("data-bs-theme", newTheme);
                box.classList.toggle("dark", newTheme === "dark"); 
                localStorage.setItem("theme", newTheme); // Save the theme preference
            });

        document.addEventListener('click', function(event) {
            // Get the dropdown element
            const dropdownToggle = document.getElementById('drop2');
            const dropdownMenu = dropdownToggle.nextElementSibling; // The dropdown menu

            // Check if the clicked element is not the dropdown toggle and not inside the dropdown menu
            if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                // If the dropdown is shown, hide it
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });

        @if(session('showpasswordModal'))
            $(document).ready(function() {
                // Initialize and show the modal using Bootstrap 5 JavaScript API
                var myModal = new bootstrap.Modal(document.getElementById('passwordChangeModal'), {
                    backdrop: 'static', // Prevent closing modal when clicking outside
                    keyboard: false      // Prevent closing modal with Escape key
                });
                myModal.show();  // Show the modal
            });
        @endif
        @if(session('showDpasswordModal'))
            $(document).ready(function() {
                // Initialize and show the modal using Bootstrap 5 JavaScript API
                var myModal = new bootstrap.Modal(document.getElementById('showDpasswordModal'), {
                    backdrop: 'static', // Prevent closing modal when clicking outside
                    keyboard: false      // Prevent closing modal with Escape key
                });
                myModal.show();  // Show the modal
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Fail!',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('loginerror'))
            Swal.fire({
                icon: 'error',
                title: 'Access Denied!',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('loginSuccess'))
            // Show the login success modal
            Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: " {{ session('success') }}.",
                confirmButtonText: 'Ok'
            });
        @endif

        @if(session('success'))
            // Show the login success modal
            Swal.fire({
                icon: 'success',
                title: 'Congratulation!',
                text: " {{ session('success') }}.",
                confirmButtonText: 'Ok'
            });
        @endif        
    });

</script>

        @yield('js')
</body>

</html>
