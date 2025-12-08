@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h2 class="mb-4">
                <a href="{{ route('pendaftaran.choice') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                Input Pasien Baru
            </h2>

            <form action="{{ route('pendaftaran.store-new-patient') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong>Data Pribadi Pasien</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                       value="{{ old('nama') }}" required>
                                @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIK *</label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                       value="{{ old('nik') }}" required placeholder="16 digit">
                                @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No RM</label>
                                <input type="text" name="no_rm" class="form-control" value="{{ $nextNoRm ?? '' }}" readonly>
                                <small class="text-muted">No RM otomatis. Akan disimpan saat simpan pasien.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin *</label>
                                <select name="kelamin" class="form-select @error('kelamin') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir *</label>
                                <input type="date" name="lahir" class="form-control @error('lahir') is-invalid @enderror" 
                                       value="{{ old('lahir') }}" required>
                                @error('lahir') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Golongan Darah *</label>
                                <select name="golongan_darah" class="form-select @error('golongan_darah') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="O" {{ old('golongan_darah') === 'O' ? 'selected' : '' }}>O</option>
                                    <option value="A" {{ old('golongan_darah') === 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('golongan_darah') === 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('golongan_darah') === 'AB' ? 'selected' : '' }}>AB</option>
                                </select>
                                @error('golongan_darah') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pasien *</label>
                                <select name="jenis_pasien" class="form-select @error('jenis_pasien') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Umum" {{ old('jenis_pasien') === 'Umum' ? 'selected' : '' }}>Umum</option>
                                    <option value="Asuransi" {{ old('jenis_pasien') === 'Asuransi' ? 'selected' : '' }}>Asuransi</option>
                                    <option value="BPJS" {{ old('jenis_pasien') === 'BPJS' ? 'selected' : '' }}>BPJS</option>
                                </select>
                                @error('jenis_pasien') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agama *</label>
                                <select name="agama" class="form-select @error('agama') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Islam" {{ old('agama') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katholik" {{ old('agama') === 'Katholik' ? 'selected' : '' }}>Katholik</option>
                                    <option value="Hindu" {{ old('agama') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ old('agama') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ old('agama') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                                @error('agama') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pendidikan</label>
                                <select name="pendidikan" class="form-select @error('pendidikan') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="SD" {{ old('pendidikan') === 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan') === 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('pendidikan') === 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="Diploma" {{ old('pendidikan') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="S1" {{ old('pendidikan') === 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan') === 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan') === 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('pendidikan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Provinsi *</label>
                                <select name="provinsi" class="form-select @error('provinsi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    <option value="Sumatera Utara" {{ old('provinsi') === 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                                    <option value="Sumatera Barat" {{ old('provinsi') === 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                                    <option value="Riau" {{ old('provinsi') === 'Riau' ? 'selected' : '' }}>Riau</option>
                                    <option value="Jambi" {{ old('provinsi') === 'Jambi' ? 'selected' : '' }}>Jambi</option>
                                    <option value="Sumatera Selatan" {{ old('provinsi') === 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                                    <option value="Bengkulu" {{ old('provinsi') === 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                                    <option value="Lampung" {{ old('provinsi') === 'Lampung' ? 'selected' : '' }}>Lampung</option>
                                    <option value="Jawa Barat" {{ old('provinsi') === 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                    <option value="Jawa Tengah" {{ old('provinsi') === 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                    <option value="DI Yogyakarta" {{ old('provinsi') === 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                                    <option value="Jawa Timur" {{ old('provinsi') === 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                    <option value="Banten" {{ old('provinsi') === 'Banten' ? 'selected' : '' }}>Banten</option>
                                    <option value="Bali" {{ old('provinsi') === 'Bali' ? 'selected' : '' }}>Bali</option>
                                    <option value="Nusa Tenggara Barat" {{ old('provinsi') === 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                                    <option value="Nusa Tenggara Timur" {{ old('provinsi') === 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                                    <option value="Kalimantan Barat" {{ old('provinsi') === 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                                    <option value="Kalimantan Tengah" {{ old('provinsi') === 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                                    <option value="Kalimantan Selatan" {{ old('provinsi') === 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                                    <option value="Kalimantan Timur" {{ old('provinsi') === 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                    <option value="Sulawesi Utara" {{ old('provinsi') === 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                                    <option value="Sulawesi Tengah" {{ old('provinsi') === 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                                    <option value="Sulawesi Selatan" {{ old('provinsi') === 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                                    <option value="Sulawesi Tenggara" {{ old('provinsi') === 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                                    <option value="Maluku" {{ old('provinsi') === 'Maluku' ? 'selected' : '' }}>Maluku</option>
                                    <option value="Maluku Utara" {{ old('provinsi') === 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>
                                    <option value="Papua Barat" {{ old('provinsi') === 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                                    <option value="Papua" {{ old('provinsi') === 'Papua' ? 'selected' : '' }}>Papua</option>
                                    <option value="DKI Jakarta" {{ old('provinsi') === 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                </select>
                                @error('provinsi') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No Telepon</label>
                                <input type="tel" name="telepon" class="form-control" value="{{ old('telepon') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan') }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat *</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                          rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Simpan & Lanjut ke Pendaftaran Poli
                        </button>
                        <a href="{{ route('pendaftaran.choice') }}" class="btn btn-secondary btn-lg">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 12px 12px 0 0;
    font-size: 1.1rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ddd;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>

<script>
// Form validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

@endsection
