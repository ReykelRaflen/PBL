@extends('akun.layout')

@section('content')
<h4>Kolaborasi</h4>

<div class="card mt-3 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <div>
                Show
                <select class="form-select d-inline-block w-auto">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                entries
            </div>
            <div>
                <input type="text" class="form-control" placeholder="Search...">
            </div>
        </div>

        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>JUDUL BUKU</th>
                    <th>STATUS</th>
                    <th>BAB</th>
                </tr>
            </thead>
            <tbody>
                {{-- Jika tidak ada data --}}
                {{-- <tr>
                    <td colspan="4">No data available in table</td>
                </tr> --}}

                {{-- Contoh data kolaborasi --}}
                <tr>
                    <td>1</td>
                    <td class="text-start">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/buku-sample.jpg') }}" alt="Sampul Buku" width="40" height="60">
                            <div>
                                <strong>Analisis Laporan Keuangan Syariah</strong><br>
                                <small class="text-muted">Teori dan praktik</small><br>
                                <small>ISBN: -</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-danger">Cancel</span></td>
                    <td>BAB</td>
                </tr>

            </tbody>
        </table>

        <div class="d-flex justify-content-between">
            <span>Showing 1 to 1 of 1 entries</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link">Previous</a></li>
                    <li class="page-item active"><a class="page-link">1</a></li>
                    <li class="page-item disabled"><a class="page-link">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
