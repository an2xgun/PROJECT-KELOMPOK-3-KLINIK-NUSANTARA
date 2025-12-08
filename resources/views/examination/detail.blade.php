@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header dengan Info Pasien -->
            <div class="card shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0">
                                <i class="bi bi-clipboard-pulse"></i> Detail Pemeriksaan
                            </h4>
                            <small class="mt-2 d-block">{{ optional(optional($pendaftaran)->pasien)->nama ?? '-' }} | No RM: {{ optional(optional($pendaftaran)->pasien)->no_rm ?? '-' }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge" style="background: rgba(255,255,255,0.3); font-size: 14px;">
                                {{ $pendaftaran->nomor_antrian ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Kolom Kiri: Form Status Pemeriksaan -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                            <h6 class="mb-0" style="color: #667eea; font-weight: 700;">
                                <i class="bi bi-gear"></i> Status Pemeriksaan
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('examination.updateStatus', $rekam->id ?? 0) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label fw-6">Status</label>
                                    <select name="status_pemeriksaan" class="form-select form-select-lg">
                                        <option value="Belum Diperiksa" {{ ($rekam->status_pemeriksaan ?? '') == 'Belum Diperiksa' ? 'selected' : '' }}>Belum Diperiksa</option>
                                        <option value="Sedang Diperiksa" {{ ($rekam->status_pemeriksaan ?? '') == 'Sedang Diperiksa' ? 'selected' : '' }}>Sedang Diperiksa</option>
                                        <option value="Sudah Diperiksa" {{ ($rekam->status_pemeriksaan ?? '') == 'Sudah Diperiksa' ? 'selected' : '' }}>Sudah Diperiksa</option>
                                        <option value="Ditolak" {{ ($rekam->status_pemeriksaan ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-6">Catatan Status</label>
                                    <textarea name="catatan_status" class="form-control" rows="4" placeholder="Catatan khusus untuk status...">{{ $rekam->catatan_status ?? '' }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-circle"></i> Perbarui Status
                                </button>
                            </form>

                            @if($rekam)
                                <hr class="my-3">
                                <div class="alert alert-info small mb-0">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Tgl Pemeriksaan:</strong> {{ $rekam->tanggalperiksa ?? '-' }}<br>
                                    <strong>Dokter:</strong> {{ optional($rekam->dokter)->nama ?? '-' }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Ringkasan Vitals -->
                    @if($rekam)
                    <div class="card shadow-sm mt-3">
                        <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #27ae60;">
                            <h6 class="mb-0" style="color: #27ae60; font-weight: 700;">
                                <i class="bi bi-heart-pulse"></i> Vitals
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Tinggi Badan</small>
                                <div style="font-size: 18px; font-weight: 600; color: #667eea;">{{ $rekam->tinggi ?? '-' }} cm</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Berat Badan</small>
                                <div style="font-size: 18px; font-weight: 600; color: #667eea;">{{ $rekam->berat ?? '-' }} kg</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Lingkar Pinggang</small>
                                <div style="font-size: 18px; font-weight: 600; color: #667eea;">{{ $rekam->pinggang ?? '-' }} cm</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Tekanan Darah</small>
                                <div style="font-size: 18px; font-weight: 600; color: #e74c3c;">{{ $rekam->darah ?? '-' }}</div>
                            </div>
                            <div>
                                <small class="text-muted">Suhu Tubuh</small>
                                <div style="font-size: 18px; font-weight: 600; color: #e74c3c;">{{ $rekam->suhu ?? '-' }} °C</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Kolom Kanan: Detail Pemeriksaan -->
                <div class="col-md-8">
                    <!-- Info Dasar -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                            <h6 class="mb-0" style="color: #667eea; font-weight: 700;">
                                <i class="bi bi-person-vcard"></i> Info Pendaftaran
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Pasien</strong>
                                    <p class="mb-0">{{ optional(optional($pendaftaran)->pasien)->nama ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Poliklinik</strong>
                                    <p class="mb-0">{{ optional(optional($pendaftaran)->poliklinik)->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Dokter</strong>
                                    <p class="mb-0">{{ optional(optional($pendaftaran->jadwalPoli)->dokter)->nama ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($rekam)
                    <!-- Detail Rekam Medis -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                            <h6 class="mb-0" style="color: #667eea; font-weight: 700;">
                                <i class="bi bi-file-text"></i> Data Pemeriksaan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Keluhan</strong>
                                <p class="mb-0">{{ $rekam->keluhan ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Diagnosa Primer</strong>
                                <p class="mb-0">
                                    @if($rekam->diagnosa_primer)
                                        {{ collect($diagnosa)->find($rekam->diagnosa_primer)?->nama ?? 'ID: ' . $rekam->diagnosa_primer }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Diagnosa Sekunder</strong>
                                <p class="mb-0">
                                    @if($rekam->diagnosa_sekunder)
                                        {{ collect($diagnosa)->find($rekam->diagnosa_sekunder)?->nama ?? 'ID: ' . $rekam->diagnosa_sekunder }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            @if($rekam->tindakan && $rekam->tindakan->count() > 0)
                            <div class="mb-3">
                                <strong>Tindakan</strong>
                                <div>
                                    @foreach($rekam->tindakan as $t)
                                        <span class="badge bg-success me-2">{{ $t->nama }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <strong>Keterangan</strong>
                                <p class="mb-0">{{ $rekam->keterangan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('examination.queue') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Antrian
                                </a>
                                <a href="{{ route('examination.form', $pendaftaran->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit Pemeriksaan
                                </a>
                                <a href="{{ route('prescription.create', $rekam->id) }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Buat Resep
                                </a>
                                <a href="{{ route('rekam.show', $rekam->id) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Belum Ada Data Pemeriksaan</strong><br>
                        Silakan buka form pemeriksaan untuk memulai.
                        <a href="{{ route('examination.form', $pendaftaran->id) }}" class="btn btn-warning btn-sm mt-2">
                            <i class="bi bi-plus-circle"></i> Mulai Pemeriksaan
                        </a>
                    </div>
                    @endif
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
    
    .form-select-lg {
        padding: 0.75rem 1rem;
        font-size: 15px;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
</style>

@endsection
