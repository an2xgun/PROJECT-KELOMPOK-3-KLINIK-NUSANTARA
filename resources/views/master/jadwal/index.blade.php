@extends('layout')

@section('content')
<h3>🗓 Jadwal Dokter</h3>

<a href="{{ route('master.jadwal.create') }}" class="btn btn-primary mb-3">+ Tambah Jadwal</a>

<table class="table table-bordered">
    <tr>
        <th>Dokter</th>
        <th>Spesialis</th>
        <th>Poliklinik</th>
        <th>Hari</th>
        <th>Jam</th>
        <th>Aksi</th> <!-- Tambah kolom aksi -->
    </tr>

    @foreach($jadwals as $row)
    <tr>
        <td>{{ $row->dokter->nama }}</td>
        <td>{{ ucfirst($row->dokter->spesialis) }}</td>
        <td>{{ $row->poliklinik->nama_poli }}</td>
        <td>{{ $row->hari }}</td>
        <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
        <td>
            <!-- Edit -->
            <a href="{{ route('master.jadwal.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>

            <!-- Delete -->
            <form action="{{ route('master.jadwal.destroy', $row->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin dihapus?')">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
