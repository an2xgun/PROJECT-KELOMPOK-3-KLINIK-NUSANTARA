@extends('layout.app')

@section('content')
<h3>📦 Master Data Obat</h3>

<a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">+ Tambah Obat</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nama Obat</th>
            <th>Jenis</th>
            <th>Satuan</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    @foreach($obat as $row)
    <tr>
        <td>{{ $row->nama_obat }}</td>
        <td>{{ $row->jenis }}</td>
        <td>{{ $row->satuan }}</td>
        <td>{{ $row->stok }}</td>
        <td>
            <a href="{{ route('obat.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <a href="{{ route('obat.delete', $row->id) }}" class="btn btn-danger btn-sm"
               onclick="return confirm('Hapus obat ini?')">Hapus</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
