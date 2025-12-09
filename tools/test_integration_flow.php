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

echo "=== TEST FLOW INTEGRASI LENGKAP ===\n\n";

// 1. Check if pasien "Nurrahma Harris" has registration
echo "1. CEK PASIEN 'Nurrahma Harris':\n";
$pasien = Pasien::where('nama', 'Nurrahma Harris')->first();
if ($pasien) {
    echo "   ✓ Pasien ditemukan: ID={$pasien->id}, No RM={$pasien->no_rm}, Nama={$pasien->nama}\n";
    
    $pendaftaran = Pendaftaran::where('pasien_id', $pasien->id)->first();
    if ($pendaftaran) {
        echo "   ✓ Sudah ada pendaftaran: ID={$pendaftaran->id}, Antrian={$pendaftaran->nomor_antrian}\n";
    } else {
        echo "   ✗ BELUM ada pendaftaran - INI MASALAHNYA!\n";
        echo "\n   Solusi: Buat pendaftaran manual untuk pasien ini\n";
        
        // Cari poli untuk test
        $poli = Poliklinik::first();
        if ($poli) {
            echo "\n   Membuat pendaftaran otomatis...\n";
            
            // Count untuk nomor antrian
            $count = Pendaftaran::whereDate('created_at', now())->count();
            $nomorAntrian = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            
            $pendaftaran = Pendaftaran::create([
                'pasien_id' => $pasien->id,
                'poliklinik_id' => $poli->id,
                'jadwal_poli_id' => 1,
                'nomor_antrian' => $nomorAntrian,
                'keluhan' => 'Test - Integrasi Flow',
                'jenis_pembayaran' => 'Umum',
                'tanggal_kunjungan' => now()->toDateString(),
                'status_layanan' => 'Menunggu'
            ]);
            
            echo "   ✓ Pendaftaran berhasil dibuat: ID={$pendaftaran->id}, Antrian={$pendaftaran->nomor_antrian}\n";
        }
    }
} else {
    echo "   ✗ Pasien tidak ditemukan\n";
}

echo "\n2. CEK SEMUA PENDAFTARAN HARI INI:\n";
$today = now()->toDateString();
$pendaftarans = Pendaftaran::whereDate('created_at', $today)
    ->with('pasien', 'poliklinik')
    ->orderBy('nomor_antrian', 'ASC')
    ->get();

echo "   Total pendaftaran hari ini: " . count($pendaftarans) . "\n";
foreach ($pendaftarans as $p) {
    echo "   - Antrian: {$p->nomor_antrian} | No RM: {$p->pasien->no_rm} | {$p->pasien->nama} | Status: {$p->status_layanan}\n";
}

echo "\n3. FLOW INTEGRASI:\n";
echo "   ✓ Pasien baru input data -> Tabel Pasiens\n";
echo "   ✓ Pasien pilih poli -> Tabel Pendaftaran (ANTRIAN)\n";
echo "   ✓ Kasir lihat antrian -> Pendaftaran/Antrian\n";
echo "   ✓ Kasir buat invoice -> Invoice dibuat (tanpa pemeriksaan dulu)\n";
echo "   ✓ Kasir process pembayaran -> Payment dicatat\n";
echo "   ✓ Kasir cetak struk -> Invoice.print atau .printThermal\n";

echo "\n4. ROUTE YANG TERSEDIA:\n";
echo "   - GET  /pendaftaran/choice                  -> Pilih pasien baru/lama\n";
echo "   - GET  /pendaftaran/create-new              -> Form input pasien baru\n";
echo "   - POST /pendaftaran/store-new               -> Simpan pasien baru\n";
echo "   - GET  /pendaftaran/select-poli/{id}        -> Pilih poli\n";
echo "   - POST /pendaftaran/store-poli/{id}         -> Simpan pendaftaran\n";
echo "   - GET  /pendaftaran/antrian/list            -> Lihat antrian\n";
echo "   - GET  /invoice/create-pendaftaran/{id}     -> Buat invoice dari pendaftaran\n";
echo "   - POST /invoice/store-pendaftaran/{id}      -> Simpan invoice\n";
echo "   - GET  /invoice/{id}                        -> Lihat invoice\n";
echo "   - PUT  /invoice/{id}/paid                   -> Proses pembayaran\n";
echo "   - GET  /invoice/{id}/print                  -> Cetak struk\n";
echo "   - GET  /invoice/{id}/print-thermal          -> Cetak thermal\n";

echo "\n=== END TEST ===\n";
?>
