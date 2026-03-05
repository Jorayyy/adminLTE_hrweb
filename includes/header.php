<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HRIS | Admin Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome (CDN fallback since local fonts are missing) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- AdminLTE Theme style -->
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/adminlte.min.css">
  <!-- Custom CSS for Improved Style based on Screenshot -->
  <style>
    body { background-color: #e9ecef !important; }
    .layout-top-nav .wrapper .main-header { border-bottom: none !important; }
    
    /* Top Logo Bar */
    .brand-container {
      background: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 15px;
      height: 70px;
      border-bottom: 2px solid #343a40;
    }
    .hr-logo-text { font-size: 32px; font-weight: 900; letter-spacing: -1px; }
    .hr-logo-text .black { color: #000; }
    .hr-logo-text .green { color: #28a745; }
    .hr-logo-text .orange { color: #f39c12; }
    
    .profile-info { display: flex; align-items: center; position: relative; }
    .profile-info .no-logo { height: 50px; margin-right: 15px; }
    .profile-info .user-img { height: 55px; width: 55px; border-radius: 50%; border: 2px solid #17a2b8; object-fit: cover; }
    .profile-info .user-name { color: #28a745; font-weight: bold; font-size: 14px; margin-left: 10px; text-transform: uppercase; }
    .profile-info .caret-blue { color: #007bff; margin-left: 5px; font-size: 12px; }

    /* Navigation Bar */
    .main-header.navbar { 
      padding: 0 !important; 
      min-height: auto !important;
      background: #343a40 !important;
      position: relative;
      z-index: 10;
    }
    .navbar-nav { width: 100%; display: flex; justify-content: center; text-align: center; }
    .nav-item { border-right: 1px solid rgba(0,0,0,0.1); flex: 1; position: relative !important; }
    .nav-link { 
      color: white !important; 
      font-weight: bold !important; 
      text-transform: capitalize !important;
      padding: 12px 0 !important;
      font-size: 16px !important;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }
    .nav-link::after { display: none !important; }
    .nav-link i { margin-right: 8px; font-size: 18px; }

    /* Fix Dropdown Alignment */
    .nav-item.dropdown .dropdown-menu {
      position: absolute !important;
      top: 100% !important;
      left: 0 !important;
      right: auto !important;
      margin: 0 !important;
      border: none;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 10px 0;
      border-radius: 0 0 4px 4px;
      min-width: 250px;
      z-index: 1050 !important;
      display: none;
    }
    .nav-item.dropdown:hover > .dropdown-menu {
      display: block;
    }
    .nav-item.bg-reports .dropdown-menu {
      left: auto !important;
      right: 0 !important;
    }

    /* User Profile Dropdown Z-index */
    .user-profile-dropdown {
      z-index: 2000 !important;
    }

    /* Colors from Reference */
    .bg-dashboard { background-color: #e8117f !important; }
    .bg-admin { background-color: #7d09a8 !important; }
    .bg-employee { background-color: #ff0000 !important; }
    .bg-transaction { background-color: #00ff00 !important; }
    .bg-time { background-color: #ffff00 !important; }
    .bg-time .nav-link { color: #000 !important; }
    .bg-reports { background-color: #6a53f5 !important; }

    .nav-item:hover { filter: brightness(1.1); }
    
    /* Dropdown Styling */
    .nav-item.dropdown .dropdown-menu {
      border: none;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 10px 0;
      border-radius: 4px;
      min-width: 250px;
      background: white;
    }
    .dropdown-item {
      padding: 8px 20px;
      font-size: 14px;
      color: #333 !important; /* Darker text */
      font-weight: 500; /* Slightly bolder */
      display: flex;
      align-items: center;
      text-align: left;
    }
    .dropdown-item i {
      font-size: 14px;
      margin-right: 15px;
      color: #555; /* Darker icons */
    }
    .dropdown-item:hover {
      background-color: #f8f9fa !important;
      color: #28a745 !important;
    }
    .dropdown-item:hover i {
      color: #28a745 !important;
    }

    .user-profile-dropdown {
      min-width: 250px !important;
      padding: 0 !important;
      border: 1px solid #ddd !important;
    }
    .user-profile-dropdown .dropdown-item {
      padding: 10px 15px !important;
      border-bottom: 1px solid #ddd;
      color: #777 !important;
      font-size: 16px;
      background: #fdfdfd;
      font-weight: 400;
      text-align: center;
      display: block;
    }
    .user-profile-dropdown .dropdown-item:last-child {
      border-bottom: none;
    }
    .user-profile-dropdown .dropdown-item:hover {
      background: #f1f1f1 !important;
      color: #333 !important;
    }
  </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Top Brand Row -->
  <div class="brand-container">
      <div class="hr-logo-text">
          <span class="black">MEBS</span><span class="green">.HIYAS</span><span class="orange">.PH</span>
      </div>
      <div class="profile-info">
          <div class="no-logo d-flex align-items-center justify-content-center bg-light border px-2 mr-3" style="height: 50px; font-weight: bold; font-size: 12px; color: #999;">
              NO LOGO
          </div>
          <div class="dropdown">
            <a href="#" class="p-0 d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-decoration: none;">
                <div class="user-img d-flex align-items-center justify-content-center bg-info text-white" style="height: 55px; width: 55px; border-radius: 50%; border: 2px solid #17a2b8; font-size: 20px;">
                    <i class="fas fa-user"></i>
                </div>
                <span class="user-name">CRISTINE BERNADETTE CAJES</span>
                <i class="fas fa-caret-down caret-blue"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right user-profile-dropdown shadow-sm" aria-labelledby="userProfileDropdown">
                <a class="dropdown-item" href="<?= (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../logout.php' : 'logout.php' ?>">Sign out</a>
                <a class="dropdown-item" href="#">Change Password</a>
                <a class="dropdown-item" href="#">Quick Links Maintenance</a>
                <a class="dropdown-item" href="#">System Help & Quick Links Settings</a>
            </div>
          </div>
      </div>
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md p-0">
    <div class="container-fluid p-0">
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav nav-justified">
          <li class="nav-item bg-dashboard">
            <a class="nav-link" href="<?= $base_url ?>index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
          </li>
          <li class="nav-item bg-admin dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user-lock"></i> Administrator
            </a>
            <div class="dropdown-menu" aria-labelledby="adminDropdown">
              <a class="dropdown-item" href="<?= $base_url ?>pages/administrator_file_maintenance.php"><i class="far fa-copy"></i> File Maintenance</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/user_management.php"><i class="far fa-copy"></i> User Management</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/user_roles.php"><i class="far fa-copy"></i> User Roles</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/leave_type.php"><i class="far fa-copy"></i> Leave Type</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/leave_management.php"><i class="far fa-copy"></i> Leave Management</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/holiday_list.php"><i class="far fa-copy"></i> Holiday List</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/form_approval.php"><i class="far fa-copy"></i> Form Approval</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/salary_approval.php"><i class="far fa-copy"></i> Salary Approval</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/section_manager.php"><i class="far fa-copy"></i> Section Manager</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/email_host_setting.php"><i class="far fa-copy"></i> Email Host Setting</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/downloadable_forms.php"><i class="far fa-copy"></i> Downloadable Forms</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/inventory_storage_setting.php"><i class="far fa-copy"></i> Inventory Storage Setting</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/working_schedule_color_code.php"><i class="far fa-copy"></i> Working Schedule Color Code</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/manage_user_interface.php"><i class="far fa-copy"></i> Manage User Interface</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/employee_acknowledgment_setting.php"><i class="far fa-copy"></i> Employee Acknowledgment Setting</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/downloadable_company_policy.php"><i class="far fa-copy"></i> Downloadable Company Policy</a>
            </div>
          </li>
          <li class="nav-item bg-employee dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="employeeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-users"></i> 201 Employee
            </a>
            <div class="dropdown-menu" aria-labelledby="employeeDropdown">
              <a class="dropdown-item" href="<?= $base_url ?>pages/employees.php"><i class="far fa-copy"></i> Employee Masterlist</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Employee Cost Center</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> User Define Fields</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Mass Update</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Employee 201 Settings</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Request for 201 Update</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Employee Trainings and Seminars</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Disabled Account due to multiple logins</a>
            </div>
          </li>
          <li class="nav-item bg-transaction dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="transactionDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-file-invoice"></i> Transaction
            </a>
            <div class="dropdown-menu" aria-labelledby="transactionDropdown">
              <a class="dropdown-item" href="<?= $base_url ?>pages/form_approval.php"><i class="far fa-copy"></i> System Default Forms</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> User Define Forms</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Transactions Management Multiple</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Transactions Management Single</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Transaction Form Settings</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Pending Transactions with Trapped Approvers</a>
            </div>
          </li>
          <li class="nav-item bg-time dropdown">
            <a class="nav-link dropdown-toggle text-dark" href="#" id="timeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-clock"></i> Time
            </a>
            <div class="dropdown-menu" aria-labelledby="timeDropdown">
              <a class="dropdown-item" href="<?= $base_url ?>pages/dtr.php"><i class="far fa-copy"></i> Generate Daily Time Record (DTR)</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/shift_table.php"><i class="far fa-copy"></i> Shift Table</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Plot Schedule</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/payroll_period.php"><i class="far fa-copy"></i> Payroll Period</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Manual Upload Attendance</a>
              <a class="dropdown-item" href="<?= $base_url ?>pages/view_attendance.php"><i class="far fa-copy"></i> View Attendance</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Fixed Schedule</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Flexi Schedule</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Compress Work Schedule</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Part Timers</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Time Settings</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Biometrics Setup</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Web Bundy</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Late and Absences Monitoring</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Form Prerequisite</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Geo-attendance</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Registered Geo Location(Ongoing New)</a>
            </div>
          </li>
          <li class="nav-item bg-reports dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-file-contract"></i> Reports
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="reportsDropdown">
              <a class="dropdown-item" href="<?= $base_url ?>pages/employees.php"><i class="far fa-copy"></i> Employee Report</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Time</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Transaction</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Leave Calendar</a>
              <a class="dropdown-item" href="#"><i class="far fa-copy"></i> Uploaded Files</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- /.navbar -->
