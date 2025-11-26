@extends('layout')

@section('content')
<h3>➕ Tambah Dokter</h3>

<form action="{{ route('master.dokter.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Dokter</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Spesialis</label>
        <select name="spesialis" class="form-control" required>
            <option value="umum">Umum</option>
            <option value="gigi">Gigi</option>
            <option value="kandungan">Kandungan</option>
        </select>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>

@endsection
