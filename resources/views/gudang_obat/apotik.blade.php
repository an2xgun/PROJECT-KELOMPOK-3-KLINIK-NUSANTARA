@extends('layout.app')

@section('content')

<div class="container-fluid">

    {{-- JUDUL HALAMAN --}}
    <h3 class="mb-4">Apotek</h3>

    {{-- FILTER BAR --}}
    <div class="d-flex gap-2 mb-3">

        {{-- Filter Jenis --}}
        <select class="form-select w-auto" id="filter-jenis">
            <option value="semua">Semua</option>
            <option value="bpjs">BPJS</option>
            <option value="umum">Umum</option>
        </select>

        {{-- Filter Klinik --}}
        <select class="form-select w-auto" id="filter-klinik">
            <option value="utama">KLINIK UTAMA</option>
            <option value="gigi">KLINIK GIGI</option>
            <option value="ugd">UGD</option>
        </select>

        {{-- Tombol Pengaturan Cetak --}}
        <button class="btn btn-secondary">Pengaturan Cetak</button>

        {{-- Tombol Export Excel (dummy) --}}
        <a href="#" class="btn btn-success">
            Export Excel
        </a>
    </div>

    {{-- FILTER TANGGAL --}}
    <div class="d-flex mb-3">
        <input type="date" class="form-control w-auto" value="{{ date('Y-m-d') }}">
    </div>

    {{-- TAB STATUS --}}
    <div class="d-flex gap-2 mb-3">

        <a href="#" class="btn btn-success">
            Sedang Antri <span class="badge bg-light text-dark">3</span>
        </a>

        <a href="#" class="btn btn-outline-success">
            Telah Dilayani <span class="badge bg-light text-dark">5</span>
        </a>

        <a href="#" class="btn btn-outline-success">
            Semua <span class="badge bg-light text-dark">8</span>
        </a>

    </div>

    {{-- TABEL PASIEN --}}
    <div class="card">
        <div class="card-body p-0">

            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Dari</th>
                        <th>Jenis Bayar</th>
                        <th>Obat</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- Data Dummy --}}
                    <tr>
                        <td>001</td>
                        <td>Budi Santoso</td>
                        <td>KLINIK UTAMA</td>
                        <td>BPJS</td>
                        <td>Paracetamol</td>
                        <td><span class="badge bg-warning text-dark">Sedang Antri</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>

                    <tr>
                        <td>002</td>
                        <td>Siti Aminah</td>
                        <td>KLINIK GIGI</td>
                        <td>UMUM</td>
                        <td>Amoxicillin</td>
                        <td><span class="badge bg-success">Telah Dilayani</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>

                    <tr>
                        <td>003</td>
                        <td>Agus Hadi</td>
                        <td>UGD</td>
                        <td>BPJS</td>
                        <td>Antalgin</td>
                        <td><span class="badge bg-warning text-dark">Sedang Antri</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection
