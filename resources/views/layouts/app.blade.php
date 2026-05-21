<!doctype html>
<html lang="id">

<head>
    <title>@yield('title') | Sistem Manajemen Parkir</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="description" content="Sistem Manajemen Parkir" />
    <meta name="author" content="Parkir System" />
    <meta name="theme-color" content="#1e293b" />

    <!-- [Favicon] icons -->
    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/svg+xml" />

    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/phosphor-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/tabler-icons.min.css') }}" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />

    <style>
        .user-info {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4680ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .pc-sidebar .user-info {
            transition: all 0.3s;
        }

        .pc-sidebar .user-info:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .badge-petugas {
            background: #7b1fa2;
            color: white;
        }

        .badge-admin {
            background: #1565c0;
            color: white;
        }

        .badge-owner {
            background: #e65100;
            color: white;
        }
    </style>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header d-flex align-items-center gap-2">
                <a href="{{ url('/dashboard') }}" class="b-brand d-flex align-items-center gap-2">
                    <div class="brand-logo">
                        <i class="ph ph-parking text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div class="brand-title">
                        <span class="fw-bold">Parkir<span class="text-primary">System</span></span>
                        <small class="text-white d-block">Manajemen Parkir</small>
                    </div>
                </a>
            </div>

            <div class="navbar-content">
                <!-- User Info -->
                <div class="user-info p-3 border-bottom border-secondary mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar-wrapper">
                            @php
                            $user = $_SESSION['user'] ?? null;
                            $namaUser = $user['nama_lengkap'] ?? 'User';
                            $role = $user['role'] ?? 'User';
                            $inisial = strtoupper(substr($namaUser, 0, 1));
                            @endphp

                            <div class="user-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px; font-size: 18px;">
                                {{ $inisial }}
                            </div>
                        </div>

                        <div class="user-details">
                            <div class="text-white small">
                                {{ $namaUser }}
                            </div>
                            <small class="text-muted">
                                @if($role == 'Admin')
                                <span class="badge bg-primary">Administrator</span>
                                @elseif($role == 'Petugas')
                                <span class="badge bg-success">Petugas</span>
                                @elseif($role == 'Owner')
                                <span class="badge bg-warning">Owner</span>
                                @else
                                <span class="badge bg-secondary">{{ $role }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <ul class="pc-navbar">
                    <!-- ==================== MENU UNTUK ADMIN ==================== -->
                    @if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'Admin')
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ url('/dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-house-line"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Manajemen Data</label>
                    </li>

                    <li class="pc-item {{ request()->is('user*') ? 'active' : '' }}">
                        <a href="{{ url('/user') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-users"></i></span>
                            <span class="pc-mtext">Kelola User</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('kendaraan*') ? 'active' : '' }}">
                        <a href="{{ url('/kendaraan') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-car"></i></span>
                            <span class="pc-mtext">Kelola Kendaraan</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('tarif*') ? 'active' : '' }}">
                        <a href="{{ url('/tarif') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-tag"></i></span>
                            <span class="pc-mtext">Kelola Tarif</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('area*') ? 'active' : '' }}">
                        <a href="{{ url('/area') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-map-pin"></i></span>
                            <span class="pc-mtext">Kelola Area Parkir</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Lainnya</label>
                    </li>

                    <li class="pc-item {{ request()->is('log*') ? 'active' : '' }}">
                        <a href="{{ url('/log') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-clock-counter-clockwise"></i></span>
                            <span class="pc-mtext">Log Aktivitas</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('shift*') ? 'active' : '' }}">
                        <a href="{{ url('/shift') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-clock"></i></span>
                            <span class="pc-mtext">Atur Shift</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('admin/appeal*') ? 'active' : '' }}">
                        <a href="{{ route('admin.appeal.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="fas fa-gavel"></i></span>
                            <span class="pc-mtext">Manajemen Appeal</span>
                        </a>
                    </li>
                    @endif

                    <!-- ==================== MENU UNTUK PETUGAS ==================== -->
                    @if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'Petugas')
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ url('/dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-house-line"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Transaksi</label>
                    </li>

                    <li class="pc-item {{ request()->is('transaksi*') ? 'active' : '' }}">
                        <a href="{{ url('/transaksi') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-receipt"></i></span>
                            <span class="pc-mtext">Transaksi Parkir</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('shift-saya*') ? 'active' : '' }}">
                        <a href="{{ url('/shift-saya') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-calendar"></i></span>
                            <span class="pc-mtext">Shift Saya</span>
                        </a>
                    </li>
                    @endif

                    <!-- ==================== MENU UNTUK OWNER ==================== -->
                    @if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'Owner')
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>

                    <li class="pc-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ url('/dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-house-line"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Laporan</label>
                    </li>

                    <li class="pc-item {{ request()->is('laporan*') ? 'active' : '' }}">
                        <a href="{{ url('/laporan') }}" class="pc-link">
                            <span class="pc-micon"><i class="ph ph-chart-line"></i></span>
                            <span class="pc-mtext">Laporan Parkir</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('appeal*') ? 'active' : '' }}">
                        <a href="{{ url('/appeal') }}" class="pc-link">
                            <span class="pc-micon"><i class="fas fa-gavel"></i></span>
                            <span class="pc-mtext">Appeal Saya</span>
                        </a>
                    </li>

                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ph ph-list"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none m-0 trig-drp-search"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-magnifying-glass"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3 py-2">
                                <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . ." />
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-sun-dim"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                                <i class="ph ph-moon"></i> <span>Dark</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                                <i class="ph ph-sun"></i> <span>Light</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                                <i class="ph ph-cpu"></i> <span>Default</span>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-diamonds-four"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a class="dropdown-item" href="#">
                                <i class="ph ph-user-circle"></i> My Account
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="ph ph-gear"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ url('/logout') }}">
                                <i class="ph ph-sign-out"></i> Logout
                            </a>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph ph-bell"></i>
                            <span class="badge bg-success pc-h-badge"></span>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown" style="min-width: 320px;">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Notifikasi</h5>
                            </div>
                            <div class="dropdown-body text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
                                <div class="text-center py-4">
                                    <i class="ph ph-bell-slash" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="mt-2 text-muted mb-0">Tidak ada notifikasi baru</p>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph ph-x-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Required JS -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>

    <script>
        layout_change('light');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        layout_theme_sidebar_change('false');
    </script>

    @stack('scripts')
</body>

</html>