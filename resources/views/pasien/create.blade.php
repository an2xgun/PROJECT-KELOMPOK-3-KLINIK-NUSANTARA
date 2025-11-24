@extends('layout') 
@section('content')

<h3>Pasien Baru</h3>

<form action="{{ route('pasien.store') }}" method="POST">
@csrf

<div class="row">

    {{-- KOLOM KIRI --}}
    <div class="col-md-6">

        <label>No RM</label>
        <input type="text" name="no_rm" value="{{ $no_rm }}" readonly class="form-control mb-2">

        <label>Nama Pasien *</label>
        <input name="nama" required class="form-control mb-2">

        <label>No KTP *</label>
        <div class="input-group mb-2">
            <input name="nik" required class="form-control">
            <button class="btn btn-secondary" type="button">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <label>Agama</label>
        <select name="agama" class="form-control mb-2">
            <option>Islam</option>
            <option>Kristen</option>
            <option>Hindu</option>
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
        <input type="date" name="tanggal_lahir" class="form-control mb-2">

        <label>Umur *</label>
        <div class="row mb-2">
            <div class="col">
                <input name="umur_tahun" placeholder="Tahun" class="form-control" readonly>
            </div>
            <div class="col">
                <input name="umur_bulan" placeholder="Bulan" class="form-control" readonly>
            </div>
            <div class="col">
                <input name="umur_hari" placeholder="Hari" class="form-control" readonly>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6">

        <label>Jenis Kelamin *</label>
        <select name="jenis_kelamin" class="form-control mb-2">
            <option>Laki-laki</option>
            <option>Perempuan</option>
        </select>

        <label>Golongan Darah</label>
        <select name="gol_darah" class="form-control mb-2">
            <option>O</option>
            <option>A</option>
            <option>B</option>
            <option>AB</option>
        </select>

        <label>Alamat *</label>
        <textarea name="alamat" class="form-control mb-2"></textarea>

        <label>Email</label>
        <input name="email" class="form-control mb-3">

        <label>Pekerjaan</label>
        <input name="pekerjaan" class="form-control mb-2">

        <label>Wilayah *</label>
        <select name="wilayah" class="form-control mb-2">
            <option>Dalam Wilayah</option>
            <option>Luar Wilayah</option>
        </select>

        <label>Desa *</label>
        <input name="desa" class="form-control mb-2">

    </div>
</div>

<button class="btn btn-success mt-3">Simpan</button>

</form>

{{-- SCRIPT HITUNG UMUR --}}
<script>
function hitungUmur() {
    let value = document.querySelector('[name="tanggal_lahir"]').value;
    if (!value) return;

    let lahir = new Date(value);
    let now   = new Date();

    let tahun = now.getFullYear() - lahir.getFullYear();
    let bulan = now.getMonth() - lahir.getMonth();
    let hari  = now.getDate() - lahir.getDate();

    if (hari < 0) {
        hari += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
        bulan--;
    }

    if (bulan < 0) {
        bulan += 12;
        tahun--;
    }

    document.querySelector('[name="umur_tahun"]').value = tahun;
    document.querySelector('[name="umur_bulan"]').value = bulan;
    document.querySelector('[name="umur_hari"]').value  = hari;
}

// Hitung saat user mengubah tanggal lahir
document.querySelector('[name="tanggal_lahir"]').addEventListener('change', hitungUmur);

// Hitung saat halaman dibuka (untuk edit)
window.addEventListener('load', hitungUmur);
</script>


@endsection
