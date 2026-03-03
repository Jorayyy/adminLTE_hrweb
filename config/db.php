<?php
// Database configuration
// Local:
$host = 'localhost';
$db   = 'mebs_hris';
$user = 'root';
$pass = '';

// For Hostinger:
// $host = 'localhost';
// $db   = 'u123456789_hris';
// $user = 'u123456789_user';
// $pass = 'your_hostinger_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>

