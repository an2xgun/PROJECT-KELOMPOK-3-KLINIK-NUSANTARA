@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <h3>Tambah Dokter Baru</h3>

    <form action="{{ route('master.dokter.store') }}" method="POST" class="mt-3">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Dokter *</label>
                    <input type="text" name="nama" class="form-control" required>
                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Poliklinik *</label>
                    <select name="poliklinik_id" class="form-control" required>
                        <option value="">-- Pilih Poliklinik --</option>
                        @foreach($polikliniks as $poli)
                            <option value="{{ $poli->id }}">{{ $poli->name }}</option>
                        @endforeach
                    </select>
                    @error('poliklinik_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control"></textarea>
                    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon *</label>
                    <input type="tel" name="telepon" class="form-control" required>
                    @error('telepon') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jadwal Praktek *</label>
                    <select name="jadwalpraktek" class="form-control" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach(\App\Models\Jadwal::all() as $jadwal)
                            <option value="{{ $jadwal->jadwalpraktek }}">{{ $jadwal->jadwalpraktek }}</option>
                        @endforeach
                    </select>
                    @error('jadwalpraktek') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('master.jadwal_dokter') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
