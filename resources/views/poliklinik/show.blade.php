@extends('layout')

@section('content')

<div class="card">
    <div class="card-body">

        <h3 class="mb-1">🏥 Poliklinik {{ $poli->nama_poli }}</h3>
        <p class="text-muted">Kode: {{ $poli->kode }}</p>

        <hr>

        {{-- INFORMASI POLIKLINIK --}}
        <h5>📘 Informasi Poliklinik</h5>
        <p>
            {{ $poli->deskripsi ?? 'Belum ada deskripsi untuk poliklinik ini.' }}
        </p>

        <hr>

        {{-- DAFTAR DOKTER --}}
        <h5 class="mb-3">👨‍⚕️ Daftar Dokter</h5>

        @if($dokter->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokter as $d)
                    <tr>
                        <td>{{ $d->nama }}</td>
                        <td>{{ ucfirst($d->spesialis) }}</td>
                        <td>
                            <a href="{{ route('pendaftaran.create', ['dokter' => $d->id]) }}"
                                class="btn btn-success btn-sm">
                                Daftar Pemeriksaan
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        @else
            <div class="alert alert-warning">
                Belum ada dokter untuk poliklinik ini.
            </div>
        @endif

    </div>
</div>

@endsection
