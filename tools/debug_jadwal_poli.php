<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== Test Jadwal Data ===\n\n";

// Check polikliniks
echo "1. POLIKLINIKS:\n";
$polis = $db->query("SELECT id, name FROM polis")->fetchAll(PDO::FETCH_ASSOC);
foreach ($polis as $p) {
    echo "  - ID: {$p['id']}, Nama: {$p['name']}\n";
}

// Check dokters
echo "\n2. DOKTERS:\n";
$dokters = $db->query("SELECT id, nama, id_poli FROM dokters")->fetchAll(PDO::FETCH_ASSOC);
foreach ($dokters as $d) {
    echo "  - ID: {$d['id']}, Nama: {$d['nama']}, Poli ID: {$d['id_poli']}\n";
}

// Check jadwal_polis
echo "\n3. JADWAL_POLIS:\n";
$jadwals = $db->query("SELECT id, poliklinik_id, dokter_id, hari, jam_mulai, jam_selesai FROM jadwal_polis")->fetchAll(PDO::FETCH_ASSOC);
if (count($jadwals) === 0) {
    echo "  ! KOSONG - Tidak ada data jadwal\n";
} else {
    foreach ($jadwals as $j) {
        echo "  - ID: {$j['id']}, Poli: {$j['poliklinik_id']}, Dokter: {$j['dokter_id']}, Hari: {$j['hari']}, Jam: {$j['jam_mulai']}-{$j['jam_selesai']}\n";
    }
}

// Test query untuk poliklinik_id = 1
echo "\n4. TEST QUERY untuk poliklinik_id = 1:\n";
$stmt = $db->prepare("
    SELECT jp.id, jp.poliklinik_id, jp.dokter_id, jp.hari, jp.jam_mulai, jp.jam_selesai, d.nama as dokter_nama
    FROM jadwal_polis jp
    LEFT JOIN dokters d ON jp.dokter_id = d.id
    WHERE jp.poliklinik_id = ?
");
$stmt->execute([1]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "  Hasil: " . count($result) . " baris\n";
foreach ($result as $row) {
    echo "  - Jadwal ID {$row['id']}: {$row['hari']} ({$row['jam_mulai']}-{$row['jam_selesai']}) - Dr. {$row['dokter_nama']}\n";
}
?>
