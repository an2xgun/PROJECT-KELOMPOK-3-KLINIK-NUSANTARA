@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3><i class="bi bi-receipt"></i> Detail Resep Obat</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('prescription.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Daftar Resep
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Patient & Examination Information -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Pasien & Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted">No RM</small>
                            <p><strong>{{ data_get(optional(optional(optional($prescription->rekam)->pendaftaran)->pasien), 'no_rm') ?? data_get(optional(optional(optional($prescription->rekam)->pendaftaran)->pasien), 'kodepasien') ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Nama Pasien</small>
                            <p><strong>{{ optional(optional(optional($prescription->rekam)->pendaftaran)->pasien)->nama ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Poliklinik</small>
                            <p><strong>{{ optional(optional($prescription->rekam)->pendaftaran)->poliklinik->name ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Dokter</small>
                            <p><strong>{{ optional($prescription->dokter)->nama ?? '-' }}</strong></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Pemeriksaan</small>
                            <p>{{ optional($prescription->rekam)->created_at ? \Carbon\Carbon::parse(optional($prescription->rekam)->created_at)->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Resep</small>
                            <p>{{ $prescription->created_at ? \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Items -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-pill"></i> Obat-obatan yang Diresepkan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Dosis/Cara Pakai</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prescription->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->obat->nama ?? '-' }}</strong></td>
                                    <td>{{ $item->dosis ?? '-' }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada obat</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">Total Biaya Obat:</th>
                                <th>
                                    <strong>Rp {{ number_format($prescription->items->sum('subtotal'), 0, ',', '.') }}</strong>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Status & Notes -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Status Resep</h5>
                </div>
                <div class="card-body">
                    @if($prescription->status === 'Pending')
                        <span class="badge bg-warning fs-6">Menunggu Apotik</span>
                    @elseif($prescription->status === 'Diproses')
                        <span class="badge bg-info fs-6">Sedang Diproses</span>
                    @elseif($prescription->status === 'Selesai')
                        <span class="badge bg-success fs-6">Selesai Diambil</span>
                    @else
                        <span class="badge bg-secondary fs-6">{{ $prescription->status }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-sticky"></i> Catatan Resep</h5>
                </div>
                <div class="card-body">
                    <p>{{ optional($prescription->rekam)->resep_catatan ?? '(Tidak ada catatan)' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            @if(Auth::user()->role === 'apoteker' && $prescription->status === 'Pending')
                <form action="{{ route('prescription.process', $prescription->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Proses Resep (Apotik)
                    </button>
                </form>
            @endif
            <a href="{{ route('prescription.index') }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

@endsection
