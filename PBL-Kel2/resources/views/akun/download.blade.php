@extends('akun.layout')

@section('content')
<h4>Download</h4>

<div class="card mt-3 shadow-sm">
    <div class="card-body">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>NO</th>
                    <th>NAMA FILE</th>
                    <th>FILE</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contoh data, bisa diganti pakai @foreach --}}
                <tr>
                    <td>1</td>
                    <td class="text-start">Template Buku Monograf Penerbit Fanya</td>
                    <td>
                        <a href="{{ asset('files/template-monograf.pdf') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Liat File
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="text-start">Template Buku Referensi Individu Penerbit Fanya</td>
                    <td>
                        <a href="{{ asset('files/template-referensi.pdf') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Liat File
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="text-start">Template Buku Kolaborasi Penerbit Fanya - Untuk Editor</td>
                    <td>
                        <a href="{{ asset('files/template-kolaborasi-editor.pdf') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Liat File
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td class="text-start">Template Buku Kolaborasi Penerbit Fanya - Untuk Penulis</td>
                    <td>
                        <a href="{{ asset('files/template-kolaborasi-penulis.pdf') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> Liat File
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
