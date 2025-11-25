@extends('layout')

@section('content')
<h3>✏️ Edit Dokter</h3>

<form action="{{ route('master.dokter.update', $d->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama Dokter</label>
        <input type="text" name="nama" value="{{ $d->nama }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Spesialis</label>
        <select name="spesialis" class="form-control">
            <option value="umum" {{ $d->spesialis=='umum'?'selected':'' }}>Umum</option>
            <option value="gigi" {{ $d->spesialis=='gigi'?'selected':'' }}>Gigi</option>
            <option value="kandungan" {{ $d->spesialis=='kandungan'?'selected':'' }}>Kandungan</option>
        </select>
    </div>

    <button class="btn btn-success">Update</button>
</form>

@endsection
