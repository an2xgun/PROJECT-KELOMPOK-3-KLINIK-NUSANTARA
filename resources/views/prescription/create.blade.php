@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3><i class="bi bi-file-medical"></i> Buat Resep Obat</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('rekam.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- No RM lookup (reusable) --}}
    @include('partials.no_rm_lookup')
    <div id="noRmPrescriptionActions" class="mb-3"></div>

    <!-- Patient Information Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Pasien & Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted">No RM</small>
                            <p><strong>{{ data_get($rekam, 'pasien.no_rm') ?? data_get($rekam, 'pasien.kodepasien') ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Nama Pasien</small>
                            <p><strong>{{ $rekam->pasien->nama ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Poliklinik</small>
                            <p><strong>{{ optional($rekam->pendaftaran)->poliklinik->name ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Tanggal Pemeriksaan</small>
                            <p><strong>{{ $rekam->created_at->format('d/m/Y H:i') }}</strong></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <small class="text-muted">Diagnosa</small>
                            <p>{{ $rekam->diagnosa ?? $rekam->diagnosa_primer ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <small class="text-muted">Tindakan Medis</small>
                            <div class="mt-2">
                                @if($rekam->tindakan && $rekam->tindakan->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($rekam->tindakan as $tindakan)
                                            <span class="badge bg-info" style="font-size: 13px; padding: 8px 12px;">
                                                {{ $tindakan->nama }}
                                                <small style="display: block; font-size: 11px; margin-top: 2px;">
                                                    Rp {{ number_format($tindakan->harga ?? 0, 0, ',', '.') }}
                                                </small>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Form -->
    <form action="{{ route('prescription.store', $rekam->id) }}" method="POST" id="prescriptionForm">
        @csrf
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-pill"></i> Obat-obatan</h5>
                    </div>
                    <div class="card-body">
                        <div id="obatContainer">
                            <!-- Obat items will be added here -->
                        </div>
                        
                        <button type="button" class="btn btn-outline-success btn-sm" id="addObatBtn">
                            <i class="bi bi-plus-circle"></i> Tambah Obat
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-sticky"></i> Catatan Resep</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="catatan_resep" class="form-control" rows="4" placeholder="Catatan tambahan untuk resep (opsional)"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Simpan Resep
                </button>
                <a href="{{ route('rekam.show', $rekam->id) }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript for dynamic obat items -->
<script>
    let obatCount = 0;
    const obatData = @json($obat);

    function createObatItem(index) {
        const itemHtml = `
            <div class="obat-item card mb-3" data-index="${index}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                            <select name="obat_items[${index}][obat_id]" class="form-select obat-select" required onchange="updateObatInfo(${index})">
                                <option value="">-- Pilih Obat --</option>
                                ${obatData.map(o => `<option value="${o.id}" data-harga="${o.harga || 0}">${o.nama} (Rp ${new Intl.NumberFormat('id-ID').format(o.harga || 0)})</option>`).join('')}
                            </select>
                            @error('obat_items.*.obat_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="obat_items[${index}][jumlah]" class="form-control jumlah-input" value="1" min="1" required onchange="updateSubtotal(${index})">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-display" value="Rp 0" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Dosis/Cara Pakai <span class="text-danger">*</span></label>
                            <input type="text" name="obat_items[${index}][dosis]" class="form-control" placeholder="Contoh: 3x sehari 1 tablet / 2x sehari sesudah makan" required>
                            @error('obat_items.*.dosis') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeObatItem(${index})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        return itemHtml;
    }

    function addObatItem() {
        const container = document.getElementById('obatContainer');
        const itemHtml = createObatItem(obatCount);
        container.insertAdjacentHTML('beforeend', itemHtml);
        obatCount++;
    }

    function removeObatItem(index) {
        document.querySelector(`[data-index="${index}"]`).remove();
    }

    function updateObatInfo(index) {
        updateSubtotal(index);
    }

    function updateSubtotal(index) {
        const item = document.querySelector(`[data-index="${index}"]`);
        const select = item.querySelector('.obat-select');
        const jumlahInput = item.querySelector('.jumlah-input');
        const subtotalDisplay = item.querySelector('.subtotal-display');

        const selectedOption = select.selectedOptions[0];
        const harga = parseInt(selectedOption?.dataset.harga || 0);
        const jumlah = parseInt(jumlahInput.value) || 0;
        const subtotal = harga * jumlah;

        subtotalDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
    }

    // Add first obat item on page load
    document.addEventListener('DOMContentLoaded', function() {
        addObatItem();

        // Listen for no-rm lookup events: if a rekam exists, offer link to open that rekam's prescription page
        window.addEventListener('no-rm-found', function(ev){
            const data = ev.detail || {};
            const actions = document.getElementById('noRmPrescriptionActions');
            actions.innerHTML = '';
            if(data.found && data.latest_rekam_id){
                const link = document.createElement('a');
                link.href = `{{ url('/prescription') }}/${data.latest_rekam_id}/create`;
                link.className = 'btn btn-sm btn-outline-primary me-2';
                link.textContent = 'Buka Rekam Terbaru untuk Buat Resep';
                actions.appendChild(link);
            } else if(data.found){
                const p = document.createElement('div');
                p.className = 'alert alert-info';
                p.innerHTML = 'Pasien ditemukan namun tidak ada rekam pemeriksaan. Silakan lakukan pemeriksaan terlebih dahulu.';
                actions.appendChild(p);
            }
        });

        // Form validation
        document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
            const obatItems = document.querySelectorAll('.obat-item');
            if (obatItems.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 obat dalam resep!');
                return false;
            }

            // Validate at least one obat is selected
            const selectedObat = Array.from(obatItems).some(item => 
                item.querySelector('.obat-select').value !== ''
            );

            if (!selectedObat) {
                e.preventDefault();
                alert('Pilih minimal 1 obat!');
                return false;
            }
        });
    });
</script>

@endsection
