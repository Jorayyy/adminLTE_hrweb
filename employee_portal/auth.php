<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty");
        exit;
    }

    try {
        // Employee login: Username and Password are both the ID (id_number)
        if ($username === $password) {
            $stmt = $conn->prepare("SELECT id, id_number, firstname, lastname FROM employees WHERE id_number = ? AND status = 'Active'");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['emp_logged_in'] = true;
                $_SESSION['emp_id'] = $user['id'];
                $_SESSION['emp_id_number'] = $user['id_number'];
                $_SESSION['emp_fullname'] = $user['firstname'] . ' ' . $user['lastname'];
                
                header("Location: pages/dashboard.php");
                exit;
            }
        }
        
        header("Location: login.php?error=invalid");
        exit;
    } catch (PDOException $e) {
        die("Login error: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit;
}
?>
