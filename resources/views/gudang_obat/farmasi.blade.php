@extends('layout.app')

@section('content')
<h3>🧪 Farmasi</h3>

<table class="table table-bordered table-striped">
    <tr>
        <th>Nama Obat</th>
        <th>Stok Masuk</th>
        <th>Stok Keluar</th>
        <th>Sisa Stok</th>
    </tr>

    @foreach($farmasi as $item)
    <tr>
        <td>{{ $item->obat->nama_obat }}</td>
        <td>{{ $item->stok_masuk }}</td>
        <td>{{ $item->stok_keluar }}</td>
        <td>{{ $item->sisa_stok }}</td>
    </tr>
    @endforeach
</table>

@endsection
