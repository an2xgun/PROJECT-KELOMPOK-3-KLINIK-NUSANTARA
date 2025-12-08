@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>Buat Rekam Medis Baru</h3>

    <form action="{{ route('rekam.store') }}" method="POST" class="mt-3">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pasien *</label>
                    @if(isset($pendaftaran) && $pendaftaran)
                        <input type="hidden" name="id_pasien" value="{{ $pendaftaran->pasien->id }}">
                        <div class="form-control">{{ $pendaftaran->pasien->nama }} ({{ $pendaftaran->pasien->no_rm ?? $pendaftaran->pasien->kodepasien }})</div>
                    @else
                        <select name="id_pasien" class="form-control" required>
                            <option value="">-- Pilih Pasien --</option>
                            @foreach(\App\Models\Pasien::all() as $pasien)
                                <option value="{{ $pasien->id }}">{{ $pasien->nama }} ({{ $pasien->no_rm ?? $pasien->kodepasien }})</option>
                            @endforeach
                        </select>
                    @endif
                    @error('id_pasien') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Antrian *</label>
                    @if(isset($pendaftaran) && $pendaftaran)
                        <input type="text" name="nomorantrian" class="form-control" value="{{ $pendaftaran->nomor_antrian ?? $pendaftaran->nomor_antrian }}" readonly required>
                    @else
                        <input type="text" name="nomorantrian" class="form-control" required>
                    @endif
                    @error('nomorantrian') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Layanan *</label>
                    @if(isset($pendaftaran) && $pendaftaran)
                        <input type="text" name="layanan" class="form-control" value="{{ optional($pendaftaran->poliklinik)->name ?? ($pendaftaran->layanan ?? '') }}" readonly required>
                    @else
                        <input type="text" name="layanan" class="form-control" required>
                    @endif
                    @error('layanan') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Keluhan *</label>
                    <textarea name="keluhan" class="form-control" required></textarea>
                    @error('keluhan') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Dokter *</label>
                    @if(isset($pendaftaran) && $pendaftaran)
                        <input type="hidden" name="id_dokter" value="{{ optional($pendaftaran->jadwalPoli)->dokter_id }}">
                        <div class="form-control">{{ optional(optional($pendaftaran->jadwalPoli)->dokter)->nama ?? 'Auto' }}</div>
                    @else
                        <select name="id_dokter" class="form-control" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach(\App\Models\Dokter::all() as $dokter)
                                <option value="{{ $dokter->id }}">{{ $dokter->nama }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('id_dokter') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Diagnosa</label>
                    <textarea name="diagnosa" class="form-control"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tinggi (cm)</label>
                            <input type="number" name="tinggi" class="form-control" placeholder="cm">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" name="berat" class="form-control" placeholder="kg">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tekanan Darah</label>
                            <input type="text" name="darah" class="form-control" placeholder="mmHg">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lingkar Pinggang (cm)</label>
                            <input type="number" name="pinggang" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('rekam.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
