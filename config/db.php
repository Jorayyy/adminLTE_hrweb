<?php
/**
 * Database configuration
 * Automatically detects if running on Local or Live Hostinger server
 * Works for both Browser (HTTP) and Terminal (SSH/CLI)
 */

// Detect environment
$is_live = false;

// 1. Check if running in browser
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'mebshiyas.com') !== false) {
    $is_live = true;
} 
// 2. Check if running in terminal (CLI) on the Hostinger server
else if (php_sapi_name() === 'cli') {
    $uname = php_uname('n'); // Get hostname
    // On Hostinger, hostname usually contains 'sg-nme' or similar as seen in your prompt
    if (strpos($uname, 'sg-nme') !== false || file_exists('/home/u502373859')) {
        $is_live = true;
    }
}

if ($is_live) {
    // For Hostinger Live:
    $host = 'localhost';
    $db   = 'u502373859_mebs';
    $user = 'u502373859_hiyas';
    $pass = 'Mjaa050501';
} else {
    // Local Environment:
    $host = 'localhost';
    $db   = 'mebs_hris';
    $user = 'root';
    $pass = '';
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>

