@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>
        <i class="bi bi-capsule"></i> Laporan Stok Obat
    </h3>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="semua" {{ $filter_status === 'semua' ? 'selected' : '' }}>Semua Obat</option>
                        <option value="kritis" {{ $filter_status === 'kritis' ? 'selected' : '' }}>Stok Kritis (< 10)</option>
                        <option value="kosong" {{ $filter_status === 'kosong' ? 'selected' : '' }}>Stok Kosong</option>
                    </select>
                </div>
                <div class="col-md-2" style="padding-top: 32px;">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan
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
                    <h5 class="text-muted">Total Jenis Obat</h5>
                    <h2 style="color: #667eea; font-weight: 700;">{{ $total_jenis }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Stok Kritis</h5>
                    <h2 style="color: #f39c12; font-weight: 700;">{{ $stok_kritis }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h5 class="text-muted">Stok Kosong</h5>
                    <h2 style="color: #e74c3c; font-weight: 700;">{{ $stok_kosong }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-header" style="background: #34495e; color: white;">
            <h6 class="mb-0">Daftar Stok Obat</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Kode Obat</th>
                            <th>Nama Obat</th>
                            <th>Jenis</th>
                            <th>Dosis</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Tgl Expired</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obat as $o)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $o->kodeobat ?? '-' }}</td>
                                <td><strong>{{ $o->nama }}</strong></td>
                                <td>{{ optional($o->jenis)->jenisobat ?? '-' }}</td>
                                <td>{{ $o->dosis ?? '-' }}</td>
                                <td>
                                    @if($o->stok <= 0)
                                        <span class="badge bg-danger">{{ $o->stok }} unit</span>
                                    @elseif($o->stok < 10)
                                        <span class="badge bg-warning">{{ $o->stok }} unit</span>
                                    @else
                                        <span class="badge bg-success">{{ $o->stok }} unit</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($o->harga ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($o->expired)
                                        @if($o->expired < now())
                                            <span class="badge bg-danger">EXPIRED</span>
                                        @elseif($o->expired < now()->addDays(30))
                                            <span class="badge bg-warning">{{ $o->expired->format('d/m/Y') }}</span>
                                        @else
                                            {{ $o->expired->format('d/m/Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($o->stok <= 0)
                                        <i class="bi bi-x-circle" style="color: #e74c3c; font-size: 18px;"></i> Kosong
                                    @elseif($o->stok < 10)
                                        <i class="bi bi-exclamation-triangle" style="color: #f39c12; font-size: 18px;"></i> Kritis
                                    @else
                                        <i class="bi bi-check-circle" style="color: #27ae60; font-size: 18px;"></i> Normal
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada data obat</td>
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
    }
</style>

@endsection
