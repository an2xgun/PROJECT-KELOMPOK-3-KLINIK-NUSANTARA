@extends('layout')

@section('content')

<h3 class="fw-bold mb-4">Dashboard</h3>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card-stat" style="background:#61154e;">
            <h6>Total Pasien</h6>
            <h2>{{ $total_pasien }}</h2>
            <i class="bi bi-people-fill fs-3"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat" style="background:#2563eb;">
            <h6>Menunggu</h6>
            <h2>{{ $menunggu }}</h2>
            <i class="bi bi-hourglass-split fs-3"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat" style="background:#9333ea;">
            <h6>Sedang Dilayani</h6>
            <h2>{{ $sedang_dilayani }}</h2>
            <i class="bi bi-person-workspace fs-3"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat" style="background:#16a34a;">
            <h6>Selesai</h6>
            <h2>{{ $selesai }}</h2>
            <i class="bi bi-check-circle-fill fs-3"></i>
        </div>
    </div>

</div>

<hr class="my-5">

<h4 class="fw-bold mb-3">Statistik Berdasarkan Poliklinik</h4>

<div class="row g-4">

    @foreach ($statistik_poli as $poli)
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>{{ $poli->nama }}</h5>
                <p class="mb-1">Total: <strong>{{ $poli->total }}</strong></p>
                <p class="mb-1 text-warning">Menunggu: <strong>{{ $poli->menunggu }}</strong></p>
                <p class="mb-1 text-primary">Dilayani: <strong>{{ $poli->sedang_dilayani }}</strong></p>
                <p class="mb-0 text-success">Selesai: <strong>{{ $poli->selesai }}</strong></p>
            </div>
        </div>
    @endforeach

</div>

@endsection
