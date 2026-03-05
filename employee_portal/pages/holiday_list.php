<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";

$page_title = "Holiday List";
include "layout_template.php";

// Simple Month/Year Logic
$month = isset($_GET['month']) ? (int)$_GET['month'] : 3;
$year = isset($_GET['year']) ? (int)$_GET['year'] : 2026;

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

// Accurate Philippine Holidays for 2026 based on Proclamation No. 727 (Mock for this project)
// RH = Regular Holiday, SNW = Special Non-Working Day
$holidays = [
    "2026-01-01" => ["label" => "NEW YEAR'S DAY (RH)", "color" => "#b8860b"],
    "2026-02-17" => ["label" => "CHINESE NEW YEAR (SNW)", "color" => "#4682b4"],
    "2026-02-25" => ["label" => "EDSA REVOLUTION ANNIV (SNW)", "color" => "#4682b4"],
    "2026-04-02" => ["label" => "MAUNDY THURSDAY (RH)", "color" => "#b8860b"],
    "2026-04-03" => ["label" => "GOOD FRIDAY (RH)", "color" => "#b8860b"],
    "2026-04-04" => ["label" => "BLACK SATURDAY (SNW)", "color" => "#4682b4"],
    "2026-04-05" => ["label" => "EASTER SUNDAY (SNW)", "color" => "#4682b4"],
    "2026-04-09" => ["label" => "ARAW NG KAGITINGAN (RH)", "color" => "#b8860b"],
    "2026-05-01" => ["label" => "LABOR DAY (RH)", "color" => "#b8860b"],
    "2026-06-12" => ["label" => "INDEPENDENCE DAY (RH)", "color" => "#b8860b"],
    "2026-08-21" => ["label" => "NINOY AQUINO DAY (SNW)", "color" => "#4682b4"],
    "2026-08-31" => ["label" => "NATIONAL HEROES DAY (RH)", "color" => "#b8860b"],
    "2026-11-01" => ["label" => "ALL SAINTS' DAY (SNW)", "color" => "#4682b4"],
    "2026-11-02" => ["label" => "ALL SOULS' DAY (SNW)", "color" => "#4682b4"],
    "2026-11-30" => ["label" => "BONIFACIO DAY (RH)", "color" => "#b8860b"],
    "2026-12-08" => ["label" => "FEAST OF THE IMMACULATE CONCEPTION (RH)", "color" => "#b8860b"],
    "2026-12-24" => ["label" => "CHRISTMAS EVE (SNW)", "color" => "#4682b4"],
    "2026-12-25" => ["label" => "CHRISTMAS DAY (RH)", "color" => "#b8860b"],
    "2026-12-30" => ["label" => "RIZAL DAY (RH)", "color" => "#b8860b"],
    "2026-12-31" => ["label" => "LAST DAY OF THE YEAR (SNW)", "color" => "#4682b4"]
];
?>

<style>
    .holiday-calendar-container {
        background: #fff;
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #c58de8;
        padding-bottom: 10px;
    }
    .calendar-header h2 {
        margin: 0;
        font-size: 28px;
        color: #555;
    }
    .calendar-nav-controls {
        display: flex;
        gap: 5px;
    }
    .nav-btn {
        background: #f4f4f4;
        border: 1px solid #ddd;
        padding: 5px 15px;
        cursor: pointer;
        color: #333;
        text-decoration: none;
        border-radius: 3px;
    }
    .nav-btn:hover {
        background: #e9e9e9;
    }
    
    .cal-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .cal-table th {
        background: #f8f9fa;
        border: 1px solid #eee;
        padding: 10px;
        font-weight: bold;
        text-align: center;
        color: #333;
    }
    .cal-table td {
        border: 1px solid #eee;
        height: 100px;
        vertical-align: top;
        padding: 5px;
        position: relative;
    }
    .day-num {
        font-size: 16px;
        color: #777;
        text-align: right;
    }
    .holiday-bar {
        position: absolute;
        bottom: 5px;
        left: 2px;
        right: 2px;
        padding: 3px 5px;
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        border-radius: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .cal-today {
        background-color: #fffde7;
    }
</style>

<div class="holiday-calendar-container">
    <div class="calendar-header">
        <div class="calendar-nav-controls">
            <a href="holiday_list.php?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="nav-btn"><</a>
            <a href="holiday_list.php?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="nav-btn">></a>
            <a href="holiday_list.php" class="nav-btn">today</a>
        </div>
        <h2><?= $monthName ?> <?= $year ?></h2>
    </div>

    <table class="cal-table">
        <thead>
            <tr>
                <th>Sun</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dayCounter = 1;
            $nextMonthDayCounter = 1;
            $lastMonthDayCounter = 1; // Simplification
            
            for ($i = 0; $i < 6; $i++) {
                echo "<tr>";
                for ($j = 0; $j < 7; $j++) {
                    $currentPos = $i * 7 + $j;
                    
                    if ($currentPos < $firstDayOfMonth) {
                        echo "<td></td>";
                    } else if ($dayCounter <= $daysInMonth) {
                        $currentDate = sprintf("%04d-%02d-%02d", $year, $month, $dayCounter);
                        $isToday = ($dayCounter == 6 && $month == 3 && $year == 2026) ? "cal-today" : "";
                        
                        echo "<td class='$isToday'>";
                        echo "<div class='day-num'>$dayCounter</div>";
                        if (isset($holidays[$currentDate])) {
                            $h = $holidays[$currentDate];
                            echo "<div class='holiday-bar' style='background-color: {$h['color']};'>{$h['label']}</div>";
                        }
                        echo "</td>";
                        $dayCounter++;
                    } else {
                        $nm = $month + 1;
                        $ny = $year;
                        if ($nm > 12) { $nm = 1; $ny++; }
                        $currentDate = sprintf("%04d-%02d-%02d", $ny, $nm, $nextMonthDayCounter);
                        
                        echo "<td>";
                        echo "<div class='day-num' style='color: #ddd;'>$nextMonthDayCounter</div>";
                        if (isset($holidays[$currentDate])) {
                            $h = $holidays[$currentDate];
                            echo "<div class='holiday-bar' style='background-color: {$h['color']};'>{$h['label']}</div>";
                        }
                        echo "</td>";
                        $nextMonthDayCounter++;
                    }
                }
                echo "</tr>";
                if ($dayCounter > $daysInMonth) {
                   // Continue to fill the 5th or 6th row to match the screenshot or just break if done
                   if ($i >= 4) break; 
                }
            }
            ?>
        </tbody>
    </table>
</div>
