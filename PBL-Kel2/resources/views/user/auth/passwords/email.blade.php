<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password | Fanya Publishing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    {{-- Header Tanpa Search dan Tombol Masuk/Daftar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <img src="{{ asset('img/logo.png') }}" alt="Fanya Logo" style="height: 40px;">
        </div>
    </nav>

    {{-- Konten Lupa Password --}}
    <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="p-4 rounded border text-center" style="width: 400px;">
            <h5 class="fw-bold mb-4">Lupa Password</h5>
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger text-start">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <p class="text-start mb-4">Masukkan alamat email Anda dan kami akan mengirimkan kode OTP untuk mereset password Anda.</p>
            
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control rounded-pill @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background-color: #a5b4fc;">Kirim Kode Reset</button>
            </form>
            <p class="mt-3 small"><a href="{{ route('login') }}">Kembali ke halaman login</a></p>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-dark text-light pt-5">
        <div class="container pb-4">
            <div class="row">
    
                <!-- Logo & Alamat -->
                <div class="col-md-4 mb-4">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Fanya" style="height: 50px;">
                    <p class="mt-3">Jalan Pasir Sebelah No.30, Pasie Nan Tigo, Koto Tangah, Kota Padang</p>
                    <a href="#" class="text-light text-decoration-none d-block mb-2">SHOW ON MAP</a>
                    <div>
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
    
                <!-- Kontak -->
                <div class="col-md-4 mb-4 border-start ps-4">
                    <h6 class="fw-bold">Need Help</h6>
                    <p class="text-danger fs-5 fw-bold mb-1">+62 813-2455-8686</p>
                    <p class="mb-0 small">Monday - Friday: 09:00–20:30</p>
                    <p class="small">Saturday: 11:00–15:00</p>
                    <p class="mt-2">admin@fanyapublishing.com</p>
                </div>
    
                <!-- Link Navigasi -->
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold">Layanan</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">• Buku Individu</a></li>
                        <li><a href="#" class="text-light text-decoration-none">• Buku Kolaborasi</a></li>
                    </ul>
                </div>
    
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold">Lainnya</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">• Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-light text-decoration-none">• Kebijakan & Privasi</a></li>
                        <li><a href="#" class="text-light text-decoration-none">• Bantuan</a></li>
                        <li><a href="#" class="text-light text-decoration-none">• Hubungi Kami</a></li>
                    </ul>
                </div>
    
            </div>
    
            <hr class="border-light mt-0">
            <div class="text-center pb-3">
                © 2025 CV. Fanya Bintang Sejahtera
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
