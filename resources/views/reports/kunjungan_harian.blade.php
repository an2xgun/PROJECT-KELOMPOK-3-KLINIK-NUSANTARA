@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>
        <i class="bi bi-calendar-event"></i> Laporan Kunjungan Harian
    </h3>

    <!-- Filter Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ $dari->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ $sampai->format('Y-m-d') }}">
                </div>
                <div class="col-md-3" style="padding-top: 32px;">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
                <div class="col-md-3" style="padding-top: 32px;">
                    <button type="button" class="btn btn-secondary w-100" onclick="window.print()">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Total Kunjungan</h5>
                    <h2 style="color: #667eea; font-weight: 700;">{{ $total_kunjungan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Menunggu</h5>
                    <h2 style="color: #f39c12; font-weight: 700;">{{ $menunggu }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Sedang Dilayani</h5>
                    <h2 style="color: #3498db; font-weight: 700;">{{ $sedang_dilayani }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Selesai</h5>
                    <h2 style="color: #27ae60; font-weight: 700;">{{ $selesai }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Detail -->
    <div class="card shadow-sm">
        <div class="card-header" style="background: #34495e; color: white;">
            <h6 class="mb-0">Detail Kunjungan Pasien</h6>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Pasien</th>
                        <th>Poliklinik</th>
                        <th>No Antrian</th>
                        <th>Status</th>
                        <th>Jam Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungan as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ optional($k->pasien)->nama ?? '-' }}</strong><br>
                                <small class="text-muted">RM: {{ optional($k->pasien)->no_rm ?? '-' }}</small>
                            </td>
                            <td>{{ optional($k->poliklinik)->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $k->nomor_antrian ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $status = $k->status_layanan;
                                    if ($status === 'Menunggu') {
                                        $badge = 'bg-warning';
                                    } elseif ($status === 'Sedang Dilayani') {
                                        $badge = 'bg-info';
                                    } else {
                                        $badge = 'bg-success';
                                    }
                                @endphp
                                <span class="badge {{ $badge }}">{{ $status ?? '-' }}</span>
                            </td>
                            <td>{{ $k->created_at ? \Carbon\Carbon::parse($k->created_at)->format('H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data kunjungan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, form { display: none; }
    }
</style>

@endsection
