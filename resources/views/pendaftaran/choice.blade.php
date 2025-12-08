@extends('layout')
@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h2 class="mb-4">Pendaftaran Pasien</h2>
            
            <div class="row">
                <!-- Pasien Lama -->
                <div class="col-md-6 mb-4">
                    <div class="card border-primary h-100">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-search"></i> Pasien Lama
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 200px;">
                            <p class="text-muted">Cari pasien yang sudah terdaftar</p>
                            <a href="{{ route('pendaftaran.search-old-patient') }}" class="btn btn-primary btn-lg mt-3">
                                <i class="bi bi-search"></i> Cari Pasien
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pasien Baru -->
                <div class="col-md-6 mb-4">
                    <div class="card border-success h-100">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-person-plus"></i> Pasien Baru
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 200px;">
                            <p class="text-muted">Daftar pasien baru ke sistem</p>
                            <a href="{{ route('pendaftaran.create-new-patient') }}" class="btn btn-success btn-lg mt-3">
                                <i class="bi bi-plus-circle"></i> Pasien Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .card-header {
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
</style>

@endsection
