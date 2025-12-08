@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h3><i class="bi bi-receipt"></i> Daftar Resep Obat</h3>
        </div>
        <div class="col-md-4 text-end">
            @if(Auth::user()->role === 'apoteker')
                <a href="{{ route('prescription.pending') }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-exclamation-circle"></i> Resep Menunggu
                </a>
            @endif
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

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>No RM</th>
                <th>Nama Pasien</th>
                <th>Poliklinik</th>
                <th>Dokter</th>
                <th>Jumlah Obat</th>
                <th>Total Biaya</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prescriptions as $prescription)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ data_get(optional(optional(optional($prescription->rekam)->pendaftaran)->pasien), 'no_rm') ?? data_get(optional(optional(optional($prescription->rekam)->pendaftaran)->pasien), 'kodepasien') ?? '-' }}</td>
                    <td>{{ optional(optional(optional($prescription->rekam)->pendaftaran)->pasien)->nama ?? '-' }}</td>
                    <td>{{ optional(optional($prescription->rekam)->pendaftaran)->poliklinik->name ?? '-' }}</td>
                    <td>{{ optional($prescription)->dokter->nama ?? '-' }}</td>
                    <td>
                        <span class="badge bg-primary">
                            {{ $prescription->items->count() }} item
                        </span>
                    </td>
                    <td>
                        <strong>Rp {{ number_format($prescription->items->sum('subtotal'), 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        @if($prescription->status === 'Pending')
                            <span class="badge bg-warning">Menunggu Apotik</span>
                        @elseif($prescription->status === 'Diproses')
                            <span class="badge bg-info">Sedang Diproses</span>
                        @elseif($prescription->status === 'Selesai')
                            <span class="badge bg-success">Selesai Diambil</span>
                        @else
                            <span class="badge bg-secondary">{{ $prescription->status }}</span>
                        @endif
                    </td>
                    <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('prescription.show', $prescription->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">Tidak ada data resep obat</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $prescriptions->links() }}
    </div>
</div>

@endsection
