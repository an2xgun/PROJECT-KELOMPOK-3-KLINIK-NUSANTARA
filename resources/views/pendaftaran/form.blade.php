@extends('layout')
@section('content')
<h3>Pendaftaran — {{ $pasien->nama }} ({{ $pasien->no_rm }})</h3>

<form method="POST" action="{{ route('pendaftaran.pasien_baru') }}" id="formPendaftaran">
  @csrf
  <input type="hidden" name="pasien_id" value="{{ $pasien->id }}">

  <div class="row">
    <div class="col-md-4 mb-3">
      <label>Tanggal Kunjungan</label>
      <input type="date" name="tanggal_kunjungan" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>

    <div class="col-md-4 mb-3">
      <label>Poliklinik</label>
      <select id="poliklinik" name="poliklinik_id" class="form-select" required>
        <option value="">-- Pilih --</option>
        @foreach($polikliniks as $p)
          <option value="{{ $p->id }}">{{ $p->nama }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label>Jadwal</label>
      <select id="jadwal_poli" name="jadwal_poli_id" class="form-select">
        <option value="">-- Pilih Jadwal --</option>
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label>Tindakan</label>
      <select name="tindakan_id" class="form-select">
        <option value="">-- Pilih Tindakan --</option>
        @foreach($tindakan as $t) <option value="{{ $t->id }}">{{ $t->nama }} ({{ number_format($t->harga) }})</option> @endforeach
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label>Diagnosa</label>
      <select name="diagnosa_id" class="form-select">
        <option value="">-- Pilih Diagnosa --</option>
        @foreach($diagnosa as $d) <option value="{{ $d->id }}">{{ $d->nama }}</option> @endforeach
      </select>
    </div>

    <div class="col-md-4 mb-3">
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

  <button class="btn btn-primary">Simpan Pendaftaran</button>
</form>

@push('scripts')
<script>
document.getElementById('poliklinik').addEventListener('change', function(){
    const poliId = this.value;
    const el = document.getElementById('jadwal_poli');
    el.innerHTML = '<option>Loading...</option>';
    if(!poliId){ el.innerHTML = '<option value="">-- Pilih Jadwal --</option>'; return; }
    fetch('/ajax/jadwal-by-poli/' + poliId)
      .then(r=>r.json())
      .then(data=>{
         let h = '<option value="">-- Pilih Jadwal --</option>';
         data.forEach(j => {
             const dokterNama = j.dokter ? j.dokter.nama : 'Dokter';
             h += `<option value="${j.id}">${j.hari} — ${dokterNama} (${j.jam_mulai.substring(0,5)}-${j.jam_selesai.substring(0,5)})</option>`;
         });
         el.innerHTML = h;
      });
});
</script>
@endpush
@endsection
