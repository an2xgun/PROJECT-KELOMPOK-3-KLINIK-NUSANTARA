@extends('layout.app')

@section('content')
<h3>📁 Data Master Pasien</h3>

<table class="table table-bordered">
    <tr>
        <th>No RM</th>
        <th>Nama</th>
        <th>NIK</th>
        <th>Jenis Kelamin</th>
        <th>Aksi</th>
    </tr>

    @foreach($pasien as $row)
    <tr>
        <td>{{ $row->no_rm }}</td>
        <td>{{ $row->nama }}</td>
        <td>{{ $row->nik }}</td>
        <td>{{ $row->jenis_kelamin }}</td>
        <td><a href="#" class="btn btn-info btn-sm">Detail</a></td>
    </tr>
    @endforeach
</table>

@endsection
