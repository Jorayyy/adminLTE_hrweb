<?php
require_once '../config/db.php';
 = "../"; 
include '../includes/header.php';


// Module Title based on filename
$filename = basename($_SERVER['PHP_SELF'], '.php');
$module_name = str_replace('_', ' ', $filename);
$module_name = ucwords($module_name);
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #E0F2F7;">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?= $module_name ?> <small class="text-muted" style="font-size: 0.9rem;">Administrator</small></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right" style="background: transparent;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item">Administrator</li>
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
            <h3 class="card-title"><?= $module_name ?></h3>
          </div>
          <div class="card-body">
            <p>Content for <?= $module_name ?> will be implemented here.</p>
          </div>
        </div>
      </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
