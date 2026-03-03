<?php
require_once '../config/db.php';
 = "../"; 
include '../includes/header.php';


// Module Title
$filename = basename($_SERVER['PHP_SELF'], '.php');
$module_name = str_replace('_', ' ', $filename);
$module_name = ucwords($module_name);
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #E0F2F7;">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?= $module_name ?> <small class="text-muted" style="font-size: 0.9rem;">File Maintenance</small></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right" style="background: transparent;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item">Administrator</li>
              <li class="breadcrumb-item"><a href="<?= $base_url ?>pages/administrator_file_maintenance.php">File Maintenance</a></li>
              <li class="breadcrumb-item active"><?= $module_name ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title"><?= $module_name ?> List</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add <?= $module_name ?></button>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name/Title</th>
                  <th>Description</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="4" class="text-center">No data found.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
