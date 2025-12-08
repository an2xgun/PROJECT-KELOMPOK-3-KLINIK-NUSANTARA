@extends('layout') 
@section('content')

<h3>Data Pasien Baru</h3>

<form action="{{ route('pasien.store') }}" method="POST">
@csrf

<div class="row">

    {{-- KOLOM KIRI --}}
    <div class="col-md-6">

        <label>No RM</label>
        <input type="text" name="no_rm" value="{{ $no_rm ?? '' }}" readonly class="form-control mb-2">

        <label>Nama Pasien *</label>
        <input name="nama" required class="form-control mb-2">

        <label>No KTP</label>
        <div class="input-group mb-2">
            <input name="nik" class="form-control">
            <button class="btn btn-secondary" type="button">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <label>Agama *</label>
        <select name="agama" required class="form-control mb-2">
            <option value="">-- Pilih --</option>
            <option value="Islam">Islam</option>
            <option value="Kristen">Kristen</option>
            <option value="Hindu">Hindu</option>
            <option value="Budha">Budha</option>
        </select>

        <label>Pendidikan</label>
        <select name="pendidikan" class="form-control mb-2">
            <option value="">-- Pilih --</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA">SMA</option>
            <option value="D3">D3</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
        </select>

        <label>Tanggal Lahir *</label>
        <input type="date" name="lahir" required class="form-control mb-2">

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6">

        <label>Jenis Kelamin *</label>
        <select name="kelamin" required class="form-control mb-2">
            <option value="">-- Pilih --</option>
            <option value="laki-laki">Laki-laki</option>
            <option value="perempuan">Perempuan</option>
        </select>

        <label>Nomor Telepon *</label>
        <input type="tel" name="telepon" required class="form-control mb-2">

        <label>Alamat</label>
        <textarea name="alamat" class="form-control mb-2"></textarea>

        <label>Pekerjaan</label>
        <input name="pekerjaan" class="form-control mb-2">

    </div>
</div>

<button type="submit" class="btn btn-success mt-3">Simpan</button>
<a href="{{ route('pasien.index') }}" class="btn btn-secondary mt-3">Batal</a>

</form>

@endsection
