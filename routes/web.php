<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\RekamController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JadwalPoliController;
use App\Http\Controllers\MasterTindakanController;
use App\Http\Controllers\MasterDiagnosaController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ExaminationFormController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;

// -------------------------
// LOGIN
// -------------------------
Route::get('/login', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// DEBUG ROUTES
Route::get('/debug/session', fn() => [
    'session_id' => session()->getId(),
    'session_lifetime' => config('session.lifetime'),
    'session_driver' => config('session.driver'),
    'csrf_token' => csrf_token(),
])->middleware('guest');

// Debug: return jadwal payload for a poliklinik (authenticated)
Route::get('/debug/jadwals/{poliId}', function($poliId) {
    $jadwals = \App\Models\JadwalPoli::with('dokter')
        ->where('poliklinik_id', $poliId)
        ->get()
        ->map(function($j) {
            return [
                'id' => $j->id,
                'hari' => $j->hari,
                'jam_mulai' => $j->jam_mulai,
                'jam_selesai' => $j->jam_selesai,
                'dokter' => [
                    'id' => optional($j->dokter)->id,
                    'nama' => optional($j->dokter)->nama ?? 'Unknown',
                ],
            ];
        });

    return response()->json($jadwals);
})->middleware('auth')->name('debug.jadwals');

// PROFILE & SETTINGS (all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// LANDING PAGE & REDIRECT ROOT
Route::get('/', fn() => view('landing'))->name('landing');
Route::get('/home', fn() => redirect()->route('dashboard'))->middleware('auth');

// DASHBOARD (semua role)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// -------------------------
// PENDAFTARAN (admin, petugas_pendaftaran)
// -------------------------
Route::prefix('pendaftaran')->middleware(['auth', 'role:admin,petugas_pendaftaran'])->group(function () {
    Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/antrian/list', [PendaftaranController::class, 'antrian'])->name('pendaftaran.antrian');
    Route::get('/choice', [PendaftaranController::class, 'choice'])->name('pendaftaran.choice');
    Route::get('/search-old', [PendaftaranController::class, 'searchOld'])->name('pendaftaran.search-old-patient');
    Route::get('/create-new', [PendaftaranController::class, 'createNew'])->name('pendaftaran.create-new-patient');
    Route::post('/store-new', [PendaftaranController::class, 'storeNew'])->name('pendaftaran.store-new-patient');
    Route::get('/select-poli/{pasien}', [PendaftaranController::class, 'selectPoli'])->name('pendaftaran.select-poli');
    Route::post('/store-poli/{pasien}', [PendaftaranController::class, 'storePoli'])->name('pendaftaran.store-poli');
    Route::get('/list', [PendaftaranController::class, 'index'])->name('pendaftaran.list');
    Route::post('/{id}/serve', [PendaftaranController::class, 'serve'])->name('pendaftaran.serve');
    Route::delete('/{id}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');
});

// AJAX: patient lookup by No RM (exact match)
Route::get('/api/patient/{no_rm}', [AjaxController::class, 'getPatientByNoRm'])->middleware('auth');
// AJAX: search patients (partial match on nama/nik/no_rm)
Route::get('/api/pasien/search', [AjaxController::class, 'searchPasien'])->middleware('auth');
// AJAX: suggest No RM values for autocomplete
Route::get('/api/pasien/suggest-no-rm', [AjaxController::class, 'suggestNoRm'])->middleware('auth');

// -------------------------
// PASIEN (admin, petugas_pendaftaran)
// -------------------------
Route::prefix('pasien')->middleware(['auth', 'role:admin,petugas_pendaftaran'])->group(function () {
    Route::get('/', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/create', [PasienController::class, 'create'])->name('pasien.create');
    Route::post('/store', [PasienController::class, 'store'])->name('pasien.store');
    Route::get('/edit/{id}', [PasienController::class, 'edit'])->name('pasien.edit');
    Route::put('/update/{id}', [PasienController::class, 'update'])->name('pasien.update');
    Route::delete('/delete/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');
    Route::get('/get/{identifier}', [PasienController::class, 'getByNoRM'])->name('pasien.getByNoRM');
});

// -------------------------
// POLIKLINIK (admin, dokter)
// -------------------------
Route::prefix('poliklinik')->middleware(['auth', 'role:admin,dokter'])->group(function () {
    Route::get('/', [PoliklinikController::class, 'index'])->name('poliklinik.index');
    Route::get('/create', [PoliklinikController::class, 'create'])->middleware('role:admin')->name('poliklinik.create');
    Route::post('/store', [PoliklinikController::class, 'store'])->middleware('role:admin')->name('poliklinik.store');
    Route::get('/edit/{id}', [PoliklinikController::class, 'edit'])->middleware('role:admin')->name('poliklinik.edit');
    Route::put('/update/{id}', [PoliklinikController::class, 'update'])->middleware('role:admin')->name('poliklinik.update');
    Route::delete('/delete/{id}', [PoliklinikController::class, 'destroy'])->middleware('role:admin')->name('poliklinik.destroy');

    Route::get('/umum', [PoliklinikController::class, 'umum'])->name('poliklinik.poli_umum');
    Route::get('/gigi', [PoliklinikController::class, 'gigi'])->name('poliklinik.poli_gigi');
    Route::get('/kandungan', [PoliklinikController::class, 'kandungan'])->name('poliklinik.poli_kandungan');
});

// -------------------------
// MASTER DATA (admin only)
// -------------------------
Route::prefix('master')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/jadwal_dokter', [DokterController::class, 'index'])->name('master.jadwal_dokter');
    Route::get('/dokter/create', [DokterController::class, 'create'])->name('master.dokter.create');
    Route::post('/dokter/store', [DokterController::class, 'store'])->name('master.dokter.store');
    Route::get('/dokter/{id}/edit', [DokterController::class, 'edit'])->name('master.dokter.edit');
    Route::put('/dokter/{id}', [DokterController::class, 'update'])->name('master.dokter.update');
    Route::delete('/dokter/{id}', [DokterController::class, 'destroy'])->name('master.dokter.destroy');

    Route::get('/jadwal_poli', [JadwalPoliController::class, 'index'])->name('master.jadwal_poli');
    Route::get('/data_tindakan', [MasterTindakanController::class, 'index'])->name('master.data_tindakan');
    Route::get('/data_diagnosa', [MasterDiagnosaController::class, 'index'])->name('master.data_diagnosa');

    // Backwards-compatible resource style routes used by controllers and views
    Route::get('/tindakan', [MasterTindakanController::class, 'index'])->name('tindakan.index');
    Route::get('/tindakan/create', [MasterTindakanController::class, 'create'])->name('tindakan.create');
    Route::post('/tindakan/store', [MasterTindakanController::class, 'store'])->name('tindakan.store');
    Route::get('/tindakan/{id}/edit', [MasterTindakanController::class, 'edit'])->name('tindakan.edit');
    Route::put('/tindakan/{id}', [MasterTindakanController::class, 'update'])->name('tindakan.update');
    Route::delete('/tindakan/{id}', [MasterTindakanController::class, 'destroy'])->name('tindakan.destroy');

    Route::get('/diagnosa', [MasterDiagnosaController::class, 'index'])->name('diagnosa.index');
    Route::get('/diagnosa/create', [MasterDiagnosaController::class, 'create'])->name('diagnosa.create');
    Route::post('/diagnosa/store', [MasterDiagnosaController::class, 'store'])->name('diagnosa.store');
    Route::get('/diagnosa/{id}/edit', [MasterDiagnosaController::class, 'edit'])->name('diagnosa.edit');
    Route::put('/diagnosa/{id}', [MasterDiagnosaController::class, 'update'])->name('diagnosa.update');
    Route::delete('/diagnosa/{id}', [MasterDiagnosaController::class, 'destroy'])->name('diagnosa.destroy');
});

