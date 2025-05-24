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
        section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
        }
        .book-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center; /* Centers the items horizontally */
    gap: 20px; /* Space between items */
    margin: 0;  /* Remove any default margin */
    padding: 0; /* Remove any default padding */
}

.book-item {
    border: 1px solid #d1d1d1; /* Border for the book item */
    border-radius: 10px; /* Rounded corners */
    padding: 15px; /* Padding inside item */
    width: 200px; /* Fixed width for book items */
    text-align: center; /* Centers the text within each book item */
    margin: 20px; /* Space between items */
}

.book-items {
            border: 1px solid #ccc; /* Warna dan ketebalan border */
            padding: 10px;          /* Jarak antara border dan isi */
            margin-bottom: 10px;    /* Jarak antara setiap bab */
            border-radius: 5px;     /* Sudut border yang melengkung */
        }

.book-item img {
    max-width: 100%; /* Ensures images are responsive */
    height: auto; /* Maintains aspect ratio */
    border-radius: 10px; /* Rounded corners for images */
}
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 10px;
        }
        .chapter-item, .payment-info {
            border: 1px solid #d1d1d1;
            border-radius: 10px;
            padding: 15px;
        }
    </style>
</head>
<body>

    @include('user.partials.header')

    <main class="py-4 container">
        @yield('content')

        <section class="collaboration">
            <h2 style="text-align: center;">Buku Kolaborasi</h2>
            <div class="book-list">
    <!-- Repeat this block for each book -->
    <div class="book-item">
        <img src="{{ asset('images/book1.jpg') }}" alt="Book 1">
        <h3>Antologi Puisi Nusantara</h3>
        <button class="btn btn-primary">Lihat Bab</button>
    </div>
    <!-- Add more book items as needed -->
    <div class="book-item">
        <img src="{{ asset('images/book2.jpg') }}" alt="Book 2">
        <h3>Title of Book 2</h3>
        <button class="btn btn-primary">Lihat Bab</button>
    </div>
    <div class="book-item">
        <img src="{{ asset('images/book3.jpg') }}" alt="Book 3">
        <h3>Title of Book 3</h3>
        <button class="btn btn-primary">Lihat Bab</button>
    </div>
</div>
        </section>
        
        <section class="chapters">
            <h2>Daftar Bab</h2>
            <div class="book-list">
            <div class="book-items">
        <div>
            <h3>Bab 1: Pesona Alam Flores</h3>
            <p>Rangkaian puisi tentang keindahan alam dan budaya Flores</p>
            <p>Deadline: 30 Mei 2025 | Rp 500.000</p>
        </div>
        <button class="btn btn-primary">Ambil Bab</button>
    </div>
            <div class="book-items">
        <div>
            <h3>Bab 2: Misteri Bromo</h3>
            <p>Puisi tentang kemegahan dan mistis Gunung Bromo</p>
            <p>Deadline: 5 Juni 2025 | Rp 500.000</p>
        </div>
        <button style="background-color: #d3d3d3; color: #333333; border: none; padding: 0.5em 1em; border-radius: 6px; cursor: pointer;">Bab Sudah Diambil</button>
    </div>

            <!-- Add more chapters as needed -->
        </section>

        <section class="payment-info">
        <h4 class="mt-4">Informasi Pembayaran</h4>
        <p>Bank BRI: 1234-5678-9101 a.n. CV. Fanya Bintang Sejahtera</p>
            <form>
            <div class="mb-3">
                    <label for="buktipembayaran" class="form-label">Upload Bukti Pembayaran (JPG/JPEG/PNG)</label>
                    <input type="file" name="file_upload" class="form-control" id="buktipembayaran" accept=".jpg, .jpeg, .png">
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary btn-right">Konfirmasi Pembayaran</button>
                </div>
            </form>
        </section>
    </main>

    @include('user.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>