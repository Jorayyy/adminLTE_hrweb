<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

// Fetch real employees from the database
$stmt = $conn->query("
    SELECT e.id_number as id, CONCAT(e.lastname, ' , ', e.firstname) as name, 
           e.department, ee.company, ee.position, ee.location, ee.section 
    FROM employees e 
    LEFT JOIN employees_extended ee ON e.id_number = ee.employee_id 
    ORDER BY e.lastname ASC
");
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
      <div class="container-fluid px-4">
        
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
            <div class="row mt-2">
                <div class="col-12 text-right">
                    <button class="btn btn-info btn-xs text-bold" style="padding: 2px 8px; font-size: 12px; border-radius: 4px; background-color: #17a2b8;">HRWeb Middleware Server RawLogs <i class="fas fa-arrow-circle-right ml-1"></i></button>
                    <button class="btn btn-info btn-xs text-bold ml-1" style="padding: 2px 8px; font-size: 12px; border-radius: 4px; background-color: #17a2b8;">Clientside MDB Cron Logtrail Report <i class="fas fa-arrow-circle-right ml-1"></i></button>
                </div>
            </div>
          </div>
          
          <div class="card-body p-0">
            <div class="p-3">
                <div class="row align-items-center mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center" style="font-size: 13px;">
                            Show 
                            <select class="form-control form-control-sm mx-1" style="width: auto; height: 30px;">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select> 
                            entries
                        </div>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="d-inline-flex align-items-center" style="font-size: 13px;">
                            Search: <input type="text" class="form-control form-control-sm ml-1" style="width: 150px; height: 30px;">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0" style="font-size: 13px;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="border-top: none;">Emp. ID <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                                <th style="border-top: none;">Employee Name <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                                <th style="border-top: none;">Company <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                                <th style="border-top: none;">Position <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                                <th style="border-top: none;">Location <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                                <th style="border-top: none;">Department <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                                <th style="border-top: none;">Section <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                                <th style="border-top: none;">Options <i class="fas fa-sort float-right mt-1 text-muted" style="font-size: 10px;"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td><?= $emp['id'] ?></td>
                                <td><?= $emp['name'] ?></td>
                                <td><?= $emp['company'] ?></td>
                                <td><?= $emp['position'] ?></td>
                                <td><?= $emp['location'] ?></td>
                                <td><?= $emp['department'] ?></td>
                                <td><?= $emp['section'] ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="text-success mx-1" title="Active"><i class="fas fa-power-off" style="font-size: 16px;"></i></a>
                                        <a href="#" class="text-primary mx-1" title="DTR"><i class="fas fa-file-alt" style="font-size: 16px;"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3 px-3 align-items-center">
                    <div class="col-sm-6">
                        <p style="font-size: 13px;">Showing 1 to 10 of 248 entries</p>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="Page navigation" class="float-right">
                            <ul class="pagination pagination-sm m-0">
                                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                                <li class="page-item"><a class="page-link" href="#">25</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
          </div>
        </div>

      </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
