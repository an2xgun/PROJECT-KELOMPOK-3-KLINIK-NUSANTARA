<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== Fixing dokter_id References ===\n";

// Update jadwal_polis ID 1-4 (poli_id=1) ke dokter_id=2 (Dr. Nadhifa)
$stmt = $db->prepare("UPDATE jadwal_polis SET dokter_id = 2 WHERE poliklinik_id = 1 AND dokter_id = 1");
$stmt->execute();
echo "Updated: Poli 1 jadwals -> dokter_id = 2\n";

// Verify
$data = $db->query('SELECT id, poliklinik_id, dokter_id, hari FROM jadwal_polis ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
echo "\n=== After Fix ===\n";
foreach ($data as $d) {
    echo "ID={$d['id']} Poli={$d['poliklinik_id']} Dokter_ID={$d['dokter_id']} Hari={$d['hari']}\n";
}
?>
