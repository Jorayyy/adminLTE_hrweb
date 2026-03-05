<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=empty&type=admin");
        exit;
    }

    try {
        // Master Admin Credentials
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = 'Administrator';
            header("Location: ../index.php");
            exit;
        }

        // Hardcoded second admin for convenience
        if ($username === 'hr_admin' && $password === 'admin456') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = 'HR Admin';
            header("Location: ../index.php");
            exit;
        }

        // Fallback for invalid admin login
        header("Location: ../login.php?error=invalid&type=admin");
        exit;
    } catch (Exception $e) {
        die("Admin Login error: " . $e->getMessage());
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>