<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== DEBUGGING DIAGNOSA & TINDAKAN ===\n\n";

// Check if tables exist
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name LIKE '%diagnosa%' OR name LIKE '%tindakan%'")->fetchAll(PDO::FETCH_ASSOC);
echo "Tables found:\n";
foreach ($tables as $t) {
    echo "  - " . $t['name'] . "\n";
}

// Check master_diagnosa content
echo "\nMASTER_DIAGNOSA content:\n";
$diag = $db->query("SELECT * FROM master_diagnosa")->fetchAll(PDO::FETCH_ASSOC);
echo "Count: " . count($diag) . "\n";
foreach ($diag as $d) {
    echo "  ID: " . $d['id'] . " | Kode: " . $d['kode'] . " | Nama: " . $d['nama'] . "\n";
}

// Check master_tindakan content
echo "\nMASTER_TINDAKAN content:\n";
$tind = $db->query("SELECT * FROM master_tindakan")->fetchAll(PDO::FETCH_ASSOC);
echo "Count: " . count($tind) . "\n";
foreach ($tind as $t) {
    echo "  ID: " . $t['id'] . " | Kode: " . $t['kode'] . " | Nama: " . $t['nama'] . "\n";
}

// Check if there are other tables with similar names
echo "\n\nAll tables in database:\n";
$allTables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
foreach ($allTables as $t) {
    echo "  - " . $t['name'] . "\n";
}

?>
