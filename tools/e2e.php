<?php
// Simple PDO-based E2E test script for local SQLite DB
$dbFile = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbFile)) {
    echo "DB not found: $dbFile\n";
    exit(1);
}

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Show current counts
    $counts = [];
    foreach (['pendaftaran','rekams','obats','dokters','prescriptions','prescription_items'] as $t) {
        $stmt = $pdo->query("SELECT count(*) as c FROM $t");
        $counts[$t] = (int)$stmt->fetchColumn();
    }
    echo "Before: " . json_encode($counts) . "\n";

    // Insert pendaftaran
    $now = (new DateTime())->format('Y-m-d H:i:s');
    $pdo->exec("INSERT INTO pendaftaran (id_pasien,id_poli,nomor_antrian,status,status_layanan,created_at,updated_at) VALUES (1,1,'E2E001','Active','Menunggu','$now','$now')");
    $pendaftaranId = $pdo->lastInsertId();

    // Mark served
    $pdo->exec("UPDATE pendaftaran SET status_layanan='Sedang Dilayani' WHERE id = $pendaftaranId");

    // Create rekam using pendaftaran values
    // get dokter id (use highest id)
    $dokterId = (int)$pdo->query("SELECT id FROM dokters ORDER BY id DESC LIMIT 1")->fetchColumn();
    if (!$dokterId) $dokterId = 1;

    $stmt = $pdo->prepare("INSERT INTO rekams (laporan,id_pasien,nomorantrian,tanggalperiksa,layanan,keluhan,id_dokter,diagnosa,created_at,updated_at) VALUES (0,?,?,?,?,?,?,NULL,?,?)");
    // use id_pasien from pendaftaran
    $idPasien = (int)$pdo->query("SELECT id_pasien FROM pendaftaran WHERE id = $pendaftaranId")->fetchColumn();
    $nomor = $pdo->query("SELECT nomor_antrian FROM pendaftaran WHERE id = $pendaftaranId")->fetchColumn();
    $tanggal = (new DateTime())->format('Y-m-d');
    $stmt->execute([$idPasien,$nomor,$tanggal,'UMUM','Keluhan contoh',$dokterId,$now,$now]);
    $rekamId = $pdo->lastInsertId();

    // Create prescription
    $pdo->exec("INSERT INTO prescriptions (rekam_id,dokter_id,status,created_at,updated_at) VALUES ($rekamId,$dokterId,'Pending','$now','$now')");
    $prescId = $pdo->lastInsertId();

    // Insert two items if obats exist
    $obat1 = (int)$pdo->query("SELECT id, harga FROM obats ORDER BY id ASC LIMIT 1")->fetchColumn();
    $row = $pdo->query("SELECT id,harga FROM obats ORDER BY id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $id1 = (int)$row['id']; $h1 = (float)$row['harga'];
        $pdo->exec("INSERT INTO prescription_items (prescription_id,obat_id,jumlah,dosis,harga_satuan,subtotal,created_at,updated_at) VALUES ($prescId,$id1,2,'2x1 tablet', $h1, " . ($h1*2) . ", '$now','$now')");
    }
    $row2 = $pdo->query("SELECT id,harga FROM obats ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($row2) {
        $id2 = (int)$row2['id']; $h2 = (float)$row2['harga'];
        $pdo->exec("INSERT INTO prescription_items (prescription_id,obat_id,jumlah,dosis,harga_satuan,subtotal,created_at,updated_at) VALUES ($prescId,$id2,1,'1x1 tablet', $h2, " . ($h2*1) . ", '$now','$now')");
    }

    // After counts
    $countsAfter = [];
    foreach (['pendaftaran','rekams','obats','dokters','prescriptions','prescription_items'] as $t) {
        $stmt = $pdo->query("SELECT count(*) as c FROM $t");
        $countsAfter[$t] = (int)$stmt->fetchColumn();
    }
    echo "After: " . json_encode($countsAfter) . "\n";
    echo "Created: pendaftaran=$pendaftaranId rekam=$rekamId prescription=$prescId\n";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}
