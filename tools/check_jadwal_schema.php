<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== JADWAL_POLIS TABLE SCHEMA ===\n";
$columns = $db->query('PRAGMA table_info(jadwal_polis)')->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo $col['name'] . " (" . $col['type'] . ")\n";
}

echo "\n=== DOKTERS TABLE SCHEMA ===\n";
$columns = $db->query('PRAGMA table_info(dokters)')->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo $col['name'] . " (" . $col['type'] . ")\n";
}

echo "\n=== SAMPLE JADWAL DATA ===\n";
$jadwals = $db->query('SELECT * FROM jadwal_polis LIMIT 1')->fetchAll(PDO::FETCH_ASSOC);
if (count($jadwals) > 0) {
    var_dump($jadwals[0]);
} else {
    echo "No jadwal data found\n";
}
?>
