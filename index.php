<?php
require_once 'config/db.php';
// Define project root for assets relative to current file
$base_url = "./"; 
include 'includes/header.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-light" style="margin-left: 0 !important;">
    <!-- Content Header (Page header) -->
    <div class="content-header pt-0">
      <div class="container-fluid">
        <div class="row pt-2 pl-3">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" style="font-size: 24px;">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            <ol class="breadcrumb float-sm-right" style="background: transparent; margin: 0; padding: 0;">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<?php
// Fetch Reminders
$reminders_stmt = $conn->query("SELECT * FROM reminders ORDER BY created_at DESC");
$reminders = $reminders_stmt->fetchAll();
?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
          <!-- Left col (Reminders & Alerts) -->
          <section class="col-lg-4 connectedSortable">
            <!-- Reminders Box -->
            <div class="card card-outline card-warning">
              <div class="card-header d-flex justify-content-center align-items-center bg-light">
                <h3 class="card-title text-center text-bold w-100" style="font-size: 14px; text-transform: uppercase;">REMINDERS</h3>
                <div class="card-tools position-absolute" style="right: 1.25rem;">
                   <i class="fas fa-plus text-primary"></i>
                </div>
              </div>
              <div class="card-body">
                <?php foreach ($reminders as $rem): ?>
                  <?php if ($rem['color_class'] != 'bg-orange'): ?>
                    <blockquote class="text-secondary" style="font-size: 14px; border-left: 5px solid #28a745;">
                      <i class="fas fa-quote-left text-muted mr-1"></i> <?= htmlspecialchars($rem['message']) ?> <i class="fas fa-quote-right text-muted"></i>
                      <div class="mt-2 text-right">
                        <i class="fas fa-trash-alt text-danger ml-1" style="cursor:pointer"></i> <i class="fas fa-edit text-warning ml-1" style="cursor:pointer"></i>
                        <small class="d-block mt-2 text-italic"><?= htmlspecialchars($rem['author']) ?></small>
                      </div>
                    </blockquote>
                  <?php else: ?>
                    <blockquote class="text-white mt-3" style="font-size: 14px; background-color: #ff851b; border-left: none; padding: 10px; position: relative; border-radius: 4px;">
                      <i class="fas fa-quote-left mr-1"></i> <?= htmlspecialchars($rem['message']) ?> <i class="fas fa-quote-right"></i>
                      <div class="mt-2 py-1">
                        <i class="fas fa-trash-alt text-danger ml-1" style="cursor:pointer"></i> <i class="fas fa-edit text-warning ml-1" style="cursor:pointer"></i>
                        <small class="float-right text-italic"><?= htmlspecialchars($rem['author']) ?></small>
                      </div>
                    </blockquote>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Employment Status Alert -->
            <div class="card card-outline card-success">
              <div class="card-header d-flex justify-content-center align-items-center bg-light">
                <h3 class="card-title text-center text-bold w-100" style="font-size: 14px; text-transform: uppercase;">EMPLOYMENT STATUS ALERT</h3>
              </div>
              <div class="card-body p-0">
                 <div class="bg-success text-center py-2 mb-2">
                   <strong style="font-size: 12px;">MANCAO ELECTRONIC CONNECT BUSINESS SOLUTIONS OPC</strong>
                 </div>
                 <table class="table table-sm text-center mb-0" style="font-size: 12px;">
                    <thead>
                      <tr>
                        <th>Employment Type</th>
                        <th>For Review Employee(s)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="2" class="py-3 text-muted">No Data For Review Yet.</td>
                      </tr>
                    </tbody>
                 </table>
              </div>
            </div>
          </section>

          <!-- Right col (Charts) -->
          <section class="col-lg-8 connectedSortable">
            <div class="row">
               <!-- Absences & Tardiness Chart -->
               <div class="col-md-6">
                <div class="card" style="background-color: #00ffff; height: 350px;">
                    <div class="card-header border-0 bg-transparent text-center">
                        <h3 class="card-title w-100 text-bold" style="font-size: 20px;">2026 Absences & Tardiness</h3>
                    </div>
                    <div class="card-body pt-0">
                        <canvas id="absencesChart" style="height: 250px;"></canvas>
                        <div class="mt-2" style="font-size: 12px; color: #333;">
                           <b>Mar</b><br>
                           Absences Total: 0<br>
                           Tardiness Total: 0
                        </div>
                    </div>
                </div>
               </div>
               <!-- Overtime Hours Chart -->
               <div class="col-md-6">
                <div class="card" style="background-color: #87ceeb; height: 350px;">
                    <div class="card-header border-0 bg-transparent text-center">
                        <h3 class="card-title w-100 text-bold" style="font-size: 20px;">2026 Overtime Hours</h3>
                    </div>
                    <div class="card-body pt-0">
                        <canvas id="overtimeChart" style="height: 250px;"></canvas>
                        <div class="mt-2" style="font-size: 12px; color: #333;">
                           <b>Mar</b><br>
                           Overtime Total: 9.87
                        </div>
                    </div>
                </div>
               </div>
            </div>
            
            <!-- Employees Per Department Placeholder -->
            <div class="row mt-3">
              <div class="col-12 text-center pt-5">
                 <h2 class="text-bold" style="color: #333;">Employees Per Department</h2>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mock data for graphs based on screenshot
        const labels = ['Jan', 'Feb', 'Mar'];
        
        // Absences & Tardiness Chart
        const ctxAbs = document.getElementById('absencesChart').getContext('2d');
        new Chart(ctxAbs, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Absences',
                    data: [0, 1, 0],
                    borderColor: '#000080',
                    backgroundColor: 'rgba(0,0,128,0.2)',
                    fill: false,
                    tension: 0.4
                }, {
                    label: 'Tardiness',
                    data: [0, 0, 0],
                    borderColor: '#888',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { suggestedMax: 1, ticks: { stepSize: 0.25 } } }
            }
        });

        // Overtime Chart
        const ctxOt = document.getElementById('overtimeChart').getContext('2d');
        new Chart(ctxOt, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Overtime',
                    data: [2, 10, 9.87],
                    borderColor: '#000080',
                    backgroundColor: 'rgba(0,0,128,0.2)',
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { suggestedMax: 10, ticks: { stepSize: 2.5 } } }
            }
        });
    });
  </script>

<?php include 'includes/footer.php'; ?>
