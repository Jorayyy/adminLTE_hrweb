<?php
require_once 'config/db.php';

// Real employee data from the 3 screenshots provided
$real_employees = [
    // Screenshot 1
    ['id' => '220156', 'name' => 'TUMULAK , NIEL GRANT N/A', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'TL/OIC', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'ORMOC ADMIN'],
    ['id' => '220163', 'name' => 'CAJES , CRISTINE BERNADETTE B.', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'TACLOBAN MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'TACLOBAN ADMIN'],
    ['id' => '220174', 'name' => 'LLUZ , YVONNE MABANSAG', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'TL/OIC', 'location' => 'TACLOBAN MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'TACLOBAN ADMIN'],
    ['id' => '220223', 'name' => 'ALIMORONG , JOHN PAUL SEÑORAN', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN SHIRLY SAFE & RELIABLE'],
    ['id' => '220225', 'name' => 'MATERO , RUBY ANN ITALLO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'OIC', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'MAASIN ADMIN'],
    ['id' => '220230', 'name' => 'LOLO , REMELYN GRACE CANINO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN - GOOGLE SEARCH'],
    ['id' => '220374', 'name' => 'DUMAGSA , IVY N/A', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220414', 'name' => 'ARDIENTE , JUN ARIES', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'GO JAPAN'],
    ['id' => '220416', 'name' => 'ESAGA , DEN-DEN LORA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'STAFF', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'ADMIN', 'section' => 'MAASIN ADMIN'],
    ['id' => '220426', 'name' => 'MORAYA , RACHEL', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN INTEGRITY FUNDING'],
    
    // Screenshot 2
    ['id' => '220437', 'name' => 'MORE , MARY JEAN LUCERO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220447', 'name' => 'CAÑON , DAVE PADUNGAO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN - GOOGLE SEARCH'],
    ['id' => '220482', 'name' => 'CAÑETE , RICA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC TAX'],
    ['id' => '220483', 'name' => 'AGUIPO , JAMES SOFRONIO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC TAX'],
    ['id' => '220495', 'name' => 'DOMINGUITO , JAYVI BATINO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220496', 'name' => 'RULONA , MICHEGUNY', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220507', 'name' => 'LLEVE , CHRISTIAN', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'SOGOD', 'department' => 'SALES', 'section' => 'SOGOD - GOOGLE SEARCH'],
    ['id' => '220520', 'name' => 'DAWAL , ROBERT', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'TL', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'MARKETING STAFF', 'section' => 'MARKETING STAFF'],
    ['id' => '220533', 'name' => 'GARING , ALDEVEN', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220537', 'name' => 'CADORNA , MARK GLEN', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'TACLOBAN MAIN OFFICE', 'department' => 'SALES', 'section' => 'TACLOBAN TIMESHARE'],
    
    // Screenshot 3
    ['id' => '220545', 'name' => 'CENIZA , ROSELYN', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'TL', 'location' => 'SOGOD', 'department' => 'ADMIN', 'section' => 'Sogod Admin'],
    ['id' => '220638', 'name' => 'ERASMO , JIECEL TOMARONG', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220646', 'name' => 'BEREZO , LORRY MARK DAYDAY', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220649', 'name' => 'DIANO , JAY-AR BESTUDIO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'ORMOC MAIN OFFICE', 'department' => 'SALES', 'section' => 'ORMOC GOOGLE SEARCH'],
    ['id' => '220670', 'name' => 'SUMABAT , BOY ORIT', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'STAFF', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'UTILITY'],
    ['id' => '220691', 'name' => 'SABLAD , LARA MAE ABRUGAR', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'TACLOBAN MAIN OFFICE', 'department' => 'SALES', 'section' => 'TACLOBAN GOOGLE SEARCH'],
    ['id' => '220704', 'name' => 'LOVETE , ALEXANDER GUIMBA', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'SOGOD', 'department' => 'SALES', 'section' => 'SOGOD - GOOGLE SEARCH'],
    ['id' => '220710', 'name' => 'HERNANDEZ , JENELA EUNICE REYES', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'SALES', 'section' => 'MAASIN - GOOGLE SEARCH'],
    ['id' => '220713', 'name' => 'PALER , DIVINE LYKA ORDIZ', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'STAFF', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'ACCOUNTING', 'section' => 'ACCOUNTING'],
    ['id' => '220714', 'name' => 'MAPALO , IRMA ROSELLO', 'company' => 'Mancao Electronic Connect Business Solutions OPC', 'position' => 'AGENT', 'location' => 'MAASIN MAIN OFFICE', 'department' => 'ACCOUNTING', 'section' => 'ACCOUNTING'],
];

try {
    $conn->beginTransaction();

    foreach ($real_employees as $emp) {
        $name_parts = explode(',', $emp['name']);
        $lastname = trim($name_parts[0]);
        $firstname = trim($name_parts[1] ?? '');

        $stmt = $conn->prepare("INSERT IGNORE INTO employees (id_number, lastname, firstname, department, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$emp['id'], $lastname, $firstname, $emp['department'], 'Active']);

        $stmt_ext = $conn->prepare("INSERT IGNORE INTO employees_extended (employee_id, company, position, location, section) VALUES (?, ?, ?, ?, ?)");
        $stmt_ext->execute([$emp['id'], $emp['company'], $emp['position'], $emp['location'], $emp['section']]);
    }

    $conn->commit();
    echo "Batch 3 real employee data (30 records) successfully imported.\n";
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
