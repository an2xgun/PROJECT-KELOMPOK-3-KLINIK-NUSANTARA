<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalPoliController;
use App\Http\Controllers\MasterTindakanController;
use App\Http\Controllers\MasterDiagnosaController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\GudangObatController;


// -------------------------
// LOGIN
// -------------------------
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// -------------------------
// REDIRECT ROOT
// -------------------------
Route::get('/', fn() => redirect()->route('dashboard'));


// -------------------------
// DASHBOARD
// -------------------------
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


// -------------------------
// PENDAFTARAN
// -------------------------
Route::prefix('pendaftaran')->group(function () {
    Route::get('/create', [PendaftaranController::class, 'create'])
        ->name('pendaftaran.create');

    Route::get('/list', [PendaftaranController::class, 'index'])
        ->name('pendaftaran.list');
});


// -------------------------
// PASIEN
// -------------------------
Route::resource('pasien', PasienController::class);


// -------------------------
// POLIKLINIK  (BLOCK SELESAI DI SINI)
// -------------------------
Route::prefix('poliklinik')->group(function () {

    Route::get('/', [PoliklinikController::class, 'index'])
        ->name('poliklinik.index');

    Route::get('/umum', [PoliklinikController::class, 'umum'])
        ->name('poliklinik.poli_umum');

    Route::get('/poli_gigi', [PoliklinikController::class, 'gigi'])
        ->name('poliklinik.poli_gigi');

    Route::get('/poli_kandungan', [PoliklinikController::class, 'kandungan'])
        ->name('poliklinik.poli_kandungan');
});  // ← ← TUTUP POLIKLINIK DI SINI, BENARNYA DI SINI


// -------------------------
// MASTER DATA (DI LUAR POLIKLINIK)
// -------------------------
Route::get('/master/jadwal_dokter', [DokterController::class, 'index'])
    ->name('master.jadwal_dokter');

Route::get('/master/jadwal_poli', [JadwalPoliController::class, 'index'])
    ->name('master.jadwal_poli');

Route::get('/master/data_tindakan', [MasterTindakanController::class, 'index'])
    ->name('master.data_tindakan');

Route::get('/master/data_diagnosa', [MasterDiagnosaController::class, 'index'])
    ->name('master.data_diagnosa');


// =========================
// 🔹 GUDANG OBAT (DI LUAR POLIKLINIK)
// =========================
Route::prefix('gudang_obat')->group(function () {

    Route::get('/apotik', [GudangObatController::class, 'apotik'])
        ->name('gudang_obat.apotik');

    Route::get('/apotik_retail', [GudangObatController::class, 'apotikRetail'])
        ->name('gudang_obat.apotik_retail');

    Route::get('/farmasi', [GudangObatController::class, 'farmasi'])
        ->name('gudang_obat.farmasi');

    Route::get('/master_obat', [GudangObatController::class, 'masterObat'])
        ->name('gudang_obat.master_obat');
});


// -------------------------
// AJAX
// -------------------------
Route::get('/ajax/jadwal-by-poli/{poliId}', [AjaxController::class,'jadwalByPoli'])
    ->name('ajax.jadwalByPoli');
