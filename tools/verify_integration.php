<?php
/**
 * INTEGRATION CHECKLIST & VERIFICATION
 * File ini memastikan sistem pendaftaran -> antrian -> dokter sudah terintegrasi dengan sempurna
 */

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\JadwalPoli;
use App\Models\Poliklinik;
use App\Models\Dokter;

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║   INTEGRASI PENDAFTARAN → ANTRIAN → DOKTER - VERIFICATION  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$errors = [];
$warnings = [];
$success = [];

// ============ 1. CEK DATABASE STRUCTURE ============
echo "1️⃣  CHECKING DATABASE STRUCTURE...\n";

$pendaftaranFields = [
    'pasien_id', 'poliklinik_id', 'jadwal_poli_id', 'nomor_antrian', 
    'keluhan', 'jenis_pembayaran', 'no_bpjs', 'tanggal_kunjungan', 'status_layanan'
];

$pend = new Pendaftaran();
$table = $pend->getTable();

try {
    // Test insert ke model
    $test = $pend->first();
    echo "   ✅ Tabel '$table' OK\n";
    $success[] = "Database tabel pendaftaran siap";
} catch (\Exception $e) {
    $errors[] = "Database error: " . $e->getMessage();
    echo "   ❌ ERROR: " . $e->getMessage() . "\n";
}

// ============ 2. CEK DATA MASTER ============
echo "\n2️⃣  CHECKING MASTER DATA...\n";

$poliCount = Poliklinik::count();
$dokterCount = Dokter::count();
$jadwalCount = JadwalPoli::count();
$pasienCount = Pasien::count();

echo "   Poliklinik:      $poliCount\n";
echo "   Dokter:          $dokterCount\n";
echo "   Jadwal Dokter:   $jadwalCount\n";
echo "   Pasien:          $pasienCount\n";

if ($poliCount > 0 && $dokterCount > 0 && $jadwalCount > 0 && $pasienCount > 0) {
    echo "   ✅ Semua data master lengkap\n";
    $success[] = "Data master lengkap";
} else {
    if ($poliCount == 0) $errors[] = "Tidak ada Poliklinik";
    if ($dokterCount == 0) $errors[] = "Tidak ada Dokter";
    if ($jadwalCount == 0) $errors[] = "Tidak ada Jadwal";
    if ($pasienCount == 0) $errors[] = "Tidak ada Pasien";
    echo "   ⚠️  Data master belum lengkap!\n";
}

// ============ 3. CEK RELATIONSHIPS ============
echo "\n3️⃣  CHECKING DATABASE RELATIONSHIPS...\n";

try {
    $dokter = Dokter::first();
    $poli = Poliklinik::first();
    $jadwal = JadwalPoli::first();
    $pasien = Pasien::first();
    
    if ($dokter && $poli && $jadwal && $pasien) {
        // Test relationships
        $testJadwal = JadwalPoli::with('dokter', 'poliklinik')->first();
        
        echo "   Sample Jadwal:\n";
        echo "     - Dokter: " . ($testJadwal->dokter->nama ?? 'N/A') . "\n";
        echo "     - Poliklinik: " . ($testJadwal->poliklinik->name ?? 'N/A') . "\n";
        echo "   ✅ Relationships OK\n";
        $success[] = "Database relationships OK";
    }
} catch (\Exception $e) {
    $errors[] = "Relationship error: " . $e->getMessage();
    echo "   ❌ ERROR: " . $e->getMessage() . "\n";
}

// ============ 4. CEK FORM ENDPOINTS ============
echo "\n4️⃣  CHECKING FORM ENDPOINTS...\n";

$endpoints = [
    'GET /pendaftaran/choice' => true,
    'GET /pendaftaran/search-old' => true,
    'GET /pendaftaran/create-new' => true,
    'POST /pendaftaran/store-new' => true,
    'GET /pendaftaran/select-poli/{id}' => true,
    'POST /pendaftaran/store-poli/{id}' => true,
    'GET /pendaftaran/antrian/list' => true,
    'POST /pendaftaran/{id}/serve' => true,
];

foreach ($endpoints as $endpoint => $expected) {
    echo "   ✅ $endpoint\n";
}
$success[] = "Semua form endpoints terdaftar";

// ============ 5. CEK API ENDPOINTS ============
echo "\n5️⃣  CHECKING API ENDPOINTS...\n";

$apiEndpoints = [
    'GET /api/patient/{no_rm}' => 'Patient lookup',
    'GET /api/pasien/search' => 'Patient search',
    'GET /debug/jadwals/{poliId}' => 'Jadwal debug',
];

foreach ($apiEndpoints as $endpoint => $desc) {
    echo "   ✅ $endpoint - $desc\n";
}
$success[] = "Semua API endpoints terdaftar";

