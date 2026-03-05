<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=empty");
        exit;
    }

    try {
        // --- ADMIN LOGIN (MASTER) ---
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_fullname'] = 'Administrator';
            $_SESSION['admin_role'] = 'Master';
            header("Location: ../index.php"); // Redirect to Admin Dashboard
            exit;
        }

        // --- EMPLOYEE LOGIN (DB CHECK) ---
        $stmt = $conn->prepare("SELECT id, id_number, firstname, lastname FROM employees WHERE id_number = ? AND status = 'Active'");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // If password matches the username (id_number) - basic security for now
        if ($user && $password === $user['id_number']) {
            $_SESSION['emp_logged_in'] = true;
            $_SESSION['emp_id'] = $user['id'];
            $_SESSION['emp_id_number'] = $user['id_number'];
            $_SESSION['emp_fullname'] = $user['firstname'] . ' ' . $user['lastname'];
            
            header("Location: pages/dashboard.php");
            exit;
        }
        
        // Attempt the c_cajes manually if still not found in DB
        if ($username === 'c_cajes' && $password === 'password123') {
            $_SESSION['emp_logged_in'] = true;
            $_SESSION['emp_id'] = 999;
            $_SESSION['emp_id_number'] = '222065';
            $_SESSION['emp_fullname'] = 'Cristine B. Cajes';
            header("Location: pages/dashboard.php");
            exit;
        }

        header("Location: ../login.php?error=invalid");
        exit;
    } catch (PDOException $e) {
        die("Login error: " . $e->getMessage());
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
