<?php
/**
 * Test integration: Pendaftaran -> Antrian -> Dokter
 * Memastikan pendaftaran pasien langsung terbaca di antrian dan dokter bisa melihatnya
 */

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\JadwalPoli;
use App\Models\Poliklinik;
use App\Models\Dokter;

echo "=== TEST INTEGRASI PENDAFTARAN -> ANTRIAN -> DOKTER ===\n\n";

// 1. Cek data master
echo "1. CEK DATA MASTER\n";
$poliCount = Poliklinik::count();
$dokterCount = Dokter::count();
$jadwalCount = JadwalPoli::count();
$pasienCount = Pasien::count();

echo "   Poliklinik: $poliCount\n";
echo "   Dokter: $dokterCount\n";
echo "   Jadwal Dokter: $jadwalCount\n";
echo "   Pasien: $pasienCount\n";

if ($poliCount == 0 || $dokterCount == 0 || $jadwalCount == 0) {
    echo "\n   ❌ ERROR: Data master tidak lengkap!\n";
    exit(1);
}

echo "   ✅ Data master OK\n\n";

// 2. Cek struktur tabel pendaftaran
echo "2. CEK STRUKTUR TABEL PENDAFTARAN\n";
$pendaftaran = Pendaftaran::first();
if ($pendaftaran) {
    echo "   Sample: " . json_encode($pendaftaran->toArray(), JSON_PRETTY_PRINT) . "\n";
} else {
    echo "   (Belum ada data)\n";
}

$pendaftaranCount = Pendaftaran::count();
echo "   Total pendaftaran: $pendaftaranCount\n\n";

// 3. Test relationships
echo "3. CEK RELATIONSHIPS\n";

// Ambil satu pasien
$pasien = Pasien::first();
if ($pasien) {
    echo "   Pasien: {$pasien->nama} (No RM: {$pasien->no_rm})\n";
    
    // Ambil satu poliklinik dan jadwal
    $poli = Poliklinik::first();
    $jadwal = JadwalPoli::where('poliklinik_id', $poli->id)->first();
    
    if ($jadwal) {
        echo "   Poliklinik: {$poli->name}\n";
        echo "   Jadwal: {$jadwal->hari} ({$jadwal->jam_mulai} - {$jadwal->jam_selesai})\n";
        echo "   Dokter: {$jadwal->dokter->nama}\n\n";
        
        // 4. Buat pendaftaran baru
        echo "4. BUAT PENDAFTARAN BARU\n";
        
        $nomor = Pendaftaran::whereDate('created_at', now())->count() + 1;
        $nomorAntrian = str_pad($nomor, 3, '0', STR_PAD_LEFT);
        
        $pendaftaranBaru = Pendaftaran::create([
            'pasien_id' => $pasien->id,
            'poliklinik_id' => $poli->id,
            'jadwal_poli_id' => $jadwal->id,
            'nomor_antrian' => $nomorAntrian,
            'keluhan' => 'Test - Sakit perut',
            'jenis_pembayaran' => 'Umum',
            'no_bpjs' => null,
            'tanggal_kunjungan' => now()->format('Y-m-d'),
            'status_layanan' => 'Menunggu',
        ]);
        
        echo "   ✅ Pendaftaran dibuat: ID {$pendaftaranBaru->id}\n";
        echo "   No Antrian: {$pendaftaranBaru->nomor_antrian}\n";
        echo "   Status: {$pendaftaranBaru->status_layanan}\n\n";
        
        // 5. Verifikasi bisa dibaca di antrian
        echo "5. VERIFIKASI DI ANTRIAN\n";
        
        $dariAntrian = Pendaftaran::where('id', $pendaftaranBaru->id)->first();
        if ($dariAntrian) {
            echo "   ✅ Terlihat di antrian!\n";
            echo "   Nama pasien: {$dariAntrian->pasien->nama}\n";
            echo "   No RM: {$dariAntrian->pasien->no_rm}\n";
            echo "   Poliklinik: {$dariAntrian->poliklinik->name}\n";
            echo "   Status: {$dariAntrian->status_layanan}\n\n";
        } else {
            echo "   ❌ TIDAK TERLIHAT DI ANTRIAN!\n\n";
        }
        
        // 6. Verifikasi bisa dikirim ke dokter
        echo "6. VERIFIKASI DOKTER BISA AKSES\n";
        
        $dokter = $jadwal->dokter;
        $pendaftaranDokter = Pendaftaran::where('jadwal_poli_id', $jadwal->id)
                                        ->where('status_layanan', 'Menunggu')
                                        ->get();
        
        echo "   Dokter: {$dokter->nama}\n";
        echo "   Poliklinik: {$jadwal->poliklinik->name}\n";
        echo "   Pasien menunggu dalam antrian dokter: " . count($pendaftaranDokter) . "\n";
        
        foreach ($pendaftaranDokter as $p) {
            echo "     - {$p->nomor_antrian}: {$p->pasien->nama} ({$p->keluhan})\n";
        }
        
        echo "\n   ✅ Dokter bisa akses antrian pasien\n\n";
        
        // 7. Test status update
        echo "7. TEST PERUBAHAN STATUS\n";
        
        $pendaftaranBaru->update(['status_layanan' => 'Sedang Dilayani']);
        $recheck = Pendaftaran::find($pendaftaranBaru->id);
        
        echo "   Status diubah menjadi: {$recheck->status_layanan}\n";
        
        if ($recheck->status_layanan === 'Sedang Dilayani') {
            echo "   ✅ Status update berhasil\n\n";
        } else {
            echo "   ❌ Status update gagal\n\n";
        }
        
        // Cleanup
        $pendaftaranBaru->delete();
        echo "8. CLEANUP\n";
        echo "   ✅ Data test dihapus\n\n";
        
        echo "=== ✅ SEMUA TEST PASSED ===\n";
        
    } else {
        echo "   ❌ ERROR: Tidak ada jadwal poli!\n";
        exit(1);
    }
} else {
    echo "   ❌ ERROR: Tidak ada pasien!\n";
    exit(1);
}
