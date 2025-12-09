<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== Check dokter_id in jadwal_polis ===\n";
$data = $db->query('SELECT id, poliklinik_id, dokter_id, hari FROM jadwal_polis')->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $d) {
    $dokter_id = $d['dokter_id'] ?? 'NULL';
    echo "ID={$d['id']} Poli={$d['poliklinik_id']} Dokter_ID=$dokter_id Hari={$d['hari']}\n";
}

echo "\n=== Dokters Available ===\n";
$dokters = $db->query('SELECT id, nama, id_poli FROM dokters')->fetchAll(PDO::FETCH_ASSOC);
foreach ($dokters as $d) {
    echo "ID={$d['id']} Nama={$d['nama']} Poli_ID={$d['id_poli']}\n";
}
?>
