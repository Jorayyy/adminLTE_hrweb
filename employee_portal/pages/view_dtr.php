<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";
$emp_id = $_SESSION['emp_id'] ?? '';
$emp_id_number = $_SESSION['emp_id_number'] ?? '';

$page_title = "My Daily Time Record (DTR)";
include "layout_template.php";

// Fetch Payroll Periods for the dropdown
// We'll show periods up to the current date
$current_date = date('Y-m-d');
$periods_stmt = $conn->prepare("
    SELECT p.*, g.group_name 
    FROM payroll_periods p 
    JOIN payroll_period_groups g ON p.group_id = g.id 
    JOIN employee_group_assignments ega ON g.id = ega.group_id
    WHERE ega.employee_id = ? AND p.date_from <= ?
    ORDER BY p.date_from DESC
");
$periods_stmt->execute([$emp_id_number, $current_date]);
$periods = $periods_stmt->fetchAll();

$selected_period_id = $_GET['period_id'] ?? '';
$dtr_data = [];
$period_info = null;

if (!empty($selected_period_id)) {
    // Fetch period details
    $p_stmt = $conn->prepare("SELECT * FROM payroll_periods WHERE id = ?");
    $p_stmt->execute([$selected_period_id]);
    $period_info = $p_stmt->fetch();

    if ($period_info) {
        $date_from = $period_info['date_from'];
        $date_to = $period_info['date_to'];

        // Fetch attendance for this period
        // Fixed: Using DATE(punch_time) because the table uses punch_time (datetime)
        $att_stmt = $conn->prepare("SELECT *, DATE(punch_time) as log_date FROM attendance WHERE employee_id = (SELECT id_number FROM employees WHERE id = ?) AND punch_time BETWEEN ? AND ? ORDER BY punch_time ASC");
        $att_stmt->execute([$emp_id, $date_from . " 00:00:00", $date_to . " 23:59:59"]);
        while ($row = $att_stmt->fetch()) {
            $dtr_data[$row['log_date']] = $row;
        }
    }
}

// Fetch employee extended info
$emp_ext_stmt = $conn->prepare("SELECT * FROM employees_extended WHERE employee_id = ?");
$emp_ext_stmt->execute([$emp_id_number]);
$emp_ext = $emp_ext_stmt->fetch();

$emp_basic_stmt = $conn->prepare("SELECT * FROM employees WHERE id_number = ?");
$emp_basic_stmt->execute([$emp_id_number]);
$emp_basic = $emp_basic_stmt->fetch();
?>

<style>
    .dtr-container { background: #fff; border: 1px solid #ddd; margin-top: 10px; }
    .dtr-header { background: #f8f9fa; padding: 10px 15px; border-bottom: 1px solid #ddd; font-size: 14px; }
    .filter-section { padding: 20px; border-bottom: 1px solid #eee; }
    .form-group-custom { display: flex; align-items: center; gap: 20px; }
    .form-group-custom label { font-weight: bold; margin-bottom: 0; min-width: 120px; }
    .form-control-custom { flex: 1; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; max-width: 600px; }
    
    .dtr-table-container { padding: 15px; }
    .info-box-dtr { background: #003d5b; color: #fff; font-size: 11px; margin-bottom: 0; border: 1px solid #17a2b8; }
    .info-box-dtr .row { margin: 0; border-bottom: 1px solid #17a2b8; }
    .info-box-dtr .col-md-4, .info-box-dtr .col-md-2 { padding: 3px 10px; border-right: 1px solid #17a2b8; }
    .label-dtr { width: 100px; display: inline-block; }
    .val-dtr { font-weight: bold; }

    .table-dtr { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 10px; }
    .table-dtr th, .table-dtr td { border: 1px solid #ddd; padding: 4px; text-align: center; }
    .table-dtr thead th { background: #003d5b; color: white; vertical-align: middle; }
    
    .bg-shift { background-color: #f06292 !important; color: white; }
    .bg-actual { background-color: #69f0ae !important; color: #333; }
    .bg-actual-row { background-color: #e8f5e9; }
    .bg-shift-row { background-color: #fce4ec; }

    .summary-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 15px; }
    .summary-table th { background: #003d5b; color: #fff; padding: 4px; text-align: left; }
    .summary-table td { border: 1px solid #ddd; padding: 4px; }
</style>

<div class="dtr-container">
    <div class="dtr-header">View DTR</div>
    
    <div class="filter-section">
        <form action="view_dtr.php" method="GET">
            <div class="form-group-custom">
                <label>Payroll Period</label>
                <select name="period_id" class="form-control-custom" onchange="this.form.submit()">
                    <option value="">Select Payroll Period</option>
                    <?php foreach ($periods as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $selected_period_id == $p['id'] ? 'selected' : '' ?>>
                            <?= date('F d Y', strtotime($p['date_from'])) ?> to <?= date('F d Y', strtotime($p['date_to'])) ?> (Paydate:<?= $p['pay_date'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <?php if ($period_info): ?>
    <div class="dtr-table-container">
        <div class="mb-2">
            <button class="btn btn-danger btn-sm"><i class="fas fa-print"></i> Print</button>
        </div>

        <div class="info-box-dtr">
            <div class="row" style="background: rgba(0,0,0,0.2);">
                <div class="col-12 p-1">
                    <span class="badge badge-secondary">count 1</span>
                    <span class="ml-4">Salary Rate: daily rate</span>
                    <span class="ml-4">Date Employed: <?= $emp_basic['created_at'] ?? '2025-08-25' ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4"><span class="label-dtr">Payroll Period</span> <span class="val-dtr"><?= $period_info['date_from'] ?> to <?= $period_info['date_to'] ?> id : <?= $period_info['id'] ?></span></div>
                <div class="col-md-4"><span class="label-dtr">Department</span> <span class="val-dtr"><?= strtoupper($emp_basic['department'] ?? 'N/A') ?></span></div>
                <div class="col-md-2"><span class="label-dtr">Employment</span> <span class="val-dtr"><?= $emp_basic['employment_type'] ?? 'Regular' ?></span></div>
                <div class="col-md-2" style="border-right:0;"><span class="label-dtr">Contractual</span> <span class="val-dtr">Contractual</span></div>
            </div>
            <div class="row">
                <div class="col-md-4"><span class="label-dtr">Employee ID</span> <span class="val-dtr"><?= $emp_id_number ?></span></div>
                <div class="col-md-4"><span class="label-dtr">Section</span> <span class="val-dtr"><?= strtoupper($emp_ext['section'] ?? 'N/A') ?></span></div>
                <div class="col-md-2"><span class="label-dtr">Classification</span> <span class="val-dtr">STAFF</span></div>
                <div class="col-md-2" style="border-right:0;"><span class="label-dtr">STAFF</span> <span class="val-dtr">STAFF</span></div>
            </div>
            <div class="row" style="border-bottom:0;">
                <div class="col-md-4"><span class="label-dtr">Name</span> <span class="val-dtr"><?= strtoupper($emp_basic['lastname'] . ', ' . $emp_basic['firstname']) ?></span></div>
                <div class="col-md-4"><span class="label-dtr">Position</span> <span class="val-dtr"><?= strtoupper($emp_ext['position'] ?? 'N/A') ?></span></div>
                <div class="col-md-2"><span class="label-dtr">Pay Type</span> <span class="val-dtr">Weekly</span></div>
                <div class="col-md-2" style="border-right:0;"><span class="label-dtr">Location</span> <span class="val-dtr"><?= strtoupper($emp_ext['location'] ?? 'N/A') ?></span></div>
            </div>
        </div>

        <table class="table-dtr">
            <thead>
                <tr>
                    <th rowspan="2">Date</th>
                    <th rowspan="2">Day</th>
                    <th colspan="2" class="bg-shift">Shift Time</th>
                    <th colspan="2" class="bg-actual">Actual Time</th>
                    <th rowspan="2">Late</th>
                    <th rowspan="2">Over break</th>
                    <th rowspan="2">Undertime</th>
                    <th colspan="2">Hours Worked</th>
                    <th colspan="2">Overtime</th>
                    <th colspan="2">Holiday</th>
                    <th colspan="2">Restday</th>
                    <th rowspan="2">ND</th>
                    <th rowspan="2">ATRO</th>
                    <th rowspan="2">Change Sched/ Restday</th>
                    <th rowspan="2">Leave</th>
                    <th rowspan="2">Official Business</th>
                    <th rowspan="2">Timekeeping Form</th>
                    <th rowspan="2">Undertime</th>
                </tr>
                <tr>
                    <th class="bg-shift">IN</th>
                    <th class="bg-shift">OUT</th>
                    <th class="bg-actual">IN</th>
                    <th class="bg-actual">OUT</th>
                    <th>REG</th>
                    <th>ND</th>
                    <th>Reg</th>
                    <th>Restday</th>
                    <th>Spec</th>
                    <th>Reg</th>
                    <th>Spec</th>
                    <th>Reg</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $start = strtotime($period_info['date_from']);
                $end = strtotime($period_info['date_to']);
                $total_reg_hrs = 0;
                $total_tardiness = 0;

                while ($start <= $end) {
                    $date_curr = date('Y-m-d', $start);
                    $day_name = date('D', $start);
                    $data = $dtr_data[$date_curr] ?? null;
                    
                    $time_in = $data && $data['time_in'] ? date('H:i', strtotime($data['time_in'])) : '--:--';
                    $time_out = $data && $data['time_out'] ? date('H:i', strtotime($data['time_out'])) : '--:--';
                    $is_absent = $data && $data['absent'] ? true : false;
                    $reg_hrs = $data ? 8 : 0; // Placeholder
                    if (!$is_absent) $total_reg_hrs += $reg_hrs;
                    ?>
                    <tr>
                        <td><?= date('m-d', $start) ?></td>
                        <td><?= $day_name ?></td>
                        <td class="bg-shift-row">21:00</td>
                        <td class="bg-shift-row">07:00</td>
                        <td class="bg-actual-row"><?= $time_in ?></td>
                        <td class="bg-actual-row"><?= $time_out ?></td>
                        <td><?= $data['tardiness_mins'] ?? '' ?></td>
                        <td></td>
                        <td></td>
                        <td class="<?= $is_absent ? 'text-danger' : '' ?>"><?= $is_absent ? 'absent' : $reg_hrs ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    $start = strtotime("+1 day", $start);
                }
                ?>
            </tbody>
        </table>

        <!-- Summary Table -->
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Regular</th>
                    <th>Restday</th>
                    <th>Regular Holiday</th>
                    <th>Special Holiday</th>
                    <th>Description</th>
                    <th>Total</th>
                    <th>Occurence</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Regular</td><td><?= number_format($total_reg_hrs, 2) ?></td><td>0.00</td><td>0.00</td><td>0.00</td><td>absences</td><td>0</td><td>0</td>
                </tr>
                <tr>
                    <td>Regular-ND</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>undertime</td><td>0</td><td>0</td>
                </tr>
                <tr>
                    <td>OVERTIME</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>tardiness</td><td>0</td><td>0</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="p-4 text-center text-muted">Please select a payroll period to view your Daily Time Record.</div>
    <?php endif; ?>
</div>
