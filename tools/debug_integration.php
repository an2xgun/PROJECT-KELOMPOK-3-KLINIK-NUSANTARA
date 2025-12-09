<?php
// Bootstrap Laravel
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Rekam;
use Illuminate\Support\Facades\DB;

echo "=== DEBUG INTEGRASI SISTEM ===\n\n";

// 1. Check database
echo "1. CEK DATA PASIEN:\n";
$pasienCount = DB::table('pasiens')->count();
echo "Total pasien di database: " . $pasienCount . "\n";

$recentPasien = DB::table('pasiens')->orderBy('id', 'DESC')->limit(3)->get(['id', 'no_rm', 'nama', 'nik', 'created_at']);
echo "3 Pasien terbaru:\n";
foreach ($recentPasien as $p) {
    echo "  - ID: {$p->id}, No RM: {$p->no_rm}, Nama: {$p->nama}, Dibuat: {$p->created_at}\n";
}

// 2. Check pendaftaran
echo "\n2. CEK DATA PENDAFTARAN:\n";
$pendaftaranCount = DB::table('pendaftarans')->count();
echo "Total pendaftaran di database: " . $pendaftaranCount . "\n";

$recentPendaftaran = DB::table('pendaftarans')
    ->join('pasiens', 'pendaftarans.pasien_id', '=', 'pasiens.id')
    ->orderBy('pendaftarans.id', 'DESC')
    ->limit(3)
    ->get(['pendaftarans.id', 'pasiens.no_rm', 'pasiens.nama', 'pendaftarans.nomor_antrian', 'pendaftarans.status_layanan', 'pendaftarans.created_at']);

echo "3 Pendaftaran terbaru:\n";
foreach ($recentPendaftaran as $p) {
    echo "  - ID: {$p->id}, No RM: {$p->no_rm}, Nama: {$p->nama}, Antrian: {$p->nomor_antrian}, Status: {$p->status_layanan}, Dibuat: {$p->created_at}\n";
}

// 3. Check relasi pasien -> pendaftaran
echo "\n3. CEK RELASI PASIEN -> PENDAFTARAN:\n";
$pasienDenganPendaftaran = Pasien::has('pendaftaran')->count();
$pasienTanpaPendaftaran = Pasien::doesntHave('pendaftaran')->count();
echo "Pasien dengan pendaftaran: " . $pasienDenganPendaftaran . "\n";
echo "Pasien tanpa pendaftaran: " . $pasienTanpaPendaftaran . "\n";

// 4. Check status_layanan values
echo "\n4. CEK STATUS LAYANAN:\n";
$statusCounts = DB::table('pendaftarans')
    ->groupBy('status_layanan')
    ->selectRaw('status_layanan, COUNT(*) as count')
    ->get();
foreach ($statusCounts as $s) {
    echo "  - {$s->status_layanan}: {$s->count} pendaftaran\n";
}

// 5. Check rekam medis
echo "\n5. CEK DATA REKAM MEDIS:\n";
$rekamCount = DB::table('rekams')->count();
echo "Total rekam medis di database: " . $rekamCount . "\n";

$recentRekam = DB::table('rekams')
    ->join('pasiens', 'rekams.pasien_id', '=', 'pasiens.id')
    ->orderBy('rekams.id', 'DESC')
    ->limit(3)
    ->get(['rekams.id', 'pasiens.no_rm', 'pasiens.nama', 'rekams.status', 'rekams.created_at']);

echo "3 Rekam Medis terbaru:\n";
foreach ($recentRekam as $r) {
    echo "  - ID: {$r->id}, No RM: {$r->no_rm}, Nama: {$r->nama}, Status: {$r->status}, Dibuat: {$r->created_at}\n";
}

// 6. Check invoices
echo "\n6. CEK DATA INVOICE:\n";
$invoiceCount = DB::table('invoices')->count();
echo "Total invoice di database: " . $invoiceCount . "\n";

$recentInvoice = DB::table('invoices')
    ->join('pasiens', 'invoices.pasien_id', '=', 'pasiens.id')
    ->orderBy('invoices.id', 'DESC')
    ->limit(3)
    ->get(['invoices.id', 'pasiens.no_rm', 'pasiens.nama', 'invoices.status', 'invoices.total', 'invoices.created_at']);

echo "3 Invoice terbaru:\n";
foreach ($recentInvoice as $i) {
    echo "  - ID: {$i->id}, No RM: {$i->no_rm}, Nama: {$i->nama}, Status: {$i->status}, Total: {$i->total}, Dibuat: {$i->created_at}\n";
}

echo "\n=== END DEBUG ===\n";
?>
