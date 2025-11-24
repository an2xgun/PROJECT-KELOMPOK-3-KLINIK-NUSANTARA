@extends('layout.app')

@section('content')
<h3>🦷 Poli Gigi</h3>

<table class="table table-bordered">
    <tr>
        <th>No RM</th>
        <th>Nama Pasien</th>
        <th>Tindakan</th>
        <th>Dokter</th>
        <th>Aksi</th>
    </tr>

    @foreach($data as $row)
    <tr>
        <td>{{ $row->pasien->no_rm }}</td>
        <td>{{ $row->pasien->nama }}</td>
        <td>{{ $row->tindakan->nama_tindakan }}</td>
        <td>{{ $row->jadwal->dokter->nama }}</td>
        <td>
            <a href="#" class="btn btn-info btn-sm">Detail</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
