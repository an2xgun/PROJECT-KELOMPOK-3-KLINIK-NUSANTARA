<?php
$pdo = new PDO('sqlite:database/database.sqlite');

echo "=== PRESCRIPTIONS (Last 5) ===\n";
$result = $pdo->query("SELECT id, rekam_id, dokter_id, status FROM prescriptions ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $r) {
    echo "ID {$r['id']}: rekam_id={$r['rekam_id']}, dokter_id={$r['dokter_id']}, status={$r['status']}\n";
}

echo "\n=== PRESCRIPTION ITEMS (Last 10) ===\n";
$items = $pdo->query("SELECT id, prescription_id, obat_id, jumlah, dosis FROM prescription_items ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
foreach ($items as $i) {
    echo "ID {$i['id']}: prescription_id={$i['prescription_id']}, obat_id={$i['obat_id']}, jumlah={$i['jumlah']}, dosis={$i['dosis']}\n";
}

echo "\n=== PENDAFTARAN & REKAMS ===\n";
$pend = $pdo->query("SELECT id, status_layanan FROM pendaftaran ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
foreach ($pend as $p) {
    echo "Pendaftaran ID {$p['id']}: status={$p['status_layanan']}\n";
}

$rekams = $pdo->query("SELECT id, pendaftaran_id, poliklinik_id FROM rekams ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rekams as $r) {
    echo "Rekam ID {$r['id']}: pendaftaran_id={$r['pendaftaran_id']}, poliklinik_id={$r['poliklinik_id']}\n";
}
?>
