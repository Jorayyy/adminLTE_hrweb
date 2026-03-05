<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";
$emp_id = $_SESSION['emp_id'] ?? ''; // Fixed from auth.php session variable emp_id

$page_title = "My Calendar";
include "layout_template.php";

// Simple Month/Year Logic
$month = isset($_GET['month']) ? (int)$_GET['month'] : 3;
$year = isset($_GET['year']) ? (int)$_GET['year'] : 2026;

// Fetch Actual Attendance from DB
$attendanceData = [];
$breakData = []; // To store break info
if (!empty($emp_id)) {
    try {
        $monthStr = sprintf("%02d", $month);
        $stmt = $conn->prepare("SELECT date, time_in, time_out, absent FROM attendance WHERE employee_id = ? AND date LIKE ?");
        $stmt->execute([$emp_id, "$year-$monthStr-%"]);
        
        while ($row = $stmt->fetch()) {
            $day = (int)date('j', strtotime($row['date']));
            if ($row['absent']) {
                $attendanceData[$day] = "ABSENT";
            } else if ($row['time_in'] && $row['time_out']) {
                $in = date('H:i', strtotime($row['time_in']));
                $out = date('H:i', strtotime($row['time_out']));
                $attendanceData[$day] = "$in IN - $out OUT";
            }
        }

        // Fetch Actual Breaks from DB
        // Based on process_punch.php, logs are stored in attendance table with punch_type
        $stmt = $conn->prepare("
            SELECT DATE(punch_time) as date, punch_time, punch_type 
            FROM attendance 
            WHERE employee_id = (SELECT id_number FROM employees WHERE id = ?) 
            AND punch_time LIKE ? 
            AND punch_type IN ('Break IN', 'Break OUT', 'Lunch IN', 'Lunch OUT')
            ORDER BY punch_time ASC
        ");
        $stmt->execute([$emp_id, "$year-$monthStr-%"]);
        
        $tempBreaks = [];
        while ($row = $stmt->fetch()) {
            $day = (int)date('j', strtotime($row['date']));
            $type = $row['punch_type'];
            $time = date('H:i', strtotime($row['punch_time']));
            
            // Logic to pair IN/OUT
            if (strpos($type, 'IN') !== false) {
                $tempBreaks[$day][str_replace(' IN', '', $type)]['start'] = $time;
            } else {
                $tempBreaks[$day][str_replace(' OUT', '', $type)]['end'] = $time;
            }
        }

        foreach ($tempBreaks as $day => $types) {
            foreach ($types as $typeName => $times) {
                $start = $times['start'] ?? '--:--';
                $end = $times['end'] ?? '--:--';
                $breakData[$day][] = [
                    'label' => "$start - $end",
                    'type' => (strpos(strtolower($typeName), 'break') !== false) ? 'morning' : 'lunch'
                ];
            }
        }

    } catch (PDOException $e) {
        // Log or handle error
    }
}

$dateObj = DateTime::createFromFormat('!m-Y', "$month-$year");
$monthName = $dateObj->format('F');
$daysInMonth = (int)$dateObj->format('t');
$firstDayOfMonth = (int)$dateObj->format('w'); // 0 (Sun) to 6 (Sat)

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

$legend_items = [
    ["Individually Plotted", "#8B0000"],
    ["Group Plotted", "#FF0000"],
    ["Fixed Schedule", "#808080"],
    ["Timekeeping Complaint", "#4B0082"],
    ["Employee Leave", "#FF1493"],
    ["Change of Restday", "#008B8B"],
    ["Change of Schedule", "#FF8C00"],
    ["Official Business", "#DAA520"],
    ["Raw Attendance", "#FF69B4"],
    ["Posted Attendance", "#9400D3"],
    ["Posted Working Schedule", "#A52A2A"],
    ["Original Rest day", "#AFEEEE"],
    ["Morning Break", "#D3D3D3"],
    ["Lunch Break", "#bdb76b"],
    ["Afternoon Break", "#483d8b"],
];
?>
<style>
    .calendar-container { display: flex; gap: 20px; align-items: flex-start; }
    .viewing-options { width: 350px; background: #fff; border: 1px solid #ddd; border-top: 2px solid #d9534f; max-height: 550px; display: flex; flex-direction: column; }
    .calendar-main { flex: 1; background: #fff; border: 1px solid #ddd; padding: 15px; }
    
    .viewing-options .header { padding: 10px; text-align: center; font-weight: bold; border-bottom: 1px solid #eee; font-size: 14px; flex-shrink: 0; }
    .options-scrollable { overflow-y: auto; flex-grow: 1; }
    .option-item { padding: 8px 15px; border-bottom: 1px solid #f9f9f9; display: flex; align-items: center; font-size: 12px; color: #3c8dbc; cursor: pointer; }
    .option-item input { margin-right: 20px; }
    
    .legend-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .legend-table th { background: #e9f5e9; padding: 5px; font-size: 11px; text-align: center; border: 1px solid #eee; }
    .legend-table td { padding: 5px 10px; border: 1px solid #eee; font-size: 11px; }
    .color-box { width: 40px; height: 15px; border: 1px solid #333; margin: 0 auto; }
    
    .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .calendar-header h4 { margin: 0; color: #333; font-weight: normal; }
    .calendar-nav { display: flex; gap: 5px; }
    .nav-btn { padding: 2px 10px; border: 1px solid #ccc; background: #fff; cursor: pointer; border-radius: 3px; font-size: 12px; }
    
    .cal-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .cal-table th { border: 1px solid #ddd; padding: 5px; background: #fcfcfc; font-size: 12px; font-weight: normal; }
    .cal-table td { border: 1px solid #ddd; height: 100px; padding: 0; text-align: right; vertical-align: top; font-size: 11px; color: #999; position: relative; }
    .day-num { padding: 5px; }
    .cal-today { background: #fffbe6; }
    
    .schedule-bar { 
        display: none; 
        background: #8B0000; 
        color: #fff; 
        font-size: 10px; 
        text-align: center; 
        padding: 2px 0; 
        margin-top: 5px; 
        width: 100%;
        font-weight: bold;
    }
    .schedule-bar.restday { background: #8B0000; }

    .attendance-bar {
        display: none;
        background: #9400D3; /* Posted Attendance Color */
        color: #fff;
        font-size: 10px;
        text-align: center;
        padding: 2px 0;
        margin-top: 2px;
        width: 100%;
        font-weight: bold;
    }
    .attendance-bar.absent {
        background: #9400D3;
    }

    .break-bar {
        display: none;
        background: #bdb76b; /* Lunch Break Color */
        color: #fff;
        font-size: 10px;
        text-align: center;
        padding: 2px 0;
        margin-top: 2px;
        width: 100%;
        font-weight: bold;
    }
    .break-bar.morning { background: #D3D3D3; color: #333; }
    .break-bar.lunch { background: #bdb76b; }
    .break-bar.afternoon { background: #483d8b; }
</style>

<div class="calendar-container">
    <!-- Left Section: Viewing Options & Legend -->
    <div class="viewing-options">
        <div class="header">Viewing Options</div>
        <div class="options-scrollable">
            <div class="option-item"><input type="checkbox" id="checkViewSchedules"> View Schedules</div>
            <div class="option-item"><input type="checkbox" id="checkViewAttendance" checked> View Attendance</div>
            <div class="option-item"><input type="checkbox" id="checkViewApprovedForms"> View Approved Forms</div>
            <div class="option-item"><input type="checkbox" id="checkViewBreaks" checked> View Breaks</div>
            
            <table class="legend-table">
                <tr>
                    <th>Legend</th>
                    <th>Color Code</th>
                </tr>
                <?php foreach($legend_items as $item): ?>
                <tr>
                    <td><?= $item[0] ?></td>
                    <td><div class="color-box" style="background: <?= $item[1] ?>;"></div></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- Right Section: Actual Calendar -->
    <div class="calendar-main">
        <div class="calendar-header">
            <h4><?= $monthName . " " . $year ?></h4>
            <div class="calendar-nav">
                <a href="my_calendar.php" class="nav-btn" style="text-decoration: none; color: inherit;">today</a>
                <a href="my_calendar.php?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="nav-btn" style="text-decoration: none; color: inherit;"><</a>
                <a href="my_calendar.php?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="nav-btn" style="text-decoration: none; color: inherit;">></a>
            </div>
        </div>
        
        <table class="cal-table">
            <thead>
                <tr>
                    <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // March 2026 Schedule Data (Mock)
                $schedules = [
                    1 => "15:00 to 01:00", 2 => "21:00 to 07:00", 3 => "21:00 to 07:00", 4 => "21:00 to 07:00", 
                    5 => "21:00 to 07:00", 6 => "21:00 to 07:00", 7 => "RESTDAY", 8 => "RESTDAY",
                    9 => "20:00 to 06:00", 10 => "20:00 to 06:00", 11 => "20:00 to 06:00", 12 => "20:00 to 06:00", 
                    13 => "20:00 to 06:00", 14 => "RESTDAY", 15 => "RESTDAY", 16 => "20:00 to 06:00",
                    17 => "20:00 to 06:00", 18 => "20:00 to 06:00", 19 => "20:00 to 06:00", 20 => "20:00 to 06:00", 
                    21 => "RESTDAY", 22 => "RESTDAY", 23 => "20:00 to 06:00", 24 => "20:00 to 06:00",
                    25 => "20:00 to 06:00", 26 => "20:00 to 06:00", 27 => "20:00 to 06:00", 28 => "RESTDAY"
                ];

                $dayCounter = 1;
                for ($i = 0; $i < 6; $i++) { // Max 6 weeks
                    echo "<tr>";
                    for ($j = 0; $j < 7; $j++) {
                        $currentPos = $i * 7 + $j;
                        if ($currentPos < $firstDayOfMonth || $dayCounter > $daysInMonth) {
                            echo "<td></td>";
                        } else {
                            $isToday = ($dayCounter == 6 && $month == 3 && $year == 2026) ? "cal-today" : "";
                            echo "<td class='$isToday'>";
                            echo "<div class='day-num'>$dayCounter</div>";
                            // For this prototype, we'll show the mock schedule for any month to simulate full occupancy
                            $dayModulo = $dayCounter % 14; 
                            if ($dayModulo == 7 || $dayModulo == 0 || $dayModulo == 1) {
                                $shift = "RESTDAY";
                            } else if ($dayModulo < 7) {
                                $shift = "21:00 to 07:00";
                            } else {
                                $shift = "20:00 to 06:00";
                            }
                            
                            $barClass = ($shift == "RESTDAY") ? "restday" : "";
                            echo "<div class='schedule-bar $barClass' style='display:none;'>$shift</div>";

                            // Display Actual Attendance Data from DB
                            $attendance = $attendanceData[$dayCounter] ?? "";

                            if (!empty($attendance)) {
                                $attClass = ($attendance == "ABSENT") ? "absent" : "";
                                echo "<div class='attendance-bar $attClass' style='display:none;'>$attendance</div>";
                            }

                            // Display Actual Breaks Data from DB
                            if (isset($breakData[$dayCounter])) {
                                foreach ($breakData[$dayCounter] as $break) {
                                    $bType = $break['type'];
                                    $bLabel = $break['label'];
                                    echo "<div class='break-bar $bType' style='display:block;'>$bLabel</div>";
                                }
                            }

                            echo "</td>";
                            $dayCounter++;
                        }
                    }
                    echo "</tr>";
                    if ($dayCounter > $daysInMonth) break;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('checkViewSchedules').addEventListener('change', function() {
    const bars = document.querySelectorAll('.schedule-bar');
    bars.forEach(bar => {
        bar.style.display = this.checked ? 'block' : 'none';
    });
});

document.getElementById('checkViewAttendance').addEventListener('change', function() {
    const bars = document.querySelectorAll('.attendance-bar');
    bars.forEach(bar => {
        bar.style.display = this.checked ? 'block' : 'none';
    });
});

document.getElementById('checkViewBreaks').addEventListener('change', function() {
    const bars = document.querySelectorAll('.break-bar');
    bars.forEach(bar => {
        bar.style.display = this.checked ? 'block' : 'none';
    });
});
</script>
