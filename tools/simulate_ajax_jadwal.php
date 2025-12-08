<?php
/**
 * Simulasi endpoint /ajax/jadwal-by-poli/{poliId}
 */
$poliId = 1; // Simulasi request untuk poliklinik_id = 1

$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

$jadwals = $db->prepare("
    SELECT jp.id, jp.poliklinik_id, jp.dokter_id, jp.hari, jp.jam_mulai, jp.jam_selesai, d.nama as dokter_nama, d.id as did
    FROM jadwal_polis jp
    LEFT JOIN dokters d ON jp.dokter_id = d.id
    WHERE jp.poliklinik_id = ?
");
$jadwals->execute([$poliId]);
$data = $jadwals->fetchAll(PDO::FETCH_ASSOC);

echo "Jadwal untuk poliklinik_id = {$poliId}:\n";
echo "Jumlah hasil: " . count($data) . "\n\n";

// Transformasi seperti AjaxController
$result = array_map(function($j) {
    return [
        'id' => (int)$j['id'],
        'hari' => $j['hari'],
        'jam_mulai' => $j['jam_mulai'],
        'jam_selesai' => $j['jam_selesai'],
        'dokter' => [
            'id' => (int)$j['did'],
            'nama' => $j['dokter_nama'] ?? 'Unknown',
        ]
    ];
}, $data);

echo "JSON Response:\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
