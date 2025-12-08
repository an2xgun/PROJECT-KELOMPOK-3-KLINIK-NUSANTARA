@extends('layout')
@section('content')
<h3>Data Master Pasien</h3>
<a href="{{ route('pasien.create') }}" class="btn btn-primary mb-3">+ Pasien Baru</a>
<table class="table table-striped">
  <thead><tr><th>No RM</th><th>Nama</th><th>NIK</th><th>JK</th><th>Kontak</th><th>Aksi</th></tr></thead>
  <tbody>
    @foreach($pasien as $p)
      <tr>
        <td>{{ $p->no_rm }}</td>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->nik }}</td>
        <td>{{ $p->jenis_kelamin }}</td>
        <td>{{ $p->kontak }}</td>
        <td>
          <a href="{{ route('pasien.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
          <form method="POST" action="{{ route('pasien.delete', $p->id) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Hapus</button></form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $pasien->links() }}
@endsection
