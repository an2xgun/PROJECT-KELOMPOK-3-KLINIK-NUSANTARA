@extends('layout')
@section('content')

<h3>Edit Pasien</h3>

<form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
@csrf
@method('PUT')

<div class="row">

    {{-- KOLOM KIRI --}}
    <div class="col-md-6">

        <label>No RM</label>
        <input type="text" name="no_rm" value="{{ $pasien->no_rm }}" readonly class="form-control mb-2">

        <label>Nama Pasien *</label>
        <input name="nama" value="{{ $pasien->nama }}" class="form-control mb-2" required>

        <label>No KTP *</label>
        <input name="nik" value="{{ $pasien->nik }}" class="form-control mb-2" required>

        <label>Agama</label>
        <select name="agama" class="form-control mb-2">
            <option {{ $pasien->agama=='Islam'?'selected':'' }}>Islam</option>
            <option {{ $pasien->agama=='Kristen'?'selected':'' }}>Kristen</option>
            <option {{ $pasien->agama=='Hindu'?'selected':'' }}>Hindu</option>
        </select>

        <label>Pendidikan</label>
        <select name="pendidikan" class="form-control mb-2">
            <option {{ $pasien->pendidikan=='SMA'?'selected':'' }}>SMA</option>
            <option {{ $pasien->pendidikan=='D3'?'selected':'' }}>D3</option>
            <option {{ $pasien->pendidikan=='S1'?'selected':'' }}>S1</option>
            <option {{ $pasien->pendidikan=='S2'?'selected':'' }}>S2</option>
        </select>

        <label>Status Keluarga</label>
        <select name="status_keluarga" class="form-control mb-2">
            <option {{ $pasien->status_keluarga=='Anak'?'selected':'' }}>Anak</option>
            <option {{ $pasien->status_keluarga=='Ayah'?'selected':'' }}>Ayah</option>
            <option {{ $pasien->status_keluarga=='Ibu'?'selected':'' }}>Ibu</option>
            <option {{ $pasien->status_keluarga=='Lainnya'?'selected':'' }}>Lainnya</option>
        </select>

        <label>Tanggal Lahir *</label>
        <input type="date" name="tanggal_lahir" value="{{ $pasien->tanggal_lahir }}" class="form-control mb-2" required>

        <label>Umur *</label>
        <div class="row mb-2">
            <div class="col">
                <input name="umur_tahun" value="{{ $pasien->umur_tahun }}" class="form-control" readonly>
            </div>
            <div class="col">
                <input name="umur_bulan" value="{{ $pasien->umur_bulan }}" class="form-control" readonly>
            </div>
            <div class="col">
                <input name="umur_hari" value="{{ $pasien->umur_hari }}" class="form-control" readonly>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6">

        <label>Jenis Kelamin *</label>
        <select name="jenis_kelamin" class="form-control mb-2">
            <option {{ $pasien->jenis_kelamin=='Laki-laki'?'selected':'' }}>Laki-laki</option>
            <option {{ $pasien->jenis_kelamin=='Perempuan'?'selected':'' }}>Perempuan</option>
        </select>

        <label>Golongan Darah</label>
        <select name="gol_darah" class="form-control mb-2">
            <option {{ $pasien->gol_darah=='O'?'selected':'' }}>O</option>
            <option {{ $pasien->gol_darah=='A'?'selected':'' }}>A</option>
            <option {{ $pasien->gol_darah=='B'?'selected':'' }}>B</option>
            <option {{ $pasien->gol_darah=='AB'?'selected':'' }}>AB</option>
        </select>

        <label>Alamat *</label>
        <textarea name="alamat" class="form-control mb-2">{{ $pasien->alamat }}</textarea>

        <label>Email</label>
        <input name="email" value="{{ $pasien->email }}" class="form-control mb-2">

        <label>Pekerjaan</label>
        <input name="pekerjaan" value="{{ $pasien->pekerjaan }}" class="form-control mb-2">

        <label>Wilayah *</label>
        <select name="wilayah" class="form-control mb-2">
            <option {{ $pasien->wilayah=='Dalam Wilayah'?'selected':'' }}>Dalam Wilayah</option>
            <option {{ $pasien->wilayah=='Luar Wilayah'?'selected':'' }}>Luar Wilayah</option>
        </select>

        <label>Desa *</label>
        <input name="desa" value="{{ $pasien->desa }}" class="form-control mb-2">

    </div>
</div>

<button class="btn btn-primary">Update</button>

</form>

{{-- SCRIPT HITUNG UMUR --}}
<script>
// Hitung ulang umur setiap kali tanggal lahir berubah
document.querySelector('[name="tanggal_lahir"]').addEventListener('change', hitungUmur);

// Hitung umur saat halaman dibuka
window.addEventListener('load', hitungUmur);

function hitungUmur() {
    let val = document.querySelector('[name="tanggal_lahir"]').value;
    if (!val) return;

    let tgl = new Date(val);
    let now = new Date();

    let tahun = now.getFullYear() - tgl.getFullYear();
    let bulan = now.getMonth() - tgl.getMonth();
    let hari  = now.getDate() - tgl.getDate();

    if (hari < 0) { hari += 30; bulan--; }
    if (bulan < 0) { bulan += 12; tahun--; }

    document.querySelector('[name="umur_tahun"]').value = tahun;
    document.querySelector('[name="umur_bulan"]').value = bulan;
    document.querySelector('[name="umur_hari"]').value = hari;
}
</script>

@endsection
