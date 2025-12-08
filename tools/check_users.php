<?php
$pdo = new PDO('sqlite:database/database.sqlite');

echo "=== USERS TABLE ===\n";
$users = $pdo->query("SELECT id, name, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
if (empty($users)) {
    echo "❌ Tidak ada users!\n";
} else {
    foreach ($users as $u) {
        echo "ID {$u['id']}: {$u['name']} ({$u['email']}) - Role: {$u['role']}\n";
    }
}

echo "\n=== SESSIONS TABLE ===\n";
$sessions = $pdo->query("SELECT COUNT(*) as count FROM sessions")->fetchColumn();
echo "Total sessions: {$sessions}\n";
?>
