<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Log the raw POST for debugging if it keeps failing
    // error_log("Raw POST received: " . print_r($_POST, true));

    $employee_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';
    $internal_id = isset($_POST['internal_id']) ? trim($_POST['internal_id']) : '';
    $group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';

    // If both are absolutely empty, we can't continue
    if ($employee_id === '' && $internal_id === '') {
        echo "<h3>Debug Information</h3>";
        echo "<p>No ID received from the form.</p>";
        echo "<pre>POST Data: ";
        print_r($_POST);
        echo "</pre>";
        die("Error: No employee identifier provided.");
    }

    try {
        $conn->beginTransaction();

        // 1. Find the employee ID Number (which is the FK in assignments table)
        // We look up by the provided Badge ID OR the Database Internal ID
        $find_sql = "SELECT id_number FROM employees WHERE (id_number = ? AND id_number != '') OR id = ?";
        $check_stmt = $conn->prepare($find_sql);
        $check_stmt->execute([$employee_id, $internal_id]);
        $row = $check_stmt->fetch();
        
        if ($row) {
            $actual_id_number = $row['id_number'];

            // 2. Clear old assignments first
            $del_stmt = $conn->prepare("DELETE FROM employee_group_assignments WHERE employee_id = ?");
            $del_stmt->execute([$actual_id_number]);

            // 3. Save new group if one was selected
            if (!empty($group_id)) {
                $ins_stmt = $conn->prepare("INSERT INTO employee_group_assignments (employee_id, group_id) VALUES (?, ?)");
                $ins_stmt->execute([$actual_id_number, $group_id]);
            }
        } else {
            $id_display = $employee_id ? $employee_id : "DB ID " . $internal_id;
            throw new Exception("Employee ($id_display) was not found in the Masterlist database.");
        }

        $conn->commit();
        header("Location: employees.php?msg=updated_group");
        exit();
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        die("Error updating group: " . $e->getMessage());
    }
} else {
    header("Location: employees.php");
    exit();
}
?>