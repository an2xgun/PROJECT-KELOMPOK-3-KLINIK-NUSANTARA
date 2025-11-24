@extends('layout.app')

@section('content')
<h3>👨‍⚕️ Data Pegawai</h3>

<a href="{{ route('pegawai.create') }}" class="btn btn-primary mb-3">+ Tambah Pegawai</a>

<table class="table table-bordered">
    <tr>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>No HP</th>
        <th>Aksi</th>
    </tr>

    @foreach($pegawai as $row)
    <tr>
        <td>{{ $row->nama }}</td>
        <td>{{ $row->jabatan }}</td>
        <td>{{ $row->no_hp }}</td>
        <td>
            <a href="{{ route('pegawai.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
        </td>
    </tr>
    @endforeach
</table>

@endsection
