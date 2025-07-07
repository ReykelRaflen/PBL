<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya | Fanya Publishing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Style - sama seperti login */
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar Style */
        .sidebar {
            background-color: #ffffff;
            min-height: calc(100vh - 76px);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 0 10px 10px 0;
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 15px 20px;
            border-radius: 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
            color: #a5b4fc;
            border-left-color: #a5b4fc;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: #e8f0fe;
            color: #a5b4fc;
            border-left-color: #a5b4fc;
            font-weight: 600;
        }

        /* Profile Section */
        .profile-section {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            background: linear-gradient(135deg, #a5b4fc 0%, #667eea 100%);
            color: white;
            border-radius: 0 10px 0 0;
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .profile-img:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Loading overlay untuk upload */
        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 12px;
        }

        .profile-img-container {
            position: relative;
            display: inline-block;
        }

        /* Content Area */
        .content-area {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px 0;
        }

        /* Form Styling - sama seperti login */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 25px;
            padding: 12px 20px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #a5b4fc;
            box-shadow: 0 0 0 0.2rem rgba(165, 180, 252, 0.25);
            transform: translateY(-2px);
        }

        /* Button Styling - sama seperti login */
        .btn-primary {
            background-color: #a5b4fc;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(165, 180, 252, 0.3);
        }

        .btn-primary:hover {
            background-color: #8b9cf7;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(165, 180, 252, 0.4);
        }

        .btn-outline-danger {
            border-radius: 50%;
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Alert Styling */
        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
            border: none;
            padding: 20px;
        }

        .page-title {
            color: #495057;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #a5b4fc;
            display: inline-block;
        }

        /* Input Group */
        .input-group-text {
            background-color: #a5b4fc;
            color: white;
            border: 1px solid #a5b4fc;
            border-radius: 25px 0 0 25px;
            font-weight: 600;
        }

        .input-group .form-control {
            border-radius: 0 25px 25px 0;
            border-left: none;
        }

        .input-group .form-control:focus {
            border-color: #a5b4fc;
            border-left: 1px solid #a5b4fc;
        }

        /* Logout button */
        .nav-link.text-danger:hover {
            background-color: #f8d7da !important;
            color: #dc3545 !important;
            border-left-color: #dc3545 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                border-radius: 0;
                min-height: auto;
            }

            .profile-section {
                border-radius: 0;
                padding: 20px 15px;
            }

            .content-area {
                margin: 10px 0;
                padding: 20px 15px;
                border-radius: 10px;
            }
        }
    </style>
</head>

