<?php
$pdo = new PDO('sqlite:database/database.sqlite');

echo "=== REKAMS TABLE STRUCTURE ===\n";
$cols = $pdo->query('PRAGMA table_info(rekams)')->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['name'] . "\n";
}

echo "\n=== SAMPLE REKAMS DATA ===\n";
$rekams = $pdo->query("SELECT * FROM rekams LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
if ($rekams) {
    foreach ($rekams as $r) {
        echo "Rekam ID {$r['id']}: " . json_encode($r, JSON_UNESCAPED_SLASHES) . "\n";
    }
}
?>
