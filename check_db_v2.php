<?php
require_once 'config/db.php';
try {
    $stmt = $conn->query('SELECT COUNT(*) FROM payroll_periods');
    echo 'Total periods: ' . $stmt->fetchColumn() . "\n";
    $stmt = $conn->query('SELECT p.*, g.group_name FROM payroll_periods p JOIN payroll_period_groups g ON p.group_id = g.id');
    while($row = $stmt->fetch()) {
        echo 'Group: ' . $row['group_name'] . ' | From: ' . $row['date_from'] . ' To: ' . $row['date_to'] . "\n";
    }
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
