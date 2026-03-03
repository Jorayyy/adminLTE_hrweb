<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_number = $_POST['id_number'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $department = $_POST['department'];
    $employment_type = $_POST['employment_type'];

    try {
        $stmt = $conn->prepare("INSERT INTO employees (id_number, firstname, lastname, department, employment_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_number, $firstname, $lastname, $department, $employment_type]);
        
        header("Location: employees.php?msg=success");
        exit();
    } catch(PDOException $e) {
        die("Error saving record: " . $e->getMessage());
    }
}
?>
