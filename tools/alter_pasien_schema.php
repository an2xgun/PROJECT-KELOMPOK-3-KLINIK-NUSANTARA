<?php
$pdo = new PDO('sqlite:database/database.sqlite');

// Add columns to pasiens table if they don't exist
$columns = [
    'no_rm' => "ALTER TABLE pasiens ADD COLUMN no_rm VARCHAR(255)",
    'golongan_darah' => "ALTER TABLE pasiens ADD COLUMN golongan_darah VARCHAR(255)",
    'provinsi' => "ALTER TABLE pasiens ADD COLUMN provinsi VARCHAR(255)",
];

foreach ($columns as $name => $sql) {
    try {
        $pdo->exec($sql);
        echo "✓ Added column: $name\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'duplicate column name') !== false) {
            echo "✓ Column already exists: $name\n";
        } else {
            echo "✗ Error adding $name: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n=== UPDATED PASIENS SCHEMA ===\n";
$cols = $pdo->query('PRAGMA table_info(pasiens)')->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['name'] . " (" . $c['type'] . ")\n";
}
?>
