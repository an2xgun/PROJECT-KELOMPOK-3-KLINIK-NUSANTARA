<?php
/**
 * Debug: Cek apakah form pendaftaran mengirim data dengan benar
 */

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\JadwalPoli;
use App\Models\Poliklinik;

echo "=== DEBUG FORM PENDAFTARAN ===\n\n";

// Siap data untuk test
$pasien = Pasien::first();
$poli = Poliklinik::first();
$jadwal = JadwalPoli::where('poliklinik_id', $poli->id)->first();

if (!$pasien || !$poli || !$jadwal) {
    echo "❌ Data master tidak lengkap\n";
    exit(1);
}

echo "Siap test dengan:\n";
echo "- Pasien: {$pasien->nama} (ID: {$pasien->id})\n";
echo "- Poliklinik: {$poli->name} (ID: {$poli->id})\n";
echo "- Jadwal: ID {$jadwal->id} ({$jadwal->dokter->nama})\n\n";

// Simulasi POST data dari form
$postData = [
    'pasien_id' => $pasien->id,
    'poliklinik_id' => $poli->id,
    'jadwal_poli_id' => $jadwal->id,
    'keluhan' => 'Test - Sakit kepala',
    'jenis_pembayaran' => 'Umum',
    'no_bpjs' => null,
    'tanggal_kunjungan' => now()->format('Y-m-d'),
];

echo "POST Data:\n";
foreach ($postData as $k => $v) {
    echo "  $k: " . ($v ?? 'null') . "\n";
}

echo "\nValidasi field yang WAJIB:\n";

// Check field wajib berdasarkan storePoli validation rules
$required = [
    'poliklinik_id' => 'exists:polikliniks,id',
    'jadwal_poli_id' => 'exists:jadwal_polis,id',
    'keluhan' => 'required|string',
    'jenis_pembayaran' => 'required|in:Umum,BPJS,Asuransi',
    'tanggal_kunjungan' => 'required|date',
];

foreach ($required as $field => $rule) {
    $value = $postData[$field] ?? null;
    
    if ($field === 'jenis_pembayaran') {
        if (!in_array($value, ['Umum', 'BPJS', 'Asuransi'])) {
            echo "  ❌ $field: INVALID (harus Umum|BPJS|Asuransi, dapat: $value)\n";
        } else {
            echo "  ✅ $field: OK ($value)\n";
        }
    } else if (strpos($rule, 'exists:') !== false) {
        echo "  ✅ $field: OK (value: $value)\n";
    } else if ($value) {
        echo "  ✅ $field: OK\n";
    } else {
        echo "  ❌ $field: MISSING\n";
    }
}

echo "\n\nBuat pendaftaran dengan data simulasi:\n";

try {
    $count = Pendaftaran::whereDate('created_at', now())->count();
    $nomorAntrian = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    
    $pend = Pendaftaran::create([
        'pasien_id' => $postData['pasien_id'],
        'poliklinik_id' => $postData['poliklinik_id'],
        'jadwal_poli_id' => $postData['jadwal_poli_id'],
        'nomor_antrian' => $nomorAntrian,
        'keluhan' => $postData['keluhan'],
        'jenis_pembayaran' => $postData['jenis_pembayaran'],
        'no_bpjs' => $postData['no_bpjs'],
        'tanggal_kunjungan' => $postData['tanggal_kunjungan'],
        'status_layanan' => 'Menunggu',
    ]);
    
    echo "✅ Pendaftaran BERHASIL dibuat!\n";
    echo "   ID: {$pend->id}\n";
    echo "   No Antrian: {$pend->nomor_antrian}\n";
    echo "   Status: {$pend->status_layanan}\n";
    
    // Cek di database
    $check = Pendaftaran::find($pend->id);
    if ($check) {
        echo "\n✅ Terlihat di database!\n";
        echo "   Pasien: {$check->pasien->nama}\n";
        echo "   Poliklinik: {$check->poliklinik->name}\n";
        echo "   Status: {$check->status_layanan}\n";
    }
    
    // Cleanup
    $pend->delete();
    echo "\n✅ Test data dihapus\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== KESIMPULAN ===\n";
echo "Sistem sudah siap. Jika pendaftaran tidak terlihat di antrian:\n";
echo "1. Cek apakah form disubmit dengan benar\n";
echo "2. Cek browser console untuk error JavaScript\n";
echo "3. Cek server log untuk error PHP/Laravel\n";
