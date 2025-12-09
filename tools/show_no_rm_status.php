#!/usr/bin/env php
<?php
/**
 * No RM Generation Integration Test
 */

require __DIR__ . '/../vendor/autoload.php';

echo "═══════════════════════════════════════════════════\n";
echo "  NO RM GENERATION TEST\n";
echo "═══════════════════════════════════════════════════\n\n";

$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

// Check current state
$seq = $db->query("SELECT next_no_rm FROM no_rm_sequences")->fetch();
echo "Current sequence state: " . $seq['next_no_rm'] . "\n";
echo "Next No RM will be: " . str_pad($seq['next_no_rm'], 4, '0', STR_PAD_LEFT) . "\n\n";

// List all patients
echo "Current patients:\n";
$patients = $db->query("SELECT id, no_rm, nama FROM pasiens ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($patients as $p) {
    echo "  • No RM: " . $p['no_rm'] . " | " . $p['nama'] . "\n";
}

echo "\n✓ System ready for testing!\n";
echo "  Navigate to: http://127.0.0.1:8000/pendaftaran/choice\n";

?>
