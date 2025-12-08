<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$columns = $db->query('PRAGMA table_info(pasiens)')->fetchAll(PDO::FETCH_ASSOC);

echo "=== PASIENS TABLE SCHEMA ===\n";
foreach ($columns as $col) {
    echo $col['name'] . ' (' . $col['type'] . ') ' . ($col['notnull'] ? 'NOT NULL' : 'NULLABLE') . "\n";
}
echo "\nTotal columns: " . count($columns) . "\n";
?>
