@extends('layout')

@section('content')
<h3>📝 Data Diagnosa</h3>

<a href="{{ route('diagnosa.create') }}" class="btn btn-primary mb-3">+ Tambah Diagnosa</a>

<table class="table table-bordered">
    <tr>
        <th>Kode</th>
        <th>Nama Diagnosa</th>
        <th>Aksi</th>
    </tr>

    @foreach($diagnosa as $row)
    <tr>
        <td>{{ $row->kode }}</td>
        <td>{{ $row->nama_diagnosa }}</td>
        <td>
            <a href="{{ route('diagnosa.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
        </td>
    </tr>
    @endforeach
</table>

@endsection
