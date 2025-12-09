<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DAFTAR TABEL DATABASE ===\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $tableName = implode(' ', (array)$t);
    echo "  - $tableName\n";
}
?>
