<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
    <div class="container align-items-center d-flex justify-content-between">

        <!-- Logo -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Fanya Logo" style="height: 40px;">
        </a>

               <!-- Search Bar -->
        <form class="d-none d-md-flex flex-grow-1 mx-4" style="max-width: 500px;" 
              action="{{ route('user.search') }}" method="GET">
            <div class="input-group">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       class="form-control border-end-0 bg-light" 
                       placeholder="Cari judul, penulis, atau penerbit..." 
                       style="border-radius: 50px 0 0 50px;">
                <button type="submit" 
                        class="input-group-text bg-primary text-white border-start-0" 
                        style="border-radius: 0 50px 50px 0; cursor: pointer; border: none;">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

                <!-- Mobile Search (optional) -->
        <div class="d-md-none w-100 mt-2">
            <form action="{{ route('user.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           class="form-control" 
                           placeholder="Cari buku...">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>



        <!-- Masuk | Daftar -->
        <div>
            @auth
                <a href="{{route('akun.index')}}" class="btn btn-primary">Akun Saya</a>
        
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-link">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
            @endauth
        </div>
        

    </div>
</nav>
