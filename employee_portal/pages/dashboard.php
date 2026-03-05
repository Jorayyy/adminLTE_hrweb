<?php
session_start();
if (!isset($_SESSION['emp_logged_in'])) {
    header("Location: ../login.php");
    exit;
}
$base_url = "../../"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Portal | Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/adminlte.min.css">
  <style>
    body { background-color: #f4f6f9 !important; }
    .main-header { border-bottom: none !important; }
    
    /* Top Brand Row */
    .brand-container {
      background: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
      height: 70px;
      border-bottom: 2px solid #343a40;
    }
    .hr-logo-text { font-size: 32px; font-weight: 900; letter-spacing: -1px; }
    .hr-logo-text .black { color: #000; }
    .hr-logo-text .green { color: #28a745; }
    .hr-logo-text .orange { color: #f39c12; }
    
    .profile-info { display: flex; align-items: center; }
    .profile-info .user-name { color: #28a745; font-weight: bold; font-size: 14px; text-transform: uppercase; }

    /* Navigation Bar */
    .main-header.navbar { 
      padding: 0 !important; 
      min-height: auto !important;
      background: #343a40 !important;
    }
    .navbar-nav { width: 100%; display: flex; justify-content: center; }
    .nav-item { flex: 1; border-right: 1px solid rgba(0,0,0,0.1); }
    .nav-link { 
      color: white !important; 
      font-weight: bold !important; 
      padding: 12px 0 !important;
      font-size: 15px !important;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .nav-link i { margin-right: 8px; font-size: 16px; }

    /* Menu Colors from Screenshot */
    .bg-dashboard { background-color: #f34b3e !important; } /* Red */
    .bg-profile { background-color: #2ECC71 !important; } /* Green */
    .bg-transactions { background-color: #F1C40F !important; } /* Yellow */
    .bg-dtr { background-color: #FF5EAA !important; } /* Pink */
    .bg-payroll { background-color: #8E44AD !important; } /* Purple/Brown */
    .bg-others { background-color: #D2B4DE !important; } /* Light Purple */
    .bg-settings { background-color: #3498DB !important; } /* Blue/Cyan */

    /* Card Box Styles */
    .custom-card { border-radius: 4px; border: 1px solid #dee2e6; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .custom-card-header { 
        padding: 10px 15px; 
        font-weight: bold; 
        font-size: 13px; 
        text-transform: uppercase;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
    }
    .custom-card-body { padding: 20px; min-height: 200px; background: white; }
    .header-announcement { background-color: #fdfdfd; color: #d32f2f; border-left: 5px solid #d32f2f; }
    .header-birthday { background-color: #fdfdfd; color: #3498DB; border-left: 5px solid #3498DB; }
  </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Top Brand Row -->
  <div class="brand-container">
      <div class="hr-logo-text">
          <span class="black">HR</span><span class="green">WEB</span><span class="orange">.PH</span>
      </div>
      <div class="profile-info">
          <span class="user-name"><?= $_SESSION['emp_fullname'] ?></span>
          <a href="../../logout.php" class="btn btn-sm btn-danger ml-2" style="font-size: 10px; font-weight: bold;">
              <i class="fas fa-sign-out-alt"></i> LOGOUT
          </a>
      </div>
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md p-0 shadow-sm">
    <div class="container-fluid p-0">
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav nav-justified">
          <li class="nav-item bg-dashboard">
            <a class="nav-link" href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
          </li>
          <li class="nav-item bg-profile">
            <a class="nav-link" href="#"><i class="fas fa-user-circle"></i> My Profile</a>
          </li>
          <li class="nav-item bg-transactions">
            <a class="nav-link" href="#"><i class="fas fa-file-invoice"></i> Transactions</a>
          </li>
          <li class="nav-item bg-dtr">
            <a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i> DTR</a>
          </li>
          <li class="nav-item bg-payroll">
            <a class="nav-link" href="#"><i class="fas fa-money-bill-wave"></i> Payroll</a>
          </li>
          <li class="nav-item bg-others">
            <a class="nav-link" href="#"><i class="fas fa-list-ul"></i> Others</a>
          </li>
          <li class="nav-item bg-settings">
            <a class="nav-link" href="#"><i class="fas fa-cog"></i> Settings</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Content Wrapper -->
  <div class="content-wrapper p-4 bg-light">
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-sm-6">
                <h2 class="text-bold text-dark" style="font-size: 24px; letter-spacing: 1px;">DASHBOARD</h2>
            </div>
            <div class="col-sm-6 text-right">
                <ol class="breadcrumb float-sm-right bg-transparent m-0 p-0" style="font-size: 11px;">
                  <li class="breadcrumb-item"><i class="fas fa-home"></i> Home</li>
                  <li class="breadcrumb-item active text-muted">Dashboard</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <!-- Announcements Section -->
            <div class="col-md-6">
                <div class="custom-card shadow-sm border-0">
                    <div class="custom-card-header header-announcement">
                        <i class="fas fa-bullhorn mr-2"></i> Announcements
                    </div>
                    <div class="custom-card-body">
                        <div class="text-center text-muted py-5 mt-4">
                            No Announcements
                        </div>
                    </div>
                </div>
            </div>

            <!-- Birthday Section -->
            <div class="col-md-6">
                <div class="custom-card shadow-sm border-0">
                    <div class="custom-card-header header-birthday d-flex justify-content-between">
                        <div><i class="fas fa-birthday-cake mr-2"></i> Birthday Celebrants of the week</div>
                        <div class="ml-auto"><i class="fas fa-bookmark text-muted"></i> Employee Licences</div>
                    </div>
                    <div class="custom-card-body pt-3">
                        <div class="d-flex align-items-center mb-3 bg-light p-2 border-bottom">
                            <h6 class="text-bold text-dark m-0" style="font-size: 13px;">Birthday Celebrants</h6>
                            <i class="fas fa-birthday-cake ml-auto text-primary" style="font-size: 14px;"></i>
                        </div>
                        
                        <!-- Sample Birthday Card 1 -->
                        <div class="d-flex align-items-center bg-white border p-2 mb-2 rounded shadow-none">
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-user-tie text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-bold text-danger" style="font-size: 12px;">JAYVI DOMINGUITO</div>
                                <div class="text-muted" style="font-size: 10px;">AGENT</div>
                                <div class="text-muted" style="font-size: 10px;">March 08</div>
                            </div>
                            <button class="btn btn-primary btn-xs px-2 py-1 shadow-none" style="font-size: 10px;">Send Message</button>
                        </div>

                         <!-- Sample Birthday Card 2 -->
                         <div class="d-flex align-items-center bg-white border p-2 mb-2 rounded shadow-none">
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-user-tie text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-bold text-danger" style="font-size: 12px;">JUN ARIES ARDIENTE</div>
                                <div class="text-muted" style="font-size: 10px;">AGENT</div>
                                <div class="text-muted" style="font-size: 10px;">March 11</div>
                            </div>
                            <button class="btn btn-primary btn-xs px-2 py-1 shadow-none" style="font-size: 10px;">Send Message</button>
                        </div>

                         <!-- Sample Birthday Card 3 -->
                         <div class="d-flex align-items-center bg-white border p-2 mb-2 rounded shadow-none">
                            <div class="bg-secondary d-flex align-items-center justify-content-center mr-3 overflow-hidden" style="width: 45px; height: 45px; border-radius: 4px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-bold text-danger" style="font-size: 12px;">RM JYRA SERATA</div>
                                <div class="text-muted" style="font-size: 10px;">AGENT</div>
                                <div class="text-muted" style="font-size: 10px;">March 07</div>
                            </div>
                            <button class="btn btn-primary btn-xs px-2 py-1 shadow-none" style="font-size: 10px;">Send Message</button>
                        </div>
                        
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 11px;">Showing 1 to 3 of 4 entries</span>
                            <div class="btn-group">
                                <button class="btn btn-default btn-xs px-2" disabled>Previous</button>
                                <button class="btn btn-default btn-xs px-2">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<script src="../../assets/plugins/jquery/jquery.min.js"></script>
<script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>
</body>
</html>
