@extends('layout')

@section('content')
<h3>✏️ Edit Jadwal Dokter</h3>

<form action="{{ route('master.jadwal.update', $d->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Dokter</label>
        <select name="dokter_id" class="form-control">
            @foreach($dokter as $d)
            <option value="{{ $d->id }}" {{ $d->dokter_id == $d->id ? 'selected' : '' }}>
                {{ $d->nama }} ({{ ucfirst($d->spesialis) }})
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Poliklinik</label>
        <select name="poli_id" class="form-control">
            @foreach($poliklinik as $p)
            <option value="{{ $p->id }}" {{ $d->poli_id == $p->id ? 'selected' : '' }}>
                {{ $p->nama_poli }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Hari</label>
        <select name="hari" class="form-control">
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
            <option {{ $d->hari == $day ? 'selected' : '' }}>
                {{ $day }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control" value="{{ $d->jam_mulai }}">
    </div>

    <div class="mb-3">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control" value="{{ $d->jam_selesai }}">
    </div>

    <button class="btn btn-success">Update</button>
</form>

@endsection
