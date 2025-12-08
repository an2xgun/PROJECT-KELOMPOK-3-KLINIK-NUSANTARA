@extends('layout')
@section('content')
<h3>Pendaftaran Pasien</h3>
<form action="{{ route('pasien.store') }}" method="POST">
@csrf

{{-- Pencarian pasien lama (AJAX live search) --}}
<div class="mb-3">
    <label>Cari pasien (NIK / Nama / No RM)</label>
    <div class="input-group">
        <input id="searchBox" class="form-control" placeholder="Ketik NIK/Nama/No RM...">
        <button id="btnSearch" class="btn btn-outline-primary">Cari</button>
    </div>
    <div id="searchResult" class="mt-2"></div>
</div>
</form>
<hr>

<form id="formPendaftaran" action="{{ route('pendaftaran.store') }}" method="POST">
    @csrf

    {{-- pasien selection (set setelah cari atau pilih manual) --}}
    <input type="hidden" name="pasien_id" id="pasien_id">

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Tanggal Kunjungan</label>
            <input type="date" name="tanggal_kunjungan" class="form-control" required value="{{ date('Y-m-d') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Tujuan Poli</label>
            <select name="poliklinik_id" id="poliklinik" class="form-select" required>
                <option value="">-- Pilih Poli --</option>
                @foreach($polikliniks as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Jadwal Dokter</label>
            <select name="jadwal_poli_id" id="jadwal_poli" class="form-select">
                <option value="">-- Pilih Jadwal --</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Tindakan</label>
            <select name="tindakan_id" class="form-select">
                <option value="">-- Pilih Tindakan --</option>
                @foreach($tindakan as $t) <option value="{{ $t->id }}">{{ $t->nama }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Diagnosa</label>
            <select name="diagnosa_id" class="form-select">
                <option value="">-- Pilih Diagnosa --</option>
                @foreach($diagnosa as $d) <option value="{{ $d->id }}">{{ $d->nama }}</option> @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Status Kunjungan</label>
            <select name="status_kunjungan" class="form-select">
                <option value="Sakit">Sakit</option>
                <option value="Sehat">Sehat</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Jenis Pembayaran</label>
            <select name="jenis_pembayaran" class="form-select">
                <option value="Umum">Umum</option>
                <option value="BPJS">BPJS</option>
                <option value="Asuransi">Asuransi</option>
            </select>
        </div>

        <div class="col-12 mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Pendaftaran</button>
    <a href="{{ route('pendaftaran.pasien_baru') }}" class="btn btn-secondary">Daftar Pasien Baru</a>
</form>

{{-- skrip ajax --}}
<script>
document.getElementById('btnSearch').addEventListener('click', function(e){
    e.preventDefault();
    const q = document.getElementById('searchBox').value.trim();
    if(!q) return;

    // Try exact No RM lookup first when input resembles a No RM (digits or with RM prefix)
    const digits = q.replace(/\D/g, '');
    if(digits.length > 0) {
        // normalize to 4-digit format (0001)
        const normalized = digits.padStart(4, '0');
        fetch('/api/patient/' + encodeURIComponent(normalized))
            .then(res => res.json())
            .then(obj => {
                const box = document.getElementById('searchResult');
                if(obj && obj.found) {
                    // If found, select immediately
                    selectPasien(obj.pasien.id, obj.pasien.no_rm, obj.pasien.nama);
                } else {
                    // Fallback to broader search endpoint
                    fetch('/api/pasien/search?q=' + encodeURIComponent(q))
                        .then(res => res.json())
                        .then(data => renderSearchResults(data));
                }
            })
            .catch(() => {
                // On error fallback to search
                fetch('/api/pasien/search?q=' + encodeURIComponent(q))
                    .then(res => res.json())
                    .then(data => renderSearchResults(data));
            });
    } else {
        // If no digits, just run the normal search
        fetch('/api/pasien/search?q=' + encodeURIComponent(q))
            .then(res => res.json())
            .then(data => renderSearchResults(data));
    }
});

function renderSearchResults(data){
    const box = document.getElementById('searchResult');
    if(!data || data.length === 0){
        box.innerHTML = '<div class="alert alert-warning">Pasien tidak ditemukan. <a href="{{ route('pasien.create') }}">Daftar pasien baru</a></div>';
        document.getElementById('pasien_id').value = '';
    } else {
        let html = '<div class="list-group">';
        data.forEach(p => {
            html += `<button type="button" class="list-group-item list-group-item-action" onclick="selectPasien(${p.id}, ${JSON.stringify(p.no_rm)}, ${JSON.stringify(p.nama)})"> ${p.no_rm} - ${p.nama} (${p.nik || '-'})</button>`;
        });
        html += '</div>';
        box.innerHTML = html;
    }
}

function selectPasien(id,no_rm,nama){
    document.getElementById('pasien_id').value = id;
    document.getElementById('searchResult').innerHTML = `<div class="alert alert-info">Pasien dipilih: <strong>${no_rm} - ${nama}</strong></div>`;
}

// AJAX get jadwal by poli
document.getElementById('poliklinik').addEventListener('change', function(){
    const poliId = this.value;
    const el = document.getElementById('jadwal_poli');
    el.innerHTML = '<option>Loading...</option>';
    if(!poliId){ el.innerHTML = '<option value="">-- Pilih Jadwal --</option>'; return; }
    fetch('/ajax/jadwal-by-poli/' + poliId)
        .then(res => res.json())
        .then(data => {
            let h = '<option value="">-- Pilih Jadwal --</option>';
            data.forEach(j => {
                h += `<option value="${j.id}">${j.hari} - ${j.dokter.nama} (${j.jam_mulai.substring(0,5)}-${j.jam_selesai.substring(0,5)})</option>`;
            });
            el.innerHTML = h;
        });
});
</script>
@endsection
