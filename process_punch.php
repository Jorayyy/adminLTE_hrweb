<?php
require_once 'config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$employee_id = $_POST['employee_id'] ?? '';
$security_code = $_POST['security_code'] ?? '';
$punch_type = $_POST['punch_type'] ?? '';

if (empty($employee_id) || empty($security_code)) {
    echo json_encode(['success' => false, 'message' => 'Employee ID and Security Code are required']);
    exit;
}

try {
    // 1. Verify Employee and Security Code
    // Pattern: Security Code must be Employee ID + 4 random characters
    // Example: ID 222065 -> Code 22206512fd
    if (strpos($security_code, $employee_id) !== 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid Security Code format']);
        exit;
    }

    // Check if employee exists in database
    $stmt = $conn->prepare("SELECT id, id_number, firstname, lastname FROM employees WHERE id_number = ?");
    $stmt->execute([$employee_id]);
    $employee = $stmt->fetch();

    if (!$employee) {
        echo json_encode(['success' => false, 'message' => 'Employee ID not found in Masterlist']);
        exit;
    }

    // 2. record the punch (we'll create an attendance table if it doesn't exist)
    $conn->exec("CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id VARCHAR(100) NOT NULL,
        punch_type VARCHAR(50) NOT NULL,
        punch_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX(employee_id)
    )");

    $ins_stmt = $conn->prepare("INSERT INTO attendance (employee_id, punch_type) VALUES (?, ?)");
    $ins_stmt->execute([$employee_id, $punch_type]);

    $now = new DateTime();
    $dateStr = $now->format('Y-m-d');
    $timeStr = $now->format('H:i:s');

    echo json_encode([
        'success' => true, 
        'message' => "✔ Your $punch_type For $dateStr : $timeStr is Successfully Saved.",
        'employee_name' => $employee['firstname'] . ' ' . $employee['lastname']
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
