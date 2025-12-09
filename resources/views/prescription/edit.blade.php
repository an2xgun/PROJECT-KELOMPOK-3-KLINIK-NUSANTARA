@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3><i class="bi bi-pencil-square"></i> Edit Resep Obat</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('prescription.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <strong>Ada kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                            <p><strong>{{ data_get($prescription, 'rekam.pasien.no_rm') ?? data_get($prescription, 'rekam.pasien.kodepasien') ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Nama Pasien</small>
                            <p><strong>{{ optional($prescription->rekam)->pasien->nama ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Poliklinik</small>
                            <p><strong>{{ optional(optional($prescription->rekam)->pendaftaran)->poliklinik->name ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Tanggal Pemeriksaan</small>
                            <p><strong>{{ optional($prescription->rekam)->created_at ? \Carbon\Carbon::parse(optional($prescription->rekam)->created_at)->format('d/m/Y H:i') : '-' }}</strong></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <small class="text-muted">Diagnosa</small>
                            <p>{{ optional($prescription->rekam)->diagnosa ?? optional($prescription->rekam)->diagnosa_primer ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Form -->
    <form action="{{ route('prescription.update', $prescription->id) }}" method="POST" id="prescriptionForm">
        @csrf
        @method('PUT')
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

        <!-- Form Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                </button>
                <a href="{{ route('prescription.index') }}" class="btn btn-secondary btn-lg">
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
    const existingItems = @json($prescription->items);

    function createObatItem(index, item = null) {
        const selectedObatId = item?.obat_id || '';
        const selectedJumlah = item?.jumlah || 1;
        const selectedDosis = item?.dosis || '';
        const hargaSatuan = item?.harga_satuan || 0;

        const itemHtml = `
            <div class="obat-item card mb-3" data-index="${index}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                            <select name="obat_items[${index}][obat_id]" class="form-select obat-select" required onchange="updateObatInfo(${index})">
                                <option value="">-- Pilih Obat --</option>
                                ${obatData.map(o => `<option value="${o.id}" data-harga="${o.harga || 0}" ${selectedObatId == o.id ? 'selected' : ''}>${o.nama} (Rp ${new Intl.NumberFormat('id-ID').format(o.harga || 0)})</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="obat_items[${index}][jumlah]" class="form-control jumlah-input" value="${selectedJumlah}" min="1" required onchange="updateSubtotal(${index})">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-display" value="Rp ${new Intl.NumberFormat('id-ID').format(hargaSatuan * selectedJumlah)}" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Dosis/Cara Pakai <span class="text-danger">*</span></label>
                            <input type="text" name="obat_items[${index}][dosis]" class="form-control" placeholder="Contoh: 3x sehari 1 tablet / 2x sehari sesudah makan" value="${selectedDosis}" required>
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

    // Load existing items and add first empty item on page load
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('obatContainer');
        
        // Load existing items
        if (existingItems.length > 0) {
            existingItems.forEach((item, idx) => {
                const itemHtml = createObatItem(idx, item);
                container.insertAdjacentHTML('beforeend', itemHtml);
                obatCount = idx + 1;
            });
        } else {
            // Add first empty item if no items exist
            addObatItem();
        }

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
