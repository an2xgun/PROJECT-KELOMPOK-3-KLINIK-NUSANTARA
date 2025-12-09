@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>
        <i class="bi bi-clipboard-prescription"></i> Laporan Resep Obat Keluar
    </h3>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->format('Y-m-d')) }}">
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
                    <h5 class="text-muted">Total Resep</h5>
                    <h2 style="color: #667eea; font-weight: 700;">{{ $total_resep }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Total Item</h5>
                    <h2 style="color: #2ecc71; font-weight: 700;">{{ $total_item }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Total Nilai</h5>
                    <h2 style="color: #f39c12; font-weight: 700;">Rp {{ number_format($total_nilai, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Resep Table -->
    <div class="card shadow-sm">
        <div class="card-header" style="background: #34495e; color: white;">
            <h6 class="mb-0">Daftar Resep Keluar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>No. Resep</th>
                            <th>Total Item</th>
                            <th>Total Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resep as $r)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($r->rekam)->created_at ? \Carbon\Carbon::parse($r->rekam->created_at)->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <strong>{{ optional(optional($r->rekam)->pasien)->nama ?? '-' }}</strong>
                                    <br><small class="text-muted">{{ optional(optional($r->rekam)->pasien)->no_rekam_medis ?? '-' }}</small>
                                </td>
                                <td>{{ optional(optional($r->rekam)->dokter)->nama ?? '-' }}</td>
                                <td>{{ $r->no_resep ?? 'AUTO' }}</td>
                                <td>{{ $r->items_count ?? 0 }} item</td>
                                <td><strong>Rp {{ number_format($r->total_nilai ?? 0, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($r->status === 'Diberikan')
                                        <span class="badge bg-success">✓ Diberikan</span>
                                    @elseif($r->status === 'Ditolak')
                                        <span class="badge bg-danger">✗ Ditolak</span>
                                    @else
                                        <span class="badge bg-warning">⧗ Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                            <!-- Detail Items -->
                            <tr class="table-light">
                                <td colspan="8" style="padding: 10px 40px;">
                                    <small>
                                        @forelse(optional($r->items) as $item)
                                            <div style="margin: 5px 0;">
                                                • <strong>{{ optional($item->obat)->nama ?? '-' }}</strong> 
                                                ({{ $item->qty ?? 0 }} {{ $item->satuan ?? 'unit' }}) 
                                                = Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}
                                            </div>
                                        @empty
                                            <div class="text-muted">Tidak ada item</div>
                                        @endforelse
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data resep</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, form { display: none; }
        tr.table-light { break-inside: avoid; }
    }
</style>

@endsection
