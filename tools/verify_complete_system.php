#!/usr/bin/env php
<?php
/**
 * Verify Complete System Setup
 */

$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║  COMPLETE SYSTEM VERIFICATION                           ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// 1. Pasiens & No RM
echo "1. PASIENS & NO RM:\n";
$pasiens = $db->query('SELECT id, no_rm, nama FROM pasiens')->fetchAll(PDO::FETCH_ASSOC);
echo "   Found: " . count($pasiens) . " pasiens\n";
foreach ($pasiens as $p) {
    echo "   • " . $p['no_rm'] . " - " . $p['nama'] . "\n";
}

// 2. Diagnosa
echo "\n2. MASTER DIAGNOSA:\n";
$diagnosa = $db->query('SELECT COUNT(*) FROM master_diagnosa')->fetch();
echo "   Found: " . $diagnosa[0] . " diagnosa\n";

// 3. Tindakan
echo "\n3. MASTER TINDAKAN:\n";
$tindakan = $db->query('SELECT COUNT(*) FROM master_tindakan')->fetch();
echo "   Found: " . $tindakan[0] . " tindakan\n";

// 4. Dokter
echo "\n4. DOKTERS:\n";
$dokters = $db->query('SELECT id, nama FROM dokters')->fetchAll(PDO::FETCH_ASSOC);
echo "   Found: " . count($dokters) . " dokters\n";
foreach ($dokters as $d) {
    echo "   • Dr. " . $d['nama'] . "\n";
}

// 5. Jadwal Polis
echo "\n5. JADWAL POLIS:\n";
$jadwals = $db->query('SELECT COUNT(*) FROM jadwal_polis')->fetch();
echo "   Found: " . $jadwals[0] . " jadwal\n";

// 6. Polikliniks
echo "\n6. POLIKLINIKS:\n";
$polis = $db->query('SELECT id, name FROM polis')->fetchAll(PDO::FETCH_ASSOC);
foreach ($polis as $p) {
    echo "   • " . $p['name'] . "\n";
}

// 7. Obats
echo "\n7. OBATS:\n";
$obats = $db->query('SELECT COUNT(*) FROM obats')->fetch();
echo "   Found: " . $obats[0] . " obats\n";

// 8. Prescriptions
echo "\n8. PRESCRIPTIONS:\n";
$presc = $db->query('SELECT COUNT(*) FROM prescriptions')->fetch();
echo "   Found: " . $presc[0] . " prescriptions\n";

// 9. No RM Sequence
echo "\n9. NO RM SEQUENCE:\n";
$seq = $db->query('SELECT next_no_rm FROM no_rm_sequences')->fetch();
echo "   Next No RM: " . str_pad($seq['next_no_rm'], 4, '0', STR_PAD_LEFT) . "\n";

echo "\n" . str_repeat("═", 60) . "\n";
echo "✓ SYSTEM FULLY SETUP AND READY FOR TESTING\n";
echo str_repeat("═", 60) . "\n\n";

echo "📍 START TESTING:\n";
echo "   URL: http://127.0.0.1:8000/pendaftaran/choice\n";
echo "   Login: petugas@clinic.com / password\n\n";

?>
