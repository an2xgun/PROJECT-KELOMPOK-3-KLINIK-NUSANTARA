<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== POLIS TABLE SCHEMA ===\n";
$columns = $db->query('PRAGMA table_info(polis)')->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo $col['name'] . " (" . $col['type'] . ")\n";
}

echo "\n=== SAMPLE POLIS DATA ===\n";
$polis = $db->query('SELECT * FROM polis LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);
foreach ($polis as $poli) {
    echo "ID: " . $poli['id'] . ", Columns: " . implode(', ', array_keys($poli)) . "\n";
}
?>
