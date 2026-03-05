<?php
/**
 * Database configuration
 * Automatically detects if running on Local or Live Hostinger server
 */

$is_hottinger = (strpos($_SERVER['HTTP_HOST'], 'mebshiyas.com') !== false);

if ($is_hottinger) {
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

