<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';
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
                <div class="form-group row mb-4 px-5">
                  <label class="col-sm-3 col-form-label text-right text-bold" style="font-size: 14px;">Individual Employee</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm shadow-none" placeholder="For Individual Processing Only : Click Me to Select Employee" style="background: white; border: 1px solid #ced4da;">
                  </div>
                </div>
                <div class="form-group row mb-4 px-5">
                  <label class="col-sm-3 col-form-label text-right text-bold" style="font-size: 14px;">Pay Type</label>
                  <div class="col-sm-9">
                    <select class="form-control form-control-sm shadow-none">
                      <option disabled selected>Select Pay Type</option>
                      <option>Monthly</option>
                      <option>Semi-Monthly</option>
                      <option>Weekly</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4 px-5">
                  <label class="col-sm-3 col-form-label text-right text-bold" style="font-size: 14px;">Option</label>
                  <div class="col-sm-9">
                    <select class="form-control form-control-sm shadow-none">
                      <option>View Processed DTR</option>
                      <option>Process DTR</option>
                      <option>Check DTR Status</option>
                      <option>Manual Encode DTR Summary</option>
                      <option>Clear DTR</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- WEEKLY MEDICARE GROUP -->
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-2" style="border-bottom: 1px solid #f0f0f0;">
                <div class="row align-items-center">
                  <div class="col-6">
                    <h3 class="card-title text-danger text-bold m-0" style="font-size: 14px; text-transform: uppercase;">WEEKLY MEDICARE GROUP</h3>
                  </div>
                  <div class="col-6 text-right">
                    <button class="btn btn-light btn-sm border text-bold filter-toggle shadow-sm" style="font-size: 13px; color: #333; padding: 10px 20px; border-radius: 4px; border: 1px solid #ddd !important; background: #f8f9fa;">Click Me To Filter OR Individual Processing</button>
                  </div>
                </div>
              </div>
              <div class="card-body py-4">
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Option</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #007bff; height: 38px; border-radius: 2px;">
                      <option>View Processed DTR</option>
                      <option>Process DTR</option>
                      <option>Check DTR Status</option>
                      <option>Clear DTR</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Payroll Period</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #ddd; height: 38px; border-radius: 2px;">
                      <option>February 27 2023 to March 05 2023 (Paydate:2023-03-08)</option>
                    </select>
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-danger px-4 py-2 text-bold shadow-sm" style="background-color: #e74c3c; border: none; font-size: 14px;"><i class="fas fa-paper-plane mr-2"></i> Generate</button>
                </div>
              </div>
            </div>
          </div>

          <!-- WEEKLY NIGHTSHIFT GROUP -->
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-2" style="border-bottom: 1px solid #f0f0f0;">
                <div class="row align-items-center">
                  <div class="col-6">
                    <h3 class="card-title text-danger text-bold m-0" style="font-size: 14px; text-transform: uppercase;">WEEKLY NIGHTSHIFT GROUP</h3>
                  </div>
                  <div class="col-6 text-right">
                    <button class="btn btn-light btn-sm border text-bold filter-toggle shadow-sm" style="font-size: 13px; color: #333; padding: 10px 20px; border-radius: 4px; border: 1px solid #ddd !important; background: #f8f9fa;">Click Me To Filter OR Individual Processing</button>
                  </div>
                </div>
              </div>
              <div class="card-body py-4">
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Option</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #007bff; height: 38px; border-radius: 2px;">
                      <option>View Processed DTR</option>
                      <option>Process DTR</option>
                      <option>Check DTR Status</option>
                      <option>Clear DTR</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Payroll Period</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #ddd; height: 38px; border-radius: 2px;">
                      <option>January 23 2023 to January 29 2023 (Paydate:2023-02-01)</option>
                    </select>
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-danger px-4 py-2 text-bold shadow-sm" style="background-color: #e74c3c; border: none; font-size: 14px;"><i class="fas fa-paper-plane mr-2"></i> Generate</button>
                </div>
              </div>
            </div>
          </div>

          <!-- SEMI-MONTHLY MORNING GROUP -->
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-2" style="border-bottom: 1px solid #f0f0f0;">
                <div class="row align-items-center">
                  <div class="col-6">
                    <h3 class="card-title text-danger text-bold m-0" style="font-size: 14px; text-transform: uppercase;">SEMI-MONTHLY MORNING GROUP</h3>
                  </div>
                  <div class="col-6 text-right">
                    <button class="btn btn-light btn-sm border text-bold filter-toggle shadow-sm" style="font-size: 13px; color: #333; padding: 10px 20px; border-radius: 4px; border: 1px solid #ddd !important; background: #f8f9fa;">Click Me To Filter OR Individual Processing</button>
                  </div>
                </div>
              </div>
              <div class="card-body py-4">
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Option</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #007bff; height: 38px; border-radius: 2px;">
                      <option>View Processed DTR</option>
                      <option>Process DTR</option>
                      <option>Check DTR Status</option>
                      <option>Clear DTR</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Payroll Period</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #ddd; height: 38px; border-radius: 2px;">
                      <option>February 01 2026 to February 15 2026 (Paydate:2026-02-20)</option>
                    </select>
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-danger px-4 py-2 text-bold shadow-sm" style="background-color: #e74c3c; border: none; font-size: 14px;"><i class="fas fa-paper-plane mr-2"></i> Generate</button>
                </div>
              </div>
            </div>
          </div>

          <!-- MAASIN NIGHTSHIFT GROUP -->
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-2" style="border-bottom: 1px solid #f0f0f0;">
                <div class="row align-items-center">
                  <div class="col-6">
                    <h3 class="card-title text-danger text-bold m-0" style="font-size: 14px; text-transform: uppercase;">MAASIN NIGHTSHIFT GROUP</h3>
                  </div>
                  <div class="col-6 text-right">
                    <button class="btn btn-light btn-sm border text-bold filter-toggle shadow-sm" style="font-size: 13px; color: #333; padding: 10px 20px; border-radius: 4px; border: 1px solid #ddd !important; background: #f8f9fa;">Click Me To Filter OR Individual Processing</button>
                  </div>
                </div>
              </div>
              <div class="card-body py-4">
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Option</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #007bff; height: 38px; border-radius: 2px;">
                      <option>View Processed DTR</option>
                      <option>Process DTR</option>
                      <option>Check DTR Status</option>
                      <option>Clear DTR</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-sm-4 col-form-label text-right text-bold" style="font-size: 15px; color: #333;">Payroll Period</label>
                  <div class="col-sm-8">
                    <select class="form-control form-control-sm shadow-none" style="border: 1px solid #ddd; height: 38px; border-radius: 2px;">
                      <option>February 23 2026 to March 01 2026 (Paydate:2026-03-04)</option>
                    </select>
                  </div>
                </div>
                <div class="text-right">
                  <button class="btn btn-danger px-4 py-2 text-bold" style="background-color: #e74c3c; border: none;"><i class="fas fa-paper-plane mr-2"></i> Generate</button>
                </div>
              </div>
            </div>
          </div>
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
