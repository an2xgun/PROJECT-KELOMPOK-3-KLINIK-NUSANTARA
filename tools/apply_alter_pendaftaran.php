<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$queries = [
    "ALTER TABLE pendaftaran ADD COLUMN jadwal_poli_id INTEGER;",
    "ALTER TABLE pendaftaran ADD COLUMN keluhan TEXT;",
    "ALTER TABLE pendaftaran ADD COLUMN jenis_pembayaran VARCHAR(255);",
    "ALTER TABLE pendaftaran ADD COLUMN tanggal_kunjungan DATE;",
];

foreach ($queries as $q) {
    try {
        $db->exec($q);
        echo "OK: $q\n";
    } catch (Exception $e) {
        echo "SKIP/ERR: " . $e->getMessage() . "\n";
    }
}

echo "Done.\n";
