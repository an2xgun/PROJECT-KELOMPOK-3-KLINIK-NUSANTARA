@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-8">
            <h3><i class="bi bi-clipboard-check"></i> Daftar Pendaftaran</h3>
        </div>
        <div class="col-md-4 text-end">
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas_pendaftaran')
                <a href="{{ route('pendaftaran.create-new-patient') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Pendaftaran Baru
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
                <th>No Antrian</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftaran as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($item->pasien)->no_rm ?? optional($item->pasien)->kodepasien ?? '-' }}</td>
                    <td>{{ optional($item->pasien)->nama ?? '-' }}</td>
                    <td>{{ optional($item->poliklinik)->name ?? '-' }}</td>
                    <td><span class="badge bg-info">{{ $item->nomor_antrian }}</span></td>
                    <td>
                        @if($item->status_layanan === 'Menunggu')
                            <span class="badge bg-warning">Menunggu</span>
                        @elseif($item->status_layanan === 'Sedang Dilayani')
                            <span class="badge bg-info">Sedang Dilayani</span>
                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') ?? '-' }}</td>
                    <td>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas_pendaftaran')
                            @if($item->status_layanan === 'Menunggu')
                                <form action="{{ route('pendaftaran.serve', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Dilayani
                                    </button>
                                </form>
                            @endif
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('pendaftaran.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pendaftaran?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        @elseif(Auth::user()->role === 'dokter')
                            @if($item->status_layanan === 'Sedang Dilayani')
                                <a href="{{ route('examination.form', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-form-check"></i> Pemeriksaan
                                </a>
                            @else
                                <span class="text-muted text-nowrap">-</span>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada data pendaftaran</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $pendaftaran->links() }}
    </div>
</div>

@endsection
