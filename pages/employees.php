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

// Fetch Helper Data for Dropdowns (Wrapped in try/catch to handle missing tables)
try {
    $departments = $conn->query("SELECT id, name FROM departments ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    $departments = [['id' => 1, 'name' => 'HR'], ['id' => 2, 'name' => 'SALES'], ['id' => 3, 'name' => 'ADMIN']];
}

try {
    $companies = $conn->query("SELECT id, name FROM companies ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    $companies = [['id' => 1, 'name' => 'Company A'], ['id' => 2, 'name' => 'Company B']];
}

try {
    $locations = $conn->query("SELECT id, name FROM locations ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    $locations = [['id' => 1, 'name' => 'Main Office'], ['id' => 2, 'name' => 'Warehouse']];
}

try {
    $positions = $conn->query("SELECT id, name FROM positions ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    $positions = [['id' => 1, 'name' => 'Staff'], ['id' => 2, 'name' => 'Manager']];
}

$payroll_groups = [];
try {
    $payroll_groups = $conn->query("SELECT id, group_name FROM payroll_period_groups ORDER BY group_name ASC")->fetchAll();
} catch (PDOException $e) {
    // payroll_period_groups should exist based on schema, but fallback just in case
}

// Fetch total count for pagination
$count_stmt = $conn->query("SELECT COUNT(*) FROM employees");
$total_employees = $count_stmt->fetchColumn();
$total_pages = ceil($total_employees / $limit);

// Fetch real employees from the database with pagination
$stmt = $conn->prepare("
    SELECT e.id as internal_id, e.id_number, e.lastname, e.firstname, e.department, 
           ee.company, ee.position, ee.location, ee.section,
           pg.group_name as current_group, pg.id as current_group_id
    FROM employees e 
    LEFT JOIN employees_extended ee ON e.id_number = ee.employee_id 
    LEFT JOIN employee_group_assignments ega ON e.id_number = ega.employee_id
    LEFT JOIN payroll_period_groups pg ON ega.group_id = pg.id
    ORDER BY e.lastname ASC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll();

// Build Msg alert
$alert_html = '';
if ($msg === 'success') {
    $alert_html = '<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mx-3 mt-2" role="alert">
        <i class="fas fa-check-circle mr-2"></i> Employee saved successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>';
} elseif ($msg === 'updated_group' || isset($_GET['msg']) && $_GET['msg'] === 'updated_group') {
    $alert_html = '<div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mx-3 mt-2" role="alert">
        <i class="fas fa-sync-alt mr-2"></i> Payroll group updated successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>';
}
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #D6E9F1;">
    <!-- Content Header (Page header) -->
    <div class="content-header p-0">
      <?= $alert_html ?>
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
                  <th class="py-2">Payroll Group <i class="fas fa-sort float-right text-muted"></i></th>
                  <th class="py-2 text-center">Options <i class="fas fa-sort float-right text-muted"></i></th>
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
                      <td class="align-middle">
                        <span class="badge badge-info px-2 py-1" style="font-weight: normal; font-size: 11px;">
                            <?= htmlspecialchars($emp['current_group'] ?? 'No Group') ?>
                        </span>
                      </td>
                      <td class="align-middle text-center" style="white-space: nowrap;">
                        <a href="javascript:void(0)" class="text-success mx-1" title="Active"><i class="fas fa-power-off" style="font-size: 16px;"></i></a>
                        <a href="employee_profile.php?id=<?= htmlspecialchars($emp['id_number']) ?>" class="text-primary mx-1" title="View Profile"><i class="fas fa-file-alt" style="font-size: 16px;"></i></a>
                        <button type="button" class="btn btn-link p-0 text-warning mx-1 edit-group-btn" 
                                data-id="<?= htmlspecialchars($emp['id_number'] ?? '') ?>" 
                                data-internal="<?= htmlspecialchars($emp['internal_id'] ?? '') ?>" 
                                data-name="<?= htmlspecialchars(($emp['lastname'] ?? '') . ', ' . ($emp['firstname'] ?? '')) ?>"
                                data-group="<?= htmlspecialchars($emp['current_group_id'] ?? '') ?>"
                                onclick="fillGroupModal('<?= htmlspecialchars($emp['id_number'] ?? '') ?>', '<?= htmlspecialchars($emp['internal_id'] ?? '') ?>', '<?= htmlspecialchars(addslashes(($emp['lastname'] ?? '') . ', ' . ($emp['firstname'] ?? ''))) ?>', '<?= htmlspecialchars($emp['current_group_id'] ?? '') ?>')"
                                data-toggle="modal"
                                data-target="#editGroupModal"
                                title="Change Payroll Group"
                                style="text-decoration: none; border: none; background: none;">
                                <i class="fas fa-users-cog" style="font-size: 16px;"></i>
                        </button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                      <td colspan="9" class="text-center text-muted py-4">No employees found.</td>
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
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 0;">
            <div class="modal-header py-2" style="background: linear-gradient(to right, #6a11cb, #2575fc); color: white;">
                <h5 class="modal-title" style="font-size: 16px; font-weight: bold;">Add New Employee</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="save_employee.php" method="POST" id="addEmployeeForm" enctype="multipart/form-data">
                <div class="modal-body bg-light p-0">
                    <div class="row no-gutters">
                        <!-- Left Sidebar: Photo & Profile -->
                        <div class="col-md-3 bg-white border-right p-4 text-center">
                            <div class="mb-4">
                                <img src="<?= $base_url ?>assets/img/user-placeholder.png" id="photo-preview" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #eee;">
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input" id="photoInput" onchange="previewImage(this)">
                                    <label class="btn btn-info btn-block btn-sm shadow-none" for="photoInput" style="border-radius: 0; background-color: #00bcd4; border: none;">Choose Photo</label>
                                </div>
                            </div>
                            
                            <div class="form-group text-left mt-4 pt-3 border-top">
                                <label class="small text-bold text-muted mb-1">Employee ID ( Must Not Start With 0/Zero ) <i class="fas fa-arrow-left text-dark"></i></label>
                                <input type="text" name="id_number" id="id_number" class="form-control form-control-sm shadow-none border-info" required placeholder="Employee ID" style="border-radius: 0; border-width: 0 0 1px 0;">
                                <small class="text-danger" id="id_error" style="display:none;">Employee ID is required</small>
                            </div>
                        </div>

                        <!-- Right Panel: Tabs/Sections -->
                        <div class="col-md-9 p-0">
                            <!-- Personal Information -->
                            <div class="p-4" style="border-bottom: 2px solid #007bff; background: white;">
                                <h6 class="text-primary border-bottom pb-2 mb-3" style="font-weight: bold; font-size: 14px;">Personal Information</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="small text-bold mb-1">Title</label>
                                        <select name="title" class="form-control form-control-sm custom-select shadow-none" style="border-radius: 4px;">
                                            <option value="">Select</option>
                                            <option value="Mr">Mr.</option>
                                            <option value="Ms">Ms.</option>
                                            <option value="Mrs">Mrs.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">First Name</label>
                                        <input type="text" name="firstname" class="form-control form-control-sm shadow-none" required placeholder="First Name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">Middle Name</label>
                                        <input type="text" name="middlename" class="form-control form-control-sm shadow-none" placeholder="Middle Name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Last Name</label>
                                        <input type="text" name="lastname" class="form-control form-control-sm shadow-none" required placeholder="Last Name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">Name extension</label>
                                        <input type="text" name="name_extension" class="form-control form-control-sm shadow-none" placeholder="Name extension">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Birthday</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="date" name="birthday" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Gender</label>
                                        <select name="gender" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Civil Status</label>
                                        <select name="civil_status" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="small text-bold mb-1">Place of Birth</label>
                                        <input type="text" name="pob" class="form-control form-control-sm shadow-none" placeholder="Place of Birth">
                                    </div>
                                </div>
                            </div>

                            <!-- Employment Information -->
                            <div class="p-4 mt-1" style="border-bottom: 2px solid #dc3545; background: white;">
                                <h6 class="text-danger border-bottom pb-2 mb-3" style="font-weight: bold; font-size: 14px;">Employment Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Company</label>
                                        <select name="company_id" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <?php foreach($companies as $c): ?>
                                                <option value="<?= $c['name'] ?>"><?= $c['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Location</label>
                                        <select name="location" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <?php foreach($locations as $l): ?>
                                                <option value="<?= $l['name'] ?>"><?= $l['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Employment Type</label>
                                        <select name="employment_type" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <option value="Regular">Regular</option>
                                            <option value="Probationary">Probationary</option>
                                            <option value="Contractual">Contractual</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Classification</label>
                                        <select name="classification" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <option value="STAFF">STAFF</option>
                                            <option value="WORKER">WORKER</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Position</label>
                                        <select name="position" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <?php foreach($positions as $p): ?>
                                                <option value="<?= $p['name'] ?>"><?= $p['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Date Employed</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="date" name="date_employed" class="form-control shadow-none" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Tax Code</label>
                                        <select name="tax_code" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <option value="S/M">S/M</option>
                                            <option value="S/M 1">S/M 1</option>
                                            <option value="S/M 2">S/M 2</option>
                                            <option value="S/M 3">S/M 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Pay Type</label>
                                        <select name="pay_type" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Semi-Monthly">Semi-Monthly</option>
                                            <option value="Monthly">Monthly</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1 text-danger">Payroll Period Group</label>
                                        <select name="payroll_group_id" class="form-control form-control-sm custom-select shadow-none" required>
                                            <option value="">Select</option>
                                            <?php foreach($payroll_groups as $pg): ?>
                                                <option value="<?= $pg['id'] ?>"><?= $pg['group_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">Report to</label>
                                        <input type="text" name="report_to" class="form-control form-control-sm shadow-none" placeholder="Report to">
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="p-4 mt-1" style="border-bottom: 2px solid #28a745; background: white;">
                                <h6 class="text-success border-bottom pb-2 mb-3" style="font-weight: bold; font-size: 14px;">Account Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">Bank</label>
                                        <select name="bank" class="form-control form-control-sm custom-select shadow-none">
                                            <option value="">Select</option>
                                            <option value="BDO">BDO</option>
                                            <option value="BPI">BPI</option>
                                            <option value="Metrobank">Metrobank</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">Account No.</label>
                                        <input type="text" name="account_no" class="form-control form-control-sm shadow-none">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">TIN</label>
                                        <input type="text" name="tin" class="form-control form-control-sm shadow-none">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">SSS No.</label>
                                        <input type="text" name="sss_no" class="form-control form-control-sm shadow-none">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">Pagibig No.</label>
                                        <input type="text" name="pagibig_no" class="form-control form-control-sm shadow-none">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small text-bold mb-1">Philhealth</label>
                                        <input type="text" name="philhealth_no" class="form-control form-control-sm shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 py-2">
                    <button type="button" class="btn btn-default btn-sm px-3" data-dismiss="modal" style="border-radius: 4px; border: 1px solid #ddd;"><i class="fas fa-undo mr-1"></i> Reset</button>
                    <button type="submit" class="btn btn-info btn-sm px-4" style="border-radius: 4px; background-color: #00bcd4; border: none;"><i class="fas fa-save mr-1"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Payroll Group Modal -->
<div class="modal fade" id="editGroupModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 4px;">
            <div class="modal-header bg-warning text-white py-2">
                <h5 class="modal-title" style="font-size: 16px; font-weight: bold;"><i class="fas fa-users-cog mr-2"></i> Update Payroll Group</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update_employee_group.php" method="POST">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="small text-bold mb-1">Employee Name</label>
                        <input type="text" id="display_emp_name" class="form-control form-control-sm bg-light border-0 shadow-none" readonly>
                        <input type="hidden" name="employee_id" id="edit_emp_id">
                        <input type="hidden" name="internal_id" id="edit_internal_id">
                    </div>
                    <div class="form-group mb-0">
                        <label class="small text-bold mb-1 text-primary">Select Payroll Group</label>
                        <select name="group_id" id="edit_group_id" class="form-control form-control-sm custom-select shadow-none" required>
                            <option value="">-- No Group assigned --</option>
                            <?php foreach($payroll_groups as $pg): ?>
                                <option value="<?= $pg['id'] ?>"><?= $pg['group_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted mt-2 d-block">This determines the payroll period dates for the employee.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 py-2">
                    <button type="button" class="btn btn-default btn-sm px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm px-4 text-white text-bold shadow-none" style="background-color: #f39c12; border: none;">Update Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fillGroupModal(id, internal, name, group) {
    console.log("Fill Group Modal called:", { id: id, internal: internal, name: name });
    document.getElementById('edit_emp_id').value = id;
    document.getElementById('edit_internal_id').value = internal;
    document.getElementById('display_emp_name').value = name;
    document.getElementById('edit_group_id').value = group;
}

$(document).ready(function() {
    console.log("Document ready - checking jQuery version: " + (typeof $ !== 'undefined' ? $.fn.jquery : 'Not Loaded'));

    // Try a direct click event first on the document level
    $(document).on('click', '.edit-group-btn', function(e) {
        // Log to console for debugging - you can check this with F12
        console.log("Edit Group Button Clicked via delegation");
        
        try {
            var btn = $(this);
            var id = btn.attr('data-id');
            var internal = btn.attr('data-internal');
            var name = btn.attr('data-name');
            var group = btn.attr('data-group');
            
            console.log("CLICKED BUTTON ATTR:", { id: id, internal: internal, name: name });
            
            $('#edit_emp_id').val(id || "");
            $('#edit_internal_id').val(internal || "");
            $('#display_emp_name').val(name || "");
            $('#edit_group_id').val(group || "");
            
            console.log("FORM VALUES AFTER SET:", {
                emp_id: $('#edit_emp_id').val(),
                internal_id: $('#edit_internal_id').val()
            });
            
            // Try to force show the modal manually just in case
            $('#editGroupModal').modal('show');
            
            console.log("Data set for: " + name + " (ID: " + id + ", Internal: " + internal + ")");
        } catch (err) {
            console.error("Error in click handler: ", err);
        }
    });

    // Handle standard modal events to ensure proper cleanup/state
    $('#editGroupModal').on('show.bs.modal', function (event) {
        console.log("Modal show event triggered");
    });
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photo-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
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
