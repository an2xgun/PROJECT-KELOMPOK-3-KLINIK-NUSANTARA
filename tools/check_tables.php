<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== DATABASE TABLES ===\n";
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
foreach ($tables as $table) {
    echo $table['name'] . "\n";
}
?>
