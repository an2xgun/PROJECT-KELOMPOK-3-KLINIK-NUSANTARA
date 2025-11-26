@extends('layout')

@section('content')
<h3>👨‍⚕️ Data Dokter</h3>

<a href="{{ route('master.dokter.create') }}" class="btn btn-primary mb-3">+ Tambah Dokter</a>

<table class="table table-bordered">
    <tr>
        <th>Nama Dokter</th>
        <th>Spesialis</th>
        <th>Aksi</th>
    </tr>

    @foreach($dokter as $row)
    <tr>
        <td>{{ $row->nama }}</td>
        <td>{{ ucfirst($row->spesialis) }}</td>
        <td>
            <a href="{{ route('master.dokter.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>

            <form action="{{ route('master.dokter.destroy', $row->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Yakin?')" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection
