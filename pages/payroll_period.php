<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

// Module Title
$filename = basename($_SERVER['PHP_SELF'], '.php');
$module_name = str_replace('_', ' ', $filename);
$module_name = ucwords($module_name);

// Fetch all payroll groups from database
try {
    $stmt = $conn->prepare("SELECT * FROM payroll_period_groups ORDER BY group_name ASC");
    $stmt->execute();
    $db_groups = $stmt->fetchAll();
} catch (PDOException $e) {
    $db_groups = [];
}
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #f4f6f9;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" style="font-size: 24px;">Time <small class="text-muted" style="font-size: 0.9rem;">Payroll Period</small></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right" style="background: transparent; font-size: 13px;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item">Time</li>
              <li class="breadcrumb-item active">Payroll Period</li>
            </ol>
            <div class="float-right w-100 d-flex justify-content-end mt-2">
               <div class="action-buttons">
                  <button class="btn btn-default btn-sm font-weight-bold py-1 px-2" onclick="showManageGroups()" style="border: 1px solid #ced4da; font-size: 12px; border-radius: 4px; color: #0056b3; background: #fff;"><i class="fas fa-cog"></i> Manage Payroll Period Employee Groups</button>
                  <button class="btn btn-default btn-sm font-weight-bold py-1 px-2 ml-1" onclick="showAddPayroll()" style="border: 1px solid #ced4da; font-size: 12px; border-radius: 4px; color: #28a745; background: #fff;"><i class="fas fa-plus-circle"></i> Add Payroll Period</button>
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <!-- Edit Interface (Hidden by default) -->
        <div id="payroll-edit-interface" class="card mb-4" style="border-radius: 4px; border: none; box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); display: none;">
            <div class="card-body p-4">
                <div class="mb-3 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-dark font-weight-bold" style="font-size: 14px;">Mancao Electronic Connect Business Solutions OPC</div>
                        <div class="text-dark" style="font-size: 14px;"><i class="fas fa-edit text-danger"></i> Working Schedule Reference (Regular)</div>
                    </div>
                    <button type="button" class="btn btn-tool" onclick="closePayrollEdit()" style="color: #6c757d; font-size: 20px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3 text-right">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Time IN</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="d-flex align-items-center">
                            <select class="form-control form-control-sm mr-2">
                                <option>00</option>
                                <?php for($i=1; $i<=23; $i++) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                            <span class="mr-2">:</span>
                            <select class="form-control form-control-sm">
                                <option>00</option>
                                <?php for($i=1; $i<=59; $i++) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3 text-right">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Time OUT</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="d-flex align-items-center">
                            <select class="form-control form-control-sm mr-2">
                                <option>09</option>
                                <?php for($i=0; $i<=23; $i++) if($i!=9) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                            <span class="mr-2">:</span>
                            <select class="form-control form-control-sm">
                                <option>00</option>
                                <?php for($i=1; $i<=59; $i++) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3 text-right">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Lunch Break</label>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm">
                            <option>60</option>
                            <option>30</option>
                            <option>0</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3 text-right">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">1st Break</label>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm">
                            <option>15</option>
                            <option>0</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3 text-right">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">2nd Break</label>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm">
                            <option>15</option>
                            <option>0</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3 text-right">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Registered Hours</label>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm">
                            <option>8</option>
                            <option>4</option>
                            <option>9</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3 pt-1 text-right">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Description</label>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" placeholder="Description">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-danger btn-sm px-3 py-1" style="background-color: #d9534f; border-color: #d43f3a; border-radius: 4px;">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
                </div>
            </div>
        </div>

        <div class="card card-outline" style="border-radius: 0; border: none; box-shadow: none;">
          <div id="payroll-table-container">
            <div class="card-body p-3">
              <!-- Filters -->
              <div class="row mb-3">
                <div class="col-md-2">
                  <label class="font-weight-bold mb-1" style="font-size: 13px;">Cover Year</label>
                  <select class="form-control form-control-sm">
                    <option>-All-</option>
                    <option>2024</option>
                    <option>2025</option>
                    <option>2026</option>
                  </select>
                </div>
                <div class="col-md-5">
                  <label class="font-weight-bold mb-1" style="font-size: 13px;">Payroll Period Group</label>
                  <select class="form-control form-control-sm">
                    <option>-All-</option>
                    <?php foreach($db_groups as $g): ?>
                    <option value="<?= $g['id'] ?>"><?= $g['group_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- DataTable Controls Placeholder -->
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center" style="font-size: 13px;">
                  Show 
                  <select class="custom-select custom-select-sm form-control form-control-sm mx-1" style="width: auto;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                  </select> entries
                </div>
                <div class="d-flex align-items-center" style="font-size: 13px;">
                  Search: <input type="search" class="form-control form-control-sm ml-1" placeholder="" style="width: 150px;">
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" style="font-size: 12px; border: 1px solid #dee2e6;">
                  <thead style="background-color: #fff; color: #333;">
                    <tr>
                      <th class="border-bottom-0">Group <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">Pay Type <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">Cut-Off <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">Date From - Date To <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">Cut-Off Day <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">Covered Year/Month <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">No. of Days <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">Pay Date <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="border-bottom-0">Description <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                      <th class="text-center border-bottom-0" style="width: 80px;">Option <i class="fas fa-sort float-right text-muted pt-1" style="font-size: 10px;"></i></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch existing periods from database
                    try {
                        $periods_stmt = $conn->query("
                            SELECT p.*, g.group_name 
                            FROM payroll_periods p 
                            JOIN payroll_period_groups g ON p.group_id = g.id 
                            ORDER BY p.date_from DESC
                        ");
                        $db_periods = $periods_stmt->fetchAll();
                    } catch (PDOException $e) {
                        $db_periods = [];
                    }

                    if (!empty($db_periods)) {
                        foreach($db_periods as $p):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($p['group_name']) ?></td>
                      <td>Weekly</td> <!-- Default or from DB if added -->
                      <td><?= htmlspecialchars($p['cut_off_day'] ?? 'N/A') ?></td>
                      <td>
                        <span class="text-danger">Payroll Period ID: <?= $p['id'] ?></span><br>
                        <?= date('F d Y', strtotime($p['date_from'])) ?> to <?= date('F d Y', strtotime($p['date_to'])) ?>
                      </td>
                      <td><?= htmlspecialchars($p['cut_off_day'] ?? '0') ?></td>
                      <td>
                        year cover: <?= htmlspecialchars($p['cover_year']) ?><br>
                        month cover: <?= htmlspecialchars($p['cover_month']) ?>
                      </td>
                      <td>
                        <?php 
                           $diff = strtotime($p['date_to']) - strtotime($p['date_from']);
                           echo round($diff / (60 * 60 * 24)) + 1;
                        ?>
                      </td>
                      <td><?= !empty($p['pay_date']) ? date('F d Y', strtotime($p['pay_date'])) : 'N/A' ?></td>
                      <td><?= htmlspecialchars($p['description'] ?? '') ?></td>
                      <td class="text-center">
                        <a href="javascript:void(0)" class="text-primary mr-2" style="font-size: 16px;"><i class="fas fa-trash text-purple" style="color: #9c27b0 !important;"></i></a>
                        <a href="javascript:void(0)" onclick="openPayrollEdit()" class="text-warning" style="font-size: 16px;"><i class="fas fa-pencil-alt" style="color: #ffc107 !important;"></i></a>
                      </td>
                    </tr>
                    <?php 
                        endforeach; 
                    } else {
                    ?>
                    <tr><td colspan="10" class="text-center">No payroll periods found.</td></tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Manage Payroll Period Employee Groups Interface -->
          <div id="manage-groups-container" style="display: none;">
            <div class="card-header p-2 d-flex justify-content-between align-items-center" style="background-color: #d9edf7; border-bottom: 1px solid #bce8f1;">
               <span class="text-info font-weight-bold ml-2" style="font-size: 14px;">Payroll Period Employee Groups</span>
            </div>
            <div class="card-body p-0">
               <!-- Group Table Controls relocated below Add Payroll Period button area -->
               <div class="d-flex justify-content-end p-2 bg-light border-bottom align-items-center">
                  <div class="btn-group border rounded bg-white shadow-sm mr-2" style="padding: 2px;">
                    <a href="javascript:void(0)" onclick="showEmployeeList()" class="btn btn-xs px-2" style="color: #17a2b8;" title="View List"><i class="fas fa-folder-open" style="font-size: 18px;"></i></a>
                    <a href="javascript:void(0)" onclick="showAddGroupForm()" class="btn btn-xs px-2 border-left" style="color: #28a745;" title="Add Group"><i class="fas fa-plus-circle" style="font-size: 18px;"></i></a>
                  </div>
                  <button class="btn btn-tool p-0" onclick="hideManageGroups()"><i class="fas fa-times text-danger" style="font-size: 20px;"></i></button>
               </div>
               
               <!-- Group Table -->
               <div id="manage-groups-table" class="table-responsive">
                  <table class="table table-bordered table-sm mb-0" style="font-size: 13px;">
                     <thead style="background-color: #f9f9f9;">
                        <tr>
                           <th>Pay Type</th>
                           <th>Group Name</th>
                           <th>Group Description</th>
                           <th>Current Status</th>
                           <th class="text-center" style="width: 150px;">Option</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        foreach($db_groups as $g):
                           $status_color = ($g['status'] == 'active') ? 'text-dark' : 'text-dark';
                           $power_color = ($g['is_on'] == 1) ? 'text-danger' : 'text-success';
                        ?>
                        <tr>
                           <td><?= $g['pay_type'] ?></td>
                           <td class="text-uppercase"><?= $g['group_name'] ?></td>
                           <td class="text-uppercase"><?= $g['description'] ?></td>
                           <td><?= $g['status'] ?></td>
                           <td class="text-center">
                              <a href="#" class="btn btn-xs px-2 <?= $power_color ?>"><i class="fas fa-power-off"></i></a>
                              <a href="javascript:void(0)" onclick="showEmployeeList()" class="btn btn-xs px-2 text-info"><i class="fas fa-folder-open"></i></a>
                              <?php if($g['status'] == 'active'): ?>
                              <a href="#" class="btn btn-xs px-2 text-warning"><i class="fas fa-pencil-alt"></i></a>
                              <a href="#" class="btn btn-xs px-2 text-danger"><i class="fas fa-trash"></i></a>
                              <?php endif; ?>
                           </td>
                        </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>

               <!-- Add Group Form -->
               <div id="add-group-form" style="display: none; padding: 20px;">
                  <div class="card card-outline mb-0" style="border: 1px solid #dee2e6; border-radius: 4px; box-shadow: none;">
                     <div class="card-body">
                        <div class="mb-4" style="font-size: 14px;">
                           <i class="fas fa-plus text-danger mr-1"></i> <span class="font-weight-bold">Create New Payroll Period Employee Group</span> <i class="fas fa-chevron-circle-right mx-1 text-muted"></i> <span class="text-dark">Mancao Electronic Connect Business Solutions OPC</span>
                        </div>
                        
                        <div class="row mb-3 align-items-center">
                           <div class="col-sm-3">
                              <label class="mb-0 font-weight-bold" style="font-size: 13px;">Pay Type</label>
                           </div>
                           <div class="col-sm-9">
                              <select class="form-control form-control-sm">
                                 <option selected disabled>Select Pay Type</option>
                                 <option>Weekly</option>
                                 <option>Semi-Monthly</option>
                                 <option>Monthly</option>
                              </select>
                           </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                           <div class="col-sm-3">
                              <label class="mb-0 font-weight-bold" style="font-size: 13px;">Group Name</label>
                           </div>
                           <div class="col-sm-9">
                              <input type="text" class="form-control form-control-sm" placeholder="group_name">
                           </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-sm-3 pt-1">
                              <label class="mb-0 font-weight-bold" style="font-size: 13px;">Description</label>
                           </div>
                           <div class="col-sm-9">
                              <input type="text" class="form-control form-control-sm" placeholder="Group Description">
                           </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                           <button class="btn btn-danger btn-sm px-3 py-1 mr-2" onclick="hideAddGroupForm()" style="background-color: #6c757d; border-color: #6c757d; border-radius: 4px;">
                              Cancel
                           </button>
                           <button class="btn btn-danger btn-sm px-3 py-1" style="background-color: #d9534f; border-color: #d43f3a; border-radius: 4px;">
                              <i class="fas fa-save mr-1"></i> Save
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
          </div>
        </div>

        <!-- Add Payroll Period Interface -->
        <div id="add-payroll-container" class="card mb-4" style="border-radius: 4px; border: none; box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); display: none;">
            <div class="card-body p-4">
                <form id="add-payroll-form">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div style="font-size: 14px;">
                            <i class="fas fa-info-circle text-dark"></i> <span class="font-weight-bold">Mancao Electronic Connect Business Solutions OPC</span> <i class="fas fa-arrow-right mx-1"></i> <span class="font-weight-bold">Create New Payroll Period</span>
                        </div>
                        <button type="button" class="btn btn-tool" onclick="hideAddPayroll()" style="color: #6c757d; font-size: 20px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Pay Type<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select name="pay_type" class="form-control form-control-sm" required>
                                <option selected disabled>Select Pay Type</option>
                                <option>Weekly</option>
                                <option>Semi-Monthly</option>
                                <option>Monthly</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Date From<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input type="date" name="date_from" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Date To<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input type="date" name="date_to" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Cover Month<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select name="cover_month" class="form-control form-control-sm" required>
                                <option selected disabled>Select (make sure this is correct.)</option>
                                <?php 
                                $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                                foreach($months as $m) echo "<option value='$m'>$m</option>"; 
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Cover Year<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select name="cover_year" class="form-control form-control-sm" required>
                                <option selected disabled>Select (make sure this is correct.)</option>
                                <option>2024</option>
                                <option>2025</option>
                                <option>2026</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Employee Group<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select name="group_id" class="form-control form-control-sm" required>
                                <option selected disabled>Select Group</option>
                                <?php foreach($db_groups as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= $g['group_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Cut-Off Day<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" name="cut_off_day" class="form-control form-control-sm" placeholder="" required>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Pay Date</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="date" name="pay_date" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-3">
                            <label class="mb-0 font-weight-bold" style="font-size: 13px;">Description</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="Description">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-danger btn-sm px-3 py-1" style="background-color: #d9534f; border-color: #d43f3a; border-radius: 4px;">
                            <i class="fas fa-save mr-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </section>

    <!-- Employee List Modal Overlay -->
    <div id="employee-list-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; padding: 50px 0;">
       <div class="container" style="max-width: 800px; height: 100%;">
          <div class="card" style="border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); height: calc(100% - 50px); overflow: hidden;">
             <div class="card-header p-2 d-flex justify-content-between align-items-center" style="background-color: #dff0d8; border-bottom: 1px solid #d6e9c6;">
                <span class="text-success font-weight-bold ml-2" style="font-size: 14px;">LIST OF EMPLOYEE(S)</span>
                <button class="btn btn-tool p-0 mr-1" onclick="hideEmployeeList()"><i class="fas fa-times-circle text-muted" style="font-size: 20px;"></i></button>
             </div>
             <div class="card-body p-0" style="overflow-y: auto;">
                <div class="table-responsive">
                   <table class="table table-bordered table-sm mb-0" style="font-size: 13px;">
                      <thead class="bg-white">
                         <tr>
                            <th class="font-weight-bold">Employee ID</th>
                            <th class="font-weight-bold">Employee Name</th>
                            <th class="font-weight-bold">Pay type</th>
                            <th class="font-weight-bold">Location / Classification</th>
                            <th class="font-weight-bold">Group Name</th>
                            <th class="font-weight-bold">Status</th>
                         </tr>
                      </thead>
                      <tbody>
                         <?php
                         $employees = [
                            ['220223', 'JOHN PAUL ALIMORONG SEÑORAN', 'Weekly', '1/1', 'WEEKLY NIGHTSHIFT GROUP', 'InActive'],
                            ['220230', 'REMELYN GRACE LOLO CANINO', 'Weekly', '1/1', 'WEEKLY MEDICARE GROUP', 'InActive'],
                            ['220225', 'RUBY ANN MATERO ITALLO', 'Weekly', '1/2', 'WEEKLY NIGHTSHIFT GROUP', 'InActive'],
                            ['220001', 'RIZA BUBA GRANADIROS', 'Semi-Monthly', '1/1', 'SEMI-MONTHLY MORNING GROUP', 'Active'],
                            ['220002', 'GEMMA GARONG FAJARDO', 'Semi-Monthly', '1/1', 'SEMI-MONTHLY MORNING GROUP', 'Active'],
                            ['220003', 'KIMBERLY DAGANATO PEREZ', 'Semi-Monthly', '1/1', 'SEMI-MONTHLY MORNING GROUP', 'Active'],
                            ['220004', 'MARGIELYN ASEO MACEDA', 'Semi-Monthly', '1/1', 'SEMI-MONTHLY MORNING GROUP', 'Active'],
                            ['220005', 'MARY NICOLE ANN LLEVARES', 'Semi-Monthly', '1/1', 'SEMI-MONTHLY MORNING GROUP', 'Active'],
                         ];
                         foreach($employees as $e):
                            $status_color = ($e[5] == 'Active') ? 'text-dark' : 'text-danger';
                         ?>
                         <tr>
                            <td><?= $e[0] ?></td>
                            <td><?= $e[1] ?></td>
                            <td><?= $e[2] ?></td>
                            <td><?= $e[3] ?></td>
                            <td class="text-uppercase"><?= $e[4] ?></td>
                            <td class="<?= $status_color ?>"><?= $e[5] ?></td>
                         </tr>
                         <?php endforeach; ?>
                      </tbody>
                   </table>
                </div>
             </div>
          </div>
       </div>
    </div>
</div>

<script src="<?= $base_url ?>assets/js/jquery.min.js"></script>
<script src="<?= $base_url ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base_url ?>assets/js/adminlte.min.js"></script>

<script>
function showEmployeeList() {
    $('#employee-list-modal').fadeIn(200);
}

function hideEmployeeList() {
    $('#employee-list-modal').fadeOut(200);
}

function openPayrollEdit() {
    $('#payroll-edit-interface').slideDown();
    $('html, body').animate({
        scrollTop: $("#payroll-edit-interface").offset().top - 100
    }, 500);
}

function closePayrollEdit() {
    $('#payroll-edit-interface').slideUp();
}

function showAddPayroll() {
    $('#payroll-table-container').hide();
    $('#manage-groups-container').hide();
    $('#add-payroll-container').show();
}

function hideAddPayroll() {
    $('#add-payroll-container').hide();
    $('#payroll-table-container').show();
}

function showManageGroups() {
    $('#payroll-table-container').hide();
    $('#add-payroll-container').hide();
    $('#manage-groups-container').show();
}

function hideManageGroups() {
    $('#manage-groups-container').hide();
    $('#payroll-table-container').show();
}

function showAddGroupForm() {
    $('#manage-groups-table').hide();
    $('#add-group-form').show();
}

function hideAddGroupForm() {
    $('#add-group-form').hide();
    $('#manage-groups-table').show();
}

$(document).ready(function() {
    console.log("Document ready - Payroll Period page");
    
    $('#add-payroll-form').on('submit', function(e) {
        e.preventDefault();
        console.log("Form submit triggered");
        const formData = $(this).serialize();
        console.log("Data to send:", formData);
        
        $.ajax({
            url: 'save_payroll.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log("Raw response:", response);
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while saving. Please check the console.');
                console.error("AJAX Error Status:", status);
                console.error("AJAX Error:", error);
                console.error("Server Response:", xhr.responseText);
            }
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
