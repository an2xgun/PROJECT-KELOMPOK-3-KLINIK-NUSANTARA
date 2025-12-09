<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;

echo "=== FIX: AUTO-REGISTER SEMUA PASIEN YANG BELUM ADA PENDAFTARAN ===\n\n";

// 1. Get all pasien without registration
echo "1. MENCARI PASIEN TANPA PENDAFTARAN:\n";
$pasienTanpaPendaftaran = Pasien::doesntHave('pendaftaran')->get();

if (count($pasienTanpaPendaftaran) === 0) {
    echo "   ✓ Semua pasien sudah punya pendaftaran!\n";
} else {
    echo "   Ditemukan " . count($pasienTanpaPendaftaran) . " pasien tanpa pendaftaran:\n";
    
    // Get first poli for default
    $poli = Poliklinik::first();
    if (!$poli) {
        echo "   ✗ GAGAL: Tidak ada poliklinik! Buat poliklinik dulu.\n";
        exit(1);
    }
    
    $today = now()->toDateString();
    
    foreach ($pasienTanpaPendaftaran as $pasien) {
        echo "\n   Membuat pendaftaran untuk: {$pasien->no_rm} | {$pasien->nama}\n";
        
        // Count untuk nomor antrian (hari ini)
        $count = Pendaftaran::whereDate('created_at', $today)->count();
        $nomorAntrian = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        try {
            $pendaftaran = Pendaftaran::create([
                'pasien_id' => $pasien->id,
                'poliklinik_id' => $poli->id,
                'jadwal_poli_id' => 1,
                'nomor_antrian' => $nomorAntrian,
                'keluhan' => 'Umum',
                'jenis_pembayaran' => 'Umum',
                'tanggal_kunjungan' => now()->toDateString(),
                'status_layanan' => 'Menunggu'
            ]);
            
            echo "      ✓ Pendaftaran berhasil: ID={$pendaftaran->id}, Antrian={$nomorAntrian}\n";
        } catch (\Exception $e) {
            echo "      ✗ Error: {$e->getMessage()}\n";
        }
    }
}

echo "\n2. VERIFIKASI HASIL:\n";
$today = now()->toDateString();
$pendaftarans = Pendaftaran::whereDate('created_at', $today)
    ->with('pasien', 'poliklinik')
    ->orderBy('nomor_antrian', 'ASC')
    ->get();

echo "   Total pendaftaran hari ini: " . count($pendaftarans) . "\n";
foreach ($pendaftarans as $p) {
    echo "   - Antrian: {$p->nomor_antrian} | No RM: {$p->pasien->no_rm} | {$p->pasien->nama} | Status: {$p->status_layanan}\n";
}

echo "\n=== SELESAI ===\n";
echo "\nKasir dapat langsung membuka:\n";
echo "  → Dashboard → Pembayaran → Antrian Pasien\n";
echo "  → Atau akses langsung: http://localhost:8000/pendaftaran/antrian/list\n";
?>
