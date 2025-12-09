<?php
$pdo = new PDO('sqlite:database/database.sqlite');

// Check password hashing
$users = $pdo->query("SELECT id, name, email, password FROM users")->fetchAll(PDO::FETCH_ASSOC);
echo "=== USERS & PASSWORD INFO ===\n";
foreach ($users as $u) {
    echo "ID {$u['id']}: {$u['name']} ({$u['email']})\n";
    echo "  Password hash: " . substr($u['password'], 0, 20) . "...\n";
    echo "  Hash type: " . (str_starts_with($u['password'], '$2y$') ? 'bcrypt' : 'unknown') . "\n";
}

// Test bcrypt verification
$testPassword = "password";
$testHash = $users[0]['password'];
require 'vendor/autoload.php';
use Illuminate\Support\Facades\Hash;

// Can't use Hash facade outside Laravel context, so test manually
echo "\n=== TESTING LOGIN ===\n";
echo "Test password: '$testPassword'\n";
echo "Against hash: " . substr($testHash, 0, 20) . "...\n";
?>
