<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";
$emp_id = $_SESSION['emp_id'] ?? '';
$emp_id_number = $_SESSION['emp_id_number'] ?? '';

$page_title = "My Attendance(s)";
include "layout_template.php";

// Filter Parameters
$covered_year = $_GET['year'] ?? date('Y');
$covered_month = $_GET['month'] ?? date('F');
$covered_day = $_GET['day'] ?? date('d');

$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

// Fetch Attendance Logs
$attendance_logs = [];
try {
    $month_num = date('m', strtotime($covered_month));
    $search_date = "$covered_year-$month_num-$covered_day";
    
    $stmt = $conn->prepare("
        SELECT DATE(punch_time) as date, punch_time, punch_type 
        FROM attendance 
        WHERE employee_id = (SELECT id_number FROM employees WHERE id = ?) 
        AND DATE(punch_time) = ?
        ORDER BY punch_time ASC
    ");
    $stmt->execute([$emp_id, $search_date]);
    
    $row_data = [
        'date' => $search_date,
        'day' => date('l', strtotime($search_date)),
        'time_in_date' => '',
        'time_in' => '',
        'break_out' => '',
        'break_in' => '',
        'lunch_out' => '',
        'lunch_in' => '',
        'break_out_2' => '', // Generic Break Out
        'break_in_2' => '',  // Generic Break In
        'time_out_date' => '',
        'time_out' => '',
        'remarks' => '',
        'filed' => ''
    ];

    $has_data = false;
    while ($row = $stmt->fetch()) {
        $has_data = true;
        $time = date('H:i', strtotime($row['punch_time']));
        $p_date = date('Y-m-d', strtotime($row['punch_time']));
        
        switch ($row['punch_type']) {
            case 'Time IN': 
                $row_data['time_in'] = $time; 
                $row_data['time_in_date'] = $p_date;
                break;
            case 'Time OUT': 
                $row_data['time_out'] = $time; 
                $row_data['time_out_date'] = $p_date;
                break;
            case 'Break OUT': $row_data['break_out'] = $time; break;
            case 'Break IN': $row_data['break_in'] = $time; break;
            case 'Lunch OUT': $row_data['lunch_out'] = $time; break;
            case 'Lunch IN': $row_data['lunch_in'] = $time; break;
        }
    }
    
    if ($has_data) {
        $attendance_logs[] = $row_data;
    }

} catch (PDOException $e) {
    // Error handling
}
?>

<style>
    .att-container { background: #fff; border: 1px solid #ddd; margin-top: 10px; padding: 0; }
    .att-header { 
        background: #e9f5e9; 
        padding: 8px 15px; 
        border-bottom: 1px solid #ddd; 
        font-size: 14px; 
        font-weight: bold; 
        color: #2e7d32;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .filter-section { padding: 15px; border-bottom: 1px solid #ddd; }
    .filter-row { display: flex; flex-direction: column; gap: 10px; }
    .filter-item { display: flex; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 5px; }
    .filter-item label { width: 180px; font-weight: bold; font-size: 13px; margin: 0; }
    .filter-item select { flex: 1; border: 1px solid #ced4da; padding: 4px; border-radius: 4px; font-size: 13px; }
    
    .btn-filter { background: #00a65a; color: #fff; border: none; padding: 6px 60px; border-radius: 4px; font-weight: bold; margin-top: 10px; }
    .btn-filter:hover { background: #008d4c; }

    .data-table-container { padding: 15px; overflow-x: auto; }
    .att-table { width: 100%; border-collapse: collapse; font-size: 11px; white-space: nowrap; }
    .att-table th, .att-table td { border: 1px solid #eee; padding: 8px; text-align: left; }
    .att-table th { background: #fff; font-weight: bold; color: #333; text-transform: uppercase; border-bottom: 2px solid #ddd; }
    
    .table-controls { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 13px; }
    .search-box input { border: 1px solid #ddd; padding: 3px 8px; border-radius: 3px; }
    
    .btn-orange { background: #f39c12; color: #fff; font-size: 11px; padding: 4px 10px; border: none; border-radius: 4px; font-weight: bold; }
</style>

<div class="att-container">
    <div class="att-header">
        My Attendance(s)
        <button class="btn-orange">VIEW RAW ATTENDANCES</button>
    </div>
    
    <div class="filter-section">
        <form action="my_attendances.php" method="GET">
            <div class="filter-row">
                <div class="filter-item">
                    <label>Covered Year</label>
                    <select name="year">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Covered Month</label>
                    <select name="month">
                        <?php foreach($months as $m): ?>
                            <option value="<?= $m ?>" <?= $covered_month == $m ? 'selected' : '' ?>><?= $m ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Covered Day</label>
                    <select name="day">
                        <?php for($i=1; $i<=31; $i++): $d=sprintf("%02d", $i); ?>
                            <option value="<?= $d ?>" <?= $covered_day == $d ? 'selected' : '' ?>><?= $d ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-filter">Filter</button>
        </form>
    </div>

    <div class="data-table-container">
        <div class="table-controls">
            <div>Show <select><option>10</option></select> entries</div>
            <div class="search-box">Search: <input type="text"></div>
        </div>
        
        <table class="att-table">
            <thead>
                <tr>
                    <th>COVERED DATE</th>
                    <th>DAY</th>
                    <th>TIME IN DATE</th>
                    <th>TIME IN</th>
                    <th>BREAK OUT</th>
                    <th>BREAK IN</th>
                    <th>LUNCH OUT</th>
                    <th>LUNCH IN</th>
                    <th>BREAK OUT</th>
                    <th>BREAK IN</th>
                    <th>TIME OUT DATE</th>
                    <th>TIME OUT</th>
                    <th>REMARKS</th>
                    <th>FILED(OB/TK)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($attendance_logs)): ?>
                    <tr>
                        <td colspan="14" class="text-center">No data available in table</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($attendance_logs as $log): ?>
                    <tr>
                        <td><?= $log['date'] ?></td>
                        <td><?= $log['day'] ?></td>
                        <td><?= $log['time_in_date'] ?></td>
                        <td><?= $log['time_in'] ?></td>
                        <td><?= $log['break_out'] ?></td>
                        <td><?= $log['break_in'] ?></td>
                        <td><?= $log['lunch_out'] ?></td>
                        <td><?= $log['lunch_in'] ?></td>
                        <td></td>
                        <td></td>
                        <td><?= $log['time_out_date'] ?></td>
                        <td><?= $log['time_out'] ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="table-controls" style="margin-top: 10px;">
            <div>Showing <?= count($attendance_logs) ?> to <?= count($attendance_logs) ?> of <?= count($attendance_logs) ?> entries</div>
            <div class="pagination">
                <button disabled>Previous</button>
                <button disabled>Next</button>
            </div>
        </div>
    </div>
</div>
