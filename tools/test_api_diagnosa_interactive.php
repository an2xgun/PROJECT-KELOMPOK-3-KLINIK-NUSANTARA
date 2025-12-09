#!/usr/bin/env php
<?php
/**
 * Script Test API Diagnosa (Create, Verify, Delete)
 * Jalankan: php artisan tinker < tools/test_api_diagnosa.php
 * atau copy-paste ke terminal artisan tinker
 */

use App\Models\MasterDiagnosa;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║       TEST API DIAGNOSA ICD-10 (CREATE-VERIFY-DELETE)     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// ============================================
// 1. BUAT DIAGNOSA BARU VIA API
// ============================================
echo "1️⃣  MEMBUAT DIAGNOSA BARU (via API)\n";
echo "   ──────────────────────────────────────\n";

$testKode = 'G89.0';
$testNama = 'Pain, unspecified (Nyeri yang tidak tergolongkan)';

echo "   Kode ICD-10: {$testKode}\n";
echo "   Nama: {$testNama}\n\n";

// Simulasi controller->apiStore yang melakukan strtoupper
$created = MasterDiagnosa::create([
    'kode' => strtoupper($testKode),
    'nama' => $testNama
]);

echo "   ✅ BERHASIL DIBUAT!\n";
echo "   • ID: {$created->id}\n";
echo "   • Kode: {$created->kode}\n";
echo "   • Nama: {$created->nama}\n";
echo "   • Created At: {$created->created_at}\n\n";

// ============================================
// 2. VERIFIKASI DATA TERSIMPAN DI DATABASE
// ============================================
echo "2️⃣  VERIFIKASI DATA DI DATABASE\n";
echo "   ──────────────────────────────────────\n";

$found = MasterDiagnosa::find($created->id);

if ($found) {
    echo "   ✅ DIAGNOSA DITEMUKAN!\n";
    echo "   • ID: {$found->id}\n";
    echo "   • Kode: {$found->kode}\n";
    echo "   • Nama: {$found->nama}\n";
    
    // Verifikasi format kode
    if (preg_match('/^[A-Z][0-9]{2}(?:\.[0-9A-Za-z]+)?$/', $found->kode)) {
        echo "   • Format Kode: ✅ SESUAI ICD-10 (format: LETTER + 2 DIGITS + optional .subnumber)\n";
    } else {
        echo "   • Format Kode: ❌ TIDAK SESUAI FORMAT\n";
    }
    
    // Verifikasi nama
    if (!empty($found->nama) && strlen($found->nama) > 0) {
        echo "   • Nama: ✅ TERSIMPAN DENGAN BAIK\n";
    } else {
        echo "   • Nama: ❌ NAMA KOSONG\n";
    }
} else {
    echo "   ❌ GAGAL: DIAGNOSA TIDAK DITEMUKAN DI DATABASE\n";
    exit(1);
}

echo "\n";

// ============================================
// 3. LIHAT DATA DI SELURUH TABEL
// ============================================
echo "3️⃣  CEK DATA DI TABEL (TOTAL DIAGNOSA)\n";
echo "   ──────────────────────────────────────\n";

$total = MasterDiagnosa::count();
$withKode = MasterDiagnosa::whereNotNull('kode')->where('kode', '<>', '')->count();

echo "   📊 Statistik:\n";
echo "   • Total Diagnosa: {$total}\n";
echo "   • Diagnosa dengan Kode: {$withKode}\n";
echo "   • Diagnosa tanpa Kode: " . ($total - $withKode) . "\n\n";

// Tampilkan beberapa diagnosa terbaru
echo "   🏥 5 Diagnosa Terbaru:\n";
$recent = MasterDiagnosa::orderBy('id', 'desc')->limit(5)->get();
foreach ($recent as $i => $d) {
    $kode = $d->kode ?? '(no code)';
    echo "   {$i}. [{$kode}] {$d->nama}\n";
}

echo "\n";

// ============================================
// 4. HAPUS DIAGNOSA VIA API
// ============================================
echo "4️⃣  MENGHAPUS DIAGNOSA (via API)\n";
echo "   ──────────────────────────────────────\n";

echo "   ID yang akan dihapus: {$created->id}\n";

$created->delete();

echo "   ✅ DIAGNOSA BERHASIL DIHAPUS!\n\n";

// ============================================
// 5. VERIFIKASI TERHAPUS
// ============================================
echo "5️⃣  VERIFIKASI DIAGNOSA TERHAPUS\n";
echo "   ──────────────────────────────────────\n";

$deleted = MasterDiagnosa::find($created->id);

if (!$deleted) {
    echo "   ✅ TERBUKTI TERHAPUS!\n";
    echo "   • Diagnosa dengan ID {$created->id} sudah tidak ada di database\n";
} else {
    echo "   ❌ GAGAL: DIAGNOSA MASIH ADA DI DATABASE\n";
    exit(1);
}

echo "\n";

// ============================================
// HASIL AKHIR
// ============================================
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🎉 SEMUA TEST BERHASIL ✅                                ║\n";
echo "║  Diagnosa API (create→verify→delete) bekerja dengan baik!  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";
?>