// -------------------------
// REKAM MEDIS (admin, dokter)
// -------------------------
Route::prefix('rekam')->middleware(['auth', 'role:admin,dokter'])->group(function () {
    Route::get('/', [RekamController::class, 'index'])->name('rekam.index');
    Route::get('/create', [RekamController::class, 'create'])->name('rekam.create');
    Route::post('/store', [RekamController::class, 'store'])->name('rekam.store');
    Route::get('/{id}', [RekamController::class, 'show'])->name('rekam.show');
    Route::get('/{id}/edit', [RekamController::class, 'edit'])->name('rekam.edit');
    Route::put('/{id}', [RekamController::class, 'update'])->name('rekam.update');
    Route::delete('/{id}', [RekamController::class, 'destroy'])->middleware('role:admin,dokter')->name('rekam.destroy');
    Route::post('/{rekam_id}/add-obat', [RekamController::class, 'addObat'])->name('rekam.addObat');
});

// -------------------------
// EXAMINATION FORMS (Poliklinik-specific forms)
// -------------------------
Route::prefix('examination')->middleware(['auth', 'role:admin,dokter'])->group(function () {
    Route::get('/{pendaftaranId}/form', [ExaminationFormController::class, 'show'])->name('examination.form');
    Route::post('/{pendaftaranId}/store', [ExaminationFormController::class, 'store'])->name('examination.store');
    // Queue for doctors to pick pending pendaftaran and perform pemeriksaan
    Route::get('/queue', [ExaminationFormController::class, 'queue'])->name('examination.queue');
    // Detail pemeriksaan dengan form inline
    Route::get('/{pendaftaranId}/detail', [ExaminationFormController::class, 'detail'])->name('examination.detail');
    // Update status pemeriksaan
    Route::put('/{rekamId}/status', [ExaminationFormController::class, 'updateStatus'])->name('examination.updateStatus');
});

