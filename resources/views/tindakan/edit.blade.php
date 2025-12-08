@extends('layout')
@section('content')

<h3>Edit Tindakan</h3>

<form action="{{ route('tindakan.update', $d->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Kode</label>
        <input type="text" name="kode" class="form-control" value="{{ old('kode', $d->kode) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $d->nama) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ old('harga', $d->harga) }}" required>
    </div>

    <div class="d-flex justify-content-end">
        <a href="{{ route('tindakan.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button class="btn btn-primary">Simpan</button>
    </div>
</form>

@endsection