<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

// Get filter parameters
$option = $_GET['option'] ?? 'view';
$group_name = $_GET['group'] ?? '';
$period_id = $_GET['period_id'] ?? '';
$emp_id = $_GET['emp_id'] ?? '';
$pay_type = $_GET['pay_type'] ?? '';

// Basic period info if selected
$period_info = null;
if (!empty($period_id)) {
    $p_stmt = $conn->prepare("SELECT * FROM payroll_periods WHERE id = ?");
    $p_stmt->execute([$period_id]);
    $period_info = $p_stmt->fetch();
}

// Build employee query based on filters
$query = "
    SELECT e.id_number as id, e.firstname, e.lastname, 
           e.department, e.employment_type, ee.company, ee.position, ee.location, ee.section,
           p.group_name as group_assigned, p.pay_type
    FROM employees e 
    LEFT JOIN employees_extended ee ON e.id_number = ee.employee_id 
    LEFT JOIN employee_group_assignments ega ON e.id_number = ega.employee_id
    LEFT JOIN payroll_period_groups p ON ega.group_id = p.id
    WHERE 1=1
";

$params = [];

if (!empty($emp_id)) {
    $query .= " AND e.id_number = ?";
    $params[] = $emp_id;
} elseif (!empty($group_name)) {
    $query .= " AND p.group_name = ?";
    $params[] = $group_name;
}

