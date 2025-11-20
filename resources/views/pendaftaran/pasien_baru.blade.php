@extends('layout')

@section('content')
<h3>🩺 Pendaftaran Pasien Baru</h3>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('pasien.store') }}" method="POST" class="mt-3">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>NIK</label>
            <input type="text" name="nik" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>No. Rekam Medis</label>
            <input type="text" name="no_rm" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select" required>
                <option value="">-- Pilih --</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>No. Telepon</label>
            <input type="text" name="no_telepon" class="form-control" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
