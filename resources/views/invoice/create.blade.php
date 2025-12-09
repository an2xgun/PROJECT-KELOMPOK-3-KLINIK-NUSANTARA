@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>Buat Invoice dari Rekam #{{ $rekam->id }}</h3>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Pasien:</strong> {{ optional($rekam->pasien)->nama ?? '-' }} (No RM: {{ optional($rekam->pasien)->no_rm ?? '-' }})</p>
            <p><strong>Tanggal Periksa:</strong> {{ $rekam->tanggalperiksa ? $rekam->tanggalperiksa->format('d/m/Y') : '-' }}</p>
            <p><strong>Layanan (Poli):</strong> {{ $rekam->layanan ?? '-' }}</p>
        </div>
    </div>

    <form action="{{ route('invoice.store', $rekam->id) }}" method="POST">
        @csrf

        <div class="card mb-3">
            <div class="card-header"><strong>Item Layanan (Tindakan)</strong></div>
            <div class="card-body">
                @php $i = 0; @endphp
                @if($rekam->tindakan && $rekam->tindakan->count() > 0)
                    <div class="list-group">
                        @foreach($rekam->tindakan as $t)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <input type="hidden" name="items[{{ $i }}][type]" value="layanan">
                                    <input type="hidden" name="items[{{ $i }}][name]" value="{{ $t->nama ?? $t->nama_tindakan ?? 'Tindakan' }}">
                                    <label class="mb-0">
                                        <input type="checkbox" name="items[{{ $i }}][include]" value="1" checked>
                                        <strong>{{ $t->nama ?? $t->nama_tindakan }}</strong>
                                    </label>
                                </div>
                                <div>
                                    <input type="number" name="items[{{ $i }}][amount]" value="{{ $t->harga ?? 0 }}" class="form-control text-end" style="width:140px; display:inline-block;" required>
                                </div>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Tidak ada tindakan yang tercatat.</p>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><strong>Item Resep (Obat)</strong></div>
            <div class="card-body">
                @if(optional($rekam->prescription)->items && optional($rekam->prescription)->items->count() > 0)
                    <div class="list-group">
                        @foreach($rekam->prescription->items as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <input type="hidden" name="items[{{ $i }}][type]" value="obat">
                                    <input type="hidden" name="items[{{ $i }}][name]" value="{{ $item->obat->nama ?? 'Obat' }}">
                                    <label class="mb-0">
                                        <input type="checkbox" name="items[{{ $i }}][include]" value="1" checked>
                                        {{ $item->obat->nama ?? 'Obat' }} <small class="text-muted">({{ $item->dosis ?? '' }})</small>
                                    </label>
                                </div>
                                <div>
                                    <input type="number" name="items[{{ $i }}][amount]" value="{{ $item->subtotal ?? (($item->harga_satuan ?? 0) * ($item->jumlah ?? 1)) }}" class="form-control text-end" style="width:140px; display:inline-block;" required>
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

        <div class="mb-3">
            <label>Jenis Pembayaran</label>
            <select name="jenis_pembayaran" class="form-select" required>
                <option value="Tunai">Tunai</option>
                <option value="BPJS">BPJS</option>
                <option value="Asuransi">Asuransi</option>
            </select>
        </div>

        <div class="mb-3" id="no-bpjs-group" style="display:none;">
            <label>No. BPJS / Nomor Asuransi</label>
            <input type="text" name="no_bpjs" id="no_bpjs" class="form-control" placeholder="Masukkan nomor BPJS atau polis asuransi jika ada">
            <small class="text-muted">BPJS: 13 digit | Asuransi: minimal 6 karakter</small>
            <div id="no-bpjs-error" class="text-danger" style="display:none; font-size:12px; margin-top:4px;"></div>
        </div>

        <input type="hidden" name="layanan" value="{{ $rekam->layanan }}">

        <button type="submit" class="btn btn-primary">Buat Invoice</button>
        <a href="{{ route('rekam.show', $rekam->id) }}" class="btn btn-secondary">Batal</a>
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
