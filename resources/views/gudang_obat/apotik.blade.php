@extends('layout')

@section('content')
<h3>💊 Apotik</h3>

<table class="table table-bordered">
    <tr>
        <th>Nama Obat</th>
        <th>Stok</th>
        <th>Satuan</th>
        <th>Aksi</th>
    </tr>

    @foreach($obat as $row)
    <tr>
        <td>{{ $row->nama }}</td>
        <td>{{ $row->stok }}</td>
        <td>{{ $row->satuan }}</td>
        <td><a href="#" class="btn btn-warning btn-sm">Edit</a></td>
    </tr>
    @endforeach
</table>
@endsection
