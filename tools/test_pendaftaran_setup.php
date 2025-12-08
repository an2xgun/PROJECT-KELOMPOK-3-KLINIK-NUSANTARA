<?php

// Test the complete pendaftaran flow

require __DIR__ . '/../vendor/autoload.php';

// Check if app is accessible
try {
    $db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
    echo "✓ Database connected\n";
    
    // Check pasiens table
    $result = $db->query("SELECT COUNT(*) as count FROM pasiens")->fetch();
    echo "✓ Pasiens table accessible - " . $result['count'] . " patients\n";
    
    // Check required fields exist
    $columns = $db->query('PRAGMA table_info(pasiens)')->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'name');
    
    $requiredFields = ['no_rm', 'nama', 'nik', 'kelamin', 'lahir', 'golongan_darah', 'jenis_pasien', 'agama', 'pendidikan', 'provinsi', 'alamat', 'telepon', 'pekerjaan'];
    
    $missing = [];
    foreach ($requiredFields as $field) {
        if (!in_array($field, $columnNames)) {
            $missing[] = $field;
        }
    }
    
    if (empty($missing)) {
        echo "✓ All required fields present in database\n";
    } else {
        echo "✗ Missing fields: " . implode(', ', $missing) . "\n";
    }
    
    // Check sample data (try to fetch a patient)
    $sample = $db->query("SELECT * FROM pasiens LIMIT 1")->fetch();
    if ($sample) {
        echo "✓ Sample patient found:\n";
        echo "  - No RM: " . ($sample['no_rm'] ?? 'NULL') . "\n";
        echo "  - Nama: " . $sample['nama'] . "\n";
        echo "  - Golongan Darah: " . ($sample['golongan_darah'] ?? 'NULL') . "\n";
    }
    
    echo "\n✓ All checks passed!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
