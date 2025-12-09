<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';

use App\Models\JadwalPoli;
use App\Models\Poliklinik;

// Simulasi apa yang dilakukan controller
$polikliniks = Poliklinik::all();

// Ambil jadwal per poliklinik sebagai fallback server-side
$jadwals = JadwalPoli::with('dokter')->get()->groupBy('poliklinik_id')->map(function($group) {
    return $group->map(function($j) {
        return [
            'id' => $j->id,
            'hari' => $j->hari,
            'jam_mulai' => $j->jam_mulai,
            'jam_selesai' => $j->jam_selesai,
            'dokter' => [
                'id' => isset($j->dokter) ? $j->dokter->id : null,
                'nama' => isset($j->dokter) ? $j->dokter->nama : 'Unknown',
            ],
        ];
    })->values();
});

echo "=== Data yang akan disisipkan ke view ===\n\n";
echo "Jadwals structure:\n";
echo json_encode($jadwals, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

echo "\n\n=== JavaScript yang akan di-render ===\n";
echo "window.serverJadwals = " . json_encode($jadwals, JSON_UNESCAPED_UNICODE) . ";\n";
?>
