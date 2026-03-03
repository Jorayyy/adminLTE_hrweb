<?php
require_once 'config/db.php';

// Real employee data from the screenshot
$real_employees = [
    ['id' => '220001', 'name' => 'BUBA , RIZA GRANADIROS', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220002', 'name' => 'GARONG , GEMMA FAJARDO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220003', 'name' => 'DAGANATO , KIMBERLY PEREZ', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220004', 'name' => 'ASEO , MARGIELYN MACEDA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220005', 'name' => 'LLEVARES , MARY NICOLE ANN', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220010', 'name' => 'GARCIA , MAE ANN ALICO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220011', 'name' => 'MASONG , MICHAEL MATIGINA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220012', 'name' => 'BALORIO , SHERAME LABASTIDA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'OPERATIONS OFFICER', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'ACCOUNTING', 'section' => 'ACCOUNTING'],
    ['id' => '220013', 'name' => 'DATO-ON , ALYSSA BERNADETTE PEDALINO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'SITE MANAGER', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'MAASIN ADMIN'],
    ['id' => '220029', 'name' => 'QUINE , KENNY ORAIS', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN - GOOGLE SEARCH'],
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
    echo "Real employee data successfully imported.\n";
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
