<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

$id_number = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch employee details
$stmt = $conn->prepare("
    SELECT e.*, ee.* 
    FROM employees e 
    LEFT JOIN employees_extended ee ON e.id_number = ee.employee_id 
    WHERE e.id_number = ?
");
$stmt->execute([$id_number]);
$emp = $stmt->fetch();

if (!$emp) {
    echo "<div class='content-wrapper'><div class='container-fluid'><div class='alert alert-danger'>Employee not found.</div></div></div>";
    include '../includes/footer.php';
    exit;
}

// Get current tab
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'personal';
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #D6E9F1;">
    <!-- Content Header -->
    <div class="content-header p-0">
      <div class="container-fluid">
        <div class="row pt-2 px-3 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" style="font-size: 20px;">201 Employee Files <small class="text-muted" style="font-size: 14px;">201 Profile</small></h1>
          </div>
          <div class="col-sm-6 text-right">
            <ol class="breadcrumb float-sm-right" style="background: transparent; margin: 0; padding: 0; font-size: 11px;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item text-dark">201 Employee Files</li>
              <li class="breadcrumb-item text-dark">Employees Masterlist</li>
              <li class="breadcrumb-item active text-muted">201 Profile Record</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content mt-3">
      <div class="container-fluid px-3">
        <div class="row">
            <!-- Sidebar Profile Card -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 mb-3" style="border-radius: 4px;">
                    <div class="card-body text-center p-4">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="<?= $base_url ?>assets/img/user-placeholder.png" class="img-circle elevation-2" alt="User Image" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff;">
                        </div>
                        <h5 class="mb-0 font-weight-bold"><?= htmlspecialchars($emp['id_number']) ?></h5>
                        <p class="text-muted small mb-0">" "</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0" style="border-radius: 4px;">
                    <div class="list-group list-group-flush small" style="max-height: 500px; overflow-y: auto;">
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center active" style="background-color: #f8f9fa; color: #333; border-left: 3px solid #007bff; border-right: 0; border-top: 0; position: sticky; top: 0; z-index: 1;">
                            <span>201 Employee Files <i class="fas fa-eye text-danger ml-1"></i></span>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=personal" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'personal') ? 'active' : '' ?> py-2 px-3">
                            Personal Information <i class="fas fa-user-tie text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=employment" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'employment') ? 'active' : '' ?> py-2 px-3">
                            Employment Information <i class="fas fa-briefcase text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=account" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'account') ? 'active' : '' ?> py-2 px-3">
                            Account Information <i class="fas fa-credit-card text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=address" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'address') ? 'active' : '' ?> py-2 px-3">
                            Address <i class="fas fa-map-marker-alt text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=residence" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'residence') ? 'active' : '' ?> py-2 px-3">
                            Residence <i class="fas fa-map-marker-alt text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=contact" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'contact') ? 'active' : '' ?> py-2 px-3">
                            Contact Information <i class="fas fa-mobile-alt text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=family" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'family') ? 'active' : '' ?> py-2 px-3">
                            Family <i class="fas fa-users text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=dependents" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'dependents') ? 'active' : '' ?> py-2 px-3">
                            Dependents <i class="fas fa-child text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=education" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'education') ? 'active' : '' ?> py-2 px-3">
                            Educational Attainment <i class="fas fa-graduation-cap text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=trainings" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'trainings') ? 'active' : '' ?> py-2 px-3">
                            Trainings and Seminars <i class="fas fa-certificate text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=experience" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'experience') ? 'active' : '' ?> py-2 px-3">
                            Employment Experience <i class="fas fa-history text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=reference" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'reference') ? 'active' : '' ?> py-2 px-3">
                            Character Reference <i class="fas fa-id-card-alt text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=skill" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'skill') ? 'active' : '' ?> py-2 px-3">
                            Skill <i class="fas fa-cogs text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=contract" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'contract') ? 'active' : '' ?> py-2 px-3">
                            Contract <i class="fas fa-file-contract text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=inventory" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'inventory') ? 'active' : '' ?> py-2 px-3">
                            Inventory <i class="fas fa-boxes text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=licenses" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'licenses') ? 'active' : '' ?> py-2 px-3">
                            Employee Licenses <i class="fas fa-id-badge text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=transactions" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'transactions') ? 'active' : '' ?> py-2 px-3">
                            Transactions <i class="fas fa-exchange-alt text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=login" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'login') ? 'active' : '' ?> py-2 px-3">
                            Login Information <i class="fas fa-user-lock text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=log_history" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'log_history') ? 'active' : '' ?> py-2 px-3">
                            Log History <i class="fas fa-history text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=status_history" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'status_history') ? 'active' : '' ?> py-2 px-3">
                            Status History <i class="fas fa-file-medical-alt text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=signature" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'signature') ? 'active' : '' ?> py-2 px-3">
                            Employee Signature <i class="fas fa-signature text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=movement" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'movement') ? 'active' : '' ?> py-2 px-3">
                            Movement History <i class="fas fa-walking text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=full_info" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'full_info') ? 'active' : '' ?> py-2 px-3">
                            View Full Information <i class="fas fa-id-card text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=other_info" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'other_info') ? 'active' : '' ?> py-2 px-3">
                            Other Info <i class="fas fa-link text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=body_picture" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'body_picture') ? 'active' : '' ?> py-2 px-3">
                            Whole Body Picture <i class="fas fa-image text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=resigned_history" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'resigned_history') ? 'active' : '' ?> py-2 px-3">
                            Resigned Date History <i class="fas fa-user-slash text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=employment_history" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'employment_history') ? 'active' : '' ?> py-2 px-3">
                            Employment Date History <i class="fas fa-calendar-alt text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=leave_history" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'leave_history') ? 'active' : '' ?> py-2 px-3">
                            Long Service Leave History <i class="fas fa-calendar-check text-muted"></i>
                        </a>
                        <a href="?id=<?= $id_number ?>&tab=delete" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($tab == 'delete') ? 'active' : '' ?> py-2 px-3 text-danger">
                            Delete Employee? <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-9">
                <?php if ($tab == 'personal'): ?>
                <!-- Personal Information -->
                <div class="card shadow-sm border-0" style="border-radius: 4px; border-top: 3px solid #28a745 !important;">
                    <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-success font-weight-bold mb-0" style="font-size: 14px; text-transform: uppercase;">Personal Information</h3>
                        <a href="#" class="text-success"><i class="fas fa-edit"></i></a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Title</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">First Name</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars(strtoupper($emp['firstname'])) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Middle Name</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Last Name</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars(strtoupper($emp['lastname'])) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Nickname</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Birthday</div>
                            <div class="col-sm-8 font-weight-bold"><?= isset($emp['birthday']) && $emp['birthday'] ? date('d M Y', strtotime($emp['birthday'])) : 'N/A' ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Age</div>
                            <div class="col-sm-8 font-weight-bold">
                                <?php 
                                if (isset($emp['birthday']) && $emp['birthday']) {
                                    $birthdate = new DateTime($emp['birthday']);
                                    $today = new DateTime();
                                    $age = $today->diff($birthdate);
                                    echo "$age->y yrs, $age->m mos. and $age->d days";
                                } else {
                                    echo "N/A";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Place of Birth</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Gender</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars($emp['gender'] ?? '') ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Civil Status</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars($emp['civil_status'] ?? 'Single') ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Blood Type</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Citizenship</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Religion</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($tab == 'employment'): ?>
                <!-- Employment Information -->
                <div class="card shadow-sm border-0" style="border-radius: 4px; border-top: 3px solid #28a745 !important;">
                    <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-success font-weight-bold mb-0" style="font-size: 14px; text-transform: uppercase;">Employment Information</h3>
                        <a href="#" class="text-success"><i class="fas fa-edit"></i></a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Company</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars($emp['company'] ?? '') ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Location</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars($emp['location'] ?? '') ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Department</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars($emp['department'] ?? '') ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Section</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars($emp['section'] ?? '') ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Employment type</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars(strtoupper($emp['employment_type'] ?? 'REGULAR')) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Position</div>
                            <div class="col-sm-8 font-weight-bold"><?= htmlspecialchars(strtoupper($emp['position'] ?? '')) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Taxcode</div>
                            <div class="col-sm-8 font-weight-bold">S/ME</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Classification</div>
                            <div class="col-sm-8 font-weight-bold">STAFF</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Date employed</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Pay type</div>
                            <div class="col-sm-8 font-weight-bold">Semi-Monthly</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted small">Report to</div>
                            <div class="col-sm-8 font-weight-bold"></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($tab == 'address'): ?>
                <!-- Address -->
                <div class="card shadow-sm border-0" style="border-radius: 4px; border-top: 3px solid #28a745 !important;">
                    <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-success font-weight-bold mb-0" style="font-size: 14px; text-transform: uppercase;">Address</h3>
                        <a href="#" class="text-success"><i class="fas fa-edit"></i></a>
                    </div>
                    <div class="card-body p-4">
                        <div class="card mb-3 shadow-none border" style="border-radius: 4px;">
                            <div class="card-header py-1 px-3" style="background-color: #f8d7da; color: #721c24; border-bottom: 1px solid #f5c6cb;">
                                <span class="small">Permanent Address</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">Address</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">City</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">Province</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">Years of stay</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-0 shadow-none border" style="border-radius: 4px;">
                            <div class="card-header py-1 px-3" style="background-color: #fff3cd; color: #856404; border-bottom: 1px solid #ffeeba;">
                                <span class="small">Present Address</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">Address</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">City</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">Province</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-sm-4 text-muted small">Years of stay</div>
                                    <div class="col-sm-8 font-weight-bold"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($tab == 'residence'): ?>
                <!-- Residence Map -->
                <div class="card shadow-sm border-0" style="border-radius: 4px; border-top: 3px solid #28a745 !important;">
                    <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-success font-weight-bold mb-0" style="font-size: 14px; text-transform: uppercase;">Residence Map</h3>
                    </div>
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <h5 class="mb-3">Update Residence Map</h5>
                            <p class="small text-muted mb-3"><strong>Choose your new residence map:</strong></p>
                            <form action="#" method="POST" enctype="multipart/form-data">
                                <div class="form-group d-flex flex-column align-items-center">
                                    <input type="file" class="form-control-file mb-2" style="width: auto;">
                                    <span class="small text-muted mb-3">Maximum Allowed Size: 500KB</span>
                                    <button type="submit" class="btn btn-success px-4" style="border-radius: 4px;">Upload</button>
                                </div>
                            </form>
                        </div>
                        <div class="text-right mt-5">
                            <button class="btn btn-success btn-sm"><i class="fas fa-download"></i> Download</button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!in_array($tab, ['personal', 'employment', 'address', 'residence'])): ?>
                <!-- Placeholder for other tabs -->
                <div class="card shadow-sm border-0" style="border-radius: 4px; border-top: 3px solid #28a745 !important;">
                    <div class="card-header bg-light py-2 px-3">
                        <h3 class="card-title text-success font-weight-bold mb-0" style="font-size: 14px; text-transform: uppercase;"><?= str_replace('_', ' ', strtoupper($tab)) ?></h3>
                    </div>
                    <div class="card-body p-4 text-center text-muted">
                        Section content for <?= htmlspecialchars($tab) ?> will be displayed here.
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
      </div>
    </section>

    <a href="#" class="btn btn-danger btn-sm shadow-sm" style="position: fixed; bottom: 20px; right: 20px; border-radius: 4px; font-size: 12px; padding: 2px 8px; z-index: 1000;">
        <i class="fas fa-angles-up"></i> go to top
    </a>
</div>

<style>
.list-group-item {
    border: none;
    border-bottom: 1px solid #f1f1f1;
    font-size: 12px;
}
.list-group-item.active {
    background-color: #f8f9fa !important;
    color: #333 !important;
    border-left: 3px solid #007bff !important;
    font-weight: bold;
}
.card-header {
    border-bottom: 1px solid #eee;
}
.text-muted.small {
    font-size: 11px;
}
</style>

<?php include '../includes/footer.php'; ?>
