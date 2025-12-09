<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\JadwalPoli;
use App\Models\Rekam;

function usage() {
    echo "Usage: php simulate_post_pendaftaran.php <pasien_id> <id_poli> <jadwal_poli_id> <keluhan> <jenis_pembayaran> <tanggal_kunjungan>\n";
    echo "Example: php simulate_post_pendaftaran.php 4 1 2 \"Sakit kepala\" Umum 2025-12-07\n";
}

if ($argc < 7) {
    usage();
    exit(1);
}

[$script, $pasienId, $idPoli, $jadwalPoliId, $keluhan, $jenisPembayaran, $tanggalKunjungan] = $argv;

$pasien = Pasien::find($pasienId);
if (!$pasien) {
    echo "Error: Pasien with id={$pasienId} not found\n";
    exit(1);
}

$poli = Poliklinik::find($idPoli);
if (!$poli) {
    echo "Error: Poliklinik with id={$idPoli} not found\n";
    exit(1);
}

$jadwal = JadwalPoli::find($jadwalPoliId);
if (!$jadwal) {
    echo "Error: JadwalPoli with id={$jadwalPoliId} not found\n";
    exit(1);
}

// Generate nomor antrian similar to controller
$count = Pendaftaran::where('id_poli', $idPoli)
    ->whereDate('created_at', now())
    ->count();
$nomorAntrian = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

$pendaftaran = Pendaftaran::create([
    'id_pasien' => $pasien->id,
    'id_poli' => $idPoli,
    'jadwal_poli_id' => $jadwalPoliId,
    'nomor_antrian' => $nomorAntrian,
    'keluhan' => $keluhan,
    'jenis_pembayaran' => $jenisPembayaran,
    'tanggal_kunjungan' => $tanggalKunjungan,
    'status_layanan' => 'Menunggu',
]);

$rekam = Rekam::create([
    'laporan' => 0,
    'id_pasien' => $pasien->id,
    'nomorantrian' => $nomorAntrian,
    'tanggalperiksa' => $tanggalKunjungan,
    'layanan' => $poli ? ($poli->name ?? null) : null,
    'keluhan' => $keluhan,
    'id_dokter' => $jadwal->dokter_id ?? null,
]);

echo "Pendaftaran created: id={$pendaftaran->id}, nomor_antrian={$pendaftaran->nomor_antrian}, jadwal_poli_id={$pendaftaran->jadwal_poli_id}\n";
echo "Rekam created: id={$rekam->id}, nomorantrian={$rekam->nomorantrian}\n";

// Show inserted row for verification
$inserted = Pendaftaran::with('pasien','poliklinik','jadwalPoli.dokter')->find($pendaftaran->id);
if ($inserted) {
    echo "\nInserted Pendaftaran record:\n";
    echo json_encode([
        'id' => $inserted->id,
        'id_pasien' => $inserted->id_pasien,
        'pasien_nama' => isset($inserted->pasien) ? $inserted->pasien->nama : null,
        'id_poli' => $inserted->id_poli,
        'poli_name' => isset($inserted->poliklinik) ? $inserted->poliklinik->name : null,
        'jadwal_poli_id' => $inserted->jadwal_poli_id,
        'jadwal' => $inserted->jadwalPoli ? [
            'id' => isset($inserted->jadwalPoli) ? $inserted->jadwalPoli->id : null,
            'hari' => isset($inserted->jadwalPoli) ? $inserted->jadwalPoli->hari : null,
            'jam_mulai' => isset($inserted->jadwalPoli) ? $inserted->jadwalPoli->jam_mulai : null,
            'jam_selesai' => isset($inserted->jadwalPoli) ? $inserted->jadwalPoli->jam_selesai : null,
            'dokter' => (isset($inserted->jadwalPoli) && isset($inserted->jadwalPoli->dokter)) ? [
                'id' => $inserted->jadwalPoli->dokter->id,
                'nama' => $inserted->jadwalPoli->dokter->nama,
            ] : null,
        ] : null,
        'nomor_antrian' => $inserted->nomor_antrian,
        'keluhan' => $inserted->keluhan,
        'jenis_pembayaran' => $inserted->jenis_pembayaran,
        'tanggal_kunjungan' => $inserted->tanggal_kunjungan,
        'status_layanan' => $inserted->status_layanan,
        'created_at' => $inserted->created_at,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

exit(0);
