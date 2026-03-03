<?php
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['group_id'] ?? '';
    $date_from = $_POST['date_from'] ?? '';
    $date_to = $_POST['date_to'] ?? '';
    $cover_month = $_POST['cover_month'] ?? '';
    $cover_year = $_POST['cover_year'] ?? '';
    $cut_off_day = $_POST['cut_off_day'] ?? '';
    $pay_date = $_POST['pay_date'] ?? null;
    $description = $_POST['description'] ?? '';

    if (empty($group_id) || empty($date_from) || empty($date_to) || empty($cover_month) || empty($cover_year)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    try {
        error_log("Saving payroll period: group_id=$group_id, from=$date_from, to=$date_to");
        $stmt = $conn->prepare("INSERT INTO payroll_periods (group_id, date_from, date_to, cover_month, cover_year, cut_off_day, pay_date, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$group_id, $date_from, $date_to, $cover_month, $cover_year, $cut_off_day, $pay_date, $description]);
        
        $lastId = $conn->lastInsertId();
        error_log("Successfully saved period ID: $lastId");

        echo json_encode(['status' => 'success', 'message' => 'Payroll period saved successfully! ID: ' . $lastId]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
