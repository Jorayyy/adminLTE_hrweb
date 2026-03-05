  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link text-center">
      <h3 style="color: #333; font-weight: bold;">HR<span style="color: #f39c12;">WEB</span>.PH</h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <img src="https://via.placeholder.com/160" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">CRISTINE B. CAJES</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?= $base_url ?>index.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $base_url ?>pages/employees.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'employees.php') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>201 Employee List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-gear"></i>
              <p>Profile Management</p>
            </a>
          </li>

          <li class="nav-header" style="background-color: #7b1fa2; color: white; padding: 10px 15px; font-weight: bold; font-size: 1.1rem; display: flex; align-items: center; cursor: default;">
            <i class="fas fa-user-lock mr-2"></i> Administrator
          </li>
          
          <li class="nav-item">
            <a href="<?= (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '' : 'pages/' ?>administrator_file_maintenance.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'administrator_file_maintenance.php') ? 'active' : '' ?>">
              <i class="nav-icon far fa-copy" style="color: #28a745;"></i>
              <p style="color: #28a745;">
                File Maintenance
              </p>
            </a>
          </li>

          <!-- 201 Employee Section -->
          <li class="nav-header" style="background-color: #ff0000; color: white; padding: 10px 15px; font-weight: bold; font-size: 1.1rem; display: flex; align-items: center; cursor: default;">
            <i class="fas fa-users mr-2"></i> 201 Employee
          </li>
          
          <li class="nav-item">
            <a href="<?= (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '' : 'pages/' ?>employees.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'employees.php') ? 'active' : '' ?>">
              <i class="nav-icon far fa-copy" style="color: #6c757d;"></i>
              <p style="color: #6c757d;">Employee Masterlist</p>
            </a>
          </li>

          <!-- Time Section -->
          <li class="nav-header" style="background-color: #f1c40f; color: white; padding: 10px 15px; font-weight: bold; font-size: 1.1rem; display: flex; align-items: center; cursor: default;">
            <i class="fas fa-clock mr-2"></i> Time
          </li>
          
          <li class="nav-item">
            <a href="<?= (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '' : 'pages/' ?>view_attendance.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'view_attendance.php') ? 'active' : '' ?>">
              <i class="nav-icon far fa-copy" style="color: #17a2b8;"></i>
              <p style="color: #17a2b8;">View Attendance</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?= (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '' : 'pages/' ?>dtr.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dtr.php') ? 'active' : '' ?>">
              <i class="nav-icon far fa-clock" style="color: #e83e8c;"></i>
              <p style="color: #e83e8c;">Daily Time Record</p>
            </a>
          </li>
          <li class="nav-item border-top mt-2 pt-2">
            <a href="<?= (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../' : '' ?>logout.php" class="nav-link text-danger">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Log Out</p>
            </a>
          </li>
          <!-- Add more items as we progress -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
