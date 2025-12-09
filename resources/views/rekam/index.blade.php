@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col">
            <h3>Daftar Rekam Medis</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('rekam.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Rekam Baru
            </a>
            @if(Auth::user()->role === 'dokter')
                <a href="{{ route('examination.queue') }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-hourglass-split"></i> Antrian Pemeriksaan
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>No Rekam</th>
                    <th>Nama Pasien</th>
                    <th>Keluhan</th>
                    <th>Dokter</th>
                    <th>Diagnosa</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekam as $r)
                    <tr>
                        <td>{{ $loop->iteration + ($rekam->currentPage() - 1) * $rekam->perPage() }}</td>
                        <td>{{ optional($r->pasien)->no_rm ?? $r->nomorantrian ?? '-' }}</td>
                        <td>{{ optional($r->pasien)->nama ?? '-' }}</td>
                        <td>{{ substr(optional($r)->keluhan ?? '-', 0, 30) }}{{ (optional($r)->keluhan) ? '...' : '' }}</td>
                        <td>{{ optional($r->dokter)->nama ?? '-' }}</td>
                        <td>
                            @if($r->diagnosa)
                                {{ $r->diagnosa }}
                            @elseif(optional($r->diagnosaPrimer)->nama)
                                {{ optional($r->diagnosaPrimer)->nama }}
                            @elseif($r->diagnosa_primer)
                                ID: {{ $r->diagnosa_primer }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $r->tanggalperiksa ?? '-' }}</td>
                        <td>
                            <a href="{{ route('rekam.show', $r->id) }}" class="btn btn-sm btn-info">Lihat</a>
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'dokter')
                                <a href="{{ route('rekam.edit', $r->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'dokter')
                                    <form action="{{ route('rekam.destroy', $r->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                    </form>
                                @endif
                                @if(optional($r->pendaftaran)->id)
                                    <a href="{{ route('examination.form', optional($r->pendaftaran)->id) }}" class="btn btn-sm btn-primary ms-1">Mulai Pemeriksaan</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data rekam medis</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $rekam->links() }}
</div>

@endsection
