@extends('layout')

@section('content')
<h3 class="mb-3">🏥 Daftar Poliklinik</h3>

<div class="row">

    <div class="col-md-4">
        <a href="{{ route('poliklinik.poli_umum') }}" class="btn btn-primary w-100 p-3 mb-3">Poli Umum</a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('poliklinik.poli_gigi') }}" class="btn btn-primary w-100 p-3 mb-3">Poli Gigi</a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('poliklinik.poli_kandungan') }}" class="btn btn-primary w-100 p-3 mb-3">Poli Kandungan</a>
    </div>

</div>
@endsection
