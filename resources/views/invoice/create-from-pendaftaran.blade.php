@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h2 class="mb-4">
                <a href="{{ route('pendaftaran.list') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                Buat Invoice - Pendaftaran
            </h2>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>Info Pasien</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>No RM:</strong> {{ $pendaftaran->pasien->no_rm ?? '-' }}</p>
                            <p><strong>Nama:</strong> {{ $pendaftaran->pasien->nama ?? '-' }}</p>
                            <p><strong>NIK:</strong> {{ $pendaftaran->pasien->nik ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Poliklinik:</strong> {{ $pendaftaran->poliklinik->name ?? '-' }}</p>
                            <p><strong>No Antrian:</strong> {{ $pendaftaran->nomor_antrian ?? '-' }}</p>
                            <p><strong>Status:</strong> <span class="badge bg-info">{{ $pendaftaran->status_layanan }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('invoice.store-pendaftaran', $pendaftaran->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong>Detail Invoice</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Layanan *</label>
                                <input type="text" name="layanan" class="form-control @error('layanan') is-invalid @enderror" 
                                       value="{{ old('layanan', 'Pendaftaran & Konsultasi') }}" required>
                                @error('layanan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Biaya Layanan (Rp) *</label>
                                <input type="number" name="biaya_layanan" class="form-control @error('biaya_layanan') is-invalid @enderror" 
                                       value="{{ old('biaya_layanan', 50000) }}" min="0" step="1000" required>
                                @error('biaya_layanan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pembayaran</label>
                                <select name="jenis_pembayaran" class="form-select" id="jenisPembayaran" onchange="toggleNoBpjs()">
                                    <option value="">-- Pilih --</option>
                                    <option value="Tunai" {{ old('jenis_pembayaran') === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="BPJS" {{ old('jenis_pembayaran') === 'BPJS' ? 'selected' : '' }}>BPJS</option>
                                    <option value="Asuransi" {{ old('jenis_pembayaran') === 'Asuransi' ? 'selected' : '' }}>Asuransi</option>
                                    <option value="Transfer" {{ old('jenis_pembayaran') === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3" id="noBpjsWrapper" style="display: none;">
                                <label class="form-label">No BPJS / Asuransi</label>
                                <input type="text" name="no_bpjs" class="form-control @error('no_bpjs') is-invalid @enderror" 
                                       value="{{ old('no_bpjs') }}" placeholder="Misal: 0012345678901 (BPJS)">
                                <small class="text-muted">BPJS: 13 digit angka | Asuransi: min 6 karakter</small>
                                <div id="noBpjsError" class="text-danger" style="display: none; margin-top: 5px;"></div>
                                @error('no_bpjs') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keterangan Pembayaran</label>
                                <textarea name="keterangan_pembayaran" class="form-control" rows="2" placeholder="Opsional...">{{ old('keterangan_pembayaran') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Buat Invoice
                    </button>
                    <a href="{{ route('pendaftaran.list') }}" class="btn btn-secondary btn-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleNoBpjs() {
    const method = document.getElementById('jenisPembayaran').value;
    const wrapper = document.getElementById('noBpjsWrapper');
    const input = document.querySelector('input[name="no_bpjs"]');
    
    if (method === 'BPJS' || method === 'Asuransi') {
        wrapper.style.display = 'block';
        input.required = true;
    } else {
        wrapper.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}

function validatePaymentNumber() {
    const method = document.getElementById('jenisPembayaran').value;
    const input = document.querySelector('input[name="no_bpjs"]');
    const errorDiv = document.getElementById('noBpjsError');
    
    if (!method || (method !== 'BPJS' && method !== 'Asuransi')) {
        errorDiv.style.display = 'none';
        return true;
    }
    
    const value = input.value.trim();
    if (!value) {
        errorDiv.style.display = 'none';
        return true;
    }
    
    let isValid = false;
    let errorMsg = '';
    
    if (method === 'BPJS') {
        isValid = /^\d{13}$/.test(value);
        errorMsg = 'Nomor BPJS harus 13 digit angka';
    } else if (method === 'Asuransi') {
        isValid = /^[a-zA-Z0-9]{6,}$/.test(value);
        errorMsg = 'Nomor Asuransi minimal 6 karakter (huruf/angka)';
    }
    
    if (!isValid) {
        errorDiv.textContent = errorMsg;
        errorDiv.style.display = 'block';
        return false;
    } else {
        errorDiv.style.display = 'none';
        return true;
    }
}

// Validate on blur and keyup
document.querySelector('input[name="no_bpjs"]').addEventListener('blur', validatePaymentNumber);
document.querySelector('input[name="no_bpjs"]').addEventListener('keyup', validatePaymentNumber);

// Form submission
document.querySelector('form').addEventListener('submit', function(e) {
    if (!validatePaymentNumber()) {
        e.preventDefault();
        return false;
    }
});

// Initial state
toggleNoBpjs();
</script>

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

@endsection
