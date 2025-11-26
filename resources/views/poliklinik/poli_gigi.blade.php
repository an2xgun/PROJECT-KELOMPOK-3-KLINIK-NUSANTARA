@extends('layout')

@section('content')
<h3>😁 Poli Gigi</h3>

<table class="table table-bordered">
    <tr>
        <th>Nama Pasien</th>
        <th>Keluhan</th>
        <th>Aksi</th>
    </tr>

    @foreach($pasien as $p)
    <tr>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->keluhan }}</td>
        <td>
            <a href="{{ route('pemeriksaan.create', $p->id) }}" class="btn btn-primary btn-sm">
                Periksa
            </a>
        </td>
    </tr>
    @endforeach

</table>
@endsection
