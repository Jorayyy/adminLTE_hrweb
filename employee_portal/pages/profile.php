<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

$emp_id = $_SESSION['emp_id'];

// Mock query if DB isn't fully populated yet, otherwise fetch real data
try {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$emp_id]);
    $user = $stmt->fetch();
} catch (Exception $e) {
    $user = null;
}

// Fallback for demonstration if DB query fails or user not found
$fname = $user['firstname'] ?? 'MARK JORY';
$mname = $user['middlename'] ?? 'A.';
$lname = $user['lastname'] ?? 'ANDRADE';
$id_num = $user['id_number'] ?? '222065';
$bday = $user['birthdate'] ?? '2001-05-05';
$gender = $user['gender'] ?? 'Male';
$civil = $user['civil_status'] ?? 'Single';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HRWEB.PH | My Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <style>
        body { background-color: #e0f2f7; font-family: 'Source Sans Pro', sans-serif; }
        .top-nav { background: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ddd; }
        .logo-text { font-weight: bold; font-size: 24px; color: #333; }
        .logo-text span { color: #f39c12; }
        
        /* Multi-colored Tab Bar */
        .tab-bar { display: flex; width: 100%; height: 45px; margin-top: 0px; border-top: 1px solid #ddd; position: relative; z-index: 1000; }
        .tab-item { flex: 1; display: flex; align-items: center; justify-content: center; color: #000; font-weight: bold; font-size: 14px; text-decoration: none; border-right: none; position: relative; height: 100%; }
        .tab-item-box { flex: 1; position: relative; display: flex; align-items: center; justify-content: center; color: #000; font-weight: bold; font-size: 14px; cursor: pointer; text-decoration: none; border: none; }
        
        .tab-dash { background: #ff8a80; }
        .tab-profile { background: #69f0ae; }
        .tab-trans { background: #eeff41; }
        .tab-dtr { background: #ff4081; color: #fff; }
        .tab-payroll { background: #a1887f; color: #fff; }
        .tab-others { background: #b39ddb; }
        .tab-settings { background: #4dd0e1; }

        /* Dropdown styling matching screenshot */
        .tab-item:hover .dropdown-menu-custom { display: block; }
        .dropdown-menu-custom { 
            display: none; 
            position: absolute; 
            top: 45px; 
            left: 0; 
            min-width: 280px; 
            background: #e1f5fe; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            z-index: 1001; 
            padding: 5px 0; 
            list-style: none; 
            border-radius: 0 0 5px 5px;
            border: 1px solid #fff;
        }
        .dropdown-menu-custom a { 
            color: #777; 
            padding: 8px 20px; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            font-size: 13.5px; 
            font-weight: 500;
            transition: all 0.2s;
        }
        .dropdown-menu-custom a:hover { color: #333; background: rgba(255,255,255,0.5); }
        .dropdown-menu-custom i { 
            width: 25px; 
            font-size: 14px; 
            margin-right: 15px;
            display: flex; 
            justify-content: center;
        }
        
        .profile-layout { display: flex; gap: 20px; padding: 20px; align-items: flex-start; }
        
        /* Left Sidebar Styling */
        .left-panel { width: 300px; display: flex; flex-direction: column; gap: 20px; }
        .user-card { background: #fff; border: 1px solid #add8e6; padding: 20px; text-align: center; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .avatar-circle { width: 120px; height: 120px; background: #eee; border-radius: 50%; margin: 0 auto 15px; border: 3px solid #334; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .avatar-circle i { font-size: 80px; color: #333; }
        .user-card h5 { margin: 5px 0; font-size: 14px; font-weight: bold; color: #d9534f; }
        .user-card p { margin: 0; font-size: 12px; color: #777; }

        /* Left Menu (201 Files) */
        .menu-card { background: #fff; border: 1px solid #add8e6; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column; max-height: 500px; }
        .menu-header { background: #e1f5fe; padding: 10px 15px; display: flex; justify-content: space-between; font-weight: bold; color: #0277bd; font-size: 13px; border-bottom: 1px solid #b3e5fc; flex-shrink: 0; }
        .menu-list { list-style: none; padding: 0; margin: 0; }
        .scrollable-menu { overflow-y: auto; flex-grow: 1; }
        .menu-item { display: flex; justify-content: space-between; padding: 10px 15px; font-size: 12px; border-bottom: 1px solid #f1f1f1; color: #444; border-left: 4px solid transparent; }
        .menu-item:hover { background: #f9f9f9; cursor: pointer; border-left-color: #0277bd; }
        .menu-item.active { background: #f5f5f5; color: #d9534f; font-weight: bold; border-left-color: #d9534f; }
        .menu-item i { width: 20px; text-align: center; color: #666; }

        /* Custom Scrollbar for Menu List */
        .scrollable-menu::-webkit-scrollbar { width: 6px; }
        .scrollable-menu::-webkit-scrollbar-track { background: #f1f1f1; }
        .scrollable-menu::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
        .scrollable-menu::-webkit-scrollbar-thumb:hover { background: #555; }

        /* Right Content Styling */
        .right-panel { flex: 1; background: #fff; border: 1px solid #c8e6c9; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .panel-header { background: #f1f8e9; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center; color: #2e7d32; font-size: 14px; border-bottom: 1px solid #c8e6c9; }
        .panel-body { padding: 30px; }
        
        .section-title { font-weight: bold; font-size: 14px; border-bottom: 1px solid #eee; margin-bottom: 20px; padding-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .info-grid { display: grid; grid-template-columns: 180px 1fr; row-gap: 8px; font-size: 14px; margin-left: 40px; }
        .info-label { font-weight: 600; text-align: right; margin-right: 20px; color: #333; }
        .info-value { color: #555; text-transform: uppercase; }
        
        .btn-action-group { display: flex; gap: 5px; }
        .btn-sm-square { background: #eee; border: 1px solid #ccc; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 3px; cursor: pointer; font-size: 12px; }
        .btn-sm-square:hover { background: #ddd; }
    </style>
</head>
<body>

    <div class="top-nav">
        <div class="logo-text">HR<span>WEB</span>.PH</div>
        <div style="font-size: 12px; font-weight: bold; color: #555;">
            <?= strtoupper($fname . ' ' . $mname . ' ' . $lname) ?>
            <a href="../../logout.php" style="margin-left: 15px; color: #d9534f;"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="tab-bar">
        <a href="dashboard.php" class="tab-item tab-dash"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
        <a href="profile.php" class="tab-item tab-profile"><i class="fas fa-user mr-2"></i> My Profile</a>
        <div class="tab-item tab-trans">
            <i class="fas fa-file-alt mr-2"></i> Transactions
            <div class="dropdown-menu-custom">
                <a href="view_filed_transactions.php"><i class="far fa-eye"></i> View Filed Transactions</a>
                <a href="create_new_transaction.php"><i class="far fa-plus-square"></i> Create New Transaction</a>
            </div>
        </div>
        <div class="tab-item tab-dtr">
            <i class="fas fa-calendar-alt mr-2"></i> DTR
            <div class="dropdown-menu-custom">
                <a href="my_calendar.php"><i class="fas fa-calendar-alt"></i> My Calendar</a>
                <a href="#"><i class="far fa-calendar"></i> My Attendances</a>
                <a href="#"><i class="far fa-calendar-check"></i> My Daily Time Record (DTR)</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> Geo Attendances</a>
            </div>
        </div>
        <div class="tab-item tab-payroll">
            <i class="fas fa-money-bill-wave mr-2"></i> Payroll
            <div class="dropdown-menu-custom">
                <a href="payslip.php"><i class="fas fa-money-bill-wave"></i> Payslip</a>
                <a href="loan_record.php"><i class="fas fa-hand-holding-usd"></i> Loan Record</a>
                <a href="other_addition.php"><i class="fas fa-plus-circle"></i> Other Addition</a>
                <a href="other_deduction.php"><i class="fas fa-minus-circle"></i> Other Deduction</a>
                <a href="payslip_13th_month.php"><i class="fas fa-gift"></i> 13th Month Payslip</a>
                <a href="leave_conversion_payslip.php"><i class="fas fa-exchange-alt"></i> Leave Conversion Payslip</a>
                <a href="bonus_payslip.php"><i class="fas fa-money-bill"></i> Bonus Payslip</a>
                <a href="my_ytd.php"><i class="fas fa-chart-line"></i> My YTD</a>
            </div>
        </div>
        <div class="tab-item tab-others">
            <i class="fas fa-list mr-2"></i> Others
            <div class="dropdown-menu-custom">
                <a href="faq.php"><i class="far fa-eye"></i> FAQ</a>
                <a href="health_check.php"><i class="far fa-eye"></i> Employee Health Check</a>
                <a href="downloadable_forms.php"><i class="far fa-eye"></i> Downloadable Forms</a>
                <a href="holiday_list.php"><i class="far fa-eye"></i> Holiday List</a>
            </div>
        </div>
        <a href="settings.php" class="tab-item tab-settings"><i class="fas fa-cog mr-2"></i> Settings</a>
    </div>

    <div class="profile-layout">
        <!-- Sidebar -->
        <div class="left-panel">
            <div class="user-card">
                <div class="avatar-circle">
                    <i class="fas fa-user"></i>
                </div>
                <p>(<?= $id_num ?>)</p>
                <h5><?= $fname . ' ' . $mname . ' ' . $lname ?></h5>
                <p>"..."</p>
            </div>

            <div class="menu-card">
                <div class="menu-header">
                    <span>201 Employee Files</span>
                    <span style="font-size: 10px; color: #0288d1; font-weight: normal;">[download manual]</span>
                </div>
                <!-- Top static items -->
                <ul class="menu-list" style="overflow-y: visible; max-height: none; flex-grow: 0; border-bottom: 2px solid #e1f5fe;">
                    <li class="menu-item">DATA PRIVACY ACT <i class="fas fa-users"></i></li>
                    <li class="menu-item">SEND 201 REQUEST <i class="fas fa-forward"></i></li>
                    <li class="menu-item">PENDING REQUEST (admin) <i class="fas fa-bell"></i></li>
                    <li class="menu-item" style="background: #fafafa; font-weight: bold;">EMPLOYEE 201 SETTINGS <i class="fas fa-cog"></i></li>
                </ul>
                <!-- Scrollable sections starting from Personal Information -->
                <ul class="menu-list scrollable-menu">
                    <li class="menu-item active">Personal Information <i class="fas fa-user"></i></li>
                    <li class="menu-item">Profile Picture <i class="fas fa-users"></i></li>
                    <li class="menu-item">Address Information <i class="fas fa-map-marker-alt"></i></li>
                    <li class="menu-item">Contact Information <i class="fas fa-mobile-alt"></i></li>
                    <li class="menu-item">Residence Map <i class="fas fa-map-pin"></i></li>
                    <li class="menu-item">Family Data <i class="fas fa-home"></i></li>
                    <li class="menu-item">Educational Attainment <i class="fas fa-graduation-cap"></i></li>
                    <li class="menu-item">Employment Experience <i class="fas fa-briefcase"></i></li>
                    <li class="menu-item">Character Reference <i class="fas fa-users"></i></li>
                    <li class="menu-item">Dependents <i class="fas fa-child"></i></li>
                    <li class="menu-item">Government Account <i class="fas fa-id-card"></i></li>
                    <li class="menu-item">Inventory <i class="fas fa-boxes"></i></li>
                    <li class="menu-item">Skills <i class="fas fa-tools"></i></li>
                    <li class="menu-item">Contract History <i class="fas fa-file-contract"></i></li>
                    <li class="menu-item">Employee Electronic Signature <i class="fas fa-signature"></i></li>
                    <li class="menu-item">Other Information <i class="fas fa-info-circle"></i></li>
                    <li class="menu-item">Employment Information <i class="fas fa-building"></i></li>
                    <li class="menu-item">Movement History <i class="fas fa-exchange-alt"></i></li>
                    <li class="menu-item">Log History <i class="fas fa-history"></i></li>
                    <li class="menu-item">Status History <i class="fas fa-user-clock"></i></li>
                    <li class="menu-item">Whole Body Picture <i class="fas fa-image"></i></li>
                    <li class="menu-item">View Full 201 Information <i class="fas fa-file-pdf"></i></li>
                    <li class="menu-item">Resigned Date History <i class="fas fa-user-minus"></i></li>
                    <li class="menu-item">Employment Date History <i class="fas fa-calendar-check"></i></li>
                    <li class="menu-item">Long Service Leave History <i class="fas fa-user-shield"></i></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="right-panel">
            <div class="panel-header">
                <div><i class="fas fa-user-check mr-2"></i> Personal Information</div>
                <div class="btn-action-group">
                    <div class="btn-sm-square"><i class="fas fa-edit"></i></div>
                    <div class="btn-sm-square"><i class="fas fa-history"></i></div>
                    <div class="btn-sm-square"><i class="fas fa-folder"></i></div>
                    <div class="btn-sm-square"><i class="fas fa-chevron-left"></i></div>
                </div>
            </div>
            <div class="panel-body">
                <div class="section-title"><i class="fas fa-user"></i> Basic Details</div>
                <div class="info-grid">
                    <div class="info-label">Firstname</div><div class="info-value"><?= $fname ?></div>
                    <div class="info-label">Middle Name</div><div class="info-value"><?= $mname ?></div>
                    <div class="info-label">Last Name</div><div class="info-value"><?= $lname ?></div>
                    <div class="info-label">NickName</div><div class="info-value"></div>
                    <div class="info-label">Birthday</div><div class="info-value"><?= $bday ?></div>
                    <div class="info-label">Birth Place</div><div class="info-value"></div>
                    <div class="info-label">Age</div><div class="info-value">24</div>
                </div>

                <div class="section-title" style="margin-top: 40px;"><i class="fas fa-plus-square"></i> More Information</div>
                <div class="info-grid">
                    <div class="info-label">Gender</div><div class="info-value"><?= $gender ?></div>
                    <div class="info-label">Civil Status</div><div class="info-value"><?= $civil ?></div>
                    <div class="info-label">Blood Type</div><div class="info-value"></div>
                    <div class="info-label">Religion</div><div class="info-value"></div>
                    <div class="info-label">Citizenship</div><div class="info-value"></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