// -------------------------
// PRESCRIPTIONS / RESEP
// Controller enforces role checks: dokter can create/store, apoteker can manage
// -------------------------
Route::prefix('prescription')->middleware(['auth'])->group(function () {
    Route::get('/', [PrescriptionController::class, 'index'])->name('prescription.index');
    Route::get('/pending', [PrescriptionController::class, 'pending'])->name('prescription.pending');
    Route::get('/{rekamId}/create', [PrescriptionController::class, 'create'])->name('prescription.create');
    Route::post('/{rekamId}/store', [PrescriptionController::class, 'store'])->name('prescription.store');
    Route::get('/{prescriptionId}', [PrescriptionController::class, 'show'])->name('prescription.show');
    Route::get('/{prescriptionId}/edit', [PrescriptionController::class, 'edit'])->name('prescription.edit');
    Route::put('/{prescriptionId}', [PrescriptionController::class, 'update'])->name('prescription.update');
    Route::delete('/{prescriptionId}', [PrescriptionController::class, 'destroy'])->name('prescription.destroy');
    Route::post('/{prescriptionId}/process', [PrescriptionController::class, 'process'])->name('prescription.process');
    Route::put('/{prescriptionId}/status', [PrescriptionController::class, 'updateStatus'])->name('prescription.updateStatus');
});

// -------------------------
// APOTEKER - DISPENSING (apoteker untuk memberikan obat)
// -------------------------
Route::prefix('dispensing')->middleware(['auth', 'role:admin,apoteker'])->group(function () {
    Route::get('/', [PrescriptionController::class, 'dispensingQueue'])->name('dispensing.queue');
    Route::get('/{prescriptionId}/form', [PrescriptionController::class, 'dispensingForm'])->name('dispensing.form');
    Route::post('/{prescriptionId}/confirm', [PrescriptionController::class, 'confirmDispensingController'])->name('dispensing.confirm');
});

// -------------------------
// GUDANG OBAT / APOTEK (apoteker only)
// -------------------------
Route::prefix('gudang_obat')->middleware(['auth', 'role:admin,apoteker'])->group(function () {
    Route::get('/', [ObatController::class, 'index'])->name('gudang_obat.index');
    Route::get('/create', [ObatController::class, 'create'])->name('gudang_obat.create');
    Route::post('/store', [ObatController::class, 'store'])->name('gudang_obat.store');
    Route::get('/{id}/edit', [ObatController::class, 'edit'])->name('gudang_obat.edit');
    Route::put('/{id}', [ObatController::class, 'update'])->name('gudang_obat.update');
    Route::delete('/{id}', [ObatController::class, 'destroy'])->name('gudang_obat.destroy');
});
// ========================
// LAPORAN / REPORTS (admin only)
// ========================
Route::prefix('reports')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/kunjungan-harian', [ReportController::class, 'kunjunganHarian'])->name('reports.kunjungan_harian');
    Route::get('/stok-obat', [ReportController::class, 'stokObat'])->name('reports.stok_obat');
    Route::get('/resep-obat-keluar', [ReportController::class, 'resepObatKeluar'])->name('reports.resep_obat_keluar');
    Route::get('/keuangan', [ReportController::class, 'keuangan'])->name('reports.keuangan');
    Route::get('/diagnosa', [ReportController::class, 'diagnosa'])->name('reports.diagnosa');
});

// ========================
// API ROUTES (untuk AJAX)
// ========================
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/pasien/search', [AjaxController::class, 'searchPasien']);
    Route::get('/jadwal-poli/{poliId}', [AjaxController::class, 'getJadwalPoli']);
    Route::get('/pending-count', [AjaxController::class, 'pendingCount']);
    // Diagnosa API for inline creation/deletion from examination form
    Route::post('/diagnosa', [\App\Http\Controllers\MasterDiagnosaController::class, 'apiStore']);
    Route::delete('/diagnosa/{id}', [\App\Http\Controllers\MasterDiagnosaController::class, 'apiDestroy']);
});

// -------------------------
// INVOICE / PEMBAYARAN (admin, kasir)
// -------------------------
Route::prefix('invoice')->middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/create/{rekam_id}', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/store/{rekam_id}', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/create-pendaftaran/{pendaftaran_id}', [InvoiceController::class, 'createFromPendaftaran'])->name('invoice.create-pendaftaran');
    Route::post('/store-pendaftaran/{pendaftaran_id}', [InvoiceController::class, 'storeFromPendaftaran'])->name('invoice.store-pendaftaran');
    Route::put('/{id}/paid', [InvoiceController::class, 'markAsPaid'])->name('invoice.markAsPaid');
    Route::get('/{id}/print', [InvoiceController::class, 'print'])->name('invoice.print');
    Route::get('/{id}/print-thermal', [InvoiceController::class, 'printThermal'])->name('invoice.printThermal');
    Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
});

// -------------------------
// AJAX
// -------------------------
Route::get('/ajax/jadwal-by-poli/{poliId}', [AjaxController::class,'jadwalByPoli'])
    ->name('ajax.jadwalByPoli');
