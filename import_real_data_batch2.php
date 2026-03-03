<?php
require_once 'config/db.php';

// Real employee data from the screenshot
$real_employees = [
    ['id' => '220045', 'name' => 'RESOS , EDMARK N/A', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN - GOOGLE SEARCH'],
    ['id' => '220064', 'name' => 'ORAG , REGINA JUSTICA ROSE MARTE', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'HR', 'location' => 'SOGOD', 'department' => 'ADMIN', 'section' => 'Sogod Admin'],
    ['id' => '220082', 'name' => 'BORNIDOR , MYLEN MONTERA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN - GOOGLE SEARCH'],
    ['id' => '220094', 'name' => 'LIMSIACO , MIRAFLOR LEGASPI', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'TL/OIC', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'ORMOC ADMIN'],
    ['id' => '220110', 'name' => 'SERATA , RM JYRA CIPRES', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN TAX'],
    ['id' => '220112', 'name' => 'LOZANO , DIANNE MARIE DELAMBACA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'SOGOD', 'department' => 'SALES', 'section' => 'SOGOD - GOOGLE SEARCH'],
    ['id' => '220119', 'name' => 'SALISID , JERVIE MAE N/A', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'TL', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220122', 'name' => 'ANORE , SUZETTE MENDOZA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220129', 'name' => 'VARGAS, JR. , ERNESTO PASTERA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN - GOOGLE SEARCH'],
    ['id' => '220148', 'name' => 'DELA TORRE , CRISRINE JAMES PONCE', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'TL', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'MAASIN ADMIN'],
];

try {
    $conn->beginTransaction();

    foreach ($real_employees as $emp) {
        // Split name (Simplified: first part is Lastname, second is Firstname)
        $name_parts = explode(',', $emp['name']);
        $lastname = trim($name_parts[0]);
        $firstname = trim($name_parts[1] ?? '');

        // Insert into employees table
        $stmt = $conn->prepare("INSERT IGNORE INTO employees (id_number, lastname, firstname, department, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$emp['id'], $lastname, $firstname, $emp['department'], 'Active']);

        // Insert into employees_extended table
        $stmt_ext = $conn->prepare("INSERT IGNORE INTO employees_extended (employee_id, company, position, location, section) VALUES (?, ?, ?, ?, ?)");
        $stmt_ext->execute([$emp['id'], $emp['company'], $emp['position'], $emp['location'], $emp['section']]);
    }

    $conn->commit();
    echo "Batch 2 real employee data successfully imported.\n";
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
