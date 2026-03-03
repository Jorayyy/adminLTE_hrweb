<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

// Handle Success/Error Messages
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch total count for pagination
$count_stmt = $conn->query("SELECT COUNT(*) FROM employees");
$total_employees = $count_stmt->fetchColumn();
$total_pages = ceil($total_employees / $limit);

// Fetch real employees from the database with pagination
$stmt = $conn->prepare("
    SELECT e.id_number, e.lastname, e.firstname, e.department, 
           ee.company, ee.position, ee.location, ee.section 
    FROM employees e 
    LEFT JOIN employees_extended ee ON e.id_number = ee.employee_id 
    ORDER BY e.lastname ASC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
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
                    Show <select class="form-control form-control-sm d-inline-block" style="width: auto;" onchange="location.href='?page=1&limit='+this.value;"><option value="10" selected>10</option></select> entries
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
                      <td class="align-middle"><?= htmlspecialchars($emp['lastname'] . ' , ' . $emp['firstname']) ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['company'] ?? 'N/A') ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['position'] ?? 'N/A') ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['location'] ?? 'N/A') ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['department']) ?></td>
                      <td class="align-middle"><?= htmlspecialchars($emp['section'] ?? 'N/A') ?></td>
                      <td class="align-middle" style="white-space: nowrap;">
                        <a href="#" class="text-success mx-1" title="Active"><i class="fas fa-power-off" style="font-size: 16px;"></i></a>
                        <a href="employee_profile.php?id=<?= htmlspecialchars($emp['id_number']) ?>" class="text-primary mx-1" title="View <?= htmlspecialchars($emp['lastname'] . ' ' . $emp['firstname']) ?>'s 201 Record"><i class="fas fa-file-alt" style="font-size: 16px;"></i></a>
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
            
            <div class="row p-2 align-items-center" style="font-size: 13px;">
                <div class="col-sm-6 text-muted">
                    Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $total_employees) ?> of <?= $total_employees ?> entries
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <nav aria-label="Page navigation">
                      <ul class="pagination pagination-sm m-0">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                          <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                          </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                          </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                          <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                          </a>
                        </li>
                      </ul>
                    </nav>
                </div>
            </div>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <form action="save_employee.php" method="POST">
                <div class="modal-header bg-light border-bottom-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-bold mb-2">Firstname</label>
                                <input type="text" name="firstname" class="form-control form-control-lg shadow-none" placeholder="Enter Firstname" required style="border-radius: 4px; border: 1px solid #ced4da;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-bold mb-2">Lastname</label>
                                <input type="text" name="lastname" class="form-control form-control-lg shadow-none" placeholder="Enter Lastname" required style="border-radius: 4px; border: 1px solid #ced4da;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-bold mb-2">Department</label>
                        <select name="department" class="form-control form-control-lg shadow-none" style="border-radius: 4px; border: 1px solid #ced4da;">
                            <option value="HR">HR</option>
                            <option value="SALES">SALES</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="ACCOUNTING">ACCOUNTING</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-bold mb-2">Employment Type</label>
                        <select name="employment_type" class="form-control form-control-lg shadow-none" style="border-radius: 4px; border: 1px solid #ced4da;">
                            <option value="Regular">Regular</option>
                            <option value="Probationary">Probationary</option>
                            <option value="Contractual">Contractual</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 justify-content-end pb-4">
                    <button type="button" class="btn btn-secondary px-4 mr-2" data-dismiss="modal" style="background-color: #6c757d; border: none; font-weight: bold;">Close</button>
                    <button type="submit" class="btn btn-danger px-4" style="background-color: #dc3545; border: none; font-weight: bold;">Save Employee</button>
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
.modal-content {
    background-color: #f8f9fa;
}
.modal-body label {
    font-size: 16px;
}
</style>
