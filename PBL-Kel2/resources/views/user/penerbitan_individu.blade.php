<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanya Publishing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
        }
        .card-package {
            border: 1px solid #d1d1d1;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-package h5 {
            font-size: 1.5rem;
        }
        .card-package p {
            font-size: 1.25rem;
        }
        section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
        }
        .form-control, .form-select {
            border-width: 2px;
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    @include('user.partials.header')

    <main class="py-4 container">
        @yield('content')
        
        <!-- Start Payment Information Section -->
        <section>
            <h2 style="text-align: center;">Pilihan Paket Penerbitan Buku</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card-package text-center">
                        <h5 class="card-title">Basic</h5>
                        <p class="card-text">Rp 500.000</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-package text-center">
                        <h5 class="card-title">Bisnis</h5>
                        <p class="card-text">Rp 1.000.000</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-package text-center">
                        <h5 class="card-title">Pro</h5>
                        <p class="card-text">Rp 2.000.000</p>
                    </div>
                </div>
            </div>
            <h4 class="mt-4">Informasi Pembayaran</h4>
            <p>Bank BRI: 1234-5678-9101 a.n. CV. Fanya Bintang Sejahtera</p>
        </section>
        <!-- End Payment Information Section -->

        <!-- Start Submission Form Section -->
        <section>
            <h2>Formulir Pengajuan Judul Buku</h2>
            <form>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select id="kategori" class="form-select">
                        <option>Pilih Kategori</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" required>
                </div>
                <div class="mb-3">
                    <label for="penulis" class="form-label">Nama Penulis</label>
                    <input type="text" class="form-control" id="penulis" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                    <textarea class="form-control" id="deskripsi" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="naskah" class="form-label">Upload Naskah (PDF/Doc)</label>
                    <input type="file" class="form-control" id="naskah">
                </div>
                <h4>Pilih Paket</h4>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paket" id="basic" value="basic" required>
                    <label class="form-check-label" for="basic">Basic</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paket" id="bisnis" value="bisnis" required>
                    <label class="form-check-label" for="bisnis">Bisnis</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paket" id="pro" value="pro" required>
                    <label class="form-check-label" for="pro">Pro</label>
                </div>
                <div class="mb-3">
                    <label for="buktipembayaran" class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" class="form-control" id="buktipembayaran">
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary btn-right">Kirim Pengajuan</button>
                </div>
            </form>
        </section>
        <!-- End Submission Form Section -->
    </main>

    @include('user.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>