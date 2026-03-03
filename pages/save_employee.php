<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conn->beginTransaction();

        $id_number = $_POST['id_number'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $department = $_POST['department'] ?? 'UNASSIGNED';
        $employment_type = $_POST['employment_type'] ?? 'Regular';

        // 1. Save to employees table
        $stmt = $conn->prepare("INSERT INTO employees (id_number, firstname, lastname, department, employment_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_number, $firstname, $lastname, $department, $employment_type]);

        // 2. Save to employees_extended table
        $company = $_POST['company_id'] ?? '';
        $position = $_POST['position'] ?? '';
        $location = $_POST['location'] ?? '';
        $section = $_POST['classification'] ?? ''; 
        
        $stmt_ext = $conn->prepare("INSERT INTO employees_extended (employee_id, company, position, location, section) VALUES (?, ?, ?, ?, ?)");
        $stmt_ext->execute([$id_number, $company, $position, $location, $section]);

        // 3. Save Payroll Group Assignment
        if (!empty($_POST['payroll_group_id'])) {
            $stmt_group = $conn->prepare("INSERT INTO employee_group_assignments (employee_id, group_id) VALUES (?, ?)");
            $stmt_group->execute([$id_number, $_POST['payroll_group_id']]);
        }

        $conn->commit();
        header("Location: employees.php?msg=success");
        exit();
    } catch(Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        die("Error saving record: " . $e->getMessage());
    }
}
?>
