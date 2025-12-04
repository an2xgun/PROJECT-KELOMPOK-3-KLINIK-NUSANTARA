@extends('layout')

@section('content')
<h3>🩺 Pemeriksaan Kandungan</h3>

<div class="card p-3">

    <h5>Nama Pasien : {{ $pasien->nama }}</h5>
    <p>Keluhan : {{ $pasien->keluhan }}</p>
    <hr>

    <form action="{{ route('pemeriksaan_kandungan.store', $pasien->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>HPHT</label>
            <input type="date" name="hpht" class="form-control">
        </div>

        <div class="mb-3">
            <label>Usia Kehamilan (minggu)</label>
            <input type="number" name="usia_kehamilan" class="form-control" placeholder="Contoh: 24">
        </div>

        <div class="mb-3">
            <label>Tekanan Darah</label>
            <input type="text" name="tekanan_darah" class="form-control" placeholder="110/80">
        </div>

        <div class="mb-3">
            <label>Denyut Jantung Janin (DJP)</label>
            <input type="text" name="djj" class="form-control" placeholder="150 bpm">
        </div>

        <div class="mb-3">
            <label>Diagnosis Dokter</label>
            <textarea name="diagnosis" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Terapi / Resep</label>
            <textarea name="terapi" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Simpan Pemeriksaan</button>
    </form>

</div>
@endsection
