<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once 'config/db.php';
$base_url = "./"; 
include 'includes/header.php';
?>
<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #D6E9F1;">
    <div class="content pt-2">
        <!-- Top Statistics Row -->
        <div class="dashboard-grid">
            <!-- Left Info Panel -->
            <div class="alert-column">
                <div class="card shadow-none border mb-2" style="border-radius: 4px;">
                    <div class="card-header border-0 py-1 px-3" style="background: #fff; border-bottom: 1px solid #eee !important;">
                        <h3 class="card-title text-uppercase font-weight-bold" style="font-size: 11px; color: #444;">Department List</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action sidebar-item sidebar-orange py-1 px-3" style="font-size: 11px;">ACCOUNTING</a>
                            <a href="#" class="list-group-item list-group-item-action sidebar-item sidebar-green py-1 px-3" style="font-size: 11px;">ADMINISTRATION</a>
                            <a href="#" class="list-group-item list-group-item-action sidebar-item sidebar-blue py-1 px-3" style="font-size: 11px;">HUMAN RESOURCE</a>
                        </div>
                    </div>
                </div>

                <div class="alert-card sidebar-item sidebar-orange p-0 overflow-hidden mb-2">
                    <div class="alert-header py-1 px-2" style="background: #f8f9fa; font-size: 11px;">REMINDERS <i class="fas fa-plus text-primary float-right"></i></div>
                    <div class="alert-body py-1 px-2">
                        <p class="mb-0" style="font-size: 10px; line-height: 1.2;">Please plot all employee schedules by the 1st of the month.</p>
                    </div>
                </div>

                <div class="d-flex">
                    <button class="btn btn-blue py-1" style="font-size: 10px;"><i class="fas fa-sync-alt mr-1"></i> REFRESH</button>
                    <button class="btn btn-blue-alt py-1 ml-1" style="font-size: 10px;"><i class="fas fa-id-card-alt mr-1"></i> REPRINT</button>
                </div>
            </div>

            <!-- Charts Center/Right -->
            <div class="main-chart-column">
                <div class="chart-card" style="height: 220px;">
                    <div class="chart-title">EMPLOYEE CLASSIFICATION</div>
                    <canvas id="classificationChart"></canvas>
                </div>
            </div>

            <div class="secondary-chart-column">
                <div class="chart-card" style="height: 220px;">
                    <div class="chart-title">GENDER RATIO</div>
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Middle Charts Row -->
        <div class="dashboard-grid mt-2" style="grid-template-columns: 1fr 1fr 1.2fr;">
            <div class="chart-card" style="height: 200px;">
                <div class="chart-title">ABSENCES & TARDINESS</div>
                <canvas id="absencesChart"></canvas>
            </div>
            <div class="chart-card" style="height: 200px;">
                <div class="chart-title">OVERTIME HOURS</div>
                <canvas id="otChart"></canvas>
            </div>
            <div class="chart-card" style="height: 200px;">
                <div class="chart-title">EMPLOYEES PER DEPT</div>
                <canvas id="deptChart"></canvas>
            </div>
        </div>

        <!-- Section Menu Area removed as requested -->
    </div>
</div>

<style>
    .dashboard-grid { display: grid; grid-template-columns: 1fr 1.2fr 1fr; gap: 15px; }
    .alert-card { background: #fff; border-radius: 4px; border: 1px solid #ddd; }
    .chart-card { background: #fff; border-radius: 4px; border: 1px solid #ddd; padding: 10px; position: relative; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .chart-title { text-align: center; font-weight: bold; font-size: 11px; margin-bottom: 5px; color: #333; }
    .btn-blue { background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-weight: bold; text-transform: uppercase; flex: 1; }
    .btn-blue-alt { background: #2e67f8; color: white; border: none; padding: 5px 10px; border-radius: 4px; font-weight: bold; text-transform: uppercase; flex: 1; }
    .sidebar-item { border-left: 10px solid; margin-bottom: 5px; font-weight: 500; color: #333 !important; }
    .sidebar-orange { border-left-color: #ff9800; }
    .sidebar-green { border-left-color: #28a745; }
    .sidebar-blue { border-left-color: #2e67f8; }
    .tab-item-box { background: #fff; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .dropdown-menu-custom { display: flex; flex-direction: column; gap: 5px; }
    .dropdown-menu-custom a { font-size: 11px; color: #007bff; text-decoration: none; display: flex; align-items: center; }
    .dropdown-menu-custom a:hover { text-decoration: underline; }
</style>

<?php include 'includes/footer.php'; ?>

<script src="assets/plugins/chart.js/Chart.min.js"></script>
<script>
$(document).ready(function() {
    new Chart(document.getElementById('classificationChart'), {
        type: 'pie',
        data: { labels: ['REGULAR', 'PROB', 'CASUAL'], datasets: [{ data: [45, 25, 30], backgroundColor: ['#28a745', '#ffc107', '#17a2b8'] }] },
        options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } } }
    });

    new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: { labels: ['MALE', 'FEMALE'], datasets: [{ data: [60, 40], backgroundColor: ['#007bff', '#e83e8c'] }] },
        options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } } }
    });

    new Chart(document.getElementById('absencesChart'), {
        type: 'line',
        data: { labels: ['Jan', 'Feb', 'Mar'], datasets: [{ data: [0, 1, 0], borderColor: '#007bff', tension: 0.4 }] },
        options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('otChart'), {
        type: 'line',
        data: { labels: ['Jan', 'Feb', 'Mar'], datasets: [{ data: [2, 10, 9.87], borderColor: '#28a745', tension: 0.4 }] },
        options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('deptChart'), {
        type: 'bar',
        data: { labels: ['Admin', 'Ops', 'Sales'], datasets: [{ data: [20, 15, 10], backgroundColor: '#ddd' }] },
        options: { maintainAspectRatio: false, responsive: true, plugins: { legend: { display: false } } }
    });
});
</script>
</body>
</html>
