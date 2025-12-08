@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>Tambah Obat Baru</h3>

    <form action="{{ route('gudang_obat.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Kode Obat</label>
                    <input type="text" name="kodeobat" class="form-control">
                    @error('kodeobat') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Obat *</label>
                    <input type="text" name="nama" class="form-control" required>
                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Obat *</label>
                    <select name="id_jenis" class="form-control" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach(\App\Models\JenisObat::all() as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->jenisobat }}</option>
                        @endforeach
                    </select>
                    @error('id_jenis') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dosis</label>
                    <input type="text" name="dosis" class="form-control" placeholder="e.g. 500mg">
                    @error('dosis') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Stok *</label>
                    <input type="number" name="stok" class="form-control" min="0" required>
                    @error('stok') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga *</label>
                    <input type="number" step="0.01" name="harga" class="form-control" required>
                    @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Expired *</label>
                    <input type="date" name="expired" class="form-control" required>
                    @error('expired') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('gudang_obat.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
