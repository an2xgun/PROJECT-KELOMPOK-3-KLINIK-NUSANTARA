@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tooth"></i>
                        Form Pemeriksaan - Poliklinik Gigi
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
                            <label for="keluhan_utama" class="form-label"><strong>Keluhan Gigi</strong></label>
                            <textarea class="form-control @error('keluhan_utama') is-invalid @enderror" 
                                      id="keluhan_utama" name="keluhan_utama" rows="3" required 
                                      placeholder="Contoh: Gigi berlubang, sakit saat mengunyah, gusi bengkak, dll">{{ old('keluhan_utama') }}</textarea>
                            @error('keluhan_utama')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Riwayat Gigi -->
                        <div class="form-group mb-3">
                            <label for="anamnesis" class="form-label"><strong>Riwayat Penyakit Gigi & Mulut</strong></label>
                            <textarea class="form-control @error('anamnesis') is-invalid @enderror" 
                                      id="anamnesis" name="anamnesis" rows="3" required 
                                      placeholder="Riwayat perawatan gigi sebelumnya, kebiasaan menggosok gigi, dll">{{ old('anamnesis') }}</textarea>
                            @error('anamnesis')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kondisi Gigi -->
                        <h6 class="mt-4 mb-3"><strong>Pemeriksaan Intra-Oral</strong></h6>
                        <div class="form-group mb-3">
                            <label for="kondisi_gigi" class="form-label"><strong>Kondisi Gigi</strong></label>
                            <textarea class="form-control @error('kondisi_gigi') is-invalid @enderror" 
                                      id="kondisi_gigi" name="kondisi_gigi" rows="3" required
                                      placeholder="Contoh: Gigi 16 karies, gigi 26 filling, gigi 36 mobile, dll">{{ old('kondisi_gigi') }}</textarea>
                            <small class="text-muted">Perincian kondisi setiap gigi (gunakan nomor FDI International)</small>
                            @error('kondisi_gigi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kondisi Gusi -->
                        <div class="form-group mb-3">
                            <label for="kondisi_gusi" class="form-label"><strong>Kondisi Gusi & Periodontal</strong></label>
                            <textarea class="form-control @error('kondisi_gusi') is-invalid @enderror" 
                                      id="kondisi_gusi" name="kondisi_gusi" rows="3" required
                                      placeholder="Contoh: Gusi eritema, pendarahan saat probing, pocket > 4mm, dll">{{ old('kondisi_gusi') }}</textarea>
                            @error('kondisi_gusi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pemeriksaan Ekstra-Oral -->
                        <div class="form-group mb-3">
                            <label for="pemeriksaan_fisik" class="form-label"><strong>Pemeriksaan Ekstra-Oral & Jaringan Lunak</strong></label>
                            <textarea class="form-control @error('pemeriksaan_fisik') is-invalid @enderror" 
                                      id="pemeriksaan_fisik" name="pemeriksaan_fisik" rows="3" required
                                      placeholder="Condisi bibir, lidah, palatum, faring, TMJ, dll">{{ old('pemeriksaan_fisik') }}</textarea>
                            @error('pemeriksaan_fisik')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Diagnosis -->
                        <div class="form-group mb-3">
                            <label for="master_diagnosa_id" class="form-label"><strong>Diagnosis Gigi</strong></label>
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
                            <label class="form-label"><strong>Rencana Perawatan / Tindakan</strong></label>
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

                        <!-- Perawatan Gigi Khusus -->
                        <div class="form-group mb-3">
                            <label for="perawatan_gigi" class="form-label"><strong>Detail Perawatan</strong></label>
                            <textarea class="form-control @error('perawatan_gigi') is-invalid @enderror" 
                                      id="perawatan_gigi" name="perawatan_gigi" rows="2"
                                      placeholder="Contoh: Scaling, polishing, filling, ekstraksi, dll">{{ old('perawatan_gigi') }}</textarea>
                            @error('perawatan_gigi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Rekomendasi Gigi -->
                        <div class="form-group mb-3">
                            <label for="rekomendasi_gigi" class="form-label"><strong>Rekomendasi & Follow-up</strong></label>
                            <textarea class="form-control @error('rekomendasi_gigi') is-invalid @enderror" 
                                      id="rekomendasi_gigi" name="rekomendasi_gigi" rows="2"
                                      placeholder="Contoh: Kontrol kembali 2 minggu, perawatan berlanjutan, dll">{{ old('rekomendasi_gigi') }}</textarea>
                            @error('rekomendasi_gigi')
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
