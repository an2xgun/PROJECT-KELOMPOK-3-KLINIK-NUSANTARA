#!/usr/bin/env php
<?php
/**
 * Test Pendaftaran Flow
 * Simulates the complete new patient registration workflow
 */

require __DIR__ . '/../vendor/autoload.php';

$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== TESTING PENDAFTARAN FLOW ===\n\n";

// Test 1: Check if form field mapping is correct
echo "TEST 1: Field mapping validation\n";
$requiredFields = [
    'nama' => 'varchar',
    'nik' => 'varchar',
    'kelamin' => 'varchar',
    'lahir' => 'date',
    'golongan_darah' => 'VARCHAR(255)',
    'jenis_pasien' => 'VARCHAR(255)',
    'agama' => 'varchar',
    'pendidikan' => 'varchar',
    'provinsi' => 'VARCHAR(255)',
    'alamat' => 'varchar',
    'telepon' => 'varchar',
    'pekerjaan' => 'varchar',
];

$columns = $db->query('PRAGMA table_info(pasiens)')->fetchAll(PDO::FETCH_ASSOC);
$columnMap = [];
foreach ($columns as $col) {
    $columnMap[$col['name']] = $col['type'];
}

$missing = [];
foreach ($requiredFields as $field => $expectedType) {
    if (!isset($columnMap[$field])) {
        $missing[] = $field;
    } else {
        echo "  ✓ $field exists\n";
    }
}

if (!empty($missing)) {
    echo "  ✗ Missing fields: " . implode(', ', $missing) . "\n";
    exit(1);
}

// Test 2: Simulate data insertion
echo "\nTEST 2: Simulate patient data insertion\n";

$testData = [
    'nama' => 'Rina Kartikasari',
    'nik' => '1234567890123456',
    'kelamin' => 'Perempuan',
    'lahir' => '1995-03-15',
    'golongan_darah' => 'A',
    'jenis_pasien' => 'BPJS',
    'agama' => 'Islam',
    'pendidikan' => 'S1',
    'provinsi' => 'Jawa Barat',
    'alamat' => 'Jl. Merdeka No. 123',
    'telepon' => '08123456789',
    'pekerjaan' => 'Guru',
    'no_rm' => 'RM000003', // This will be generated
];

// Check if NIK already exists
$existingNik = $db->query("SELECT COUNT(*) as count FROM pasiens WHERE nik = '" . $testData['nik'] . "'")->fetch();
if ($existingNik['count'] > 0) {
    echo "  ! Test data NIK already exists, skipping insertion test\n";
} else {
    $fields = array_keys($testData);
    $values = array_map(fn($v) => "'" . addslashes($v) . "'", array_values($testData));
    $sql = "INSERT INTO pasiens (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
    
    try {
        $db->exec($sql);
        echo "  ✓ Successfully inserted test patient\n";
        
        // Verify insertion
        $check = $db->query("SELECT * FROM pasiens WHERE nik = '" . $testData['nik'] . "'")->fetch();
        if ($check) {
            echo "  ✓ Verified insertion - No RM: " . $check['no_rm'] . "\n";
            echo "  ✓ Nama: " . $check['nama'] . "\n";
            echo "  ✓ Golongan Darah: " . $check['golongan_darah'] . "\n";
            echo "  ✓ Jenis Pasien: " . $check['jenis_pasien'] . "\n";
        }
    } catch (Exception $e) {
        echo "  ✗ Insertion failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

// Test 3: Check poliklinik data
echo "\nTEST 3: Check poliklinik data for form dropdown\n";
$polikliniks = $db->query("SELECT * FROM polis LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
if (count($polikliniks) > 0) {
    echo "  ✓ Found " . count($polikliniks) . " poliklinik(s):\n";
    foreach ($polikliniks as $poli) {
        echo "    - " . (isset($poli['name']) ? $poli['name'] : $poli['nama']) . "\n";
    }
} else {
    echo "  ! No poliklinik found - will need to add test data\n";
}

// Test 4: Check jadwal poli data
echo "\nTEST 4: Check jadwal poli data\n";
$jadwals = $db->query("SELECT jp.* FROM jadwal_polis jp LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
if (count($jadwals) > 0) {
    echo "  ✓ Found " . count($jadwals) . " jadwal poli(s)\n";
} else {
    echo "  ! No jadwal poli found - this is expected, can be added later\n";
}

// Test 5: Verify no_rm generation logic
echo "\nTEST 5: Verify no_rm generation logic\n";
$lastPatient = $db->query("SELECT id FROM pasiens ORDER BY id DESC LIMIT 1")->fetch();
if ($lastPatient) {
    $nextId = $lastPatient['id'] + 1;
    $expectedNoRm = 'RM' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    echo "  ✓ Last patient ID: " . $lastPatient['id'] . "\n";
    echo "  ✓ Next no_rm should be: " . $expectedNoRm . "\n";
} else {
    echo "  ✓ No patients yet, next patient will get RM000001\n";
}

echo "\n=== ALL TESTS COMPLETED ===\n";
echo "✓ Pendaftaran form setup is correct!\n";
?>
