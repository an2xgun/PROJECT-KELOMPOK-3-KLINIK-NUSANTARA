<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "Inserting test pendaftaran...\n";
try {
    $stmt = $db->prepare("INSERT INTO pendaftaran (id_pasien, id_poli, nomor_antrian, status_layanan, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))");
    $stmt->execute([1, 1, '001', 'Menunggu']);
    echo "Insert OK. Last ID: " . $db->lastInsertId() . "\n";
} catch (Exception $e) {
    echo "Insert failed: " . $e->getMessage() . "\n";
}

$count = $db->query("SELECT COUNT(*) FROM pendaftaran")->fetch()[0];
echo "Total pendaftaran rows: $count\n";
?>