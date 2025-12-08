@extends('layout')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Master Tindakan</h3>
    <a href="{{ route('tindakan.create') }}" class="btn btn-success">+ Buat Tindakan</a>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->kode }}</td>
            <td>{{ $item->nama }}</td>
            <td>Rp {{ number_format($item->harga ?? 0,0,',','.') }}</td>
            <td>
                <a href="{{ route('tindakan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('tindakan.destroy', $item->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus tindakan?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-muted">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

@endsection