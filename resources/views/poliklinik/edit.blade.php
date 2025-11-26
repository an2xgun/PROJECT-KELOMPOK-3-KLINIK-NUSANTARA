@extends('layout')

@section('content')
<h3>✏️ Edit Poliklinik</h3>

<form action="{{ route('poliklinik.update', $poliklinik->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama Poliklinik</label>
        <input type="text" name="nama_poli" value="{{ $poliklinik->nama_poli }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Kode Poli</label>
        <input type="text" name="kode" value="{{ $poliklinik->kode }}" class="form-control" required>
    </div>

    <button class="btn btn-success">Update</button>
</form>

@endsection
