<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';


// Module Title
$filename = basename($_SERVER['PHP_SELF'], '.php');
$module_name = str_replace('_', ' ', $filename);
$module_name = ucwords($module_name);
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #f4f6f9;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Time <small class="text-muted" style="font-size: 0.9rem;">Shift Table</small></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right" style="background: transparent;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item">Time</li>
              <li class="breadcrumb-item active">Shift Table</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <!-- Working Schedule Reference (Edit Interface) -->
        <div id="edit-interface" class="card mb-4" style="border-radius: 4px; border: none; box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); display: none;">
            <div class="card-body p-4">
                <div class="mb-3 d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-dark font-weight-bold" style="font-size: 14px;">Mancao Electronic Connect Business Solutions OPC</div>
                        <div class="text-dark" style="font-size: 14px;"><i class="fas fa-edit text-danger"></i> Working Schedule Reference (Regular)</div>
                    </div>
                    <button type="button" class="btn btn-tool" onclick="closeEditInterface()" style="color: #6c757d; font-size: 20px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>,
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Time IN</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="d-flex align-items-center">
                            <select class="form-control form-control-sm mr-2" style="width: 100%;">
                                <option>00</option>
                                <?php for($i=1; $i<=23; $i++) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                            <span class="mr-2">:</span>
                            <select class="form-control form-control-sm" style="width: 100%;">
                                <option>00</option>
                                <?php for($i=1; $i<=59; $i++) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Time OUT</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="d-flex align-items-center">
                            <select class="form-control form-control-sm mr-2" style="width: 100%;">
                                <option>09</option>
                                <?php for($i=0; $i<=23; $i++) if($i!=9) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                            <span class="mr-2">:</span>
                            <select class="form-control form-control-sm" style="width: 100%;">
                                <option>00</option>
                                <?php for($i=1; $i<=59; $i++) echo "<option>".sprintf("%02d", $i)."</option>"; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Lunch Break</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm">
                            <option>60</option>
                            <option>30</option>
                            <option>0</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">1st Break</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm">
                            <option>15</option>
                            <option>0</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">2nd Break</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm">
                            <option>15</option>
                            <option>0</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Registered Hours</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm">
                            <option>8</option>
                            <option>4</option>
                            <option>9</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3 pt-1">
                        <label class="mb-0 font-weight-bold" style="font-size: 13px;">Description</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" placeholder="Description">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-danger btn-sm px-3 py-1 btn-save" style="background-color: #d9534f; border-color: #d43f3a; border-radius: 4px;">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
                </div>
            </div>
        </div>

        <div class="card card-outline" style="border-radius: 0; border: none; box-shadow: none;">
          <div class="card-header p-2" style="background-color: #E8F5E9; border-bottom: 2px solid #28a745;">
            <div class="d-flex justify-content-between align-items-center w-100">
               <div class="info-text d-flex align-items-center">
                  <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 25px; height: 25px;">
                    <i class="fas fa-info text-white" style="font-size: 14px;"></i>
                  </div>
                  <span class="text-success font-weight-bold" style="font-size: 14px;">Mancao Electronic Connect Business Solutions OPC</span>
               </div>
               <div class="action-buttons">
                  <button class="btn btn-success btn-sm font-weight-bold py-1 px-2" style="background-color: #1a8a44; border-color: #1a8a44; font-size: 12px; border-radius: 4px;"><i class="fas fa-plus-circle"></i> Regular/Wholeday</button>
                  <button class="btn btn-outline-success btn-sm font-weight-bold py-1 px-2 ml-1" style="color: #1a8a44; border-color: #ced4da; font-size: 12px; background: #fff; border-radius: 4px;"><i class="fas fa-plus-circle"></i> Half Day</button>
                  <button class="btn btn-outline-success btn-sm font-weight-bold py-1 px-2 ml-1" style="color: #1a8a44; border-color: #ced4da; font-size: 12px; background: #fff; border-radius: 4px;"><i class="fas fa-plus-circle"></i> Rest day/Holiday</button>
                  <button class="btn btn-outline-success btn-sm font-weight-bold py-1 px-2 ml-1" style="color: #1a8a44; border-color: #ced4da; font-size: 12px; background: #fff; border-radius: 4px;"><i class="fas fa-plus-circle"></i> Controlled Flexi</button>
               </div>
            </div>
          </div>
          <div class="card-body p-0 pt-4" style="background-color: #f8f9fa;">
            <style>
                .classification-item {
                    padding: 0 25px 30px 25px;
                }
                .classification-box {
                    background: #eeeeee;
                    border: 1px solid #c8c8c8;
                    padding: 6px 15px;
                    border-radius: 4px;
                    display: inline-flex;
                    align-items: center;
                    min-width: 320px;
                    box-shadow: none;
                    cursor: pointer;
                    user-select: none;
                }
                .classification-box i {
                    color: #ff0000;
                    font-size: 32px;
                    margin-right: 15px;
                }
                .classification-box span {
                    font-weight: 500;
                    color: #333;
                    font-size: 14px;
                }

                .schedule-content {
                    width: 100%;
                }

                .schedule-table {
                    width: 100%;
                    border-collapse: collapse;
                    background: transparent;
                }
                .schedule-table th {
                    border: none;
                    border-bottom: 1px solid #eee;
                    padding: 12px 10px;
                    text-align: left;
                    color: #333;
                    font-weight: 700;
                    font-size: 14px;
                }
                .schedule-table td {
                    padding: 12px 10px;
                    border: none;
                    font-size: 13px;
                    vertical-align: top;
                }
                .schedule-table tr:nth-child(even) {
                    background-color: #fcfcfc;
                }
                .section-title {
                    color: #a52a2a;
                    font-size: 14px;
                    font-weight: bold;
                    margin: 20px 0 10px 0;
                    display: flex;
                    align-items: center;
                }
                .section-title i {
                    margin-right: 8px;
                    font-size: 16px;
                }
                .btn-action {
                    padding: 0;
                    background: none;
                    border: none;
                    font-size: 28px;
                    cursor: pointer;
                    margin-left: 5px;
                    position: relative;
                    z-index: 10;
                }
                .btn-edit { color: #ffcc00; }
                .btn-delete { color: #d81b60; }
                
                /* Ensure clicks are captured */
                .btn-action i {
                    pointer-events: none;
                }
            </style>

            <div class="classification-list">
                <!-- STAFF -->
                <div class="classification-item border-top" style="border-top: 3px solid #28a745 !important;">
                    <div class="classification-box mb-3 mt-3" data-target="#staff-schedules">
                        <i class="fas fa-arrow-circle-down" style="pointer-events: none;"></i>
                        <span style="pointer-events: none;">Classification: STAFF</span>
                    </div>

                    <div id="staff-schedules" class="schedule-content">
                        <div class="section-title">
                            <i class="far fa-clock"></i> Regular Schedules
                        </div>

                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Shift</th>
                                    <th style="width: 30%;">Break(s)</th>
                                    <th style="width: 15%;">Registered Hours</th>
                                    <th style="width: 30%;">Description</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>00:00 to 09:00</td>
                                    <td>lunch break: 60min(s).<br>1st break: 15min(s).<br>2nd break: 15min(s).</td>
                                    <td>8 hr(s)</td>
                                    <td></td>
                                    <td class="action-cell">
                                        <div class="d-flex">
                                            <a href="javascript:void(0)" onclick="openEditInterface()" class="btn-action" title="Edit Shift">
                                                <i class="fas fa-edit" style="color: #ffcc00; font-size: 28px; pointer-events: none;"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-delete-trigger" title="Delete Shift">
                                                <i class="fas fa-trash" style="color: #d81b60; font-size: 28px; pointer-events: none;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>00:00 to 10:00</td>
                                    <td>lunch break: 60min(s).<br>1st break: 15min(s).<br>2nd break: 15min(s).</td>
                                    <td>8 hr(s)</td>
                                    <td>for accounting</td>
                                    <td class="action-cell">
                                        <div class="d-flex">
                                            <a href="javascript:void(0)" onclick="openEditInterface()" class="btn-action" title="Edit Shift">
                                                <i class="fas fa-edit" style="color: #ffcc00; font-size: 28px; pointer-events: none;"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-delete-trigger" title="Delete Shift">
                                                <i class="fas fa-trash" style="color: #d81b60; font-size: 28px; pointer-events: none;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>00:00 to 05:00</td>
                                    <td>lunch break: 60min(s).<br>1st break: 00min(s).<br>2nd break: 00min(s).</td>
                                    <td>8 hr(s)</td>
                                    <td></td>
                                    <td class="action-cell">
                                        <div class="d-flex">
                                            <a href="javascript:void(0)" onclick="openEditInterface()" class="btn-action" title="Edit Shift">
                                                <i class="fas fa-edit" style="color: #ffcc00; font-size: 28px; pointer-events: none;"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-delete-trigger" title="Delete Shift">
                                                <i class="fas fa-trash" style="color: #d81b60; font-size: 28px; pointer-events: none;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>00:00 to 08:00</td>
                                    <td>lunch break: 60min(s).<br>1st break: 15min(s).<br>2nd break: 15min(s).</td>
                                    <td>8 hr(s)</td>
                                    <td></td>
                                    <td class="action-cell">
                                        <div class="d-flex">
                                            <a href="javascript:void(0)" onclick="openEditInterface()" class="btn-action" title="Edit Shift">
                                                <i class="fas fa-edit" style="color: #ffcc00; font-size: 28px; pointer-events: none;"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-delete-trigger" title="Delete Shift">
                                                <i class="fas fa-trash" style="color: #d81b60; font-size: 28px; pointer-events: none;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>00:00 to 06:00</td>
                                    <td>lunch break: 00min(s).<br>1st break: 00min(s).<br>2nd break: 00min(s).</td>
                                    <td>4 hr(s)</td>
                                    <td></td>
                                    <td class="action-cell">
                                        <div class="d-flex">
                                            <a href="javascript:void(0)" onclick="openEditInterface()" class="btn-action" title="Edit Shift">
                                                <i class="fas fa-edit" style="color: #ffcc00; font-size: 28px; pointer-events: none;"></i>
                                            </a>
                                            <button type="button" class="btn-action btn-delete-trigger" title="Delete Shift">
                                                <i class="fas fa-trash" style="color: #d81b60; font-size: 28px; pointer-events: none;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SUPERVISORY -->
                <div class="classification-item border-top" style="border-top: 3px solid #28a745 !important;">
                    <div class="classification-box mt-3 collapsed" data-target="#supervisory-schedules">
                        <i class="fas fa-arrow-circle-down" style="pointer-events: none;"></i>
                        <span style="pointer-events: none;">Classification: SUPERVISORY</span>
                    </div>
                </div>

                <!-- EXECUTIVE -->
                <div class="classification-item border-top" style="border-top: 3px solid #28a745 !important;">
                    <div class="classification-box mt-3 collapsed" data-target="#executive-schedules">
                        <i class="fas fa-arrow-circle-down" style="pointer-events: none;"></i>
                        <span style="pointer-events: none;">Classification: EXECUTIVE</span>
                    </div>
                </div>

                <!-- MANAGERIAL -->
                <div class="classification-item border-top" style="border-top: 3px solid #28a745 !important;">
                    <div class="classification-box mt-3 collapsed" data-target="#managerial-schedules">
                        <i class="fas fa-arrow-circle-down" style="pointer-events: none;"></i>
                        <span style="pointer-events: none;">Classification: MANAGERIAL</span>
                    </div>
                </div>

                <!-- RECRUITMENT -->
                <div class="classification-item border-top" style="border-top: 3px solid #28a745 !important;">
                    <div class="classification-box mt-3 collapsed" data-target="#recruitment-schedules">
                        <i class="fas fa-arrow-circle-down" style="pointer-events: none;"></i>
                        <span style="pointer-events: none;">Classification: RECRUITMENT</span>
                    </div>
                </div>
                <div class="border-top" style="border-top: 3px solid #28a745 !important;"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
          </div>
        </div>
      </div>
    </section>
</div>

<script>
function openEditInterface() {
    console.log("Opening edit interface...");
    var interface = document.getElementById('edit-interface');
    if (interface) {
        interface.style.display = 'block';
        window.scrollTo({
            top: interface.offsetTop - 20,
            behavior: 'smooth'
        });
    } else {
        alert("Edit interface element not found!");
    }
}

function closeEditInterface() {
    console.log("Closing edit interface...");
    var interface = document.getElementById('edit-interface');
    if (interface) {
        interface.style.display = 'none';
    }
}

$(document).ready(function() {
    // Show STAFF by default
    $('#staff-schedules').show();
    $('.classification-box[data-target="#staff-schedules"]').removeClass('collapsed');
    $('.classification-box[data-target="#staff-schedules"] i').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down');

    $(document).on('click', '.classification-box', function() {
        var target = $(this).attr('data-target');
        
        // If clicking an already open one, close it
        if (!$(this).hasClass('collapsed')) {
            $(target).stop().slideUp(200);
            $(this).addClass('collapsed');
            $(this).find('i').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right');
        } else {
            // Close all others first (optional, but cleaner for "return to normal")
            $('.schedule-content').stop().slideUp(200);
            $('.classification-box').addClass('collapsed');
            $('.classification-box i').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right');

            // Open the clicked one
            $(target).stop().slideDown(200);
            $(this).removeClass('collapsed');
            $(this).find('i').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down');
        }
    });

    // Initialize icons based on initial collapsed state
    $('.classification-box.collapsed i').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right');

    // Save functionality prompt
    $('.btn-save').on('click', function() {
        alert('Saving changes...');
    });
});
</script>

<?php include '../includes/footer.php'; ?>