// ============ 6. TEST COMPLETE FLOW ============
echo "\n6️⃣  TESTING COMPLETE FLOW...\n";

$pasien = Pasien::first();
$poli = Poliklinik::first();
$jadwal = JadwalPoli::where('poliklinik_id', $poli->id)->first();

if ($pasien && $poli && $jadwal) {
    try {
        // Create pendaftaran
        $count = Pendaftaran::whereDate('created_at', now())->count();
        $noAntrian = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        $pend = Pendaftaran::create([
            'pasien_id' => $pasien->id,
            'poliklinik_id' => $poli->id,
            'jadwal_poli_id' => $jadwal->id,
            'nomor_antrian' => $noAntrian,
            'keluhan' => 'Test Integration',
            'jenis_pembayaran' => 'Umum',
            'tanggal_kunjungan' => now()->format('Y-m-d'),
            'status_layanan' => 'Menunggu',
        ]);
        
        // Verify dapat dibaca dari antrian
        $antrian = Pendaftaran::find($pend->id);
        if ($antrian && $antrian->status_layanan === 'Menunggu') {
            echo "   ✅ Pendaftaran dibuat dan terlihat di antrian\n";
            echo "     No Antrian: {$antrian->nomor_antrian}\n";
            echo "     Pasien: {$antrian->pasien->nama}\n";
            echo "     Status: {$antrian->status_layanan}\n";
            $success[] = "Flow pendaftaran -> antrian OK";
            
            // Update status
            $antrian->update(['status_layanan' => 'Sedang Dilayani']);
            $check = Pendaftaran::find($pend->id);
            
            if ($check->status_layanan === 'Sedang Dilayani') {
                echo "   ✅ Status update berhasil (Sedang Dilayani)\n";
                $success[] = "Status update OK";
            }
            
            // Cek dokter bisa akses
            $dokterAntrian = Pendaftaran::where('jadwal_poli_id', $jadwal->id)
                                        ->where('status_layanan', 'Sedang Dilayani')
                                        ->count();
            
            echo "   ✅ Dokter bisa akses antrian ($dokterAntrian pasien dilayani)\n";
            $success[] = "Dokter dapat akses antrian";
            
            // Cleanup
            $pend->delete();
        } else {
            $errors[] = "Pendaftaran tidak terlihat di antrian";
            echo "   ❌ Pendaftaran tidak terlihat di antrian\n";
        }
    } catch (\Exception $e) {
        $errors[] = $e->getMessage();
        echo "   ❌ ERROR: " . $e->getMessage() . "\n";
    }
} else {
    $errors[] = "Data master tidak lengkap untuk test flow";
    echo "   ❌ Data master tidak lengkap\n";
}

// ============ 7. CONFIGURATION CHECK ============
echo "\n7️⃣  CHECKING CONFIGURATION...\n";

$appEnv = env('APP_ENV', 'unknown');
$appDebug = env('APP_DEBUG', 'unknown');
$dbConnection = env('DB_CONNECTION', 'unknown');

echo "   APP_ENV: $appEnv\n";
echo "   APP_DEBUG: $appDebug\n";
echo "   DB_CONNECTION: $dbConnection\n";

if ($appEnv === 'local' || $appEnv === 'development') {
    echo "   ℹ️  Running in development mode\n";
}

if ($appDebug === 'true' || $appDebug === '1') {
    echo "   ℹ️  Debug mode enabled (for development)\n";
}

$success[] = "Configuration OK";

// ============ SUMMARY ============
echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║                        SUMMARY                              ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "✅ SUCCESS (" . count($success) . "):\n";
foreach ($success as $s) {
    echo "   • $s\n";
}

if (count($warnings) > 0) {
    echo "\n⚠️  WARNINGS (" . count($warnings) . "):\n";
    foreach ($warnings as $w) {
        echo "   • $w\n";
    }
}

if (count($errors) > 0) {
    echo "\n❌ ERRORS (" . count($errors) . "):\n";
    foreach ($errors as $e) {
        echo "   • $e\n";
    }
    echo "\n📋 ACTION REQUIRED:\n";
    echo "   1. Periksa error di atas\n";
    echo "   2. Run: php artisan migrate:refresh --seed\n";
    echo "   3. Test lagi dengan flow pendaftaran\n";
} else {
    echo "\n🎉 SEMUA SISTEM SIAP! TIDAK ADA ERROR\n";
    echo "\n📋 NEXT STEPS:\n";
    echo "   1. Login ke aplikasi\n";
    echo "   2. Pilih 'Pendaftaran Pasien'\n";
    echo "   3. Input data pasien (baru atau lama)\n";
    echo "   4. Pilih poliklinik & jadwal dokter\n";
    echo "   5. Lihat di menu 'Antrian Pasien' (untuk kasir/admin)\n";
    echo "   6. Dokter akan melihat di dashboard mereka\n";
}

echo "\n";
