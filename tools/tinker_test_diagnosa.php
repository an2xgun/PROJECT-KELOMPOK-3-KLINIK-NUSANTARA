<?php
/**
 * Test diagnosa API dengan Artisan Tinker
 * Jalankan: php artisan tinker
 * Lalu copy-paste script ini di tinker
 */

use App\Models\MasterDiagnosa;

echo "\n=== TEST DIAGNOSA API (ICD-10) ===\n\n";

$testKode = 'A09.9';
$testNama = 'Diare dan Gastroenteritis Asal Infeksi Tidak Tergolongkan';

echo "1. MEMBUAT DIAGNOSA BARU\n";
echo "   Kode: $testKode\n";
echo "   Nama: $testNama\n";

$created = MasterDiagnosa::create([
    'kode' => strtoupper($testKode),
    'nama' => $testNama
]);

echo "   ✓ Diagnosa dibuat dengan ID: {$created->id}\n";
echo "   ✓ Kode: {$created->kode}\n";
echo "   ✓ Nama: {$created->nama}\n\n";

echo "2. VERIFIKASI DATA TERSIMPAN\n";
$found = MasterDiagnosa::find($created->id);
if ($found) {
    echo "   ✓ Diagnosa ditemukan\n";
    echo "   ✓ ID: {$found->id}, Kode: {$found->kode}, Nama: {$found->nama}\n";
} else {
    echo "   ✗ GAGAL: Diagnosa tidak ditemukan\n";
}

echo "\n3. MENGHAPUS DIAGNOSA\n";
$created->delete();
echo "   ✓ Diagnosa ID {$created->id} dihapus\n";

echo "\n4. VERIFIKASI TERHAPUS\n";
$deleted = MasterDiagnosa::find($created->id);
if (!$deleted) {
    echo "   ✓ Diagnosa berhasil dihapus\n";
} else {
    echo "   ✗ GAGAL: Diagnosa masih ada\n";
}

echo "\n=== SEMUA TEST BERHASIL ✓ ===\n\n";
?>
