<?php
/**
 * SEED SCRIPT FOR PAYROLL PERIODS ONLY
 * Run via SSH: php seed_periods.php
 */

require_once 'config/db.php';

echo "--- Seeding Payroll Periods and Group Assignments ---\n";

try {
    $conn->beginTransaction();

    // 1. Ensure the Payroll Group exists (ID 1)
    $conn->exec("INSERT IGNORE INTO payroll_period_groups (id, pay_type, group_name, is_on) 
                 VALUES (1, 'Weekly', 'TACLOBAN ADMIN', 1)");
    echo "[1/3] Payroll group verified.\n";

    // 2. Create the Mock Payroll Period
    // Current date is March 06, 2026. 
    // Period: Feb 23 to March 01 | Pay Date: March 07, 2026
    $conn->exec("INSERT IGNORE INTO payroll_periods (id, group_id, date_from, date_to, pay_date, description) 
                 VALUES (1394, 1, '2026-02-23', '2026-03-01', '2026-03-07', 'Mock Period Feb 23 - Mar 01')");
    echo "[2/3] Payroll period (ID: 1394) seeded.\n";

    // 3. Assign specific employees to this group so they can see the period
    // ID 222065 (Mark Jory Andrade)
    $conn->exec("INSERT IGNORE INTO employee_group_assignments (employee_id, group_id) VALUES ('222065', 1)");
    
    // IDs from your batch imports
    $conn->exec("INSERT IGNORE INTO employee_group_assignments (employee_id, group_id) VALUES ('220001', 1)"); // RIZA BUBA
    $conn->exec("INSERT IGNORE INTO employee_group_assignments (employee_id, group_id) VALUES ('220163', 1)"); // CRISTINE CAJES
    $conn->exec("INSERT IGNORE INTO employee_group_assignments (employee_id, group_id) VALUES ('220045', 1)"); // RESOS
    $conn->exec("INSERT IGNORE INTO employee_group_assignments (employee_id, group_id) VALUES ('220064', 1)"); // ORAG
    
    echo "[3/3] Employee group assignments completed.\n";

    $conn->commit();
    echo "\n✔ PERIOD SEEDING COMPLETED SUCCESSFULLY.\n";

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo "\nFATAL ERROR: " . $e->getMessage() . "\n";
}
