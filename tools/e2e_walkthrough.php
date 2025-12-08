<?php
// E2E walkthrough script: set pendaftaran -> create rekam -> create prescription -> items
$dbFile = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbFile)) { echo "DB not found\n"; exit(1); }
try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Start E2E walkthrough\n";

    // 1) Choose pendaftaran that is not Selesai (prefer Menunggu)
    $stmt = $pdo->query("SELECT * FROM pendaftaran WHERE status_layanan != 'Selesai' ORDER BY id DESC LIMIT 1");
    $pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$pendaftaran) {
        echo "Tidak ada pendaftaran aktif untuk diproses.\n"; exit(0);
    }

    $pendaftaranId = (int)$pendaftaran['id'];
    echo "Selected pendaftaran id={$pendaftaranId}, status_layanan={$pendaftaran['status_layanan']}\n";

    // 1.a) Set to Sedang Dilayani if not already
    if ($pendaftaran['status_layanan'] !== 'Sedang Dilayani') {
        $pdo->exec("UPDATE pendaftaran SET status_layanan='Sedang Dilayani', updated_at=datetime('now') WHERE id={$pendaftaranId}");
        echo "Updated pendaftaran {$pendaftaranId} -> Sedang Dilayani\n";
    } else {
        echo "Pendaftaran sudah berstatus Sedang Dilayani\n";
    }

    // 2) Create Rekam if not exists for that pendaftaran
    $r = $pdo->prepare("SELECT id FROM rekams WHERE id_pasien = ? AND nomorantrian = ? ORDER BY id DESC LIMIT 1");
    $r->execute([$pendaftaran['id_pasien'],$pendaftaran['nomor_antrian']]);
    $rekam = $r->fetch(PDO::FETCH_ASSOC);
    if ($rekam) {
        $rekamId = (int)$rekam['id'];
        echo "Found existing rekam id={$rekamId}\n";
    } else {
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $ins = $pdo->prepare("INSERT INTO rekams (laporan,id_pasien,nomorantrian,tanggalperiksa,layanan,keluhan,id_dokter,diagnosa,created_at,updated_at) VALUES (0,?,?,?,?,?,?,NULL,?,?)");
        // Use id_pasien and nomor_antrian from pendaftaran; set layanan UMUM and dokter last id
        $dokterId = (int)$pdo->query("SELECT id FROM dokters ORDER BY id DESC LIMIT 1")->fetchColumn();
        if (!$dokterId) $dokterId = 1;
        $ins->execute([$pendaftaran['id_pasien'],$pendaftaran['nomor_antrian'],(new DateTime())->format('Y-m-d'), 'UMUM', 'Keluhan terisi otomatis', $dokterId, $now, $now]);
        $rekamId = (int)$pdo->lastInsertId();
        echo "Created rekam id={$rekamId}\n";
    }

    // 3) Simulate storing examination: update rekam fields and set pendaftaran finished
    // Note: DB column is 'diagnosa'
    $pdo->exec("UPDATE rekams SET diagnosa='Diagnosa contoh', keterangan='Catatan pemeriksaan otomatis', updated_at=datetime('now') WHERE id={$rekamId}");
    $pdo->exec("UPDATE pendaftaran SET status_layanan='Selesai', updated_at=datetime('now') WHERE id={$pendaftaranId}");
    echo "Marked pendaftaran {$pendaftaranId} -> Selesai and updated rekam {$rekamId}\n";

    // 4) Create prescription for rekam
    if (!isset($dokterId) || !$dokterId) {
        $dokterId = (int)$pdo->query("SELECT id FROM dokters ORDER BY id DESC LIMIT 1")->fetchColumn();
        if (!$dokterId) $dokterId = 1;
    }
    $pdo->exec("INSERT INTO prescriptions (rekam_id,dokter_id,status,created_at,updated_at) VALUES ({$rekamId},{$dokterId},'Pending',datetime('now'),datetime('now'))");
    $prescId = (int)$pdo->lastInsertId();
    echo "Created prescription id={$prescId}\n";

    // 5) Add up to 2 items using available obats
    $rows = $pdo->query("SELECT id,harga FROM obats ORDER BY id ASC LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "Tidak ada obat di DB untuk menambahkan item resep.\n";
    } else {
        $count = 0;
        foreach ($rows as $row) {
            $oid = (int)$row['id'];
            $harga = is_numeric($row['harga']) ? (float)$row['harga'] : 0;
            $jumlah = ($count==0)?2:1;
            $subtotal = $harga * $jumlah;
            $pdo->exec("INSERT INTO prescription_items (prescription_id,obat_id,jumlah,dosis,harga_satuan,subtotal,created_at,updated_at) VALUES ({$prescId},{$oid},{$jumlah},'Dosis contoh',{$harga},{$subtotal},datetime('now'),datetime('now'))");
            $count++;
        }
        echo "Inserted {$count} prescription items for prescription {$prescId}\n";
    }

    // 6) Output URLs to check
    $base = 'http://127.0.0.1:8000';
    echo "Open these URLs in browser to verify:\n";
    echo "- Pendaftaran list: {$base}/pendaftaran\n";
    echo "- Examination form (GET): {$base}/examination/{$pendaftaranId}/form\n";
    echo "- Rekam page: {$base}/rekam/{$rekamId}\n";
    echo "- Prescription create: {$base}/prescription/{$rekamId}/create\n";
    echo "- Prescription show: {$base}/prescription/{$prescId}\n";

    echo "Done.\n";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n"; exit(1);
}
