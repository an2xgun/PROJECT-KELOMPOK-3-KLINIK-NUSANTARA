<?php
/**
 * Test script untuk API diagnosa (create, verify, delete)
 * Gunakan: php tools/test_diagnosa_api.php
 */

require __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\MasterDiagnosa;

// ============================================
// 1. TEST: Buat diagnosa via API (simulasi)
// ============================================
echo "=== TEST DIAGNOSA API ===\n\n";

$testKode = 'A09.9';
$testNama = 'Diare dan Gastroenteritis Asal Infeksi Tidak Tergolongkan';

echo "1. MEMBUAT DIAGNOSA BARU\n";
echo "   Kode: $testKode\n";
echo "   Nama: $testNama\n";

// Simulasi create (seperti API POST /api/diagnosa)
$created = MasterDiagnosa::create([
    'kode' => strtoupper($testKode),
    'nama' => $testNama
]);

echo "   ✓ Diagnosa dibuat dengan ID: {$created->id}\n";
echo "   ✓ Kode: {$created->kode}\n";
echo "   ✓ Nama: {$created->nama}\n\n";

// ============================================
// 2. TEST: Verifikasi data tersimpan
// ============================================
echo "2. VERIFIKASI DATA TERSIMPAN\n";

$found = MasterDiagnosa::find($created->id);

if ($found) {
    echo "   ✓ Diagnosa ditemukan di database\n";
    echo "   ✓ ID: {$found->id}\n";
    echo "   ✓ Kode: {$found->kode}\n";
    echo "   ✓ Nama: {$found->nama}\n";
    
    // Verifikasi kode sesuai format
    if (preg_match('/^[A-Z][0-9]{2}(?:\.[0-9A-Za-z]+)?$/', $found->kode)) {
        echo "   ✓ Kode sesuai format ICD-10\n";
    } else {
        echo "   ✗ Kode TIDAK sesuai format ICD-10\n";
    }
} else {
    echo "   ✗ Diagnosa TIDAK ditemukan di database\n";
    exit(1);
}

echo "\n";

// ============================================
// 3. TEST: Hapus diagnosa via API
// ============================================
echo "3. MENGHAPUS DIAGNOSA\n";
echo "   ID: {$created->id}\n";

$created->delete();
echo "   ✓ Diagnosa dihapus\n\n";

// ============================================
// 4. TEST: Verifikasi sudah terhapus
// ============================================
echo "4. VERIFIKASI TERHAPUS\n";

$deleted = MasterDiagnosa::find($created->id);

if (!$deleted) {
    echo "   ✓ Diagnosa berhasil dihapus dari database\n";
} else {
    echo "   ✗ Diagnosa MASIH ada di database\n";
    exit(1);
}

echo "\n";
echo "=== SEMUA TEST BERHASIL ✓ ===\n";
?>
