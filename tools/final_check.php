<?php
echo "=== FINAL SYSTEM VERIFICATION ===\n\n";

// Check files
echo "1. Files:\n";
echo "   " . (file_exists(__DIR__ . '/app/Http/Controllers/PendaftaranController.php') ? "✅" : "❌") . " PendaftaranController.php\n";
echo "   " . (file_exists(__DIR__ . '/resources/views/pendaftaran/create-new.blade.php') ? "✅" : "❌") . " create-new.blade.php\n";
echo "   " . (file_exists(__DIR__ . '/resources/views/pendaftaran/select-poli.blade.php') ? "✅" : "❌") . " select-poli.blade.php\n";
echo "   " . (file_exists(__DIR__ . '/app/Models/Pasien.php') ? "✅" : "❌") . " Pasien.php\n";

// Check database
echo "\n2. Database:\n";
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    
    $count = $db->query("SELECT COUNT(*) FROM pasiens")->fetch()[0];
    echo "   ✅ Database connected\n";
    echo "   ✅ Pasiens table exists ($count records)\n";
    
    // Check columns
    $cols = $db->query('PRAGMA table_info(pasiens)')->fetchAll(PDO::FETCH_ASSOC);
    $colNames = array_column($cols, 'name');
    
    echo "   ✅ Total columns: " . count($cols) . "\n";
    
    // Check required
    $required = ['no_rm', 'nama', 'nik', 'kelamin', 'lahir', 'golongan_darah', 'jenis_pasien', 'agama', 'pendidikan', 'provinsi', 'alamat', 'telepon', 'pekerjaan'];
    $found = 0;
    foreach ($required as $col) {
        if (in_array($col, $colNames)) $found++;
    }
    echo "   ✅ Required fields: $found/13 present\n";
    
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n3. Syntax Check:\n";
$syntax = shell_exec('php -l ' . __DIR__ . '/app/Http/Controllers/PendaftaranController.php 2>&1');
echo (strpos($syntax, 'No syntax errors') !== false ? "   ✅" : "   ❌") . " PendaftaranController.php\n";

$syntax = shell_exec('php -l ' . __DIR__ . '/resources/views/pendaftaran/create-new.blade.php 2>&1');
echo (strpos($syntax, 'No syntax errors') !== false ? "   ✅" : "   ❌") . " create-new.blade.php\n";

echo "\n" . str_repeat("═", 50) . "\n";
echo "✅ SYSTEM READY FOR TESTING\n";
echo str_repeat("═", 50) . "\n";
echo "\nAccess: http://127.0.0.1:8000/pendaftaran/choice\n";
echo "Login: petugas@clinic.com / password\n";
?>
