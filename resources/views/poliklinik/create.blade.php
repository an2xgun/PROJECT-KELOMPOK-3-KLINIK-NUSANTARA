@extends('layout')

@section('content')
<h3>➕ Tambah Poliklinik</h3>

<form action="{{ route('poliklinik.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Poliklinik</label>
        <input type="text" name="nama_poli" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Kode Poli</label>
        <input type="text" name="kode" class="form-control" placeholder="A / G / K" required>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>

@endsection
