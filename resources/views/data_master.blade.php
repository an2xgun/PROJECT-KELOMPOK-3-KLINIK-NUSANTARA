@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>📋 Data Master Pasien</h3>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('pasien.baru') }}" class="btn btn-primary">➕ Tambah Pasien Baru</a>
        <a href="{{ route('pasien.lama') }}" class="btn btn-secondary">🔍 Cari Pasien Lama</a>
    </div>

    <table class="table table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>No. RM</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>No. Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pasien as $key => $p)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $p->nik }}</td>
                <td>{{ $p->no_rm }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->alamat }}</td>
                <td>{{ $p->jenis_kelamin }}</td>
                <td>{{ $p->tanggal_lahir }}</td>
                <td>{{ $p->no_telepon }}</td>
                <td>
                    <a href="{{ route('pasien.edit', $p->id) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <form action="{{ route('pasien.destroy', $p->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data pasien ini?')">🗑️ Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Belum ada data pasien.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
