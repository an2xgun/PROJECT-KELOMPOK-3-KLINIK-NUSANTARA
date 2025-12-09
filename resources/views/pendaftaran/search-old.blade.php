@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h2 class="mb-4">
                <a href="{{ route('pendaftaran.choice') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                Cari Pasien Lama
            </h2>

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('pendaftaran.search-old-patient') }}" class="row g-3">
                        <div class="col-md-5">
                            <label for="searchBox" class="form-label">Cari berdasarkan No RM atau Nama</label>
                            <input type="text" class="form-control" id="searchBox" name="q" placeholder="Masukkan No RM atau Nama Pasien">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    @if(isset($results) && count($results) > 0)
                        <div class="table-responsive mt-4">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No RM</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $pasien)
                                        <tr>
                                            <td><strong>{{ $pasien->no_rm }}</strong></td>
                                            <td>{{ $pasien->nama }}</td>
                                            <td>{{ $pasien->nik ?? '-' }}</td>
                                            <td>{{ $pasien->tanggal_lahir ?? $pasien->lahir ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('pendaftaran.select-poli', $pasien->id) }}" class="btn btn-sm btn-success">
                                                    <i class="bi bi-arrow-right"></i> Pilih
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif(isset($q) && $q !== '')
                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle"></i> Pasien tidak ditemukan. 
                            <a href="{{ route('pendaftaran.create-new-patient') }}" class="alert-link">Daftarkan pasien baru?</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
