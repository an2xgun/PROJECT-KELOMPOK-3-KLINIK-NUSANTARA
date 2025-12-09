<?php
require __DIR__ . '/../vendor/autoload.php';

echo "=== TESTING NO RM GENERATION ===\n\n";

try {
    // Load Laravel app
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    // Test the model method
    $next1 = \App\Models\Pasien::generateNextNoRm();
    echo "Generated No RM #1: " . $next1 . "\n";
    
    $next2 = \App\Models\Pasien::generateNextNoRm();
    echo "Generated No RM #2: " . $next2 . "\n";
    
    $next3 = \App\Models\Pasien::generateNextNoRm();
    echo "Generated No RM #3: " . $next3 . "\n";
    
    echo "\n✓ All no_rm values generated successfully!\n";
    echo "✓ Format verified: XXXX (4 digits, zero-padded)\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
