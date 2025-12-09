<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pendaftaran;

$id = $argv[1] ?? 7;
try {
    $pendaftaran = Pendaftaran::with(['pasien','poliklinik','jadwalPoli.dokter'])->findOrFail($id);
    echo "Found pendaftaran id={$pendaftaran->id}\n";
    echo "Poli: " . (isset($pendaftaran->poliklinik) ? $pendaftaran->poliklinik->name : 'n/a') . "\n";
    echo "Jadwal dokter: " . (isset($pendaftaran->jadwalPoli) && isset($pendaftaran->jadwalPoli->dokter) ? $pendaftaran->jadwalPoli->dokter->nama : 'n/a') . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
