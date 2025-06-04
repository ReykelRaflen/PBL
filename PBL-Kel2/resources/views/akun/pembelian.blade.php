@extends('akun.layout')

@section('content')
<h4>Pembelian</h4>

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

        <table class="table table-bordered table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th>NO</th>
                    <th>INVOICE</th>
                    <th>STATUS PESANAN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                {{-- Simulasi kosong --}}
                <tr>
                    <td colspan="4">No data available in table</td>
                </tr>

                {{-- Contoh jika ada data --}}
                <!--
                <tr>
                    <td>1</td>
                    <td>INV-20250603-01</td>
                    <td><span class="badge bg-warning text-dark">Belum Bayar</span></td>
                    <td><a href="#" class="btn btn-sm btn-primary">Lihat</a></td>
                </tr>
                -->
            </tbody>
        </table>

        <div class="d-flex justify-content-between">
            <span>Showing 0 to 0 of 0 entries</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link">Previous</a></li>
                    <li class="page-item disabled"><a class="page-link">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
