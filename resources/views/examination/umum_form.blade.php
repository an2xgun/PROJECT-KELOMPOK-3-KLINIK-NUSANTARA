@extends('layout')
@section('content')

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-pulse"></i> Form Pemeriksaan Medis
                    </h5>
                </div>
                <div class="card-body p-5">
                    <!-- Info Pasien - Professional Card -->
                    <div class="alert alert-light border-left border-4" style="border-left-color: #667eea;">
                        <div class="row">
                                <div class="col-md-4">
                                    <div><strong>Pasien:</strong></div>
                                    <div style="font-size: 18px; color: #667eea; font-weight: 600;">{{ optional(optional($pendaftaran)->pasien)->nama ?? '-' }}</div>
                                    <small class="text-muted">No RM: {{ optional(optional($pendaftaran)->pasien)->no_rm ?? '-' }}</small>
                                </div>
                                <div class="col-md-4">
                                    <div><strong>Poliklinik:</strong></div>
                                    <div style="font-size: 16px; font-weight: 600;">{{ optional(optional($pendaftaran)->poliklinik)->name ?? '-' }}</div>
                                </div>
                            <div class="col-md-4">
                                <div><strong>Tanggal & Waktu:</strong></div>
                                <div style="font-size: 16px; font-weight: 600;">{{ now()->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('examination.store', $pendaftaran->id) }}" method="POST">
                        @csrf

                        <!-- Section 1: Keluhan & Anamnesis -->
                        <div class="mb-4">
                            <h6 class="mb-3" style="color: #667eea; font-weight: 700;">
                                <i class="bi bi-info-circle"></i> Data Keluhan & Riwayat Penyakit
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-6">Keluhan Utama <span class="text-danger">*</span></label>
                                <textarea name="keluhan_utama" class="form-control form-control-lg @error('keluhan_utama') is-invalid @enderror" 
                                          rows="3" placeholder="Tuliskan keluhan pasien..." required>{{ $pendaftaran->keluhan ?? '' }}</textarea>
                                @error('keluhan_utama') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-6">Anamnesis / Riwayat Penyakit</label>
                                <textarea name="anamnesis" class="form-control form-control-lg @error('anamnesis') is-invalid @enderror" 
                                          rows="3" placeholder="Riwayat penyakit sebelumnya...">{{ old('anamnesis') }}</textarea>
                                @error('anamnesis') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-6">Pemeriksaan Fisik</label>
                                <textarea name="pemeriksaan_fisik" class="form-control form-control-lg @error('pemeriksaan_fisik') is-invalid @enderror" rows="2" placeholder="Ringkasan pemeriksaan fisik...">{{ old('pemeriksaan_fisik') }}</textarea>
                                @error('pemeriksaan_fisik') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label">Tinggi Badan (cm)</label>
                                    <input type="number" step="0.1" name="tinggi" value="{{ old('tinggi', $rekam->tinggi ?? '') }}" class="form-control @error('tinggi') is-invalid @enderror" placeholder="cm">
                                    @error('tinggi') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Berat Badan (kg)</label>
                                    <input type="number" step="0.1" name="berat" value="{{ old('berat', $rekam->berat ?? '') }}" class="form-control @error('berat') is-invalid @enderror" placeholder="kg">
                                    @error('berat') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Suhu (°C)</label>
                                    <input type="number" step="0.1" name="suhu" value="{{ old('suhu', $rekam->suhu ?? '') }}" class="form-control @error('suhu') is-invalid @enderror" placeholder="°C">
                                    @error('suhu') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tekanan Darah</label>
                                    <input type="text" name="darah" value="{{ old('darah', $rekam->darah ?? '') }}" class="form-control @error('darah') is-invalid @enderror" placeholder="120/80">
                                    @error('darah') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr style="border-color: #e0e0e0; margin: 2rem 0;">

                        <!-- Section 2: Diagnosis & Treatment -->
                        <div class="mb-4">
                            <h6 class="mb-3" style="color: #667eea; font-weight: 700;">
                                <i class="bi bi-stethoscope"></i> Diagnosis & Treatment
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-6">Diagnosa Primer <span class="text-danger">*</span></label>
                                    <select name="diagnosa_primer" id="diagnosa_primer" class="form-select form-select-lg diagnosa-select @error('diagnosa_primer') is-invalid @enderror" style="width: 100%;">
                                        <option value="">-- Ketik untuk mencari diagnosa --</option>
                                        @foreach($diagnosa as $d)
                                            <option value="{{ $d->id }}" data-kode="{{ $d->kode }}" {{ (old('diagnosa_primer', $rekam->diagnosa_primer ?? '') == $d->id) ? 'selected' : '' }}>
                                                [{{ $d->kode }}] {{ $d->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('diagnosa_primer') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-6">Diagnosa Sekunder</label>
                                    <select name="diagnosa_sekunder" id="diagnosa_sekunder" class="form-select form-select-lg diagnosa-select @error('diagnosa_sekunder') is-invalid @enderror" style="width: 100%;">
                                        <option value="">-- Ketik untuk mencari diagnosa --</option>
                                        @foreach($diagnosa as $d)
                                            <option value="{{ $d->id }}" data-kode="{{ $d->kode }}" {{ (old('diagnosa_sekunder', $rekam->diagnosa_sekunder ?? '') == $d->id) ? 'selected' : '' }}>
                                                [{{ $d->kode }}] {{ $d->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('diagnosa_sekunder') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Info Box with selected diagnoses -->
                            <div class="alert alert-info" id="diagnosa-info" style="display: none; margin-top: 15px;">
                                <small><strong>Diagnosa Terpilih:</strong></small>
                                <div id="selected-diagnosa" class="d-flex flex-wrap gap-2" style="margin-top: 8px;"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-6">Tindakan / Rencana Perawatan <small class="text-muted">(pilih satu atau lebih)</small></label>
                                    <div class="border rounded p-3" style="background-color: #f8f9fa; max-height: 300px; overflow-y: auto;">
                                        @if($tindakan->count() > 0)
                                            @foreach($tindakan as $t)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="tindakan_ids[]" value="{{ $t->id }}" 
                                                           id="tindakan_{{ $t->id }}"
                                                           {{ in_array($t->id, old('tindakan_ids', ($rekam && $rekam->tindakan) ? $rekam->tindakan->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tindakan_{{ $t->id }}">
                                                        <strong>{{ $t->nama }}</strong>
                                                        <small class="text-muted d-block">Rp {{ number_format($t->harga ?? 0, 0, ',', '.') }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted">Tidak ada tindakan tersedia</p>
                                        @endif
                                    </div>
                                    @error('tindakan_ids') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Catatan Tambahan -->
                        <div class="mb-4">
                            <h6 class="mb-3" style="color: #667eea; font-weight: 700;">
                                <i class="bi bi-file-text"></i> Catatan Tambahan
                            </h6>
                            
                            <div class="mb-3">
                                <textarea name="catatan" class="form-control form-control-lg @error('catatan') is-invalid @enderror" 
                                          rows="2" placeholder="Catatan atau keterangan tambahan...">{{ old('catatan') }}</textarea>
                                @error('catatan') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-5 pt-3 border-top">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                    <i class="bi bi-check-circle"></i> Simpan & Lanjut ke Resep
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label.fw-6 {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    
    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1rem;
        font-size: 15px;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
(function(){
    // Initialize Select2 for diagnosis dropdowns with autocomplete
    $('#diagnosa_primer, #diagnosa_sekunder').select2({
        theme: 'bootstrap-5',
        language: 'id',
        allowClear: true,
        placeholder: 'Ketik untuk mencari diagnosa...',
        width: '100%',
        matcher: function(params, data) {
            if (!params.term) return data;
            const term = params.term.toUpperCase();
            const text = data.text.toUpperCase();
            const id = (data.id || '').toUpperCase();
            if (text.includes(term) || id.includes(term)) return data;
            return null;
        },
        templateResult: function(data) {
            if (!data.id) return data.text;
            const option = $(data.element);
            const kode = option.data('kode') || '';
            return $('<span><strong>[' + kode + ']</strong> ' + data.text.substring(kode ? kode.length + 3 : 0) + '</span>');
        },
        templateSelection: function(data) {
            if (!data.id) return data.text;
            const option = $(data.element);
            const kode = option.data('kode') || '';
            return '[' + kode + '] ' + data.text.substring(kode ? kode.length + 3 : 0);
        }
    });

    // Update info box when diagnoses are selected
    function updateDiagnosaInfo() {
        const primer = $('#diagnosa_primer').find('option:selected');
        const sekunder = $('#diagnosa_sekunder').find('option:selected');
        const infoBox = $('#diagnosa-info');
        const selectedDiv = $('#selected-diagnosa');
        
        selectedDiv.empty();
        let hasSelection = false;
        
        if (primer.val()) {
            const kode = primer.data('kode') || '';
            const nama = primer.text();
            selectedDiv.append(`<span class="badge bg-primary">[${kode}] ${nama.substring(kode.length + 3)}</span>`);
            hasSelection = true;
        }
        
        if (sekunder.val()) {
            const kode = sekunder.data('kode') || '';
            const nama = sekunder.text();
            selectedDiv.append(`<span class="badge bg-info">[${kode}] ${nama.substring(kode.length + 3)}</span>`);
            hasSelection = true;
        }
        
        infoBox.toggle(hasSelection);
    }

    $('#diagnosa_primer, #diagnosa_sekunder').change(updateDiagnosaInfo);
    updateDiagnosaInfo();
})();
</script>

@endsection