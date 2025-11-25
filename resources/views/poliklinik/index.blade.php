@extends('layout')

@section('content')
<h3>📋 Data Poliklinik</h3>

<a href="{{ route('poliklinik.create') }}" class="btn btn-primary mb-3">+ Tambah Poliklinik</a>

<table class="table table-bordered">
    <tr>
        <th>Nama Poli</th>
        <th>Kode</th>
        <th>Aksi</th>
    </tr>

    @foreach($poliklinik as $row)
    <tr>
        <td>{{ $row->nama_poli }}</td>
        <td>{{ $row->kode }}</td>
        <td>
            <a href="{{ route('poliklinik.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('poliklinik.destroy', $row->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Yakin?')" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection
