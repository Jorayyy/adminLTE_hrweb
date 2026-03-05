<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HRWEB.PH | <?= $page_title ?? 'Portal' ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <style>
        body { background-color: #e0f2f7; font-family: 'Source Sans Pro', sans-serif; overflow-x: hidden; }
        .top-nav { background: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ddd; }
        .logo-text { font-weight: bold; font-size: 24px; color: #333; }
        .logo-text span { color: #f39c12; }
        
        .tab-bar { display: flex; width: 100%; height: 45px; margin-top: 0px; border-top: 1px solid #ddd; position: relative; z-index: 1000; }
        .tab-item { flex: 1; display: flex; align-items: center; justify-content: center; color: #000; font-weight: bold; font-size: 14px; text-decoration: none; border-right: none; position: relative; height: 100%; }
        
        .tab-dash { background: #f87272; }
        .tab-profile { background: #71f29b; }
        .tab-trans { background: #d9e64e; }
        .tab-dtr { background: #f946b5; }
        .tab-payroll { background: #8e2b2b; color: #fff; }
        .tab-others { background: #c58de8; }
        .tab-settings { background: #55b6c3; }

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
        
        .content-area { padding: 20px; }
        .page-title { color: #3c8dbc; font-size: 20px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        
        .card-row { display: flex; gap: 20px; align-items: flex-start; }
        .card-custom { flex: 1; min-width: 0; margin-bottom: 20px; }
        .card-header-custom { display: flex; align-items: flex-end; }
        
        .tab-link { 
            color: #555; 
            font-size: 13px; 
            text-decoration: none !important; 
            padding: 8px 15px; 
            border: 1px solid #ddd;
            border-bottom: none;
            background: #f8f9fa;
            margin-right: -1px;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        .tab-link.active { 
            border-top: 3px solid #3c8dbc; 
            color: #333; 
            font-weight: bold; 
            background: #fff;
            z-index: 2;
        }
        
        .celebrant-item { display: flex; align-items: center; padding: 12px 15px; border-bottom: 1px solid #f4f4f4; position: relative; width: 100%; }
        .celebrant-img { width: 45px; height: 45px; border-radius: 50%; background: #333; color: #fff; margin-right: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .celebrant-info { display: block; flex-grow: 1; }
        .celebrant-info h6 { margin: 0; font-size: 13px; color: #d9534f; font-weight: bold; }
        .celebrant-info p { margin: 0; font-size: 11px; color: #777; line-height: 1.4; }
        .btn-send { background: #3c8dbc; color: #fff; border: none; padding: 5px 12px; border-radius: 3px; font-size: 11px; cursor: pointer; }
        .bread-crumb a { color: #777; }
    </style>
</head>
<body>
    <div class="top-nav">
        <div class="logo-text">HR<span>WEB</span>.PH</div>
        <div style="font-size: 12px; font-weight: bold; color: #555;">
            <?= strtoupper($_SESSION['emp_fullname'] ?? 'USER') ?>
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
                <a href="my_attendances.php"><i class="far fa-calendar"></i> My Attendance(s)</a>
                <a href="view_dtr.php"><i class="far fa-calendar-check"></i> My Daily Time Record (DTR)</a>
                <a href="geo_attendances.php"><i class="fas fa-map-marker-alt"></i> Geo Attendances</a>
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

    <div class="content-area">
        <!-- Content will be injected here -->
<?php
// Footer inclusion could be added here if needed, 
// for now closing tags are enough.
?>
    </div>
</body>
</html>
