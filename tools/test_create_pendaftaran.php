<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Models\Pendaftaran;

// get first pasien
$pasien = Pasien::first();
if (!$pasien) {
    $pasien = Pasien::create([
        'no_rm' => Pasien::generateNextNoRm(),
        'nama' => 'Test Pasien',
        'nik' => '0000000000000000',
        'lahir' => '1990-01-01',
        'kelamin' => 'Laki-laki',
        'telepon' => '000',
        'agama' => 'Islam',
        'pendidikan' => 'S1',
        'pekerjaan' => 'Tester',
        'golongan_darah' => 'O',
        'jenis_pasien' => 'Umum',
    ]);
}

$pendaftaran = Pendaftaran::create([
    'pasien_id' => $pasien->id,
    'poliklinik_id' => 1,
    'jadwal_poli_id' => 1,
    'nomor_antrian' => '999',
    'keluhan' => 'Test',
    'jenis_pembayaran' => 'Umum',
    'tanggal_kunjungan' => date('Y-m-d'),
    'status_layanan' => 'Menunggu'
]);

echo "Created pendaftaran id={$pendaftaran->id} for pasien_id={$pendaftaran->pasien_id}\n";
