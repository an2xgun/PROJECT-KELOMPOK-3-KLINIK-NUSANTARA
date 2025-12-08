@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col">
            <h3>Data Dokter</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('master.dokter.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Dokter Baru
            </a>
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
                    <th>Nama Dokter</th>
                    <th>Poliklinik</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $dokter)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dokter->nama }}</td>
                        <td>{{ optional($dokter->poliklinik)->name ?? '-' }}</td>
                        <td>{{ $dokter->alamat }}</td>
                        <td>{{ $dokter->telepon }}</td>
                        <td>{{ $dokter->jadwalpraktek }}</td>
                        <td>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('master.dokter.edit', $dokter->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('master.dokter.destroy', $dokter->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data dokter</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $data->links() }}
</div>

@endsection
