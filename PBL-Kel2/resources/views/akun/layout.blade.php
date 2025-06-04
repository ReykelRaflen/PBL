<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Akun Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f7f7f7;
        }
        .sidebar {
            background-color: #f6f6f7;
            height: 100vh;
            box-shadow: 2px 0 8px rgba(253, 253, 253, 0.1);
        }
        .sidebar a {
            display: block;
            padding: 15px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar a.active, .sidebar a:hover {
            background-color: #eee;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar p-0">
            <div class="p-4 text-center border-bottom">
                <form action="#" method="post" enctype="multipart/form-data">
                    <label for="fotoProfil">
                        <img src="{{ asset('storage/foto_profil/default.png') }}" width="80" class="mb-2 rounded-circle" style="cursor:pointer;">
                    </label>
                    <input type="file" id="fotoProfil" name="foto" style="display:none" onchange="this.form.submit()">
                </form>
                <h6 class="m-0">Mutiara Febrianti Rukmana</h6>
            </div>
            <a href="{{ route('akun.index') }}" class="{{ request()->routeIs('akun.index') ? 'active' : '' }}">Profil</a>
            <a href="{{ route('akun.kolaborasi') }}" class="{{ request()->routeIs('akun.kolaborasi') ? 'active' : '' }}">Kolaborasi</a>
            <a href="{{ route('akun.pembelian') }}" class="{{ request()->routeIs('akun.pembelian') ? 'active' : '' }}">Pembelian</a>
            <a href="{{ route('akun.download') }}" class="{{ request()->routeIs('akun.download') ? 'active' : '' }}">Download</a>
        </div>

        <!-- Konten -->
        <div class="col-md-9 p-4" style="background-color: #b7b8bf; min-height: 100vh;">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
