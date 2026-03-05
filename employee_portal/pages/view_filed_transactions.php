<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

$forms = [
    "All Forms", "Apply Leave", "Request of change of schedule", 
    "Authorization to render overtime", "Payroll Complaints Form", 
    "Official Business Form", "Under Time Form", "Cancellation of leave", 
    "Timekeeping Complaint", "Request of Change of Rest day"
];

$page_title = "View Filed Transactions";
include "layout_template.php";
?>
<div class="transaction-card" style="width: 500px; margin: 20px auto; background: #fff; border: 1px solid #28a745; border-radius: 4px; overflow: hidden;">
    <div class="transaction-header" style="background: #28a745; color: #fff; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center; font-weight: bold; font-size: 16px;">
        <span>Transactions</span>
        <input type="text" class="search-box" style="background: #fff; border: none; padding: 5px 10px; border-radius: 2px; font-size: 12px; width: 180px;" placeholder="Search a Transaction Here">
    </div>
    <ul class="transaction-list" style="list-style: none; padding: 0; margin: 0;">
        <?php foreach($forms as $form): ?>
            <li class="transaction-item" style="padding: 12px 20px; border-bottom: 1px solid #f1f1f1; color: #007bff; font-weight: 500; font-size: 14px; background: #fff; cursor: pointer;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='#fff'"><?= $form ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
// End of file
?>

</body>
</html>