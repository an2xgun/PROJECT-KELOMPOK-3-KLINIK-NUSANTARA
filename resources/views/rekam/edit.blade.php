@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>Edit Rekam Medis</h3>

    <form action="{{ route('rekam.update', $rekam->id) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pasien</label>
                    <input type="text" class="form-control" value="{{ optional($rekam->pasien)->nama ?? '-' }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Antrian</label>
                    <input type="text" name="nomorantrian" class="form-control" value="{{ $rekam->nomorantrian }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keluhan *</label>
                    <textarea name="keluhan" class="form-control" required>{{ old('keluhan', $rekam->keluhan) }}</textarea>
                    @error('keluhan') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Diagnosa</label>
                    <textarea name="diagnosa" class="form-control">{{ old('diagnosa', $rekam->diagnosa) }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Dokter</label>
                    <select name="id_dokter" class="form-control">
                        <option value="">-- Pilih Dokter --</option>
                        @foreach(\App\Models\Dokter::all() as $dok)
                            <option value="{{ $dok->id }}" {{ $dok->id == $rekam->id_dokter ? 'selected' : '' }}>{{ $dok->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tinggi (cm)</label>
                            <input type="number" name="tinggi" class="form-control" value="{{ old('tinggi', $rekam->tinggi) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" name="berat" class="form-control" value="{{ old('berat', $rekam->berat) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tekanan Darah</label>
                            <input type="text" name="darah" class="form-control" value="{{ old('darah', $rekam->darah) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lingkar Pinggang (cm)</label>
                            <input type="number" name="pinggang" class="form-control" value="{{ old('pinggang', $rekam->pinggang) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('rekam.show', $rekam->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
