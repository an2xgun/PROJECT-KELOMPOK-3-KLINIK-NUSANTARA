<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// CRUD Pasien
Route::get('/pendaftaran/pasien-baru', [PasienController::class, 'create'])->name('pasien.baru');
Route::post('/pendaftaran/pasien-baru', [PasienController::class, 'store'])->name('pasien.store');
Route::get('/pendaftaran/pasien-lama', [PasienController::class, 'searchForm'])->name('pasien.lama');
Route::get('/pendaftaran/pasien-lama/cari', [PasienController::class, 'search'])->name('pasien.cari');

// Data Master
Route::get('/data_master', [PasienController::class, 'index'])->name('data.master');
Route::get('/data_master/edit/{id}', [PasienController::class, 'edit'])->name('pasien.edit');
Route::put('/data_master/update/{id}', [PasienController::class, 'update'])->name('pasien.update');
Route::delete('/data_master/delete/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

