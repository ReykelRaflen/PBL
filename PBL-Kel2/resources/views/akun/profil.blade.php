@extends('akun.layouts')

@section('content')
<div class="content-area">
    <h4 class="page-title">
        <i class="fas fa-user-edit me-2"></i>Profil Saya
    </h4>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('akun.profil.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Informasi Dasar -->
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #a5b4fc 0%, #667eea 100%);">
                <h5 class="mb-0 text-white"><i class="fas fa-user me-2"></i>Informasi Dasar</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-1" style="color: #a5b4fc;"></i>Nama Lengkap *
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name ?? '') }}" 
                               required
                               placeholder="Masukkan nama lengkap Anda">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1" style="color: #a5b4fc;"></i>Email
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               value="{{ $user->email ?? '' }}" 
                               readonly
                               style="background-color: #f8f9fa; cursor: not-allowed;">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Email tidak dapat diubah
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-1" style="color: #a5b4fc;"></i>No Telepon
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone ? str_replace('62', '', $user->phone) : '') }}"
                                   placeholder="8123456789"
                                   pattern="[0-9]{10,13}"
                                   title="Masukkan nomor telepon yang valid (10-13 digit)">
                        </div>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: 8123456789 (tanpa +62 atau 0)</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">
                            <i class="fas fa-venus-mars me-1" style="color: #a5b4fc;"></i>Jenis Kelamin
                        </label>
                        <select class="form-select @error('gender') is-invalid @enderror" 
                                id="gender" 
                                name="gender">
                            <option value="">Pilih Jenis Kelamin...</option>
                            <option value="Laki-laki" {{ old('gender', $user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('gender', $user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Personal -->
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Informasi Personal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="birthdate" class="form-label">
                            <i class="fas fa-calendar-alt me-1 text-info"></i>Tanggal Lahir
                        </label>
                        <input type="date" 
                               class="form-control @error('birthdate') is-invalid @enderror" 
                               id="birthdate" 
                               name="birthdate" 
                               value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}">
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="agama" class="form-label">
                            <i class="fas fa-pray me-1 text-info"></i>Agama
                        </label>
                        <select class="form-select @error('agama') is-invalid @enderror"
                                id="agama"
                                name="agama">
                            <option value="">Pilih Agama...</option>
                            <option value="Islam" {{ old('agama', $user->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $user->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $user->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $user->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $user->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $user->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">
                        <i class="fas fa-map-marker-alt me-1 text-info"></i>Alamat
                    </label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address"
                              name="address"
                              rows="3"
                              placeholder="Masukkan alamat lengkap Anda"
                              style="border-radius: 15px;">{{ old('address', $user->address ?? '') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tombol Simpan Profil -->
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-save me-2"></i>Simpan Profil
            </button>
        </div>
    </form>

    <!-- Form Ubah Password (Terpisah) -->
    <form action="{{ route('akun.password.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-lock me-2"></i>Ubah Password
                    <small class="ms-2 opacity-75">(Opsional)</small>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-key me-1 text-success"></i>Password Saat Ini
                        </label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password"
                               placeholder="Masukkan password saat ini">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="new_password" class="form-label">
                            <i class="fas fa-lock me-1 text-success"></i>Password Baru
                        </label>
                        <input type="password" 
                               class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" 
                               name="new_password"
                               placeholder="Masukkan password baru">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="new_password_confirmation" class="form-label">
                            <i class="fas fa-check-double me-1 text-success"></i>Konfirmasi Password
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation"
                               placeholder="Ulangi password baru">
                    </div>
                </div>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Kosongkan jika tidak ingin mengubah password. Password minimal 8 karakter.
                </small>
                
                <!-- Tombol Ubah Password -->
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Custom styling untuk form profil */
.content-area {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.form-control:focus, .form-select:focus {
    animation: inputFocus 0.3s ease;
    border-color: #a5b4fc;
    box-shadow: 0 0 0 0.2rem rgba(165, 180, 252, 0.25);
}

@keyframes inputFocus {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Styling untuk textarea */
textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Custom styling untuk input group */
.input-group-text {
    font-weight: 600;
    font-size: 14px;
    background-color: #a5b4fc;
    color: white;
    border-color: #a5b4fc;
}

/* Button hover effect */
.btn-primary:hover, .btn-success:hover {
    animation: buttonPulse 0.6s ease;
}

@keyframes buttonPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Alert styling */
.alert {
    animation: slideInDown 0.5s ease-out;
    border: none;
    border-radius: 10px;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsif tambahan untuk layar kecil */
@media (max-width: 576px) {
    .form-label i {
        display: none;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
}
</style>
@endsection
