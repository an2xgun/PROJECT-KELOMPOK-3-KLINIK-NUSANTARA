<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CEK STRUKTUR TABEL PENDAFTARAN ===\n";
$columns = DB::select('DESCRIBE pendaftaran');
echo "Kolom-kolom yang ada:\n";
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type}) - Null: {$col->Null}, Default: {$col->Default}\n";
}

echo "\n=== CEK DATA PENDAFTARAN ===\n";
$pendaftaran = DB::table('pendaftaran')->count();
echo "Total pendaftaran: $pendaftaran\n";

echo "\n=== CEK STRUKTUR TABEL PASIENS ===\n";
$columns = DB::select('DESCRIBE pasiens');
echo "Kolom-kolom yang ada:\n";
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}

echo "\n=== CEK RELASI PASIEN -> PENDAFTARAN ===\n";
$lastPasien = DB::table('pasiens')->orderBy('id', 'DESC')->first();
if ($lastPasien) {
    echo "Pasien terbaru:\n";
    echo "  - ID: {$lastPasien->id}\n";
    echo "  - No RM: {$lastPasien->no_rm}\n";
    echo "  - Nama: {$lastPasien->nama}\n";
    
    $pendaftaranCount = DB::table('pendaftaran')->where('pasien_id', $lastPasien->id)->count();
    echo "  - Jumlah pendaftaran: $pendaftaranCount\n";
}

echo "\n=== CEK TABEL REKAMS ===\n";
$columns = DB::select('DESCRIBE rekams');
echo "Kolom-kolom yang ada:\n";
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}

echo "\n=== CEK DATA REKAMS ===\n";
$rekamCount = DB::table('rekams')->count();
echo "Total rekam medis: $rekamCount\n";

echo "\n=== CEK TABEL INVOICES ===\n";
$columns = DB::select('DESCRIBE invoices');
echo "Kolom-kolom yang ada:\n";
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}

echo "\n=== CEK DATA INVOICES ===\n";
$invoiceCount = DB::table('invoices')->count();
echo "Total invoices: $invoiceCount\n";

echo "\n=== ALUR INTEGRASI YANG DIHARAPKAN ===\n";
echo "1. Pasien baru didaftar -> simpan ke tabel pasiens\n";
echo "2. Pasien memilih poli -> simpan ke tabel pendaftaran + buat rekam medis\n";
echo "3. Dokter periksa pasien -> update rekam medis dengan hasil pemeriksaan\n";
echo "4. Kasir proses pembayaran -> buat invoice\n";
echo "5. Kasir mark as paid -> update invoice status + buat payment record\n";

echo "\n";
?>