$query .= " ORDER BY e.lastname ASC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$employees = $stmt->fetchAll();
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #D6E9F1;">
    <!-- Content Header (Page header) -->
    <div class="content-header p-0">
      <div class="container-fluid">
        <div class="row pt-2 px-3 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" style="font-size: 20px;">Time <small class="text-muted" style="font-size: 14px;">View Attendance</small></h1>
          </div>
          <div class="col-sm-6 text-right">
            <ol class="breadcrumb float-sm-right" style="background: transparent; margin: 0; padding: 0; font-size: 11px;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item text-dark">Time</li>
              <li class="breadcrumb-item active text-muted">View Attendance</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content mt-3">
      <div class="container-fluid px-2">
        
        <?php if ($option === 'view' && (!empty($period_id) || !empty($emp_id))): ?>
            <!-- DTR VIEW MODE: Show detailed DTR for each employee as per Photo 2 -->
            <?php foreach ($employees as $index => $emp): ?>
            <div class="card mb-4 shadow-sm" style="border-radius: 0; border: 1px solid #17a2b8;">
                <!-- Header with employee info -->
                <div class="card-header p-1" style="background: linear-gradient(to bottom, #003d5b, #005a87); color: white; font-size: 13px;">
                    <div class="row no-gutters">
                        <div class="col-12 px-2 py-1" style="background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <span class="badge badge-secondary">count <?= $index + 1 ?></span>
                            <span class="ml-4">Salary Rate: daily rate</span>
                            <span class="ml-4">Date Employed: 2025-10-03</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0 text-white" style="background: #003d5b; font-size: 12px;">
                    <div class="row no-gutters border-bottom border-info">
                        <div class="col-md-4 border-right border-info p-1">
                            <div class="row">
                                <div class="col-4">Payroll Period</div>
                                <div class="col-8 text-bold">
                                    <?php if ($period_info): ?>
                                        <?= $period_info['date_from'] ?> to <?= $period_info['date_to'] ?> id : <?= $period_info['id'] ?>
                                    <?php else: ?>
                                        ---
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 border-right border-info p-1">
                            <div class="row text-white">
                                <div class="col-4">Department</div>
                                <div class="col-8 text-bold"><?= strtoupper($emp['department'] ?? '') ?></div>
                            </div>
                        </div>
                        <div class="col-md-2 border-right border-info p-1">
                            <div class="row">
                                <div class="col-6">Employment</div>
                                <div class="col-6 text-bold"><?= $emp['employment_type'] ?? 'Regular' ?></div>
                            </div>
                        </div>
                        <div class="col-md-2 p-1">
                            <div class="row">
                                <div class="col-6">Contractual</div>
                                <div class="col-6 text-bold">Contractual</div>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters border-bottom border-info">
                        <div class="col-md-4 border-right border-info p-1">
                            <div class="row">
                                <div class="col-4">Employee ID</div>
                                <div class="col-8 text-bold"><?= $emp['id'] ?></div>
                            </div>
                        </div>
                        <div class="col-md-4 border-right border-info p-1">
                            <div class="row">
                                <div class="col-4">Section</div>
                                <div class="col-8 text-bold"><?= strtoupper($emp['section'] ?? '') ?></div>
                            </div>
                        </div>
                        <div class="col-md-2 border-right border-info p-1">
                            <div class="row">
                                <div class="col-6">Classification</div>
                                <div class="col-6 text-bold">STAFF</div>
                            </div>
                        </div>
                        <div class="col-md-2 p-1">
                            <div class="row">
                                <div class="col-6">STAFF</div>
                                <div class="col-6 text-bold">STAFF</div>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-md-4 border-right border-info p-1">
                            <div class="row">
                                <div class="col-4">Name</div>
                                <div class="col-8 text-bold"><?= strtoupper($emp['lastname']) ?>, <?= strtoupper($emp['firstname']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4 border-right border-info p-1">
                            <div class="row">
                                <div class="col-4">Position</div>
                                <div class="col-8 text-bold"><?= strtoupper($emp['position'] ?? '') ?></div>
                            </div>
                        </div>
                        <div class="col-md-2 border-right border-info p-1">
                            <div class="row">
                                <div class="col-6">Pay Type</div>
                                <div class="col-6 text-bold">Weekly</div>
                            </div>
                        </div>
                        <div class="col-md-2 p-1">
                            <div class="row">
                                <div class="col-6">Location</div>
                                <div class="col-6 text-bold">
                                    <?php if(!empty($emp['location'])): ?>
                                        <?= strtoupper($emp['location']) ?>
                                    <?php else: ?>
                                        <span class="text-warning small italic">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-bordered table-sm m-0 text-center" style="font-size: 11px;">
                        <thead style="background-color: #003d5b; color: white;">
                            <tr>
                                <th rowspan="2" class="align-middle" style="width: 50px;">Date</th>
                                <th rowspan="2" class="align-middle" style="width: 40px;">Day</th>
                                <th colspan="2" class="text-center" style="background-color: #f06292;">Shift Time</th>
                                <th colspan="2" class="text-center" style="background-color: #69f0ae; color: #333;">Actual Time</th>
                                <th rowspan="2" class="align-middle">Late</th>
                                <th rowspan="2" class="align-middle">Over break</th>
                                <th rowspan="2" class="align-middle">Undertime</th>
                                <th colspan="2" class="text-center">Hours Worked</th>
                                <th colspan="2" class="text-center">Overtime</th>
                                <th colspan="2" class="text-center" style="background-color: #f5f5f5; color: #333;">Holiday</th>
                                <th colspan="2" class="text-center" style="background-color: #f5f5f5; color: #333;">Restday</th>
                                <th rowspan="2" class="align-middle">ND</th>
                                <th rowspan="2" class="align-middle">ATRO</th>
                                <th rowspan="2" class="align-middle">Change Sched/ Restday</th>
                                <th rowspan="2" class="align-middle">Leave</th>
                                <th rowspan="2" class="align-middle">Official Business</th>
                                <th rowspan="2" class="align-middle">Timekeeping Form</th>
                                <th rowspan="2" class="align-middle">Undertime</th>
                            </tr>
                            <tr style="background-color: #003d5b; color: white;">
                                <th style="background-color: #f06292;">IN</th>
                                <th style="background-color: #f06292;">OUT</th>
                                <th style="background-color: #69f0ae; color: #333;">IN</th>
                                <th style="background-color: #69f0ae; color: #333;">OUT</th>
                                <th>REG</th>
                                <th>ND</th>
                                <th>Reg</th>
                                <th>Restday</th>
                                <th style="background-color: #f5f5f5; color: #333;">Spec</th>
                                <th style="background-color: #f5f5f5; color: #333;">Reg</th>
                                <th style="background-color: #f5f5f5; color: #333;">Spec</th>
                                <th style="background-color: #f5f5f5; color: #333;">Reg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Placeholder rows - in a real app, these would come from the database
                            $current_date = $period_info ? strtotime($period_info['date_from']) : time();
                            $end_date = $period_info ? strtotime($period_info['date_to']) : time();
                            
                            $has_data = false; // Check if we have logs
                            if ($period_info && $current_date <= $end_date):
                                $has_data = true;
                                while ($current_date <= $end_date):
                                    $date_str = date('m-d', $current_date);
                                    $day_name = date('D', $current_date);
                            ?>
                            <tr>
                                <td><?= $date_str ?></td>
                                <td><?= $day_name ?></td>
                                <td style="background-color: #fce4ec;"></td>
                                <td style="background-color: #fce4ec;"></td>
                                <td style="background-color: #e8f5e9;">--:--</td>
                                <td style="background-color: #e8f5e9;">--:--</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-danger small">absent</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="background-color: #fcfcfc;"></td>
                                <td style="background-color: #fcfcfc;"></td>
                                <td style="background-color: #fcfcfc;"></td>
                                <td style="background-color: #fcfcfc;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php 
                                    $current_date = strtotime("+1 day", $current_date);
                                endwhile; 
                             else: ?>
                                <tr>
                                    <td colspan="24" class="py-4 bg-light text-muted">
                                        <i class="fas fa-search mr-2"></i> No detailed DTR logs available for this period. 
                                        <br>
                                        <small class="text-warning ml-4 italic">Please process the DTR from the main DTR page first.</small>
                                    </td>
                                </tr>
                             <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary Tables -->
                <div class="card-footer p-2" style="background-color: #003d5b;">
                    <div class="row no-gutters">
                        <div class="col-md-3 pr-1">
                            <table class="table table-bordered table-sm bg-white m-0 text-center" style="font-size: 10px;">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Description</th>
                                        <th>Regular</th>
                                        <th>Restday</th>
                                        <th>Regular Holiday</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="text-left py-1">Regular</td><td>0.00</td><td>0.00</td><td>0.00</td></tr>
                                    <tr><td class="text-left py-1">Regular-ND</td><td>0.00</td><td>0.00</td><td>0.00</td></tr>
                                    <tr><td class="text-left py-1">OVERTIME</td><td>0.00</td><td>0.00</td><td>0.00</td></tr>
                                    <tr><td class="text-left py-1">OVERTIME-ND</td><td>0.00</td><td>0.00</td><td>0.00</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2 pr-1">
                            <table class="table table-bordered table-sm bg-white m-0 text-center" style="font-size: 10px;">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th colspan="2">Regular Holiday/Restday</th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #fce4ec; color: #c2185b;">Type 1</th>
                                        <th style="background-color: #fce4ec; color: #c2185b;">Type 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="py-1">0.00</td><td class="py-1">0.00</td></tr>
                                    <tr><td class="py-1">0.00</td><td class="py-1"></td></tr>
                                    <tr><td class="py-1">0.00</td><td class="py-1"></td></tr>
                                    <tr><td class="py-1">0.00</td><td class="py-1"></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2 pr-1">
                            <table class="table table-bordered table-sm bg-white m-0 text-center" style="font-size: 10px;">
                                <thead class="bg-dark text-white">
                                    <tr><th>Special Holiday</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td class="py-1">0.00</td></tr>
                                    <tr><td class="py-1">0.00</td></tr>
                                    <tr><td class="py-1">0.00</td></tr>
                                    <tr><td class="py-1">0.00</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2 pr-1">
                            <table class="table table-bordered table-sm bg-white m-0 text-center" style="font-size: 10px;">
                                <thead class="bg-dark text-white">
                                    <tr><th>Special Holiday/Restday</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td class="py-1">0.00</td></tr>
                                    <tr><td class="py-1">0.00</td></tr>
                                    <tr><td class="py-1">0.00</td></tr>
                                    <tr><td class="py-1">0.00</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-bordered table-sm bg-white m-0 text-center" style="font-size: 10px;">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Description</th>
                                        <th>Total</th>
                                        <th>Occurence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="text-left py-1">absences</td><td>7</td><td>7</td></tr>
                                    <tr><td class="text-left py-1">undertime</td><td>0</td><td>0</td></tr>
                                    <tr><td class="text-left py-1">tardiness</td><td>0</td><td>0</td></tr>
                                    <tr><td class="text-left py-1">overbreak</td><td>0</td><td>0</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Buttons at very bottom -->
                <div class="p-1" style="background-color: #003d5b;">
                    <button class="btn btn-info btn-xs text-bold"><i class="fas fa-arrow-down mr-1"></i> Show DTR Summary History</button>
                    <button class="btn btn-info btn-xs text-bold ml-1"><i class="fas fa-arrow-down mr-1"></i> Show Logs Root</button>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($employees)): ?>
                <div class="alert alert-warning shadow-sm border-0" style="border-left: 5px solid #ffc107 !important;">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> No Results Found!</h5>
                    <p class="mb-0">Wealth of information is waiting, but no employees were found matching your current selection:
                        <ul class="mb-0 mt-2">
                            <?php if(!empty($group_name)): ?><li>Group: <strong><?= htmlspecialchars($group_name) ?></strong></li><?php endif; ?>
                            <?php if(!empty($emp_id)): ?><li>Employee ID: <strong><?= htmlspecialchars($emp_id) ?></strong></li><?php endif; ?>
                            <?php if(!empty($period_id)): ?><li>Payroll Period: <strong>ID #<?= htmlspecialchars($period_id) ?></strong></li><?php endif; ?>
                        </ul>
                    </p>
                    <hr>
                    <p class="mb-0 small text-muted">Please ensure employees are assigned to this group in <a href="user_management.php" class="text-primary text-bold">User Management</a> or try a different filter.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- ORIGINAL LIST VIEW (when no period/employee selected or option is not 'view') -->
            <?php if (empty($employees)): ?>
                <div class="alert alert-info shadow-sm border-0" style="border-left: 5px solid #17a2b8 !important;">
                    <h5><i class="icon fas fa-info-circle"></i> No Employees Available</h5>
                    <p>It seems your employee database is currently empty or no employees match the criteria.</p>
                    <a href="employees.php" class="btn btn-info btn-sm">Add New Employee</a>
                </div>
            <?php else: ?>
                <div class="card shadow-sm border-0">
                  <div class="card-header py-2" style="background-color: #e9f7ef; border-bottom: 2px solid #d4edda;">
                <div class="row align-items-center">
                  <div class="col-md-4">
                    <h3 class="card-title text-success text-bold m-0" style="font-size: 14px;">Employee Attendances</h3>
                  </div>
                  <div class="col-md-8 text-right">
                    <button class="btn btn-danger btn-xs text-bold" style="padding: 2px 8px; font-size: 12px; border-radius: 4px;">Synching Logtrail Report <i class="fas fa-arrow-circle-right ml-1"></i></button>
                    <button class="btn btn-warning btn-xs text-bold text-white ml-1" style="padding: 2px 8px; font-size: 12px; border-radius: 4px; background-color: #f39c12;">Cronjob Running Log Report <i class="fas fa-arrow-circle-right ml-1"></i></button>
                    <button class="btn btn-success btn-xs text-bold ml-1" style="padding: 2px 8px; font-size: 12px; border-radius: 4px;">Monthly report <i class="fas fa-arrow-circle-right ml-1"></i></button>
                    <button class="btn btn-success btn-xs text-bold ml-1" style="padding: 2px 8px; font-size: 12px; border-radius: 4px;">Daily report <i class="fas fa-arrow-circle-right ml-1"></i></button>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-0">
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0" style="font-size: 13px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="border-top: none;">Emp. ID</th>
                                    <th style="border-top: none;">Employee Name</th>
                                    <th style="border-top: none;">Company</th>
                                    <th style="border-top: none;">Position</th>
                                    <th style="border-top: none;">Department</th>
                                    <th style="border-top: none;">Options</th>
                                </tr>
                            </thead>
                            <?php if (empty($employees)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No employees found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($employees as $emp): ?>
                                <tr>
                                    <td><?= $emp['id'] ?></td>
                                    <td><?= $emp['lastname'] ?>, <?= $emp['firstname'] ?></td>
                                    <td><?= $emp['company'] ?></td>
                                    <td><?= $emp['position'] ?></td>
                                    <td><?= $emp['department'] ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="view_attendance.php?option=view&emp_id=<?= $emp['id'] ?>" class="text-primary mx-1" title="DTR"><i class="fas fa-file-alt" style="font-size: 16px;"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

      </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>

