@extends('layout')
@section('content')

<h3>Data Pasien</h3>

{{-- FORM PENCARIAN --}}
<form method="GET" action="{{ route('pasien.index') }}" class="mb-3">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Cari nama / No RM..." value="{{ request('q') }}">
        <button class="btn btn-primary">Cari</button>
    </div>
</form>

<a href="{{ route('pasien.create') }}" class="btn btn-success mb-3">+ Pasien Baru</a>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>No RM</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Jenis Kelamin</th>
            <th>Umur</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->no_rm }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->nik }}</td>
            <td>{{ $item->jenis_kelamin }}</td>
            <td>{{ $item->umur_tahun }} th</td>

            <td class="text-center">
                <a href="{{ route('pasien.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('pasien.destroy', $item->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus pasien?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $data->links() }}

@endsection
