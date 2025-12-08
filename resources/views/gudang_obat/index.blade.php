@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col">
            <h3>Gudang Obat</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('gudang_obat.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Obat Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" action="{{ route('gudang_obat.index') }}" class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari obat..." value="{{ request('q') }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 100px;">Kode Obat</th>
                    <th>Nama Obat</th>
                    <th style="width: 120px;">Jenis</th>
                    <th style="width: 100px;">Dosis</th>
                    <th style="width: 80px;">Stok</th>
                    <th style="width: 100px;">Harga</th>
                    <th style="width: 100px;">Expired</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $obat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><small class="badge bg-secondary">{{ $obat->kodeobat ?? 'N/A' }}</small></td>
                        <td><strong>{{ $obat->nama }}</strong></td>
                        <td><span class="badge bg-info">{{ $obat->jenis->jenisobat ?? '-' }}</span></td>
                        <td><small>{{ $obat->dosis ?? '-' }}</small></td>
                        <td>
                            <span class="badge {{ $obat->stok > 10 ? 'bg-success' : ($obat->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $obat->stok }} unit
                            </span>
                        </td>
                        <td><strong>Rp {{ number_format($obat->harga, 0, ',', '.') }}</strong></td>
                        <td>
                            @php
                                $expDate = \Carbon\Carbon::parse($obat->expired);
                                $now = \Carbon\Carbon::now();
                                $isExpired = $expDate < $now;
                                $daysLeft = $expDate->diffInDays($now);
                            @endphp
                            <small class="{{ $isExpired ? 'text-danger font-weight-bold' : ($daysLeft <= 30 ? 'text-warning' : 'text-success') }}">
                                {{ $obat->expired }}
                                @if($isExpired)
                                    <br><span class="badge bg-danger">EXPIRED</span>
                                @elseif($daysLeft <= 30)
                                    <br><span class="badge bg-warning text-dark">{{ $daysLeft }} hari</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            @if(in_array(Auth::user()->role, ['admin','apoteker']))
                                <a href="{{ route('gudang_obat.edit', $obat->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('gudang_obat.destroy', $obat->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox"></i> Belum ada data obat
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $data->links() }}
</div>

@endsection
