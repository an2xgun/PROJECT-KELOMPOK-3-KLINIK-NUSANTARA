@extends('layout')

@section('content')
<h3>🤰 Poli Kandungan</h3>

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
        <td>{{ optional($row->pasien)->no_rm ?? '-' }}</td>
        <td>{{ optional($row->pasien)->nama ?? '-' }}</td>
        <td>{{ optional($row->tindakan)->nama_tindakan ?? '-' }}</td>
        <td>{{ optional(optional($row->jadwal)->dokter)->nama ?? '-' }}</td>
        <td>
            <a href="#" class="btn btn-info btn-sm">Detail</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
