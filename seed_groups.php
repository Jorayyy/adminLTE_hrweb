<?php
require_once 'config/db.php';

$seed_groups = [
    ['Weekly', 'WEEKLY MEDICARE GROUP', 'WEEKLY MEDICARE GROUP', 'deactivated', 0],
    ['Weekly', 'WEEKLY NIGHTSHIFT GROUP', 'WEEKLY NIGHTSHIFT GROUP', 'deactivated', 0],
    ['Semi-Monthly', 'SEMI-MONTHLY MORNING GROUP', 'SEMI-MONTHLY MORNING GROUP', 'active', 1],
    ['Weekly', 'MAASIN NIGHTSHIFT GROUP', 'MAASIN SITE ONLY', 'active', 1],
    ['Weekly', 'CEBU WEEKLY GROUP', 'CEBU SITE ONLY', 'active', 1],
    ['Weekly', 'TACLOBAN WEEKLY GROUP', 'TACLOBAN SITE ONLY', 'active', 1],
    ['Weekly', 'SOGOD WEEKLY GROUP', 'SOGOD SITE ONLY', 'active', 1],
    ['Weekly', 'ORMOC NIGHTSHIFT GROUP', 'ORMOC SITE ONLY', 'active', 1],
    ['Weekly', 'UNITED TOWING 2ND AND 3RD SHIFT WEEKLY GROUP', 'FOR 2ND AND 3RD SHIFT ONLY', 'deactivated', 0],
    ['Weekly', 'MAASIN MEDICARE WEEKLY GROUP', 'MAASIN MEDICARE WEEKLY GROUP', 'deactivated', 0],
    ['Weekly', 'ORMOC MEDICARE WEEKLY GROUP', 'ORMOC MEDICARE WEEKLY GROUP', 'deactivated', 0],
    ['Weekly', 'SOGOD MEDICARE WEEKLY GROUP', 'SOGOD MEDICARE WEEKLY GROUP', 'deactivated', 0],
    ['Weekly', 'TACLOBAN MEDICARE WEEKLY GROUP', 'TACLOBAN MEDICARE WEEKLY GROUP', 'deactivated', 0],
    ['Weekly', 'MEBS CONSTUCTION', 'MEBS CONSTUCTION', 'active', 1],
];

try {
    // Clear existing to avoid duplicates if re-running
    $conn->exec("DELETE FROM payroll_period_groups");
    
    $stmt = $conn->prepare("INSERT INTO payroll_period_groups (pay_type, group_name, description, status, is_on) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($seed_groups as $group) {
        $stmt->execute($group);
    }
    
    echo "Successfully seeded " . count($seed_groups) . " payroll period groups into the database.\n";
} catch (PDOException $e) {
    echo "Error seeding database: " . $e->getMessage() . "\n";
}
?>
