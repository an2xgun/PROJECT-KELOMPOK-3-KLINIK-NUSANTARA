<?php
$pdo = new PDO('sqlite:database/database.sqlite');

echo "=== TABLE SCHEMA ===\n\n";

$tables = ['rekams', 'pendaftaran', 'pasiens', 'polis', 'jadwal_polis', 'dokters'];

foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    $cols = $pdo->query("PRAGMA table_info($table)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo "  {$col['name']} ({$col['type']}) " . ($col['notnull'] ? 'NOT NULL' : 'NULL') . "\n";
    }
    echo "\n";
}

echo "=== SAMPLE DATA ===\n\n";

echo "Last 3 Pendaftaran:\n";
$pend = $pdo->query("SELECT id, id_pasien, id_poli, jadwal_poli_id, nomor_antrian, status_layanan FROM pendaftaran ORDER BY id DESC LIMIT 3")->fetchAll();
foreach ($pend as $p) {
    print_r($p);
}

echo "\nLast 3 Rekams:\n";
$rekam = $pdo->query("SELECT id, id_pasien, nomorantrian, id_dokter, layanan FROM rekams ORDER BY id DESC LIMIT 3")->fetchAll();
foreach ($rekam as $r) {
    print_r($r);
}
?>
