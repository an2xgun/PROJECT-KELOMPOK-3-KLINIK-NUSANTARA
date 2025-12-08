@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>Edit Obat</h3>

    <form action="{{ route('gudang_obat.update', $obat->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Kode Obat</label>
                    <input type="text" name="kodeobat" class="form-control" value="{{ $obat->kodeobat }}" {{ Auth::user()->role === 'apoteker' ? 'readonly' : '' }}>
                    @error('kodeobat') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Obat *</label>
                    <input type="text" name="nama" class="form-control" value="{{ $obat->nama }}" {{ Auth::user()->role === 'apoteker' ? 'readonly' : '' }} required>
                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Obat *</label>
                    <select name="id_jenis" class="form-control" {{ Auth::user()->role === 'apoteker' ? 'disabled' : '' }} required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenis as $j)
                            <option value="{{ $j->id }}" {{ $j->id == $obat->id_jenis ? 'selected' : '' }}>{{ $j->jenisobat }}</option>
                        @endforeach
                    </select>
                    @error('id_jenis') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dosis</label>
                    <input type="text" name="dosis" class="form-control" value="{{ $obat->dosis }}" {{ Auth::user()->role === 'apoteker' ? 'readonly' : '' }}>
                    @error('dosis') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Stok *</label>
                    <input type="number" name="stok" class="form-control" min="0" value="{{ $obat->stok }}" required>
                    @error('stok') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga *</label>
                    <input type="number" step="0.01" name="harga" class="form-control" value="{{ $obat->harga }}" required>
                    @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Expired *</label>
                    <input type="date" name="expired" class="form-control" value="{{ $obat->expired }}" {{ Auth::user()->role === 'apoteker' ? 'readonly' : '' }} required>
                    @error('expired') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    @if($obat->photo)
                        <div class="mb-2"><img src="/{{ $obat->photo }}" alt="foto" style="max-width:150px;border-radius:6px;"></div>
                    @endif
                    <input type="file" name="photo" class="form-control" accept="image/*" {{ Auth::user()->role === 'apoteker' ? 'disabled' : '' }}>
                    @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('gudang_obat.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
