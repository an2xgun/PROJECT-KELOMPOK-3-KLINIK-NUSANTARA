@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse"></i>
                        Form Pemeriksaan - Poliklinik Kandungan & Kebidanan
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Patient Info -->
                    <div class="alert alert-info">
                        <h6><strong>{{ optional(optional($pendaftaran)->pasien)->nama ?? '-' }}</strong></h6>
                        <small class="text-muted">
                            No. Identitas: {{ optional(optional($pendaftaran)->pasien)->no_identitas ?? '-' }}<br>
                            Poliklinik: {{ optional(optional($pendaftaran)->poliklinik)->nama ?? '-' }}<br>
                            Dokter: {{ optional($pendaftaran->dokter)->nama ?? 'Belum ditentukan' }}<br>
                            Waktu: {{ optional($pendaftaran)->created_at ? \Carbon\Carbon::parse(optional($pendaftaran)->created_at)->format('d M Y H:i') : '-' }}
                        </small>
                    </div>

                    <form action="{{ route('examination.store', $pendaftaran->id) }}" method="POST">
                        @csrf

                        <!-- Keluhan Utama -->
                        <div class="form-group mb-3">
                            <label for="keluhan_utama" class="form-label"><strong>Keluhan Utama</strong></label>
                            <textarea class="form-control @error('keluhan_utama') is-invalid @enderror" 
                                      id="keluhan_utama" name="keluhan_utama" rows="3" required 
                                      placeholder="Contoh: Sakit perut, mual, demam, perdarahan, dll">{{ old('keluhan_utama') }}</textarea>
                            @error('keluhan_utama')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Riwayat Obstetri -->
                        <h6 class="mt-4 mb-3"><strong>Riwayat Obstetri & Ginekologi</strong></h6>
                        <div class="form-group mb-3">
                            <label for="riwayat_obstetri" class="form-label"><strong>Riwayat Kehamilan & Persalinan Sebelumnya</strong></label>
                            <textarea class="form-control @error('riwayat_obstetri') is-invalid @enderror" 
                                      id="riwayat_obstetri" name="riwayat_obstetri" rows="3" required
                                      placeholder="Jumlah anak, cara persalinan (normal/SC), komplikasi, riwayat keguguran, dll">{{ old('riwayat_obstetri') }}</textarea>
                            @error('riwayat_obstetri')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Anamnesis -->
                        <div class="form-group mb-3">
                            <label for="anamnesis" class="form-label"><strong>Anamnesis (Riwayat Penyakit Sekarang)</strong></label>
                            <textarea class="form-control @error('anamnesis') is-invalid @enderror" 
                                      id="anamnesis" name="anamnesis" rows="3" required
                                      placeholder="Riwayat penyakit terkini, pengobatan sebelumnya, alergi, dll">{{ old('anamnesis') }}</textarea>
                            @error('anamnesis')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Usia Kehamilan (if applicable) -->
                        <div class="form-group mb-3">
                            <label for="usia_kehamilan" class="form-label"><strong>Usia Kehamilan (minggu)</strong></label>
                            <input type="number" class="form-control @error('usia_kehamilan') is-invalid @enderror" 
                                   id="usia_kehamilan" name="usia_kehamilan" placeholder="0-42" min="0" max="42" value="{{ old('usia_kehamilan') }}">
                            <small class="text-muted">Kosongkan jika bukan pemeriksaan kehamilan</small>
                            @error('usia_kehamilan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Trimester -->
                        <div class="form-group mb-3">
                            <label for="trimester" class="form-label"><strong>Trimester</strong></label>
                            <select class="form-select @error('trimester') is-invalid @enderror" 
                                    id="trimester" name="trimester">
                                <option value="">-- Pilih Trimester --</option>
                                <option value="1" {{ old('trimester') == 1 ? 'selected' : '' }}>Trimester 1 (0-13 minggu)</option>
                                <option value="2" {{ old('trimester') == 2 ? 'selected' : '' }}>Trimester 2 (14-20 minggu)</option>
                                <option value="3" {{ old('trimester') == 3 ? 'selected' : '' }}>Trimester 3 (21-42 minggu)</option>
                            </select>
                            @error('trimester')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pemeriksaan Umum -->
                        <h6 class="mt-4 mb-3"><strong>Pemeriksaan Fisik Umum</strong></h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tekanan_darah" class="form-label"><strong>Tekanan Darah (mmHg)</strong></label>
                                    <input type="text" class="form-control @error('tekanan_darah') is-invalid @enderror" 
                                           id="tekanan_darah" name="tekanan_darah" placeholder="120/80" required value="{{ old('tekanan_darah') }}">
                                    @error('tekanan_darah')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="berat_badan" class="form-label"><strong>Berat Badan (kg)</strong></label>
                                    <input type="number" class="form-control @error('berat_badan') is-invalid @enderror" 
                                           id="berat_badan" name="berat_badan" step="0.1" placeholder="60" required value="{{ old('berat_badan') }}">
                                    @error('berat_badan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pemeriksaan Kandungan -->
                        <h6 class="mt-4 mb-3"><strong>Pemeriksaan Obstetri / Ginekologi</strong></h6>
                        <div class="form-group mb-3">
                            <label for="kondisi_janin" class="form-label"><strong>Kondisi Janin (jika ada)</strong></label>
                            <textarea class="form-control @error('kondisi_janin') is-invalid @enderror" 
                                      id="kondisi_janin" name="kondisi_janin" rows="2"
                                      placeholder="DJJ, aktivitas janin, posisi, besar janin, dll">{{ old('kondisi_janin') }}</textarea>
                            @error('kondisi_janin')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Detail Pemeriksaan -->
                        <div class="form-group mb-3">
                            <label for="pemeriksaan_fisik" class="form-label"><strong>Hasil Pemeriksaan Lanjutan</strong></label>
                            <textarea class="form-control @error('pemeriksaan_fisik') is-invalid @enderror" 
                                      id="pemeriksaan_fisik" name="pemeriksaan_fisik" rows="3" required
                                      placeholder="Inspeksi, palpasi, ukuran fundus, tinggi fundus uteri, edema, varices, dll">{{ old('pemeriksaan_fisik') }}</textarea>
                            @error('pemeriksaan_fisik')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Diagnosis -->
                        <div class="form-group mb-3">
                            <label for="master_diagnosa_id" class="form-label"><strong>Diagnosis</strong></label>
                            <select class="form-select @error('master_diagnosa_id') is-invalid @enderror" 
                                    id="master_diagnosa_id" name="master_diagnosa_id" required>
                                <option value="">-- Pilih Diagnosis --</option>
                                @foreach($diagnosa as $diag)
                                    <option value="{{ $diag->id }}" {{ old('master_diagnosa_id') == $diag->id ? 'selected' : '' }}>
                                        {{ $diag->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('master_diagnosa_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tindakan -->
                        <div class="form-group mb-3">
                            <label class="form-label"><strong>Rencana Tindakan / Terapi</strong></label>
                            <div class="border rounded p-3" style="background-color: #f8f9fa; max-height: 300px; overflow-y: auto;">
                                @if($tindakan->count() > 0)
                                    @foreach($tindakan as $tind)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="tindakan_ids[]" value="{{ $tind->id }}" 
                                                   id="tindakan_{{ $tind->id }}"
                                                   {{ in_array($tind->id, old('tindakan_ids', ($rekam && $rekam->tindakan) ? $rekam->tindakan->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tindakan_{{ $tind->id }}">
                                                <strong>{{ $tind->nama }}</strong>
                                                <small class="text-muted d-block">Rp {{ number_format($tind->harga ?? 0, 0, ',', '.') }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Tidak ada tindakan tersedia</p>
                                @endif
                            </div>
                            @error('tindakan_ids') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Rekomendasi -->
                        <div class="form-group mb-3">
                            <label for="rekomendasi_kandungan" class="form-label"><strong>Rekomendasi & Follow-up</strong></label>
                            <textarea class="form-control @error('rekomendasi_kandungan') is-invalid @enderror" 
                                      id="rekomendasi_kandungan" name="rekomendasi_kandungan" rows="2"
                                      placeholder="USG, lab, kontrol berikutnya, istirahat, pantang, dll">{{ old('rekomendasi_kandungan') }}</textarea>
                            @error('rekomendasi_kandungan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="form-group mb-3">
                            <label for="catatan" class="form-label"><strong>Catatan Tambahan</strong></label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan Pemeriksaan
                            </button>
                            <a href="{{ route('pendaftaran.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
