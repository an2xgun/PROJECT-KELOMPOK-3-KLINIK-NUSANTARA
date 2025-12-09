<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== SEEDING DIAGNOSA & TINDAKAN ===\n\n";

// Clear existing data first
echo "Clearing existing data...\n";
$db->exec("DELETE FROM master_diagnosa");
$db->exec("DELETE FROM master_tindakan");

// 1. Seed Master Diagnosa
echo "\n1. Seeding MASTER DIAGNOSA:\n";
$diagnosaData = [
    ['kode' => 'A01', 'nama' => 'Demam Berdarah'],
    ['kode' => 'A02', 'nama' => 'Tifoid'],
    ['kode' => 'B01', 'nama' => 'Hipertensi'],
    ['kode' => 'B02', 'nama' => 'Diabetes Melitus'],
    ['kode' => 'C01', 'nama' => 'Gigi Berlubang'],
    ['kode' => 'C02', 'nama' => 'Radang Gusi'],
    ['kode' => 'D01', 'nama' => 'Hamil Normal'],
    ['kode' => 'D02', 'nama' => 'Preeklampsia'],
];

$inserted = 0;
foreach ($diagnosaData as $d) {
    try {
        $stmt = $db->prepare("INSERT INTO master_diagnosa (kode, nama, created_at, updated_at) 
                      VALUES (?, ?, datetime('now'), datetime('now'))");
        $stmt->execute([$d['kode'], $d['nama']]);
        echo "   ✓ " . $d['nama'] . "\n";
        $inserted++;
    } catch (Exception $e) {
        echo "   ✗ " . $d['nama'] . " - Error: " . $e->getMessage() . "\n";
    }
}
echo "   Total inserted: $inserted\n";

// 2. Seed Master Tindakan
echo "\n2. Seeding MASTER TINDAKAN:\n";
$tindakanData = [
    ['kode' => 'T001', 'nama' => 'Pemeriksaan Darah', 'harga' => 50000],
    ['kode' => 'T002', 'nama' => 'Suntik Obat', 'harga' => 25000],
    ['kode' => 'T003', 'nama' => 'Resep Obat Minum', 'harga' => 0],
    ['kode' => 'T004', 'nama' => 'Pembersihan Karang Gigi', 'harga' => 100000],
    ['kode' => 'T005', 'nama' => 'Penambalan Gigi', 'harga' => 150000],
    ['kode' => 'T006', 'nama' => 'Scaling Gigi', 'harga' => 75000],
    ['kode' => 'T007', 'nama' => 'USG Kandungan', 'harga' => 200000],
    ['kode' => 'T008', 'nama' => 'Konsultasi Kehamilan', 'harga' => 50000],
    ['kode' => 'T009', 'nama' => 'Vitamin Kehamilan', 'harga' => 75000],
];

$inserted = 0;
foreach ($tindakanData as $t) {
    try {
        $stmt = $db->prepare("INSERT INTO master_tindakan (kode, nama, harga, created_at, updated_at) 
                      VALUES (?, ?, ?, datetime('now'), datetime('now'))");
        $stmt->execute([$t['kode'], $t['nama'], $t['harga']]);
        echo "   ✓ " . $t['nama'] . "\n";
        $inserted++;
    } catch (Exception $e) {
        echo "   ✗ " . $t['nama'] . " - Error: " . $e->getMessage() . "\n";
    }
}
echo "   Total inserted: $inserted\n";

// Verify
echo "\n=== VERIFICATION ===\n";
$diagCount = $db->query("SELECT COUNT(*) FROM master_diagnosa")->fetch()[0];
$tindCount = $db->query("SELECT COUNT(*) FROM master_tindakan")->fetch()[0];

echo "Master Diagnosa: " . $diagCount . " items\n";
echo "Master Tindakan: " . $tindCount . " items\n";

if ($diagCount > 0 && $tindCount > 0) {
    echo "\n✓ Seeding completed successfully!\n";
} else {
    echo "\n✗ Seeding incomplete\n";
}

?>
