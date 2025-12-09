@extends('layout')

@section('content')
<div class="container-fluid">
    
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 fw-bold">
                <i class="bi bi-speedometer2"></i> Dashboard
            </h1>
            <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <!-- DASHBOARD UNTUK ADMIN -->
    @if(Auth::user()->role === 'admin')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Pasien</h6>
                            <h3 class="mb-0">{{ $total_pasien }}</h3>
                        </div>
                        <i class="bi bi-people" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Rekam Medis</h6>
                            <h3 class="mb-0">{{ $total_rekam }}</h3>
                        </div>
                        <i class="bi bi-file-medical" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Pendaftaran</h6>
                            <h3 class="mb-0">{{ $menunggu + $sedang_dilayani + $selesai }}</h3>
                        </div>
                        <i class="bi bi-journal-medical" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Invoice</h6>
                            <h3 class="mb-0">{{ $total_invoice }}</h3>
                        </div>
                        <i class="bi bi-file-earmark-pdf" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Status Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted">Menunggu</h6>
                                <h3 class="text-warning">{{ $menunggu }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted">Sedang Dilayani</h6>
                                <h3 class="text-info">{{ $sedang_dilayani }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted">Selesai</h6>
                                <h3 class="text-success">{{ $selesai }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-hospital"></i> Statistik Poliklinik</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Poliklinik</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Menunggu</th>
                                <th class="text-center">Sedang</th>
                                <th class="text-center">Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistik_poli as $poli)
                            <tr>
                                <td>{{ $poli['name'] }}</td>
                                <td class="text-center"><span class="badge bg-primary">{{ $poli['total'] }}</span></td>
                                <td class="text-center"><span class="badge bg-warning">{{ $poli['menunggu'] }}</span></td>
                                <td class="text-center"><span class="badge bg-info">{{ $poli['sedang_dilayani'] }}</span></td>
                                <td class="text-center"><span class="badge bg-success">{{ $poli['selesai'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DASHBOARD UNTUK DOKTER -->
    @elseif(Auth::user()->role === 'dokter')
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Rekam Hari Ini</h6>
                            <h3 class="mb-0">{{ $rekam_hari_ini ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-file-medical" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Rekam</h6>
                            <h3 class="mb-0">{{ $total_rekam }}</h3>
                        </div>
                        <i class="bi bi-file-text" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Pasien</h6>
                            <h3 class="mb-0">{{ $total_pasien }}</h3>
                        </div>
                        <i class="bi bi-people" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h5>
        </div>
        <div class="card-body">
            <p class="text-muted text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                Belum ada aktivitas terbaru
            </p>
        </div>
    </div>

    <!-- DASHBOARD UNTUK PETUGAS PENDAFTARAN -->
    @elseif(Auth::user()->role === 'petugas_pendaftaran')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Pendaftaran Hari Ini</h6>
                            <h3 class="mb-0">{{ $pendaftaran_hari_ini ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-journal-medical" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Pasien</h6>
                            <h3 class="mb-0">{{ $total_pasien }}</h3>
                        </div>
                        <i class="bi bi-people" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Menunggu Layanan</h6>
                            <h3 class="mb-0">{{ $menunggu }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Selesai</h6>
                            <h3 class="mb-0">{{ $selesai }}</h3>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Cepat ke Menu</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                {{-- PETUGAS PENDAFTARAN --}}
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas_pendaftaran')
                <div class="col-md-6">
                    <a href="{{ route('pendaftaran.choice') }}" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-plus-circle"></i> Pendaftaran Baru
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('pendaftaran.list') }}" class="btn btn-info w-100 py-3">
                        <i class="bi bi-list-check"></i> Daftar Pendaftaran
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('pasien.index') }}" class="btn btn-success w-100 py-3">
                        <i class="bi bi-people"></i> Data Pasien
                    </a>
                </div>
                @endif

                {{-- DOKTER --}}
                @if(Auth::user()->role === 'dokter')
                <div class="col-md-6">
                    <a href="{{ route('pendaftaran.list') }}" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-list-check"></i> Antrian Pemeriksaan
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('prescription.index') }}" class="btn btn-info w-100 py-3">
                        <i class="bi bi-receipt"></i> Daftar Resep
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('rekam.index') }}" class="btn btn-success w-100 py-3">
                        <i class="bi bi-file-medical"></i> Rekam Medis
                    </a>
                </div>
                @endif

                {{-- APOTEKER --}}
                @if(Auth::user()->role === 'apoteker')
                <div class="col-md-6">
                    <a href="{{ route('prescription.pending') }}" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-clock-history"></i> Resep Pending
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('prescription.index') }}" class="btn btn-info w-100 py-3">
                        <i class="bi bi-receipt"></i> Daftar Resep
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('gudang_obat.index') }}" class="btn btn-success w-100 py-3">
                        <i class="bi bi-capsule"></i> Data Obat
                    </a>
                </div>
                @endif

                {{-- KASIR --}}
                @if(Auth::user()->role === 'kasir')
                <div class="col-md-6">
                    <a href="{{ route('invoice.index') }}" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-file-earmark-pdf"></i> Invoice
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('rekam.index') }}" class="btn btn-info w-100 py-3">
                        <i class="bi bi-file-medical"></i> Rekam Medis
                    </a>
                </div>
                @endif

                {{-- ADMIN --}}
                @if(Auth::user()->role === 'admin')
                <div class="col-md-6">
                    <a href="{{ route('pendaftaran.choice') }}" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-plus-circle"></i> Pendaftaran Baru
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('pendaftaran.list') }}" class="btn btn-info w-100 py-3">
                        <i class="bi bi-list-check"></i> Daftar Pendaftaran
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('pasien.index') }}" class="btn btn-success w-100 py-3">
                        <i class="bi bi-people"></i> Data Pasien
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('master.jadwal_dokter') }}" class="btn btn-warning w-100 py-3">
                        <i class="bi bi-calendar-event"></i> Master Data
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>



    <!-- DASHBOARD UNTUK KASIR -->
    @elseif(Auth::user()->role === 'kasir')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Invoice Pending</h6>
                            <h3 class="mb-0">{{ $invoice_pending ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Invoice</h6>
                            <h3 class="mb-0">{{ $total_invoice }}</h3>
                        </div>
                        <i class="bi bi-file-earmark-pdf" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Pendaftaran</h6>
                            <h3 class="mb-0">{{ $menunggu + $sedang_dilayani + $selesai }}</h3>
                        </div>
                        <i class="bi bi-journal-medical" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Pasien</h6>
                            <h3 class="mb-0">{{ $total_pasien }}</h3>
                        </div>
                        <i class="bi bi-people" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Cepat ke Menu</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('invoice.index') }}" class="btn btn-primary w-100 py-3">
                <i class="bi bi-file-earmark-pdf"></i> Lihat Invoice
            </a>
        </div>
    </div>

    <!-- DASHBOARD UNTUK APOTEKER -->
    @elseif(Auth::user()->role === 'apoteker')
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Resep Pending</h6>
                            <h3 class="mb-0">{{ $resep_pending ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Resep</h6>
                            <h3 class="mb-0">{{ \App\Models\Prescription::count() }}</h3>
                        </div>
                        <i class="bi bi-prescription2" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-2">Total Obat</h6>
                            <h3 class="mb-0">{{ \App\Models\Obat::count() }}</h3>
                        </div>
                        <i class="bi bi-capsule" style="font-size: 2rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Cepat ke Menu</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('gudang_obat.index') }}" class="btn btn-primary w-100 py-3">
                <i class="bi bi-capsule"></i> Kelola Gudang Obat
            </a>
        </div>
    </div>

    @endif

</div>
@endsection
