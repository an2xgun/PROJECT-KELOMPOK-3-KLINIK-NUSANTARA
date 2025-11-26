@extends('layout')

@section('content')
<h3>➕ Tambah Jadwal Dokter</h3>

<form action="{{ route('master.jadwal.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Dokter</label>
        <select name="dokter_id" class="form-control" required>
            @foreach($dokter as $d)
            <option value="{{ $d->id }}">{{ $d->nama }} ({{ ucfirst($d->spesialis) }})</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Poliklinik</label>
        <select name="poli_id" class="form-control" required>
            @foreach($poliklinik as $p)
            <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Hari</label>
        <select name="hari" class="form-control" required>
            <option>Senin</option>
            <option>Selasa</option>
            <option>Rabu</option>
            <option>Kamis</option>
            <option>Jumat</option>
            <option>Sabtu</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control" required>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>

@endsection
