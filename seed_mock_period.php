<?php
require_once 'config/db.php';

try {
    // 0. Ensure employee 222065 exists in the employees masterlist
    $emp_check = $conn->prepare("SELECT COUNT(*) FROM employees WHERE id_number = '222065'");
    $emp_check->execute();
    if ($emp_check->fetchColumn() == 0) {
        $conn->exec("INSERT INTO employees (id_number, firstname, lastname, department, status) 
                     VALUES ('222065', 'MARK JORY A.', 'ANDRADE', 'ADMIN', 'Active')");
    }

    // 1. Ensure a group exists
    $conn->exec("INSERT IGNORE INTO payroll_period_groups (id, pay_type, group_name, is_on) VALUES (1, 'Weekly', 'TACLOBAN ADMIN', 1)");

    // 2. Create a mock period for last week: Feb 23 to March 01
    $conn->exec("INSERT IGNORE INTO payroll_periods (id, group_id, date_from, date_to, pay_date, description) 
                 VALUES (1394, 1, '2026-02-23', '2026-03-01', '2026-03-07', 'Mock Period Last Week')");

    // 3. Assign the employee '222065' to this group
    $check = $conn->prepare("SELECT COUNT(*) FROM employee_group_assignments WHERE employee_id = '222065' AND group_id = 1");
    $check->execute();
    if ($check->fetchColumn() == 0) {
        $conn->exec("INSERT INTO employee_group_assignments (employee_id, group_id) VALUES ('222065', 1)");
    }

    echo "Mock employee, payroll period, and group assignment created successfully.";
} catch (PDOException $e) {
    echo "Query Error: " . $e->getMessage();
}
?>
