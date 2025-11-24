@extends('layout')
@section('content')

<h3>Pasien Baru</h3>

@csrf

<div class="row">
    {{-- KOLOM KIRI --}}
    <div class="col-md-6">

        <label>Kode RM Lama</label>
        <input type="text" name="no_rm_lama" class="form-control mb-2" placeholder="DD-MM-YYYY">

        <label>Nama Pasien *</label>
        <input name="nama" required class="form-control mb-2">

        <label>No KTP *</label>
        <div class="input-group mb-2">
            <input name="nik" required class="form-control">
            <button class="btn btn-secondary" type="button"><i class="bi bi-search"></i></button>
        </div>

        <label>No IHS Pasien</label>
        <div class="input-group mb-2">
            <input name="no_ihs" class="form-control">
            <button class="btn btn-secondary" type="button"><i class="bi bi-search"></i></button>
        </div>

        <label>Agama</label>
        <select name="agama" class="form-control mb-2">
            <option>Islam</option><option>Kristen</option><option>Hindu</option>
        </select>

        <label>Pendidikan</label>
        <select name="pendidikan" class="form-control mb-2">
            <option>SMA</option>
            <option>D3</option>
            <option>S1</option>
            <option>S2</option>
        </select>

        <label>Status Dalam Keluarga</label>
        <select name="status_keluarga" class="form-control mb-2">
            <option>Anak</option>
            <option>Ayah</option>
            <option>Ibu</option>
            <option>Lainnya</option>
        </select>

        <label>Tanggal Lahir *</label>
        <div class="d-flex gap-2 mb-2">
            <input type="date" name="tanggal_lahir" class="form-control" placeholder="DD-MM-YYYY">
        </div>

        <label>Umur *</label>
        <div class="row mb-2">
            <div class="col"><input name="umur_tahun" placeholder="Tahun" class="form-control"></div>
            <div class="col"><input name="umur_bulan" placeholder="Bulan" class="form-control"></div>
            <div class="col"><input name="umur_hari" placeholder="Hari" class="form-control"></div>
        </div>

        <label>Jenis Kelamin *</label>
        <select name="jenis_kelamin" class="form-control mb-2">
            <option>Laki-laki</option>
            <option>Perempuan</option>
        </select>

        <label>Golongan Darah</label>
        <select name="gol_darah" class="form-control mb-2">
            <option>O</option><option>A</option><option>B</option><option>AB</option>
        </select>

        <label>Alamat *</label>
        <textarea name="alamat" class="form-control mb-2"></textarea>

        <label>Email</label>
        <input name="email" class="form-control mb-3">

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6">

        <label>Pekerjaan</label>
        <input name="pekerjaan" class="form-control mb-2">

        <label>Wilayah *</label>
        <select name="wilayah" class="form-control mb-2">
            <option>Dalam Wilayah</option>
            <option>Luar Wilayah</option>
        </select>

        <label>Desa *</label>
        <input name="desa" class="form-control mb-2">
        
        <label>Rujukan Dari</label>
        <select name="rujukan_dari" class="form-control mb-2">
            <option>Puskesmas</option>
            <option>Rumah Sakit</option>
            <option> Datang Sendiri</option>
             <option> Datang Rujukan Lainnya</option>
        </select>

        <label>Keterangan Rujukan</label>
        <input name="ket_rujukan" class="form-control mb-2">

        <label>Tanggal Kunjungan</label>
        <input type="date" 
        name="tanggal_kunjungan" 
       class="form-control mb-2"
       value="{{ date('Y-m-d') }}">
       

      <label>No RM</label>
        <input type="text" name="no_rm" value="{{ $no_rm }}" readonly class="form-control mb-2">


       <label>Poli Tujuan *</label>
        <select name="tujuan" class="form-control mb-2" required>
         <option value="">-- Pilih Poli --</option>
         @foreach($poli as $p)
        <option value="{{ $p->nama_poli }}">{{ $p->nama_poli }}</option>
         @endforeach
        </select>


        <label>Kunjungan</label>
        <select name="jenis_kunjungan" class="form-control mb-2">
            <option>Sakit</option>
            <option>Sehat</option>
        </select>

        <label>Jenis Pembayaran</label>
        <select name="jenis_pembayaran" class="form-control mb-3">
            <option>Umum</option>
            <option>BPJS</option>
        </select>

    </div>
</div>

<button class="btn btn-success">Simpan</button>

</form>
<script>
document.querySelector('[name="tanggal_lahir"]').addEventListener('change', function() {
    let tgl = new Date(this.value);
    let now = new Date();

    let tahun = now.getFullYear() - tgl.getFullYear();
    let bulan = now.getMonth() - tgl.getMonth();
    let hari  = now.getDate() - tgl.getDate();

    if (hari < 0) { hari += 30; bulan--; }
    if (bulan < 0) { bulan += 12; tahun--; }

    document.querySelector('[name="umur_tahun"]').value = tahun;
    document.querySelector('[name="umur_bulan"]').value = bulan;
    document.querySelector('[name="umur_hari"]').value = hari;
});
</script>

@endsection
