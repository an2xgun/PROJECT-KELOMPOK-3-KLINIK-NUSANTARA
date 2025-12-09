<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== NO RM SEQUENCE VERIFICATION ===\n\n";

// Check no_rm_sequences table
echo "1. Checking no_rm_sequences table:\n";
$sequences = $db->query("SELECT * FROM no_rm_sequences")->fetchAll(PDO::FETCH_ASSOC);
if (count($sequences) > 0) {
    echo "   ✓ Table exists\n";
    echo "   Current next_no_rm: " . $sequences[0]['next_no_rm'] . "\n";
} else {
    echo "   ! Table is empty\n";
}

// Check pasiens table for no_rm
echo "\n2. Checking pasiens table for no_rm column:\n";
$columns = $db->query('PRAGMA table_info(pasiens)')->fetchAll(PDO::FETCH_ASSOC);
$hasNoRm = false;
foreach ($columns as $col) {
    if ($col['name'] === 'no_rm') {
        $hasNoRm = true;
        echo "   ✓ no_rm column exists\n";
        break;
    }
}

if (!$hasNoRm) {
    echo "   ! no_rm column not found\n";
}

// Check existing patients and their no_rm
echo "\n3. Existing patients and their no_rm:\n";
$pasiens = $db->query("SELECT id, no_rm, nama FROM pasiens")->fetchAll(PDO::FETCH_ASSOC);
if (count($pasiens) > 0) {
    foreach ($pasiens as $p) {
        echo "   ID: " . $p['id'] . ", No RM: " . ($p['no_rm'] ?? 'NULL') . ", Nama: " . $p['nama'] . "\n";
    }
} else {
    echo "   No patients yet\n";
}

echo "\n✓ All checks completed!\n";
?>
