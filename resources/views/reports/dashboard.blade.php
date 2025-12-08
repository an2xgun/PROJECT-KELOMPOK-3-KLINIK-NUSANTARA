@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>
        <i class="bi bi-graph-up"></i> Dashboard Laporan Manajemen
    </h3>
    <small class="text-muted">Ringkasan kinerja klinik hari ini dan bulan berjalan</small>

    <!-- KPI Cards -->
    <div class="row mt-4">
        <!-- Kunjungan Hari Ini -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0" style="opacity: 0.9;">Kunjungan Hari Ini</h6>
                            <h2 class="mb-0 mt-2">{{ $kunjungan_hari_ini }}</h2>
                        </div>
                        <i class="bi bi-people" style="font-size: 40px; opacity: 0.3;"></i>
                    </div>
                    <small style="opacity: 0.8;">Pasien yang terdaftar</small>
                </div>
            </div>
        </div>

        <!-- Pemeriksaan Selesai -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0" style="opacity: 0.9;">Pemeriksaan Selesai</h6>
                            <h2 class="mb-0 mt-2">{{ $pemeriksaan_selesai }}</h2>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 40px; opacity: 0.3;"></i>
                    </div>
                    <small style="opacity: 0.8;">Rekam medis tercatat</small>
                </div>
            </div>
        </div>

        <!-- Resep Diberikan -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0" style="opacity: 0.9;">Resep Diberikan</h6>
                            <h2 class="mb-0 mt-2">{{ $resep_diberikan }}</h2>
                        </div>
                        <i class="bi bi-prescription2" style="font-size: 40px; opacity: 0.3;"></i>
                    </div>
                    <small style="opacity: 0.8;">Resep digital</small>
                </div>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0" style="opacity: 0.9;">Pendapatan Hari Ini</h6>
                            <h2 class="mb-0 mt-2">Rp {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}</h2>
                        </div>
                        <i class="bi bi-cash-coin" style="font-size: 40px; opacity: 0.3;"></i>
                    </div>
                    <small style="opacity: 0.8;">Terbayar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Bulan -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                    <h6 class="mb-0" style="color: #667eea; font-weight: 700;">
                        <i class="bi bi-calendar-event"></i> Statistik Bulan Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong>Total Kunjungan</strong>
                            <span style="font-size: 18px; color: #667eea; font-weight: 700;">{{ $total_kunjungan_bulan }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <strong>Total Pendapatan</strong>
                            <span style="font-size: 18px; color: #27ae60; font-weight: 700;">Rp {{ number_format($total_pendapatan_bulan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Stok Obat -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #e74c3c;">
                    <h6 class="mb-0" style="color: #e74c3c; font-weight: 700;">
                        <i class="bi bi-exclamation-triangle"></i> Peringatan Stok Obat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0" style="background: #fff3cd; border: 1px solid #ffc107; color: #856404;">
                        <i class="bi bi-exclamation-circle"></i> <strong>{{ $obat_kritis }} jenis obat</strong> dengan stok kritis (< 10 unit)
                        <br><small>Silakan lakukan pengadaan segera</small>
                    </div>
                    <a href="{{ route('reports.stok_obat', ['status' => 'kritis']) }}" class="btn btn-warning btn-sm mt-3 w-100">
                        <i class="bi bi-eye"></i> Lihat Detail Stok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Diagnosa & Laporan Cepat -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                    <h6 class="mb-0" style="color: #667eea; font-weight: 700;">
                        <i class="bi bi-graph-up"></i> Top 5 Diagnosa
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr>
                                <th>Diagnosa</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_diagnosa as $d)
                                <tr>
                                    <td>{{ $d['nama'] }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ $d['jumlah'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                    <h6 class="mb-0" style="color: #667eea; font-weight: 700;">
                        <i class="bi bi-file-earmark-text"></i> Akses Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('reports.kunjungan_harian') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="bi bi-calendar"></i> Laporan Kunjungan Harian
                    </a>
                    <a href="{{ route('reports.resep_obat_keluar') }}" class="btn btn-outline-success btn-sm w-100 mb-2">
                        <i class="bi bi-box-seam"></i> Laporan Resep & Obat Keluar
                    </a>
                    <a href="{{ route('reports.stok_obat') }}" class="btn btn-outline-warning btn-sm w-100 mb-2">
                        <i class="bi bi-capsule"></i> Laporan Stok Obat
                    </a>
                    <a href="{{ route('reports.keuangan') }}" class="btn btn-outline-info btn-sm w-100 mb-2">
                        <i class="bi bi-receipt"></i> Laporan Keuangan
                    </a>
                    <a href="{{ route('reports.diagnosa') }}" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-graph-up"></i> Laporan Diagnosa (ICD-10)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
