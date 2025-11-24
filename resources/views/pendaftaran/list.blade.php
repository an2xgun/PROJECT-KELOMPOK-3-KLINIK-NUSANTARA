@extends('layout')
@section('content')
<h3>List Pendaftaran</h3>
<table class="table table-striped">
  <thead><tr><th>No RM</th><th>Nama</th><th>Poli</th><th>Tanggal</th><th>Antrian</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody>
    @foreach($list as $row)
      <tr>
        <td>{{ $row->pasien->no_rm }}</td>
        <td>{{ $row->pasien->nama }}</td>
        <td>{{ $row->poliklinik->nama }}</td>
        <td>{{ $row->tanggal_kunjungan }}</td>
        <td>{{ $row->no_antrian }}</td>
        <td>{{ $row->status_antrian }}</td>
        <td>
          @if($row->status_antrian == 'Sedang Antri')
            <form method="POST" action="{{ route('pendaftaran.serve', $row->id) }}" style="display:inline">
              @csrf
              <button class="btn btn-sm btn-success">Tandai Dilayani</button>
            </form>
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $list->links() }}
@endsection
