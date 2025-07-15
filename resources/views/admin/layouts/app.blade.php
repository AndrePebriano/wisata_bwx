<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link rel="icon" href="{{ asset('asset/image/LOGO.png') }}" type="image/png">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="{{ asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.min.css') }}">

    <style>
        .brand-link img {
            width: 35px;
            height: 35px;
            object-fit: cover;
        }

        .brand-link span {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .nav-sidebar .nav-link.active {
            background-color: #222222 !important;
            color: #fff;
        }

        .nav-sidebar .nav-link.active i {
            color: #fff;
        }

        .nav-sidebar .nav-link:hover {
            background-color: #1d1d1d;
            color: #fff;
        }

        .nav-icon {
            font-size: 1rem;
        }

        .nav-header {
            border-top: 1px solid #646464;
            margin-top: 1rem;
            padding-top: 0.5rem;
            font-size: 0.9rem;
            color: #c2c7d0;
            letter-spacing: 0.5px;
        }

        @media (max-width: 767.98px) {
            .brand-link span {
                display: none !important;
            }

            .brand-link img {
                margin: 0 auto;
            }

            .sidebar {
                padding-top: 1rem;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img src="{{ asset('asset/image/Logo hitam.png') }}" alt="Logo"
                style="max-width: 150px; animation: shake 1.5s infinite;">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white" style="background-color: #8A1E30;">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/home') }}" class="nav-link text-white">Dashboard</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link text-white p-0" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link d-flex align-items-center">
                <img src="{{ asset('admin/dist/img/LOGO.png') }}" alt="Logo"
                    class="brand-image img-circle elevation-3 me-2">
                <span class="brand-text fw-bold text-white fs-4 d-none d-sm-inline">Wisata Bwx</span>
            </a>

            <div class="sidebar">
                <!-- User Panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image me-2">
                        @if (Auth::user()->photo)
                            <img src="{{ asset('storage/photos/' . Auth::user()->photo) }}"
                                class="rounded-circle elevation-2 border" alt="User Image"
                                style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}"
                                class="rounded-circle elevation-2 border" alt="Default User Image"
                                style="width: 40px; height: 40px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="info">
                        <a href="#" class="d-block fw-bold">{{ Auth::user()->name }}</a>
                    </div>
                </div>

                <!-- SidebarSearch -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('admin.home') }}"
                                class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <!-- Tempat Wisata -->
                        <li class="nav-item">
                            <a href="{{ route('admin.tempat-wisata.index') }}"
                                class="nav-link {{ request()->routeIs('admin.tempat-wisata.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>Tempat Wisata</p>
                            </a>
                        </li>

                        <!-- Kategori -->
                        <li class="nav-item">
                            <a href="{{ route('admin.kategori.index') }}"
                                class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori</p>
                            </a>
                        </li>

                        <!-- Fasilitas -->
                        <li class="nav-item">
                            <a href="{{ route('admin.fasilitas.index') }}"
                                class="nav-link {{ request()->routeIs('admin.fasilitas.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-concierge-bell"></i>
                                <p>Fasilitas</p>
                            </a>
                        </li>

                        <!-- Header -->
                        <li class="nav-header">PENGATURAN</li>

                        <!-- Edit Profile -->
                        <li class="nav-item">
                            <a href="{{ route('admin.edit') }}"
                                class="nav-link {{ request()->routeIs('admin.edit.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Edit Profile</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        @yield('script')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('admin/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('admin/dist/js/demo.js') }}"></script>
    <script src="{{ asset('admin/dist/js/pages/dashboard.js') }}"></script>

</body>

</html>
