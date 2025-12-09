<?php
$pdo = new PDO('sqlite:database/database.sqlite');

echo "=== PASIENS TABLE COLUMNS ===\n";
$cols = $pdo->query('PRAGMA table_info(pasiens)')->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['name'] . " (" . $c['type'] . ") " . ($c['notnull'] ? "NOT NULL" : "nullable") . "\n";
}

echo "\n=== SAMPLE PASIEN DATA ===\n";
$pasiens = $pdo->query("SELECT * FROM pasiens LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
if ($pasiens) {
    foreach ($pasiens as $p) {
        echo json_encode($p, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
?>
