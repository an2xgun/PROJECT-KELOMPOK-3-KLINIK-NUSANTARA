<?php
require __DIR__ . '/../vendor/autoload.php';

// This script updates existing patients with no_rm values
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== UPDATING EXISTING PATIENTS WITH NO RM ===\n\n";

try {
    // Get all patients without no_rm
    $pasiens = $db->query("SELECT id FROM pasiens WHERE no_rm IS NULL ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($pasiens) === 0) {
        echo "✓ All patients already have no_rm\n";
        exit(0);
    }

    // Get current sequence value
    $sequence = $db->query("SELECT next_no_rm FROM no_rm_sequences LIMIT 1")->fetch();
    $nextNumber = $sequence ? $sequence['next_no_rm'] : 1;

    echo "Updating " . count($pasiens) . " patient(s)...\n";

    // Update each patient
    foreach ($pasiens as $p) {
        $noRm = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        $stmt = $db->prepare("UPDATE pasiens SET no_rm = :no_rm WHERE id = :id");
        $stmt->execute([':no_rm' => $noRm, ':id' => $p['id']]);
        
        echo "  ID " . $p['id'] . " → No RM: " . $noRm . "\n";
        $nextNumber++;
    }

    // Update sequence
    $db->prepare("UPDATE no_rm_sequences SET next_no_rm = ?")->execute([$nextNumber]);

    echo "\n✓ Update completed! Next no_rm will be: " . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . "\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
