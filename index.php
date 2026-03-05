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
    <div class="content">
        <div class="dashboard-grid">
            <div class="alert-column">
                <div class="alert-card">
                    <div class="alert-header" style="background: #e9ecef; border-bottom: 2px solid #28a745;">
                        <span style="color: #28a745;"><i class="fas fa-exclamation-triangle"></i> ALERT MSG (ADMIN)</span>
                        <i class="fas fa-times text-muted" style="cursor: pointer;"></i>
                    </div>
                    <div class="alert-body">No Transaction found.</div>
                </div>
                
                <div class="card shadow-none border" style="border-radius: 4px;">
                    <div class="card-header border-0 py-2 px-3" style="background: #fff; border-bottom: 1px solid #eee !important;">
                        <h3 class="card-title text-uppercase font-weight-bold" style="font-size: 13px; color: #444;">Department List</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action sidebar-item sidebar-orange py-2 px-3" style="font-size: 13px;">ACCOUNTING</a>
                            <a href="#" class="list-group-item list-group-item-action sidebar-item sidebar-green py-2 px-3" style="font-size: 13px;">ADMINISTRATION</a>
                            <a href="#" class="list-group-item list-group-item-action sidebar-item sidebar-blue py-2 px-3" style="font-size: 13px;">HUMAN RESOURCE</a>
                        </div>
                    </div>
                </div>

                <button class="btn btn-blue mt-3"><i class="fas fa-sync-alt mr-1"></i> REFRESH DASHBOARD DATA</button>
                <button class="btn btn-blue-alt"><i class="fas fa-id-card-alt mr-1"></i> REPRINT ID FORM</button>
            </div>

            <div class="main-chart-column">
                <div class="chart-card">
                    <div class="chart-title">EMPLOYEE CLASSIFICATION CHART</div>
                    <canvas id="classificationChart" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                </div>
            </div>

            <div class="secondary-chart-column">
                <div class="chart-card">
                    <div class="chart-title">EMPLOYEE GENDER CHART</div>
                    <canvas id="genderChart" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
    .dashboard-grid { display: grid; grid-template-columns: 1fr 1.2fr 1fr; gap: 15px; padding-top: 15px; }
    .alert-card { background: #fff; border-radius: 4px; overflow: hidden; border: 1px solid #ddd; margin-bottom: 15px; }
    .alert-header { padding: 8px 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; font-size: 12px; }
    .alert-body { padding: 12px 15px; font-size: 13px; color: #444; }
    .chart-card { background: #fff; border-radius: 4px; border: 1px solid #ddd; padding: 15px; position: relative; min-height: 420px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .chart-title { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 20px; color: #333; }
    .btn-blue { background: #28a745; color: white; width: 100%; border: none; padding: 10px; border-radius: 4px; font-weight: bold; margin-bottom: 15px; font-size: 13px; text-transform: uppercase; }
    .btn-blue-alt { background: #2e67f8; color: white; width: 100%; border: none; padding: 10px; border-radius: 4px; font-weight: bold; font-size: 13px; text-transform: uppercase; }
    .sidebar-item { border-left: 10px solid; margin-bottom: 5px; font-weight: 500; color: #333 !important; }
    .sidebar-orange { border-left-color: #ff9800; }
    .sidebar-green { border-left-color: #28a745; }
    .sidebar-blue { border-left-color: #2e67f8; }
</style>

<script src="assets/plugins/chart.js/Chart.min.js"></script>
<script>
$(function () {
    // Classification Chart
    var classificationData = {
        labels: ['REGULAR', 'PROBATIONARY', 'CASUAL'],
        datasets: [{
            data: [45, 25, 30],
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8'],
        }]
    }
    var classificationChartCanvas = $('#classificationChart').get(0).getContext('2d')
    new Chart(classificationChartCanvas, {
        type: 'pie',
        data: classificationData,
        options: { maintainAspectRatio: false, responsive: true }
    })

    // Gender Chart
    var genderData = {
        labels: ['MALE', 'FEMALE'],
        datasets: [{
            data: [60, 40],
            backgroundColor: ['#007bff', '#e83e8c'],
        }]
    }
    var genderChartCanvas = $('#genderChart').get(0).getContext('2d')
    new Chart(genderChartCanvas, {
        type: 'pie',
        data: genderData,
        options: { maintainAspectRatio: false, responsive: true }
    })
})
</script>
                <a href="pages/section_manager.php"><i class="far fa-copy"></i> Section Manager</a>
                <a href="pages/user_management.php"><i class="far fa-copy"></i> User Management</a>
                <a href="pages/user_roles.php"><i class="far fa-copy"></i> User Roles</a>
            </div>
        </div>

        <div class="tab-item-box tab-201">
            <i class="fas fa-users mr-2"></i> 201 Employee
            <div class="dropdown-menu-custom">
                <a href="pages/employees.php"><i class="far fa-copy"></i> Employee List</a>
                <a href="pages/employment.php"><i class="far fa-copy"></i> Employment Status</a>
                <a href="pages/civil_status.php"><i class="far fa-copy"></i> Civil Status</a>
                <a href="pages/gender.php"><i class="far fa-copy"></i> Gender</a>
                <a href="pages/classification.php"><i class="far fa-copy"></i> Classification</a>
                <a href="pages/education.php"><i class="far fa-copy"></i> Education</a>
            </div>
        </div>

        <div class="tab-item-box tab-trans">
            <i class="fas fa-exchange-alt mr-2"></i> Transaction
            <div class="dropdown-menu-custom">
                <a href="pages/form_approval.php"><i class="far fa-copy"></i> Form Approval</a>
                <a href="pages/leave_management.php"><i class="far fa-copy"></i> Leave Management</a>
                <a href="pages/leave_type.php"><i class="far fa-copy"></i> Leave Types</a>
                <a href="pages/salary_approval.php"><i class="far fa-copy"></i> Salary Approval</a>
            </div>
        </div>

        <div class="tab-item-box tab-time">
            <i class="fas fa-clock mr-2"></i> Time
            <div class="dropdown-menu-custom">
                <a href="pages/dtr.php"><i class="far fa-copy"></i> Generate Daily Time Record (DTR)</a>
                <a href="pages/shift_table.php"><i class="far fa-copy"></i> Shift Table</a>
                <a href="#"><i class="far fa-copy"></i> Plot Schedule</a>
                <a href="pages/payroll_period.php"><i class="far fa-copy"></i> Payroll Period</a>
                <a href="#"><i class="far fa-copy"></i> Manual Upload Attendance</a>
                <a href="pages/view_attendance.php"><i class="far fa-copy"></i> View Attendance</a>
                <a href="#"><i class="far fa-copy"></i> Fixed Schedule</a>
                <a href="#"><i class="far fa-copy"></i> Flexi Schedule</a>
                <a href="#"><i class="far fa-copy"></i> Compress Work Schedule</a>
                <a href="#"><i class="far fa-copy"></i> Part Timers</a>
                <a href="#"><i class="far fa-copy"></i> Time Settings</a>
                <a href="#"><i class="far fa-copy"></i> Biometrics Setup</a>
                <a href="login.php"><i class="far fa-copy"></i> Web Bundy</a>
                <a href="#"><i class="far fa-copy"></i> Late and Absences Monitoring</a>
                <a href="#"><i class="far fa-copy"></i> Form Prerequisite</a>
                <a href="#"><i class="far fa-copy"></i> Geo-attendance</a>
                <a href="#"><i class="far fa-copy"></i> Registered Geo Location(Ongoing New)</a>
            </div>
        </div>

        <div class="tab-item-box tab-reports">
            <i class="fas fa-file-invoice mr-2"></i> Reports
            <div class="dropdown-menu-custom">
                <a href="pages/save_payroll.php"><i class="far fa-copy"></i> Payroll Reports</a>
                <a href="pages/save_employee.php"><i class="far fa-copy"></i> Employee Reports</a>
                <a href="pages/downloadable_forms.php"><i class="far fa-copy"></i> Downloadable Forms</a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="dashboard-grid">
            <div>
                <div class="alert-card sidebar-item sidebar-orange">
                    <div class="alert-header">REMINDERS <i class="fas fa-plus text-primary"></i></div>
                    <div class="alert-body">
                        <p><i class="fas fa-quote-left"></i> Good day team! Please be reminded to plot all the employees' schedule every first day of the month. Thank you! <i class="fas fa-quote-right"></i></p>
                        <div style="text-align: right; font-style: italic; font-size: 11px;">SHERAME BALORIO</div>
                    </div>
                </div>
                <div class="alert-card" style="background: #ff8c00; color: white;">
                    <div class="alert-body">
                        <p><i class="fas fa-quote-left"></i> Good Day Team! Please be reminded to file TK at the end of the shift... <i class="fas fa-quote-right"></i></p>
                        <div style="text-align: right; font-style: italic; font-size: 11px;">SHERAME BALORIO</div>
                    </div>
                </div>
                <div class="alert-card sidebar-item sidebar-green">
                    <div class="alert-header">EMPLOYMENT STATUS ALERT</div>
                    <div class="alert-body">
                        <button class="btn-blue">MANCAO ELECTRONIC CONNECT BUSINESS SOLUTIONS OPC</button>
                    </div>
                </div>
            </div>
            <div class="chart-card" style="background: #00ffff;">
                <div class="chart-title">2026 Absences & Tardiness</div>
                <canvas id="absencesChart"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-title">2026 Overtime Hours</div>
                <canvas id="otChart"></canvas>
                <div style="font-size: 11px; color: #888; margin-top:10px;">Mar Overtime Total: 9.87</div>
                <h6 style="text-align: center; font-weight: bold; margin-top:20px;">Employees Per Department</h6>
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener('load', function() {
            new Chart(document.getElementById('absencesChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar'],
                    datasets: [{
                        data: [0, 1, 0],
                        borderColor: '#007bff',
                        backgroundColor: 'transparent',
                        pointBackgroundColor: '#fff',
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
            new Chart(document.getElementById('otChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar'],
                    datasets: [{
                        data: [2, 10, 9.87],
                        borderColor: '#007bff',
                        backgroundColor: 'transparent',
                        pointBackgroundColor: '#007bff',
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
            new Chart(document.getElementById('deptChart'), {
                type: 'bar',
                data: {
                    labels: ['Admin', 'Operations', 'Sales'],
                    datasets: [{
                        data: [2000, 1500, 1000],
                        backgroundColor: '#ddd'
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        });
    </script>
</body>
</html>