<body>
    @include('user.partials.header')

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="sidebar">
                    <!-- Profile Section -->
                    <div class="profile-section">
                        <div class="profile-img-container">
                            <form action="{{ route('akun.foto.update') }}" method="post" enctype="multipart/form-data"
                                id="fotoForm">
                                @csrf
                                <label for="fotoProfil" title="Klik untuk mengubah foto profil"
                                    style="cursor: pointer;">
                                    @if(Auth::user()->foto && file_exists(public_path('uploads/foto_profil/' . Auth::user()->foto)))
                                        <img src="{{ asset('uploads/foto_profil/' . Auth::user()->foto) }}"
                                            class="profile-img" alt="Profile" id="profileImage">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" class="profile-img"
                                            alt="Default Profile" id="profileImage"
                                            onerror="this.src='https://via.placeholder.com/80x80/ffffff/a5b4fc?text={{ strtoupper(substr(Auth::user()->name, 0, 1)) }}'">
                                    @endif

                                </label>
                                <input type="file" id="fotoProfil" name="foto" style="display:none"
                                    accept="image/jpeg,image/png,image/jpg,image/gif" onchange="handleFileSelect(this)">
                            </form>

                            <!-- Loading overlay -->
                            <div class="upload-overlay" id="uploadOverlay">
                                <div class="text-center">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div style="font-size: 10px; margin-top: 5px;">Uploading...</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-3 mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                        <small class="opacity-75">{{ Auth::user()->email }}</small>

                        @if(Auth::user()->foto)
                            <div class="mt-2">
                                <form action="{{ route('akun.foto.delete') }}" method="post" style="display: inline;"
                                    id="deleteFotoForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDeletePhoto()" title="Hapus foto profil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="nav flex-column py-3">
                        <a href="{{ route('akun.profil') }}"
                            class="nav-link {{ request()->routeIs('akun.profil') || request()->routeIs('akun.index') ? 'active' : '' }}">
                            <i class="fas fa-user me-3"></i>Profil Saya
                        </a>
                        {{-- <a href="{{ route('akun.kolaborasi') }}"
                            class="nav-link {{ request()->routeIs('akun.kolaborasi') ? 'active' : '' }}">
                            <i class="fas fa-handshake me-3"></i>Kolaborasi
                        </a> --}}
                        <!-- Add this menu item to your existing navigation -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('akun.kolaborasi') ? 'active' : '' }}" 
                            href="{{ route('akun.kolaborasi') }}">
                                <i class="fas fa-users me-2"></i>
                                Kolaborasi
                                @php
                                    $pendingKolaborasi = \App\Models\PesananKolaborasi::where('user_id', auth()->id())
                                        ->whereIn('status_penulisan', ['dapat_mulai', 'revisi'])
                                        ->count();
                                @endphp
                                @if($pendingKolaborasi > 0)
                                    <span class="badge bg-warning rounded-pill ms-1">{{ $pendingKolaborasi }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Penerbitan Individu Menu -->
                        <a href="{{ route('akun.penerbitan-individu') }}"
                            class="nav-link {{ request()->routeIs('akun.penerbitan-individu') ? 'active' : '' }}">
                            <i class="fas fa-book-open me-3"></i>Penerbitan Individu
                            @php
                                $penerbitanPending = \App\Models\PenerbitanIndividu::where('user_id', auth()->id())
                                    ->where(function($query) {
                                        $query->where('status_pembayaran', 'menunggu')
                                              ->orWhere('status_pembayaran', 'ditolak')
                                              ->orWhere('status_penerbitan', 'dapat_mulai')
                                              ->orWhere('status_penerbitan', 'revisi');
                                    })
                                    ->count();
                            @endphp
                            @if($penerbitanPending > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ $penerbitanPending }}</span>
                            @endif
                        </a>

                        <a href="{{ route('akun.pembelian') }}"
                            class="nav-link {{ request()->routeIs('akun.pembelian') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart me-3"></i>Pembelian
                        </a>
                        <a href="{{ route('akun.templates.index') }}"
                            class="nav-link {{ request()->routeIs('akun.templates.*') ? 'active' : '' }}">
                            <i class="fas fa-download me-3"></i>Template Penulisan
                        </a>

                        <hr class="my-3 mx-3">
                        <a href="{{ route('logout') }}" class="nav-link text-danger"
                            onclick="event.preventDefault(); confirmLogout();">
                            <i class="fas fa-sign-out-alt me-3"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </nav>

                        
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                @yield('content')
            </div>
        </div>
    </div>

    @include('user.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 2MB. Silakan pilih file yang lebih kecil.',
                        confirmButtonColor: '#a5b4fc'
                    });
                    input.value = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tipe File Tidak Didukung',
                        text: 'Hanya file gambar JPG, JPEG, PNG, atau GIF yang diperbolehkan.',
                        confirmButtonColor: '#a5b4fc'
                    });
                    input.value = '';
                    return;
                }

                // Tampilkan overlay
                document.getElementById('uploadOverlay').style.display = 'flex';

                // Submit form setelah validasi berhasil
                document.getElementById('fotoForm').submit();
            }
        }

        function confirmDeletePhoto() {
            Swal.fire({
                title: 'Hapus Foto Profil?',
                text: "Foto profil akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteFotoForm').submit();
                }
            });
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Keluar dari akun?',
                text: "Anda yakin ingin keluar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#a5b4fc',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>

</html>