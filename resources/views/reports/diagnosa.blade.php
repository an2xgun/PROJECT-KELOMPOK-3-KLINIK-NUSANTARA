@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>
        <i class="bi bi-graph-up"></i> Laporan Diagnosa
    </h3>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-2" style="padding-top: 32px;">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-2" style="padding-top: 32px;">
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
                    <h5 class="text-muted">Total Pemeriksaan</h5>
                    <h2 style="color: #667eea; font-weight: 700;">{{ $total_pemeriksaan }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Jenis Diagnosa</h5>
                    <h2 style="color: #2ecc71; font-weight: 700;">{{ $total_diagnosa }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Diagnosa Terbanyak</h5>
                    <h4 style="color: #e74c3c;">{{ $top_diagnosa['kode'] ?? 'N/A' }}</h4>
                    <small class="text-muted">{{ $top_diagnosa['count'] ?? 0 }} kasus</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Diagnosa Table -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #34495e; color: white;">
                    <h6 class="mb-0">10 Diagnosa Terbanyak</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Kode Diagnosa</th>
                                    <th>Nama Diagnosa</th>
                                    <th class="text-end">Jumlah Kasus</th>
                                    <th class="text-end">Persentase</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($top_diagnosa_list as $d)
                                    @php
                                        $percentage = $total_pemeriksaan > 0 ? round(($d['count'] / $total_pemeriksaan) * 100, 1) : 0;
                                        $trend = $d['trend'] ?? 0;
                                        $trend_color = $trend >= 0 ? '#27ae60' : '#e74c3c';
                                        $trend_icon = $trend >= 0 ? '▲' : '▼';
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $loop->iteration }}</span>
                                        </td>
                                        <td><strong>{{ $d['kode'] ?? '-' }}</strong></td>
                                        <td>{{ $d['nama'] ?? '-' }}</td>
                                        <td class="text-end">
                                            <strong>{{ $d['count'] ?? 0 }}</strong>
                                        </td>
                                        <td class="text-end">{{ $percentage }}%</td>
                                        <td>
                                            <span style="color: {{ $trend_color }};">{{ $trend_icon }} {{ abs($trend) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data diagnosa</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Pemeriksaan -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #34495e; color: white;">
                    <h6 class="mb-0">Daftar Pemeriksaan & Diagnosa</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th>Diagnosa Primer</th>
                                    <th>Diagnosa Sekunder</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksaan as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            <strong>{{ optional($p->pasien)->nama ?? '-' }}</strong>
                                        </td>
                                        <td>{{ optional($p->dokter)->nama ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ optional($p->diagnosa_primer)->kode ?? 'N/A' }}
                                            </span>
                                            <small>{{ optional($p->diagnosa_primer)->nama ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @if($p->diagnosa_sekunder)
                                                <span class="badge bg-secondary">
                                                    {{ optional($p->diagnosa_sekunder)->kode ?? 'N/A' }}
                                                </span>
                                                <small>{{ optional($p->diagnosa_sekunder)->nama ?? '-' }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->status_pemeriksaan === 'Sudah Diperiksa')
                                                <span class="badge bg-success">✓ Selesai</span>
                                            @elseif($p->status_pemeriksaan === 'Sedang Diperiksa')
                                                <span class="badge bg-warning">⧗ Proses</span>
                                            @else
                                                <span class="badge bg-light text-dark">○ Belum</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Tidak ada data pemeriksaan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, form { display: none; }
    }
</style>

@endsection
