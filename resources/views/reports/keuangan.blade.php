@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>
        <i class="bi bi-cash-coin"></i> Laporan Keuangan
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

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm" style="border-left: 5px solid #27ae60;">
                <div class="card-body">
                    <h5 class="text-muted">Total Pemasukan</h5>
                    <h2 style="color: #27ae60; font-weight: 700;">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</h2>
                    <small class="text-muted">{{ $total_invoice }} invoice</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm" style="border-left: 5px solid #e74c3c;">
                <div class="card-body">
                    <h5 class="text-muted">Total Pengeluaran</h5>
                    <h2 style="color: #e74c3c; font-weight: 700;">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</h2>
                    <small class="text-muted">{{ $total_resep }} resep</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm" style="border-left: 5px solid #f39c12;">
                <div class="card-body">
                    <h5 class="text-muted">Saldo Bersih</h5>
                    <h2 style="color: #f39c12; font-weight: 700;">Rp {{ number_format($saldo_bersih, 0, ',', '.') }}</h2>
                    <small class="text-muted">{{ $saldo_bersih >= 0 ? 'Surplus' : 'Deficit' }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakdown by Payment Method -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #34495e; color: white;">
                    <h6 class="mb-0">Rincian Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Pembayaran</th>
                                    <th class="text-end">Jumlah Transaksi</th>
                                    <th class="text-end">Total Nominal</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($breakdown as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item['jenis'] }}</strong>
                                        </td>
                                        <td class="text-end">{{ $item['count'] }} transaksi</td>
                                        <td class="text-end"><strong>Rp {{ number_format($item['total'], 0, ',', '.') }}</strong></td>
                                        <td class="text-end">
                                            {{ $total_pemasukan > 0 ? round(($item['total'] / $total_pemasukan) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #34495e; color: white;">
                    <h6 class="mb-0">Daftar Invoice (Pemasukan)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>No. Invoice</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th>Jenis Bayar</th>
                                    <th class="text-end">Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $inv)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($inv->rekam)->created_at ? \Carbon\Carbon::parse($inv->rekam->created_at)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $inv->no_invoice ?? 'AUTO' }}</td>
                                        <td>
                                            <strong>{{ optional(optional($inv->rekam)->pasien)->nama ?? '-' }}</strong>
                                        </td>
                                        <td>{{ optional(optional($inv->rekam)->dokter)->nama ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $inv->jenis_pembayaran ?? 'Tunai' }}</span>
                                        </td>
                                        <td class="text-end"><strong>Rp {{ number_format($inv->total_harga ?? 0, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if($inv->status === 'Lunas')
                                                <span class="badge bg-success">✓ Lunas</span>
                                            @else
                                                <span class="badge bg-warning">⧗ Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">Tidak ada data invoice</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background: #34495e; color: white;">
                    <h6 class="mb-0">Daftar Resep (Pengeluaran Obat)</h6>
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
                                    <th>No. Resep</th>
                                    <th class="text-end">Total Item</th>
                                    <th class="text-end">Nilai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prescriptions as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($p->rekam)->created_at ? \Carbon\Carbon::parse($p->rekam->created_at)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <strong>{{ optional(optional($p->rekam)->pasien)->nama ?? '-' }}</strong>
                                        </td>
                                        <td>{{ optional(optional($p->rekam)->dokter)->nama ?? '-' }}</td>
                                        <td>{{ $p->no_resep ?? 'AUTO' }}</td>
                                        <td class="text-end">{{ $p->items_count ?? 0 }}</td>
                                        <td class="text-end"><strong>Rp {{ number_format($p->total_nilai ?? 0, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if($p->status === 'Diberikan')
                                                <span class="badge bg-success">✓ Diberikan</span>
                                            @else
                                                <span class="badge bg-warning">⧗ Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">Tidak ada data resep</td>
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
