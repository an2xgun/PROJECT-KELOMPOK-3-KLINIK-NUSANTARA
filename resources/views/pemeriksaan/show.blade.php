@extends('layout')
@section('content')

<h3>Pemeriksaan Pasien</h3>

<div class="row">
    {{-- =======================
        KOLOM KIRI – DATA PASIEN
    ========================== --}}
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <b>Data Pasien</b>
            </div>
            <div class="card-body">

                <table class="table table-sm">
                    <tr>
                        <th>No RM</th><td>{{ $pasien->no_rm }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th><td>{{ $pasien->nama }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th><td>{{ $pasien->nik }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th><td>{{ $pasien->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <th>Umur</th><td>{{ $pasien->umur_tahun }} th</td>
                    </tr>
                    <tr>
                        <th>Alamat</th><td>{{ $pasien->alamat }}</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    {{-- =======================
        KOLOM KANAN – FORM PEMERIKSAAN
    ========================== --}}
    <div class="col-md-6">
        <form action="{{ route('pemeriksaan.store') }}" method="POST">
            @csrf

            <input type="hidden" name="pasien_id" value="{{ $pasien->id }}">

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <b>Form Pemeriksaan</b>
                </div>

                <div class="card-body">

                    {{-- POLIKLINIK --}}
                    <div class="mb-3">
                        <label>Poliklinik Tujuan</label>
                        <select name="poli_id" class="form-control" required>
                            <option value="">-- Pilih Poli --</option>
                            @foreach($poli as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JADWAL DOKTER --}}
                    <div class="mb-3">
                        <label>Dokter</label>
                        <select name="jadwal_id" class="form-control" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($jadwal as $j)
                                <option value="{{ $j->id }}">
                                    {{ $j->dokter->nama }} ({{ $j->poli->nama_poli }})
                                    — {{ $j->hari }} {{ $j->jam_mulai }}-{{ $j->jam_selesai }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TANGGAL KUNJUNGAN --}}
                    <div class="mb-3">
                        <label>Tanggal Kunjungan</label>
                        <input type="date" name="tgl_kunjungan" class="form-control" required>
                    </div>

                    {{-- JENIS PEMBAYARAN --}}
                    <div class="mb-3">
                        <label>Jenis Pembayaran</label>
                        <select name="jenis_pembayaran" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Umum">Umum</option>
                            <option value="BPJS">BPJS</option>
                            <option value="Asuransi">Asuransi Lain</option>
                        </select>
                    </div>

                    <button class="btn btn-success w-100">Simpan Pemeriksaan</button>

                </div>
            </div>

        </form>
    </div>
</div>

@endsection
