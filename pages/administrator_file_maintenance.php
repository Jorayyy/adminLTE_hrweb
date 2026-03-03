<?php
require_once '../config/db.php';
$base_url = "../"; 
include '../includes/header.php';

// Define the modules for File Maintenance according to the screenshot
$modules = [
    'Advance Type' => 'advance_type.php',
    'Announcement' => 'announcement.php',
    'Bank' => 'bank.php',
    'Civil Status' => 'civil_status.php',
    'Classification' => 'classification.php',
    'Company' => 'company.php',
    'Department' => 'department.php',
    'Division' => 'division.php',
    'Education' => 'education.php',
    'Employee Frequently Asked Questions' => 'employee_faq.php',
    'Employment' => 'employment.php',
    'Gender' => 'gender.php',
    'Locations' => 'locations.php',
    'News and Events' => 'news_events.php'
];
?>

<div class="content-wrapper" style="margin-left: 0 !important; min-height: 100vh; background-color: #D6E9F1; margin-left: 0 !important;">
    <!-- Content Header (Page header) -->
    <div class="content-header p-0">
      <div class="container-fluid">
        <div class="row pt-2 px-3 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark" style="font-size: 20px;">Administrator <small class="text-muted" style="font-size: 14px;">File Maintenance</small></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right" style="background: transparent; margin: 0; padding: 0; font-size: 11px;">
              <li class="breadcrumb-item"><a href="<?= $base_url ?>index.php" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item"><a href="#" class="text-dark">Administrator</a></li>
              <li class="breadcrumb-item active text-muted">File Maintenance</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content mt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <div class="card shadow-none border" style="border-radius: 0;">
              <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                  <?php foreach ($modules as $name => $file): ?>
                    <a href="<?= $file ?>" class="list-group-item list-group-item-action py-2" style="font-size: 13px; font-weight: 500; color: #333; border-bottom: 1px solid #dee2e6;">
                      <?= $name ?>
                    </a>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
          
          <!-- Empty space as seen in screenshot -->
          <div class="col-md-9" style="position: relative; min-height: 80vh;">
             <a href="#" class="btn btn-danger btn-sm shadow-sm" style="position: absolute; bottom: 20px; right: 20px; border-radius: 4px; font-size: 12px; padding: 2px 8px;">
                <i class="fas fa-angles-up"></i> go to top
             </a>
          </div>
        </div>
      </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
<style>
/* Matching the purple sidebar header from the first pic */
.nav-header {
    background: #7b1fa2 !important; /* Purple color */
    color: #fff !important;
}
.list-group-item-action:hover {
    background-color: #f8f9fa;
}
</style>
