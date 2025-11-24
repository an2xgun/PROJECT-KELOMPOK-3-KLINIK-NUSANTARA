@extends('layout')

@section('content')
<div class="container mt-4">
    <h4>Edit Pasien</h4>

    <form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>No Rekam Medis</label>
            <input type="text" name="no_rm" class="form-control" value="{{ $pasien->no_rm }}" readonly>
        </div>

        <div class="mb-3">
            <label>Nama Pasien</label>
            <input type="text" name="nama" class="form-control" value="{{ $pasien->nama }}" required>
        </div>

        <div class="mb-3">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="L" {{ $pasien->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $pasien->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ $pasien->tanggal_lahir }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telp" value="{{ $pasien->no_telp }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ $pasien->alamat }}</textarea>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
