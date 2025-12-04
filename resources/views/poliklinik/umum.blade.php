@extends('layout.app')

@section('content')

<div class="container-fluid">

    {{-- JUDUL HALAMAN --}}
    <h3 class="mb-4">🏥 Poliklinik Umum</h3>

    {{-- FILTER BAR --}}
    <div class="d-flex gap-2 mb-3">

        {{-- Filter Jenis Pembayaran --}}
        <select class="form-select w-auto" id="filter-jenis">
            <option value="semua">Semua</option>
            <option value="bpjs">BPJS</option>
            <option value="umum">Umum</option>
        </select>

        {{-- Filter Status --}}
        <select class="form-select w-auto" id="filter-status">
            <option value="ANTRI">Sedang Antri</option>
            <option value="PERIKSA">Sedang Diperiksa</option>
            <option value="SELESAI">Selesai</option>
        </select>

        {{-- Tombol Pengaturan Cetak --}}
        <button class="btn btn-secondary">Pengaturan Cetak</button>

        {{-- Export Excel --}}
        <a href="#" class="btn btn-success">Export Excel</a>
    </div>

    {{-- FILTER TANGGAL --}}
    <div class="d-flex mb-3">
        <input type="date" class="form-control w-auto" value="{{ date('Y-m-d') }}">
    </div>

    {{-- TAB STATUS --}}
    <div class="d-flex gap-2 mb-3">

        <a href="#" class="btn btn-success">
            Sedang Antri
            <span class="badge bg-light text-dark">{{ $count_antri }}</span>
        </a>

        <a href="#" class="btn btn-outline-success">
            Diperiksa
            <span class="badge bg-light text-dark">{{ $count_periksa }}</span>
        </a>

        <a href="#" class="btn btn-outline-success">
            Selesai
            <span class="badge bg-light text-dark">{{ $count_selesai }}</span>
        </a>

        <a href="#" class="btn btn-outline-success">
            Semua
            <span class="badge bg-light text-dark">{{ $count_semua }}</span>
        </a>

    </div>

    {{-- TABEL PASIEN POLI UMUM --}}
    <div class="card">
        <div class="card-body p-0">

            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Keluhan</th>
                        <th>Jenis Bayar</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($pasien as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->keluhan }}</td>
                        <td>{{ strtoupper($p->jenis_bayar) }}</td>

                        <td>
                            @if($p->status == 'ANTRI')
                                <span class="badge bg-warning text-dark">Sedang Antri</span>
                            @elseif($p->status == 'PERIKSA')
                                <span class="badge bg-primary">Sedang Diperiksa</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('pemeriksaan.create', $p->id) }}" 
                               class="btn btn-sm btn-primary">
                                Periksa
                            </a>
                        </td>
                    </tr>
                    @empty

                    <tr>
                        <td colspan="6" class="text-center p-3">
                            Tidak ada pasien
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection
