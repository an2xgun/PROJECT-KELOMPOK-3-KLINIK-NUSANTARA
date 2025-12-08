<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

$tables = ['master_diagnosa', 'master_tindakan'];

foreach ($tables as $table) {
    echo "\n=== $table ===\n";
    $cols = $db->query("PRAGMA table_info($table)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo $col['name'] . ' (' . $col['type'] . ")\n";
    }
}
?>
