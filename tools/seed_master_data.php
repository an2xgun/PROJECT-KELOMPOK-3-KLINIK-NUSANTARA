<?php
/**
 * Seed master data: diagnosa, tindakan, jadwal dokter
 */

$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== SEEDING MASTER DATA ===\n\n";

// 1. Seed Master Diagnosa
echo "1. MASTER DIAGNOSA\n";
$diagnosaData = [
    ['kode' => 'A01', 'nama' => 'Demam Berdarah', 'deskripsi' => 'Infeksi virus dengue'],
    ['kode' => 'A02', 'nama' => 'Tifoid', 'deskripsi' => 'Infeksi bakteri Salmonella'],
    ['kode' => 'B01', 'nama' => 'Hipertensi', 'deskripsi' => 'Tekanan darah tinggi'],
    ['kode' => 'B02', 'nama' => 'Diabetes Melitus', 'deskripsi' => 'Gangguan metabolik gula'],
    ['kode' => 'C01', 'nama' => 'Gigi Berlubang', 'deskripsi' => 'Karies gigi'],
    ['kode' => 'C02', 'nama' => 'Radang Gusi', 'deskripsi' => 'Peradangan jaringan gusi'],
    ['kode' => 'D01', 'nama' => 'Hamil Normal', 'deskripsi' => 'Kehamilan tanpa komplikasi'],
    ['kode' => 'D02', 'nama' => 'Preeklampsia', 'deskripsi' => 'Tekanan darah tinggi kehamilan'],
];

foreach ($diagnosaData as $d) {
    try {
        $stmt = $db->prepare("INSERT INTO master_diagnosa (kode, nama, deskripsi, created_at, updated_at) 
                              VALUES (?, ?, ?, datetime('now'), datetime('now'))");
        $stmt->execute([$d['kode'], $d['nama'], $d['deskripsi']]);
        echo "   ✓ " . $d['nama'] . "\n";
    } catch (Exception $e) {
        echo "   ! " . $d['nama'] . " - Already exists\n";
    }
}

// 2. Seed Master Tindakan
echo "\n2. MASTER TINDAKAN\n";
$tindakanData = [
    // Umum
    ['kode' => 'T001', 'nama' => 'Pemeriksaan Darah', 'poliklinik_id' => 1],
    ['kode' => 'T002', 'nama' => 'Suntik Obat', 'poliklinik_id' => 1],
    ['kode' => 'T003', 'nama' => 'Resep Obat Minum', 'poliklinik_id' => 1],
    // Gigi
    ['kode' => 'T004', 'nama' => 'Pembersihan Karang Gigi', 'poliklinik_id' => 2],
    ['kode' => 'T005', 'nama' => 'Penambalan Gigi', 'poliklinik_id' => 2],
    ['kode' => 'T006', 'nama' => 'Scaling Gigi', 'poliklinik_id' => 2],
    // Kandungan
    ['kode' => 'T007', 'nama' => 'USG Kandungan', 'poliklinik_id' => 3],
    ['kode' => 'T008', 'nama' => 'Konsultasi Kehamilan', 'poliklinik_id' => 3],
    ['kode' => 'T009', 'nama' => 'Vitamin Kehamilan', 'poliklinik_id' => 3],
];

foreach ($tindakanData as $t) {
    try {
        $stmt = $db->prepare("INSERT INTO master_tindakan (kode, nama, poliklinik_id, created_at, updated_at) 
                              VALUES (?, ?, ?, datetime('now'), datetime('now'))");
        $stmt->execute([$t['kode'], $t['nama'], $t['poliklinik_id']]);
        echo "   ✓ " . $t['nama'] . "\n";
    } catch (Exception $e) {
        echo "   ! " . $t['nama'] . " - Already exists\n";
    }
}

// 3. Seed Dokters if not exist
echo "\n3. DOKTERS\n";
$dokterData = [
    ['nama' => 'Dr. Nur Anggun', 'id_poli' => 1, 'telepon' => '081234567890', 'jadwalpraktek' => 'Senin-Jumat 08:00-17:00'],
    ['nama' => 'Dr. Siti Nurhaliza', 'id_poli' => 2, 'telepon' => '081234567891', 'jadwalpraktek' => 'Senin-Rabu 10:00-15:00'],
    ['nama' => 'Dr. Budi Santoso', 'id_poli' => 3, 'telepon' => '081234567892', 'jadwalpraktek' => 'Selasa-Kamis 13:00-18:00'],
];

foreach ($dokterData as $d) {
    try {
        $stmt = $db->prepare("INSERT INTO dokters (nama, id_poli, telepon, jadwalpraktek, created_at, updated_at) 
                              VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))");
        $stmt->execute([$d['nama'], $d['id_poli'], $d['telepon'], $d['jadwalpraktek']]);
        echo "   ✓ " . $d['nama'] . "\n";
    } catch (Exception $e) {
        echo "   ! " . $d['nama'] . " - Already exists\n";
    }
}

// 4. Seed Jadwal Polis
echo "\n4. JADWAL POLIS\n";
$jadwalData = [
    // Poli Umum (ID 1)
    ['poliklinik_id' => 1, 'dokter_id' => 1, 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
    ['poliklinik_id' => 1, 'dokter_id' => 1, 'hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
    ['poliklinik_id' => 1, 'dokter_id' => 1, 'hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
    ['poliklinik_id' => 1, 'dokter_id' => 1, 'hari' => 'Jumat', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00'],
    // Poli Gigi (ID 2)
    ['poliklinik_id' => 2, 'dokter_id' => 2, 'hari' => 'Senin', 'jam_mulai' => '10:00', 'jam_selesai' => '12:00'],
    ['poliklinik_id' => 2, 'dokter_id' => 2, 'hari' => 'Selasa', 'jam_mulai' => '14:00', 'jam_selesai' => '15:00'],
    ['poliklinik_id' => 2, 'dokter_id' => 2, 'hari' => 'Rabu', 'jam_mulai' => '10:00', 'jam_selesai' => '12:00'],
    // Poli Kandungan (ID 3)
    ['poliklinik_id' => 3, 'dokter_id' => 3, 'hari' => 'Selasa', 'jam_mulai' => '13:00', 'jam_selesai' => '15:00'],
    ['poliklinik_id' => 3, 'dokter_id' => 3, 'hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '15:00'],
    ['poliklinik_id' => 3, 'dokter_id' => 3, 'hari' => 'Kamis', 'jam_mulai' => '13:00', 'jam_selesai' => '18:00'],
];

foreach ($jadwalData as $j) {
    try {
        $stmt = $db->prepare("INSERT INTO jadwal_polis (poliklinik_id, dokter_id, hari, jam_mulai, jam_selesai, created_at, updated_at) 
                              VALUES (?, ?, ?, ?, ?, datetime('now'), datetime('now'))");
        $stmt->execute([$j['poliklinik_id'], $j['dokter_id'], $j['hari'], $j['jam_mulai'], $j['jam_selesai']]);
        echo "   ✓ " . $j['hari'] . " " . $j['jam_mulai'] . " Poli ID: " . $j['poliklinik_id'] . "\n";
    } catch (Exception $e) {
        echo "   ! " . $j['hari'] . " - Already exists\n";
    }
}

echo "\n" . str_repeat("═", 50) . "\n";
echo "✓ Master data seeding completed!\n";
echo str_repeat("═", 50) . "\n";

?>
