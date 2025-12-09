<?php
require __DIR__ . '/../vendor/autoload.php';

// Direct database check
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

echo "=== DIRECT DATABASE CHECK ===\n\n";

// Check no_rm_sequences
$result = $db->query("SELECT * FROM no_rm_sequences")->fetch(PDO::FETCH_ASSOC);
echo "no_rm_sequences record:\n";
var_dump($result);

// Now test with Laravel
echo "\n=== TESTING WITH LARAVEL ===\n\n";
try {
    $app = require __DIR__ . '/../bootstrap/app.php';
    
    // Test DB connection
    $record = \Illuminate\Support\Facades\DB::table('no_rm_sequences')->first();
    echo "Laravel DB Access:\n";
    var_dump($record);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
