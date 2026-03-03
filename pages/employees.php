<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

// Handle Success/Error Messages
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Fetch all employees
$stmt = $conn->query("SELECT * FROM employees ORDER BY id DESC");
$employees = $stmt->fetchAll();
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #D6E9F1; margin-left: 0 !important;">
    <!-- Content Header (Page header) -->
    <div class="content-header p-0">
      <div class="container-fluid">
        <div class="row pt-2 px-3 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" style="font-size: 20px;">201 Employee Files <small class="text-muted" style="font-size: 14px;">Employees Masterlist</small></h1>
          </div>
          <div class="col-sm-6 text-right">
            <ol class="breadcrumb float-sm-right" style="background: transparent; margin: 0; padding: 0; font-size: 11px;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item text-dark">201 Employee Files</li>
              <li class="breadcrumb-item active text-muted">Employees Masterlist</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content mt-1">
      <div class="container-fluid">
        <div class="row px-3 mb-2">
            <div class="col-12 p-0">
                <span style="color: #ff4d4d; font-weight: bold; font-size: 13px;">[ Employee License : 250 , Total Active Employees : 248 + InActive Processed Dtr : 0 , Remaining License : 2 ]</span>
                <div class="float-right">
                    <button class="btn btn-success btn-xs px-2 py-1 mr-1 shadow-sm" style="font-size: 11px; background-color: #28a745; border: none;"><i class="fas fa-users"></i> Mass Upload</button>
                    <button class="btn btn-primary btn-xs px-2 py-1 mr-1 shadow-sm" style="font-size: 11px; background-color: #007bff; border: none;" data-toggle="modal" data-target="#addEmployeeModal"><i class="fas fa-user-plus"></i> Add Employee</button>
                    <button class="btn btn-danger btn-xs px-2 py-1 mr-1 shadow-sm" style="font-size: 11px; background-color: #dc3545; border: none;"><i class="fas fa-user-times"></i> InActive Employee</button>
                    <button class="btn btn-warning btn-xs px-2 py-1 text-white shadow-sm" style="font-size: 11px; background-color: #f39c12; border: none;"><i class="fas fa-user-clock"></i> View Deactivated Employees Due To On Leave</button>
                </div>
            </div>
        </div>

        <div class="card shadow-none border-0" style="border-radius: 0;">
          <div class="card-body p-0 bg-white">
            <div class="row p-2 align-items-center" style="font-size: 13px;">
                <div class="col-sm-6">
                    Show <select class="form-control form-control-sm d-inline-block" style="width: auto;"><option>10</option></select> entries
                </div>
                <div class="col-sm-6 text-right">
                    Search: <input type="text" class="form-control form-control-sm d-inline-block" style="width: 200px;">
                </div>
            </div>
            
            <table class="table table-sm table-striped table-hover mb-0" style="font-size: 12px; border-top: 1px solid #dee2e6;">
              <thead class="bg-light">
                <tr>
                  <th class="py-2">Emp. ID <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2">Employee Name <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2">Company <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2">Position <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2">Location <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2">Department <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2">Section <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2">Options <i class="fas fa-sort float-right text-muted"></i></th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($employees) > 0): ?>
                    <?php foreach($employees as $emp): ?>
                    <tr>
                      <td class="align-middle"><?= htmlspecialchars($emp['id_number']) ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['lastname'] . ', ' . $emp['firstname']) ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['company'] ?? 'N/A') ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['position'] ?? 'N/A') ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['location'] ?? 'N/A') ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['department']) ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['section'] ?? 'N/A') ?></td>
                      <td class="align-middle">
                        <button class="btn btn-link btn-sm p-0 text-success"><i class="fas fa-power-off"></i></button>
                        <button class="btn btn-link btn-sm p-0 text-primary ml-2"><i class="fas fa-file-lines"></i></button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                      <td colspan="8" class="text-center text-muted py-4">No employees found.</td>
                    </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    
    <a href="#" class="btn btn-danger btn-sm shadow-sm" style="position: fixed; bottom: 20px; right: 20px; border-radius: 4px; font-size: 12px; padding: 2px 8px; z-index: 1000;">
        <i class="fas fa-angles-up"></i> go to top
    </a>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="save_employee.php" method="POST">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Add New Employee</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Number</label>
                        <input type="text" name="id_number" class="form-control" placeholder="e.g. EMP-2026-001" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<style>
.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}
.btn-xs {
    padding: 1px 5px;
    font-size: 12px;
}
</style>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" name="firstname" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" name="lastname" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control">
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                            <option value="Accounting">Accounting</option>
                            <option value="Operations">Operations</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Employment Type</label>
                        <select name="employment_type" class="form-control">
                            <option value="Regular">Regular</option>
                            <option value="Probationary">Probationary</option>
                            <option value="Contractual">Contractual</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
