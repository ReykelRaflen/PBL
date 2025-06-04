@extends('akun.layout')

@section('content')
<div class="p-4 rounded shadow-sm" style="background-color: white;">
    <h4 class="mb-4">Profil Saya</h4>
    <form class="mt-2">
        <div class="row mb-3">
            <div class="col">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="Mutiara Febrianti Rukmana">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label>No Telepon</label>
                <input type="text" class="form-control" value="082391936097">
            </div>
            <div class="col">
                <label>Agama</label>
                <select class="form-control">
                    <option selected disabled>Pilih Agama...</option>
                    <option>Islam</option>
                    <option>Kristen</option>
                    <option>Hindu</option>
                    <option>Budha</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label>Tanggal Lahir</label>
                <input type="date" class="form-control">
            </div>
            <div class="col">
                <label>Jenis Kelamin</label>
                <select class="form-control">
                    <option>Laki-laki</option>
                    <option>Perempuan</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label>Alamat</label>
            <textarea class="form-control" rows="2"></textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">Simpan</button>
        </div>
    </form>
</div>
@endsection
