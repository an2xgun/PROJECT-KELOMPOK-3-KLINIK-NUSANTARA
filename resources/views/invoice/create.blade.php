@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3><i class="bi bi-receipt"></i> Buat Invoice Pembayaran</h3>

    <div class="card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Pasien:</strong></p>
                    <p style="font-size: 16px; font-weight: 600;">{{ optional($rekam->pasien)->nama ?? '-' }}</p>
                    <p class="mb-0"><small>No RM: {{ optional($rekam->pasien)->no_rm ?? '-' }}</small></p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Tanggal Periksa:</strong></p>
                    <p style="font-size: 14px;">{{ $rekam->tanggalperiksa ? \Carbon\Carbon::parse($rekam->tanggalperiksa)->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Poli/Layanan:</strong></p>
                    <p style="font-size: 14px;">{{ $rekam->layanan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('invoice.store', $rekam->id) }}" method="POST">
        @csrf

        <!-- PEMERIKSAAN -->
        <div class="card mb-3">
            <div class="card-header" style="background: #e7f3ff; border-left: 4px solid #667eea;">
                <strong><i class="bi bi-stethoscope"></i> Biaya Pemeriksaan</strong>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Biaya dasar konsultasi dan pemeriksaan medis</p>
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div style="flex: 1;">
                            <input type="hidden" name="items[0][type]" value="pemeriksaan">
                            <input type="hidden" name="items[0][name]" value="Konsultasi & Pemeriksaan Medis">
                            <label class="mb-0">
                                <input type="checkbox" name="items[0][include]" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                <strong style="margin-left: 8px;">Konsultasi & Pemeriksaan Medis</strong>
                            </label>
                        </div>
                        <div style="min-width: 140px;">
                            <input type="number" name="items[0][amount]" value="50000" class="form-control text-end" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TINDAKAN -->
        <div class="card mb-3">
            <div class="card-header" style="background: #fff3cd; border-left: 4px solid #ffc107;">
                <strong><i class="bi bi-tools"></i> Biaya Tindakan Medis</strong>
            </div>
            <div class="card-body">
                @php $i = 1; @endphp
                @if($rekam->tindakan && $rekam->tindakan->count() > 0)
                    <p class="text-muted mb-3">Tindakan atau prosedur medis yang dilakukan</p>
                    <div class="list-group">
                        @foreach($rekam->tindakan as $t)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div style="flex: 1;">
                                    <input type="hidden" name="items[{{ $i }}][type]" value="tindakan">
                                    <input type="hidden" name="items[{{ $i }}][name]" value="{{ $t->nama ?? $t->nama_tindakan ?? 'Tindakan' }}">
                                    <label class="mb-0">
                                        <input type="checkbox" name="items[{{ $i }}][include]" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                        <strong style="margin-left: 8px;">{{ $t->nama ?? $t->nama_tindakan }}</strong>
                                    </label>
                                </div>
                                <div style="min-width: 140px;">
                                    <input type="number" name="items[{{ $i }}][amount]" value="{{ $t->harga ?? 0 }}" class="form-control text-end" required>
                                </div>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Tidak ada tindakan medis yang tercatat.</p>
                @endif
            </div>
        </div>

        <!-- OBAT & RESEP -->
        <div class="card mb-3">
            <div class="card-header" style="background: #f0f9e8; border-left: 4px solid #28a745;">
                <strong><i class="bi bi-capsule"></i> Biaya Obat & Resep</strong>
            </div>
            <div class="card-body">
                @if(optional($rekam->prescription)->items && optional($rekam->prescription)->items->count() > 0)
                    <p class="text-muted mb-3">Obat dan resep yang diberikan kepada pasien</p>
                    <div class="list-group">
                        @foreach($rekam->prescription->items as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div style="flex: 1;">
                                    <input type="hidden" name="items[{{ $i }}][type]" value="obat">
                                    <input type="hidden" name="items[{{ $i }}][name]" value="{{ $item->obat->nama ?? 'Obat' }}">
                                    <label class="mb-0">
                                        <input type="checkbox" name="items[{{ $i }}][include]" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                                        <strong style="margin-left: 8px;">{{ $item->obat->nama ?? 'Obat' }}</strong>
                                        <small class="text-muted" style="display: block; margin-top: 4px; margin-left: 26px;">
                                            Qty: {{ $item->jumlah ?? 0 }} | Dosis: {{ $item->dosis ?? '-' }} | @: Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}
                                        </small>
                                    </label>
                                </div>
                                <div style="min-width: 140px;">
                                    <input type="number" name="items[{{ $i }}][amount]" value="{{ $item->subtotal ?? (($item->harga_satuan ?? 0) * ($item->jumlah ?? 1)) }}" class="form-control text-end" required>
                                </div>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Tidak ada resep/obat pada rekam ini.</p>
                @endif
            </div>
        </div>

        <!-- PEMBAYARAN -->
        <div class="card mb-3">
            <div class="card-header">
                <strong><i class="bi bi-credit-card"></i> Informasi Pembayaran</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Jenis Pembayaran <span class="text-danger">*</span></label>
                    <select name="jenis_pembayaran" class="form-select" required>
                        <option value="">-- Pilih Jenis Pembayaran --</option>
                        <option value="Tunai">Tunai</option>
                        <option value="BPJS">BPJS</option>
                        <option value="Asuransi">Asuransi</option>
                        <option value="Transfer">Transfer Bank</option>
                    </select>
                </div>

                <div class="mb-3" id="no-bpjs-group" style="display:none;">
                    <label class="form-label">No. BPJS / Nomor Asuransi</label>
                    <input type="text" name="no_bpjs" id="no_bpjs" class="form-control" placeholder="Masukkan nomor BPJS atau polis asuransi">
                    <small class="text-muted">BPJS: 13 digit | Asuransi: minimal 6 karakter</small>
                    <div id="no-bpjs-error" class="text-danger" style="display:none; font-size:12px; margin-top:4px;"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan (Opsional)</label>
                    <input type="text" name="keterangan_pembayaran" class="form-control" placeholder="Catatan pembayaran atau bukti transfer">
                </div>
            </div>
        </div>

        <input type="hidden" name="layanan" value="{{ $rekam->layanan }}">

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle"></i> Buat Invoice
            </button>
            <a href="{{ route('rekam.show', $rekam->id) }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>
    </form>

<script>
    (function(){
        const jenis = document.querySelector('select[name="jenis_pembayaran"]');
        const group = document.getElementById('no-bpjs-group');
        const input = document.getElementById('no_bpjs');
        const errDiv = document.getElementById('no-bpjs-error');
        const form = document.querySelector('form');
        
        function validateNumber() {
            const val = input.value.trim();
            const method = jenis.value;
            errDiv.style.display = 'none';
            errDiv.textContent = '';
            
            if (method === 'BPJS') {
                if (!/^\d{13}$/.test(val)) {
                    errDiv.textContent = 'Nomor BPJS harus tepat 13 digit angka';
                    errDiv.style.display = 'block';
                    return false;
                }
            } else if (method === 'Asuransi') {
                if (!/^[a-zA-Z0-9]{6,}$/.test(val)) {
                    errDiv.textContent = 'Nomor Asuransi minimal 6 karakter (huruf/angka)';
                    errDiv.style.display = 'block';
                    return false;
                }
            }
            return true;
        }
        
        function toggle() {
            if (!jenis) return;
            if (jenis.value === 'BPJS' || jenis.value === 'Asuransi') {
                group.style.display = 'block';
                input.required = true;
            } else {
                group.style.display = 'none';
                input.required = false;
                errDiv.style.display = 'none';
            }
        }
        
        jenis && jenis.addEventListener('change', toggle);
        input && input.addEventListener('blur', validateNumber);
        input && input.addEventListener('keyup', validateNumber);
        
        form && form.addEventListener('submit', (e) => {
            if ((jenis.value === 'BPJS' || jenis.value === 'Asuransi') && !validateNumber()) {
                e.preventDefault();
            }
        });
        
        toggle();
    })();
</script>

</div>

@endsection
