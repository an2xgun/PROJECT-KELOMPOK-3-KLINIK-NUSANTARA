@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col">
            <h3>Detail Rekam Medis #{{ $rekam->id }}</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('rekam.edit', $rekam->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('invoice.create', $rekam->id) }}" class="btn btn-primary">Buat Invoice</a>
            <a href="{{ route('rekam.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Pasien</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ optional($rekam->pasien)->nama ?? '-' }}</p>
                            <p><strong>No RM:</strong> {{ optional($rekam->pasien)->no_rm ?? optional($rekam->pasien)->kodepasien ?? '-' }}</p>
                            <p><strong>NIK:</strong> {{ optional($rekam->pasien)->nik ?? '-' }}</p>
                            <p><strong>Telepon:</strong> {{ optional($rekam->pasien)->telepon ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Lahir:</strong> {{ optional($rekam->pasien)->lahir ?? '-' }}</p>
                            <p><strong>Jenis Kelamin:</strong> {{ optional($rekam->pasien)->kelamin ?? '-' }}</p>
                            <p><strong>Agama:</strong> {{ optional($rekam->pasien)->agama ?? '-' }}</p>
                            <p><strong>Alamat:</strong> {{ optional($rekam->pasien)->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Informasi Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>No Antrian:</strong> {{ $rekam->nomorantrian ?? '-' }}</p>
                            <p><strong>Layanan:</strong> {{ $rekam->layanan ?? '-' }}</p>
                            <p><strong>Keluhan:</strong> {{ optional($rekam)->keluhan ?? '-' }}</p>
                            <p><strong>Dokter:</strong> {{ optional($rekam->dokter)->nama ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Diagnosa:</strong> {{ $rekam->diagnosa ?? $rekam->diagnosa_primer ?? '-' }}</p>
                            <p><strong>Tanggal Periksa:</strong> {{ optional($rekam)->tanggalperiksa ?? '-' }}</p>
                            <p><strong>Jadwal Kedatangan:</strong> {{ optional($rekam)->jadwal_kedatangan ?? '-' }}</p>
                            <p><strong>Jadwal Selesai:</strong> {{ optional($rekam)->jadwal_selesai ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Vital Signs</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tinggi:</strong> {{ optional($rekam)->tinggi ?? '-' }} cm</p>
                            <p><strong>Berat:</strong> {{ optional($rekam)->berat ?? '-' }} kg</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tekanan Darah:</strong> {{ optional($rekam)->darah ?? '-' }}</p>
                            <p><strong>Lingkar Pinggang:</strong> {{ optional($rekam)->pinggang ?? '-' }} cm</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($prescription && $prescription->items->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Resep Obat</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Obat</th>
                                <th>Dosis</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $item)
                            <tr>
                                <td>{{ $item->obat->nama }}</td>
                                <td>{{ $item->obat->dosis }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Tambah Obat ke Resep</h5>
                </div>
                <div class="card-body">
                    @if($prescription)
                    <form action="{{ route('rekam.addObat', $rekam->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Obat *</label>
                            <select name="obat_id" class="form-control" required>
                                <option value="">-- Pilih Obat --</option>
                                @foreach($obats as $obat)
                                    <option value="{{ $obat->id }}">{{ $obat->nama }} (Rp {{ number_format($obat->harga, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah *</label>
                            <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan *</label>
                            <input type="number" step="0.01" name="harga_satuan" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Tambah Obat</button>
                    </form>
                    @else
                    <p class="text-warning">Belum ada resep. Silakan simpan rekam medis terlebih dahulu.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
