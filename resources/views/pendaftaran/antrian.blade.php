@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h2 class="mb-4">
        <i class="bi bi-clock-history"></i> Antrian Pasien
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded" style="background-color: #fff3cd;">
                                <h5 class="text-warning">Menunggu</h5>
                                <h2 class="text-warning">{{ $statsWaiting }}</h2>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded" style="background-color: #cfe2ff;">
                                <h5 class="text-primary">Sedang Dilayani</h5>
                                <h2 class="text-primary">{{ $statsServing }}</h2>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded" style="background-color: #d1e7dd;">
                                <h5 class="text-success">Selesai</h5>
                                <h2 class="text-success">{{ $statsCompleted }}</h2>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded" style="background-color: #f8d7da;">
                                <h5 class="text-danger">Total Hari Ini</h5>
                                <h2 class="text-danger">{{ $statsTotal }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>Daftar Antrian Pendaftaran</strong>
        </div>
        <div class="card-body">
            @if(count($pendaftarans) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No Antrian</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>Poliklinik</th>
                                <th>Keluhan</th>
                                <th>Jenis Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendaftarans as $p)
                                <tr>
                                    <td>
                                        <span class="badge bg-info" style="font-size: 0.95rem;">
                                            {{ $p->nomor_antrian ?? '-' }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $p->pasien->no_rm ?? '-' }}</strong></td>
                                    <td>{{ $p->pasien->nama ?? '-' }}</td>
                                    <td>{{ $p->poliklinik->name ?? '-' }}</td>
                                    <td>
                                        <small>{{ Str::limit($p->keluhan ?? '-', 30) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $p->jenis_pembayaran ?? 'Umum' }}</span>
                                    </td>
                                    <td>
                                        @if($p->status_layanan === 'Menunggu')
                                            <span class="badge bg-warning">{{ $p->status_layanan }}</span>
                                        @elseif($p->status_layanan === 'Sedang Dilayani')
                                            <span class="badge bg-primary">{{ $p->status_layanan }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $p->status_layanan }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($p->status_layanan !== 'Selesai')
                                                <form action="{{ route('pendaftaran.serve', $p->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning" title="Panggil pasien">
                                                        <i class="bi bi-telephone"></i> Panggil
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('invoice.create-pendaftaran', $p->id) }}" class="btn btn-primary" title="Buat invoice">
                                                <i class="bi bi-receipt"></i> Invoice
                                            </a>
                                            
                                            @if($p->status_layanan !== 'Selesai')
                                                <form action="{{ route('pendaftaran.destroy', $p->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus pendaftaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $pendaftarans->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada pendaftaran hari ini
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    padding: 6px 10px;
    font-size: 0.85rem;
}

.btn-group-sm .btn {
    padding: 5px 10px;
    font-size: 0.85rem;
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 12px 12px 0 0;
}
</style>

@endsection
