@extends('layout')

@section('content')
<h3>🗓 Jadwal Dokter</h3>

<a href="{{ route('jadwal.create') }}" class="btn btn-primary mb-3">+ Tambah Jadwal</a>

<table class="table table-bordered">
    <tr>
        <th>Dokter</th>
        <th>Poli</th>
        <th>Hari</th>
        <th>Jam</th>
        <th>Aksi</th>
    </tr>

    @foreach($jadwal as $row)
    <tr>
        <td>{{ optional($row->dokter)->nama ?? '-' }}</td>
        <td>{{ optional($row->poli)->nama_poli ?? '-' }}</td>
        <td>{{ $row->hari }}</td>
        <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
        <td>
            <a href="{{ route('jadwal.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
        </td>
    </tr>
    @endforeach
</table>

@endsection
