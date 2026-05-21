<!doctype html>
<html lang="en">
<head>
    <title>Login | Sistem Manajemen Parkir</title>
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
        .input-icon {
            position: relative;
        }
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4680ff;
        }
        .input-icon input {
            padding-left: 45px;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .role-badge:hover {
            transform: translateY(-2px);
        }
        .role-badge.admin { background: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
        .role-badge.guru { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .role-badge.siswa { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }
        .role-badge.petugas { background: #f3e5f5; color: #7b1fa2; border: 1px solid #ce93d8; }
        .role-badge i { font-size: 1rem; }
        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }
        .login-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 12px;
            margin-top: 15px;
        }
        .login-info small {
            color: #6c757d;
        }
    </style>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
    <!-- Pre-loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main" style="background-image: url({{ asset('assets/images/authentication/img-auth-bg-2.jpg') }})">
        <div class="auth-wrapper v2">
            <div class="auth-sidecontent">
                <div class="d-flex align-items-center w-100 justify-content-center">
                    <div class="col-md-8">
                        <h1 class="text-white mb-5">Sistem Manajemen Parkir</h1>
                        <p class="text-white">Sistem manajemen parkir yang cepat, mudah, dan terintegrasi untuk kendaraan Anda.</p>

                        <div class="row mt-5">
                            <div class="col-3 text-center">
                                <i class="ti ti-car text-white" style="font-size: 2rem;"></i>
                                <p class="text-white mt-2 mb-0"><small>Manajemen Kendaraan</small></p>
                            </div>
                            <div class="col-3 text-center">
                                <i class="ti ti-receipt text-white" style="font-size: 2rem;"></i>
                                <p class="text-white mt-2 mb-0"><small>Transaksi Cepat</small></p>
                            </div>
                            <div class="col-3 text-center">
                                <i class="ti ti-chart-line text-white" style="font-size: 2rem;"></i>
                                <p class="text-white mt-2 mb-0"><small>Laporan Real-time</small></p>
                            </div>
                            <div class="col-3 text-center">
                                <i class="ti ti-shield text-white" style="font-size: 2rem;"></i>
                                <p class="text-white mt-2 mb-0"><small>Aman & Terpercaya</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="auth-form">
                <div class="card my-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="ti ti-parking text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-center f-w-500 mb-2">Login Aplikasi</h4>
                        <p class="text-center text-muted small mb-4">Masukkan Username dan Password untuk login</p>

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="ph ph-warning-circle me-2"></i>
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="ph ph-warning-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ url('/login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-icon">
                                    <i class="ph ph-user-circle"></i>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                           placeholder="Masukkan username" 
                                           value="{{ old('username') }}" required autofocus />
                                </div>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-icon">
                                    <i class="ph ph-lock"></i>
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required />
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary shadow px-sm-4 w-100">
                                    <i class="ph ph-sign-in me-2"></i>Login
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-3">
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        
        // Auto focus on login field
        document.querySelector('input[name="username"]').focus();
    </script>
</body>
</html>