@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>
        <i class="bi bi-hourglass-split"></i> Antrian Pemeriksaan
    </h3>

    <div class="card mt-3">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h6 class="mb-0">Daftar Pasien Menunggu Pemeriksaan</h6>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr style="background: #34495e; color: white;">
                        <th>#</th>
                        <th>No. Antrian</th>
                        <th>Pasien (No RM)</th>
                        <th>Poliklinik</th>
                        <th>Dokter</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-info">{{ $p->nomor_antrian ?? '-' }}</span>
                            </td>
                            <td>
                                <strong>{{ optional($p->pasien)->nama ?? '-' }}</strong><br>
                                <small class="text-muted">RM: {{ optional($p->pasien)->no_rm ?? '-' }}</small>
                            </td>
                            <td>{{ optional($p->poliklinik)->name ?? '-' }}</td>
                            <td>{{ optional(optional($p->jadwalPoli)->dokter)->nama ?? '-' }}</td>
                            <td>
                                <small>{{ substr($p->keluhan ?? '-', 0, 40) }}...</small>
                            </td>
                            <td>
                                @php
                                    $status = $p->status_layanan;
                                    if ($status === 'Menunggu') {
                                        $badge = 'bg-warning';
                                    } elseif ($status === 'Sedang Dilayani') {
                                        $badge = 'bg-info';
                                    } else {
                                        $badge = 'bg-success';
                                    }
                                @endphp
                                <span class="badge {{ $badge }}">{{ $status ?? '-' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('examination.detail', $p->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                                <a href="{{ route('examination.form', $p->id) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-clipboard-pulse"></i> Mulai Pemeriksaan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Tidak ada antrian untuk pemeriksaan saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .table tbody tr:hover {
        background: #f5f7fa;
    }
    
    .badge {
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

@endsection
