@extends('layout')

@section('content')
<div class="container mt-4">
    <h3>🔍 Cari Pasien Lama</h3>

    {{-- Form Pencarian --}}
    <form action="{{ route('pasien.cari') }}" method="GET" class="mt-3 d-flex gap-2">
        <input type="text" name="keyword" class="form-control" placeholder="Masukkan NIK / Nama / No RM" required>
        <button type="submit" class="btn btn-success">Cari</button>
    </form>

    {{-- Pesan Info Jika Pasien Tidak Ditemukan --}}
    @if (session('info'))
        <div class="alert alert-warning mt-3">
            {{ session('info') }}
            <a href="{{ route('pasien.baru') }}" class="btn btn-sm btn-primary ms-2">Daftar Pasien Baru</a>
        </div>
    @endif

    {{-- Hasil Pencarian --}}
    @if(isset($hasil) && $hasil->count() > 0)
        <h5 class="mt-4">Hasil Pencarian:</h5>
        <table class="table table-bordered mt-2">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>No RM</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>No Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasil as $index => $pasien)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pasien->nama }}</td>
                        <td>{{ $pasien->nik }}</td>
                        <td>{{ $pasien->no_rm }}</td>
                        <td>{{ $pasien->jenis_kelamin }}</td>
                        <td>{{ $pasien->tanggal_lahir }}</td>
                        <td>{{ $pasien->no_telepon }}</td>
                        <td>
                            <a href="{{ route('pasien.edit', $pasien->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pasien ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(isset($hasil))
        <div class="alert alert-danger mt-3">
            Tidak ada pasien ditemukan untuk pencarian tersebut.
            <a href="{{ route('pasien.baru') }}" class="btn btn-sm btn-primary ms-2">Daftar Pasien Baru</a>
        </div>
    @endif
</div>
@endsection
