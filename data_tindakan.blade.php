@extends('layout.app')

@section('content')
<h3>🛠 Data Tindakan</h3>

<a href="{{ route('tindakan.create') }}" class="btn btn-primary mb-3">+ Tambah Tindakan</a>

<table class="table table-bordered">
    <tr>
        <th>Kode</th>
        <th>Nama Tindakan</th>
        <th>Tarif</th>
        <th>Aksi</th>
    </tr>

    @foreach($tindakan as $row)
    <tr>
        <td>{{ $row->kode }}</td>
        <td>{{ $row->nama_tindakan }}</td>
        <td>{{ number_format($row->tarif) }}</td>
        <td>
            <a href="{{ route('tindakan.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
        </td>
    </tr>
    @endforeach
</table>

@endsection
