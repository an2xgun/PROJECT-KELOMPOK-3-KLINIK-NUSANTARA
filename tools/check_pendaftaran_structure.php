<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "\n=== pendaftaran table structure ===\n";
$cols = $db->query("PRAGMA table_info(pendaftaran)")->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $col) {
    echo $col['name'] . ' (' . $col['type'] . ')\n';
}

?>