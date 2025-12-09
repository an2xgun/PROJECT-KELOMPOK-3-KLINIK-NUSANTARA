<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JadwalPoli;

$jadwals = JadwalPoli::with('dokter','poliklinik')->get();
foreach($jadwals as $j){
    echo "Jadwal id={$j->id} poli_id={$j->poliklinik_id} dokter_id={$j->dokter_id} hari={$j->hari} dokter.nama=" . (isset($j->dokter) ? $j->dokter->nama : 'NULL') . "\n";
}
