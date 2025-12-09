@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3><i class="bi bi-cart-check"></i> Antrian Dispensing Obat</h3>
            <p class="text-muted">Daftar resep yang siap untuk diberikan kepada pasien</p>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Daftar Resep Menunggu Dispensing</h5>
                </div>
                <div class="card-body">
                    @if($prescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Pasien</th>
                                        <th>No RM</th>
                                        <th>Dokter</th>
                                        <th>Jumlah Item</th>
                                        <th>Status</th>
                                        <th>Tanggal Resep</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $key => $prescription)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>{{ optional($prescription->rekam->pasien)->nama ?? '-' }}</strong><br>
                                            <small class="text-muted">{{ data_get($prescription, 'rekam.pasien.no_rm') ?? data_get($prescription, 'rekam.pasien.kodepasien') ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $prescription->rekam->nomorantrian ?? '-' }}</span>
                                        </td>
                                        <td>{{ optional($prescription->rekam->dokter)->nama ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $prescription->items->count() }} item</span>
                                        </td>
                                        <td>
                                            @if($prescription->status === 'Pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($prescription->status === 'Diberikan')
                                                <span class="badge bg-success">Diberikan</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $prescription->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $prescription->created_at ? \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            <a href="{{ route('dispensing.form', $prescription->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-box-arrow-in-right"></i> Berikan
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i> Tidak ada resep yang menunggu dispensing
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
