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
                    <td>{{ data_get($prescription, 'rekam.pendaftaran.poliklinik.name', '-') }}</td>
                    <td>{{ data_get($prescription, 'dokter.nama') ?? data_get($prescription, 'rekam.dokter.nama', '-') }}</td>
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
                    <td>{{ $prescription->created_at ? \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <a href="{{ route('prescription.show', $prescription->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                        @if($prescription->status === 'Pending')
                            <a href="{{ route('prescription.edit', $prescription->id) }}" class="btn btn-sm btn-warning" title="Edit Resep">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $prescription->id }}" title="Hapus Resep">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        @endif
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

<!-- Delete Modals -->
@foreach($prescriptions as $prescription)
    @if($prescription->status === 'Pending')
        <div class="modal fade" id="deleteModal{{ $prescription->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus Resep</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus resep untuk <strong>{{ optional(optional(optional($prescription->rekam)->pendaftaran)->pasien)->nama ?? '-' }}</strong>?</p>
                        <p class="text-muted small">Aksi ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('prescription.destroy', $prescription->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Resep</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
