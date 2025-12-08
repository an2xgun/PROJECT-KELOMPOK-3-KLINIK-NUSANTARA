<?php
session_start();
echo "Session ID: " . session_id() . "\n";
echo "Session data: " . json_encode($_SESSION) . "\n";
echo "CSRF Token dari env: " . getenv('APP_KEY') . "\n";
?>
