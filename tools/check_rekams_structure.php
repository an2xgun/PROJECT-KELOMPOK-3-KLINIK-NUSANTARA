<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "\n=== rekams ===\n";
$cols = $db->query('PRAGMA table_info(rekams)')->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['name'] . ' (' . $c['type'] . ')\n';
}
?>