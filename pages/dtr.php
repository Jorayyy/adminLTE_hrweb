<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

// Fetch payroll periods
$periods_stmt = $conn->query("
    SELECT p.*, g.group_name 
    FROM payroll_periods p 
    JOIN payroll_period_groups g ON p.group_id = g.id 
    ORDER BY p.date_from DESC
");
$all_periods = $periods_stmt->fetchAll();

// Group periods by group name
$periods_by_group = [];
foreach ($all_periods as $period) {
    $periods_by_group[$period['group_name']][] = $period;
}

// Fetch all groups (even those without periods)
$all_groups_stmt = $conn->query("SELECT group_name, id FROM payroll_period_groups WHERE is_on = 1 ORDER BY group_name ASC");
$all_groups = $all_groups_stmt->fetchAll();

// Fetch employees for the individual select
$employees_stmt = $conn->query("SELECT id_number, firstname, lastname FROM employees ORDER BY lastname ASC");
$employees_list = $employees_stmt->fetchAll();
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #D6E9F1; margin-left: 0 !important;">
    <!-- Content Header (Page header) -->
    <div class="content-header p-0">
      <div class="container-fluid">
        <div class="row pt-2 px-3 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" style="font-size: 20px;">Time <small class="text-muted" style="font-size: 14px;">Daily Time Record</small></h1>
          </div>
          <div class="col-sm-6 text-right">
            <ol class="breadcrumb float-sm-right" style="background: transparent; margin: 0; padding: 0; font-size: 11px;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item text-dark">Time</li>
              <li class="breadcrumb-item active text-muted">Daily Time Record</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content mt-3">
      <div class="container-fluid px-4">
        
        <!-- Individual Filter Section (Hidden by default) -->
        <div id="individualFilterSection" class="row mb-4" style="display: none;">
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-2" style="border-bottom: 2px solid #f8d7da;">
                <h3 class="card-title text-danger text-bold m-0" style="font-size: 14px;">Mancao Electronic Connect Business Solutions OPC</h3>
              </div>
              <div class="card-body py-4">
                <form action="view_attendance.php" method="GET">
                  <div class="form-group row mb-4 px-5">
                    <label class="col-sm-3 col-form-label text-right text-bold" style="font-size: 14px;">Individual Employee</label>
                    <div class="col-sm-9">
                      <select name="emp_id" class="form-control form-control-sm shadow-none" style="background: white; border: 1px solid #ced4da;">
                        <option value="">Select Employee</option>
                        <?php foreach($employees_list as $e): ?>
                          <option value="<?= $e['id_number'] ?>"><?= $e['lastname'] ?>, <?= $e['firstname'] ?> (<?= $e['id_number'] ?>)</option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row mb-4 px-5">
                    <label class="col-sm-3 col-form-label text-right text-bold" style="font-size: 14px;">Pay Type</label>
                    <div class="col-sm-9">
                      <select name="pay_type" class="form-control form-control-sm shadow-none">
                        <option value="">Select Pay Type</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Semi-Monthly">Semi-Monthly</option>
                        <option value="Weekly">Weekly</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row mb-4 px-5">
                    <label class="col-sm-3 col-form-label text-right text-bold" style="font-size: 14px;">Option</label>
                    <div class="col-sm-9">
                      <select name="option" class="form-control form-control-sm shadow-none">
                        <option value="view">View Processed DTR</option>
                        <option value="process">Process DTR</option>
                        <option value="status">Check DTR Status</option>
                        <option value="manual">Manual Encode DTR Summary</option>
                        <option value="clear">Clear DTR</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row mb-0 px-5">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                      <button type="submit" class="btn btn-danger btn-sm px-4">Generate</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <?php 
          // Definition of actual groups from the system
          if (!empty($all_groups)) {
              foreach ($all_groups as $group): 
                  $group_name = $group['group_name'];
                  $periods = $periods_by_group[$group_name] ?? [];
          ?>
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-2" style="border-bottom: 1px solid #f0f0f0;">
                <div class="row align-items-center">
                  <div class="col-6">
                    <h3 class="card-title text-danger text-bold m-0" style="font-size: 14px; text-transform: uppercase;"><?= htmlspecialchars($group_name) ?></h3>
                  </div>
                  <div class="col-6 text-right">
                    <button class="btn btn-light btn-sm border text-bold filter-toggle shadow-sm" style="font-size: 13px; color: #333; padding: 10px 20px; border-radius: 4px; border: 1px solid #ddd !important; background: #f8f9fa;">Click Me To Filter OR Individual Processing</button>
                  </div>
                </div>
              </div>
              <div class="card-body py-4">
                <form action="view_attendance.php" method="GET">
                  <input type="hidden" name="group" value="<?= htmlspecialchars($group_name) ?>">
                  <div class="form-group row mb-4">
                    <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Option</label>
                    <div class="col-sm-8">
                      <select name="option" class="form-control form-control-sm shadow-none" style="border: 1px solid #007bff; height: 38px; border-radius: 2px;">
                        <option value="view" selected>View Processed DTR</option>
                        <option value="process">Process DTR</option>
                        <option value="status">Check DTR Status</option>
                        <option value="clear">Clear DTR</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Payroll Period</label>
                    <div class="col-sm-8">
                      <select name="period_id" class="form-control form-control-sm shadow-none" style="border: 1px solid #ddd; height: 38px; border-radius: 2px;">
                        <?php if(!empty($periods)): ?>
                          <?php foreach ($periods as $p): ?>
                            <option value="<?= $p['id'] ?>">
                              <?= date('F d Y', strtotime($p['date_from'])) ?> to <?= date('F d Y', strtotime($p['date_to'])) ?> (Paydate:<?= $p['pay_date'] ?>)
                            </option>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <option value="">No Active Periods Found</option>
                        <?php endif; ?>
                      </select>
                    </div>
                  </div>
                  <div class="text-right">
                    <button type="submit" class="btn btn-danger px-4 py-2 text-bold shadow-sm" style="background-color: #e74c3c; border: none; font-size: 14px;"><i class="fas fa-paper-plane mr-2"></i> Generate</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php endforeach; 
          } else { ?>
             <div class="col-12">
               <div class="alert alert-info">No payroll groups found. Please define groups and periods in the settings.</div>
             </div>
          <?php } ?>
        </div>
      </div>
    </section>
</div>

<script>
document.querySelectorAll('.filter-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const filterSection = document.getElementById('individualFilterSection');
        if (filterSection.style.display === 'none') {
            filterSection.style.display = 'block';
        } else {
            filterSection.style.display = 'none';
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
