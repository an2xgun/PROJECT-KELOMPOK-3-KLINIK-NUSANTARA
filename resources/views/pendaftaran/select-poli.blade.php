@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h2 class="mb-4">
                <a href="javascript:history.back()" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                Pendaftaran Poliklinik
            </h2>

            <div class="card mb-3 bg-light">
                <div class="card-body">
                    <h5>Pasien: <strong>{{ $pasien->nama }}</strong></h5>
                    <p class="text-muted mb-0">No RM: <strong>{{ $pasien->no_rm }}</strong> | NIK: <strong>{{ $pasien->nik }}</strong></p>
                </div>
            </div>

            <form action="{{ route('pendaftaran.store-poli', $pasien->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <!-- Pilih Poli & Jadwal Dokter -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong><i class="bi bi-hospital"></i> Pilih Poliklinik & Jadwal</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                            <label class="form-label">Poliklinik *</label>
                                            <select id="poliklinik" name="poliklinik_id" class="form-select @error('poliklinik_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Poliklinik --</option>
                                    @foreach($polikliniks as $poli)
                                        <option value="{{ $poli->id }}" {{ old('poliklinik_id') == $poli->id ? 'selected' : '' }}>
                                            {{ $poli->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('poliklinik_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jadwal Dokter *</label>
                                <select id="jadwal" name="jadwal_poli_id" class="form-select @error('jadwal_poli_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jadwal --</option>
                                </select>
                                @error('jadwal_poli_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Anamnesa -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong><i class="bi bi-chat-dots"></i> Anamnesa (Riwayat Keluhan)</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Keluhan Utama *</label>
                            <textarea name="keluhan" class="form-control @error('keluhan') is-invalid @enderror" 
                                      rows="3" placeholder="Jelaskan keluhan pasien..." required>{{ old('keluhan') }}</textarea>
                            @error('keluhan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pembayaran *</label>
                                <select name="jenis_pembayaran" class="form-select @error('jenis_pembayaran') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Umum" {{ old('jenis_pembayaran') === 'Umum' ? 'selected' : '' }}>Umum</option>
                                    <option value="BPJS" {{ old('jenis_pembayaran') === 'BPJS' ? 'selected' : '' }}>BPJS</option>
                                    <option value="Asuransi" {{ old('jenis_pembayaran') === 'Asuransi' ? 'selected' : '' }}>Asuransi</option>
                                </select>
                                @error('jenis_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Kunjungan *</label>
                                <input type="date" name="tanggal_kunjungan" class="form-control @error('tanggal_kunjungan') is-invalid @enderror" 
                                       value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" required>
                                @error('tanggal_kunjungan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Lanjut ke Pemeriksaan Dokter
                        </button>
                        <a href="javascript:history.back()" class="btn btn-secondary btn-lg">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Load jadwal ketika poli dipilih
function loadJadwal() {
    const poliId = document.getElementById('poliklinik').value;
    const jadwalSelect = document.getElementById('jadwal');
    
    console.log('[JADWAL DEBUG] Poliklinik dipilih:', poliId);
    console.log('[JADWAL DEBUG] serverJadwals:', window.serverJadwals);
    
    if(!poliId) {
        jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
        return;
    }

    // Gunakan server-side fallback langsung (karena data sudah ada)
    try {
        const map = window.serverJadwals || {};
        console.log('[JADWAL DEBUG] Fallback map keys:', Object.keys(map));
        const list = map[poliId] || [];
        console.log('[JADWAL DEBUG] Fallback list for poli', poliId, ':', list);
        
        let html = '<option value="">-- Pilih Jadwal --</option>';
            if (list && list.length > 0) {
            list.forEach(j => {
                const dokterNama = j.dokter ? (j.dokter.nama || 'Dokter') : 'Dokter';
                html += `<option value="${j.id}">${j.hari} (${j.jam_mulai} - ${j.jam_selesai}) - Dr. ${dokterNama}</option>`;
            });
            jadwalSelect.innerHTML = html;
            jadwalSelect.disabled = false;
            // Auto-select first option if only one jadwal available
            if (list.length === 1) {
                jadwalSelect.selectedIndex = 1;
            }
            console.log('[JADWAL DEBUG] Jadwal dropdown updated with', (list ? list.length : 0), 'items (from serverJadwals)');
            return;
        }

        // Jika fallback kosong, coba ambil lewat AJAX (API) — pertama coba API ter-proses, lalu raw AJAX endpoint
        const tryFetchJadwal = async () => {
            try {
                // try processed API
                let res = await fetch(`/api/jadwal-poli/${poliId}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('API fetch failed');
                let data = await res.json();
                if (Array.isArray(data) && data.length > 0) {
                    let h = '<option value="">-- Pilih Jadwal --</option>';
                    data.forEach(j => {
                        const dokterNama = j.dokter ? (j.dokter.nama || 'Dokter') : 'Dokter';
                        h += `<option value="${j.id}">${j.hari} (${j.jam_mulai} - ${j.jam_selesai}) - Dr. ${dokterNama}</option>`;
                    });
                    jadwalSelect.innerHTML = h;
                    jadwalSelect.disabled = false;
                    if (data.length === 1) jadwalSelect.selectedIndex = 1;
                    console.log('[JADWAL DEBUG] Jadwal dropdown updated with', data.length, 'items (from /api/jadwal-poli)');
                    return;
                }
                // if empty, try raw ajax endpoint
                res = await fetch(`/ajax/jadwal-by-poli/${poliId}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('AJAX fetch failed');
                data = await res.json();
                if (Array.isArray(data) && data.length > 0) {
                    let h2 = '<option value="">-- Pilih Jadwal --</option>';
                    data.forEach(j => {
                        const dokterNama = j.dokter ? (j.dokter.nama || 'Dokter') : 'Dokter';
                        h2 += `<option value="${j.id}">${j.hari} (${j.jam_mulai} - ${j.jam_selesai}) - Dr. ${dokterNama}</option>`;
                    });
                    jadwalSelect.innerHTML = h2;
                    jadwalSelect.disabled = false;
                    if (data.length === 1) jadwalSelect.selectedIndex = 1;
                    console.log('[JADWAL DEBUG] Jadwal dropdown updated with', data.length, 'items (from /ajax/jadwal-by-poli)');
                    return;
                }
                // nothing found — try server-side doctors fallback (show doctors even when no jadwal exists)
                const doctorsMap = window.serverDokters || {};
                const doctors = doctorsMap[poliId] || [];
                if (doctors.length > 0) {
                    let hd = '<option value="">-- Pilih Jadwal --</option>';
                    doctors.forEach(d => {
                        hd += `<option value="dokter-${d.id}">No jadwal - Dr. ${d.nama}</option>`;
                    });
                    jadwalSelect.innerHTML = hd;
                    jadwalSelect.disabled = false;
                    console.log('[JADWAL DEBUG] Jadwal dropdown updated with', doctors.length, 'doctors (fallback)');
                    return;
                }

                jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
                jadwalSelect.disabled = true;
                console.log('[JADWAL DEBUG] No jadwal found via AJAX for poli', poliId);
            } catch (err) {
                console.error('[JADWAL DEBUG] AJAX error when fetching jadwal:', err);
                jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
                jadwalSelect.disabled = true;
            }
        };

        tryFetchJadwal();
    } catch (e) {
        console.error('[JADWAL DEBUG] Error rendering jadwal:', e);
        jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
    }
}

document.getElementById('poliklinik').addEventListener('change', loadJadwal);

// Sisipkan data jadwal server-side ke JS (fallback)
window.serverJadwals = {!! isset($jadwals) ? json_encode($jadwals) : '{}' !!};
window.serverDokters = {!! isset($doctors) ? json_encode($doctors) : '{}' !!};
console.log('[JADWAL DEBUG] Page loaded. serverJadwals:', window.serverJadwals, 'serverDokters:', window.serverDokters);

// Populate jadwal dropdown on page load if a poli is already selected
document.addEventListener('DOMContentLoaded', function() {
    try { loadJadwal(); } catch(e) { console.error('[JADWAL DEBUG] loadJadwal error on DOMContentLoaded', e); }
});

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
