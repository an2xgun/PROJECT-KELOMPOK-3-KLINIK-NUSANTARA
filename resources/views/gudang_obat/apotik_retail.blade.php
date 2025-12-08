@extends('layout')

@section('content')
<h3>🏪 Apotik Retail</h3>

<table class="table table-bordered table-striped">
    <tr>
        <th>Nama Obat</th>
        <th>Harga Jual</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

    @foreach($obat as $row)
    <tr>
        <td>{{ $row->nama }}</td>
        <td>{{ number_format($row->harga_jual) }}</td>
        <td>{{ $row->stok }}</td>
        <td>
            <a href="{{ route('retail.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
