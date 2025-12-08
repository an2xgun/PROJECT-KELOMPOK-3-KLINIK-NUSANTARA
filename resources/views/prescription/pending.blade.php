@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h3><i class="bi bi-exclamation-circle"></i> Resep Obat Menunggu Proses (Apotik)</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('prescription.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Semua Resep
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($prescriptions->count() > 0)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Terdapat <strong>{{ $prescriptions->total() }}</strong> resep yang menunggu untuk diproses oleh apotik.
        </div>

        <div class="row">
            @forelse($prescriptions as $prescription)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-person-badge"></i>
                                    {{ optional(optional(optional($prescription->rekam)->pendaftaran)->pasien)->nama ?? 'Pasien' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">No RM</small>
                                        <p><strong>{{ data_get(optional(optional(optional($prescription->rekam)->pendaftaran)->pasien), 'no_rm') ?? data_get(optional(optional(optional($prescription->rekam)->pendaftaran)->pasien), 'kodepasien') ?? '-' }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Poliklinik</small>
                                        <p><strong>{{ optional(optional($prescription->rekam)->pendaftaran)->poliklinik->name ?? '-' }}</strong></p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Obat-obatan:</small>
                                <div class="list-group list-group-sm">
                                    @foreach($prescription->items as $item)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $item->obat->nama ?? '-' }}</h6>
                                                    <small class="text-muted">
                                                        Dosis: {{ $item->dosis ?? '-' }} | Jumlah: {{ $item->jumlah }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-primary">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">Total Biaya</small>
                                    <p class="text-success">
                                        <strong>Rp {{ number_format($prescription->items->sum('subtotal'), 0, ',', '.') }}</strong>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tanggal Resep</small>
                                    <p>{{ $prescription->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <form action="{{ route('prescription.process', $prescription->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Proses Resep
                                    </button>
                                </form>
                                <a href="{{ route('prescription.show', $prescription->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert-success text-center">
                        <i class="bi bi-check-circle"></i>
                        <h4>Semua Resep Sudah Diproses!</h4>
                        <p>Tidak ada resep obat yang menunggu untuk diproses saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $prescriptions->links() }}
        </div>
    @else
        <div class="alert alert-success text-center">
            <i class="bi bi-check-circle"></i>
            <h4>Semua Resep Sudah Diproses!</h4>
            <p>Tidak ada resep obat yang menunggu untuk diproses saat ini.</p>
        </div>
    @endif
</div>

@endsection
