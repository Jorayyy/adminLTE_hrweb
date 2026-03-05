<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";
$emp_id = $_SESSION['emp_id'] ?? '';
$emp_id_number = $_SESSION['emp_id_number'] ?? '';

$page_title = "Loan Record";
include "layout_template.php";

// Filter parameters
$year_from = $_GET['year_from'] ?? date('Y');
$year_to = $_GET['year_to'] ?? date('Y');
$loan_status = $_GET['loan_status'] ?? 'Active';

$loans = [];
try {
    // Assuming a 'loans' table exists with columns like loan_type, date_granted, effectivity_date, principal_amount, loan_amount, amortization, current_balance, reference_no, status
    $stmt = $conn->prepare("
        SELECT * FROM loans 
        WHERE employee_id = ? 
        AND strftime('%Y', effectivity_date) BETWEEN ? AND ?
        AND status = ?
        ORDER BY effectivity_date DESC
    ");
    // For MySQL: YEAR(effectivity_date) BETWEEN ? AND ?
    // I'll use a more generic search if engine is unknown, but based on setup_db.php it's SQLite
    $stmt->execute([$emp_id_number, $year_from, $year_to, $loan_status]);
    $loans = $stmt->fetchAll();
} catch (Exception $e) {
    // Silent fail if table doesn't exist yet
}
?>

<style>
    .loan-container { background: #fff; border: 1px solid #ddd; margin-top: 10px; padding: 0; }
    .loan-header { 
        background: #e9f5e9; 
        padding: 8px 15px; 
        border-bottom: 1px solid #ddd; 
        font-size: 14px; 
        font-weight: bold; 
        color: #2e7d32;
    }
    .filter-section { padding: 15px; border-bottom: 1px solid #ddd; }
    .filter-row { display: flex; flex-direction: column; gap: 10px; }
    .filter-item { display: flex; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 5px; }
    .filter-item label { width: 250px; font-weight: bold; font-size: 13px; margin: 0; }
    .filter-item label small { font-weight: normal; font-style: italic; color: #777; }
    .filter-item select { flex: 1; border: 1px solid #ced4da; padding: 4px; border-radius: 4px; font-size: 13px; }
    
    .btn-filter { background: #00a65a; color: #fff; border: none; padding: 6px 60px; border-radius: 4px; font-weight: bold; margin-top: 10px; }
    .btn-filter:hover { background: #008d4c; }

    .data-table-container { padding: 15px; overflow-x: auto; }
    .table-title { text-align: center; font-size: 24px; color: #333; margin: 15px 0; }
    
    .loan-table { width: 100%; border-collapse: collapse; font-size: 13px; white-space: nowrap; }
    .loan-table th, .loan-table td { border: 1px solid #eee; padding: 10px; text-align: left; }
    .loan-table th { background: #fff; font-weight: bold; color: #333; border-bottom: 2px solid #ddd; }
    
    .table-controls { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 13px; }
    .search-box input { border: 1px solid #ddd; padding: 3px 8px; border-radius: 3px; }
</style>

<div class="loan-container">
    <div class="loan-header">Loan Record</div>
    
    <div class="filter-section">
        <form action="loan_record.php" method="GET">
            <div class="filter-row">
                <div class="filter-item">
                    <label>Covered Year From <small>(Effectivity Date)</small></label>
                    <select name="year_from">
                        <option value="2026" <?= $year_from == '2026' ? 'selected' : '' ?>>2026</option>
                        <option value="2025" <?= $year_from == '2025' ? 'selected' : '' ?>>2025</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Covered Year To <small>(Effectivity Date)</small></label>
                    <select name="year_to">
                        <option value="2026" <?= $year_to == '2026' ? 'selected' : '' ?>>2026</option>
                        <option value="2025" <?= $year_to == '2025' ? 'selected' : '' ?>>2025</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Loan Status</label>
                    <select name="loan_status">
                        <option value="Active" <?= $loan_status == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Fully Paid" <?= $loan_status == 'Fully Paid' ? 'selected' : '' ?>>Fully Paid</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-filter">Filter</button>
        </form>
    </div>

    <div class="data-table-container">
        <div class="table-title">All <?= htmlspecialchars($loan_status) ?> Loan(s)</div>
        
        <div class="table-controls">
            <div>Show <select><option>10</option></select> entries</div>
            <div class="search-box">Search: <input type="text"></div>
        </div>
        
        <table class="loan-table">
            <thead>
                <tr>
                    <th>Loan Type</th>
                    <th>Date Granted</th>
                    <th>Effectivity Date</th>
                    <th>Principal Amount</th>
                    <th>Loan Amount</th>
                    <th>Amortization</th>
                    <th>Current Balance</th>
                    <th>Reference No</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($loans)): ?>
                    <tr>
                        <td colspan="9" class="text-center">No data available in table</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($loans as $loan): ?>
                    <tr>
                        <td><?= htmlspecialchars($loan['loan_type']) ?></td>
                        <td><?= htmlspecialchars($loan['date_granted']) ?></td>
                        <td><?= htmlspecialchars($loan['effectivity_date']) ?></td>
                        <td><?= number_format($loan['principal_amount'], 2) ?></td>
                        <td><?= number_format($loan['loan_amount'], 2) ?></td>
                        <td><?= number_format($loan['amortization'], 2) ?></td>
                        <td><?= number_format($loan['current_balance'], 2) ?></td>
                        <td><?= htmlspecialchars($loan['reference_no']) ?></td>
                        <td><button class="btn btn-info btn-xs"><i class="fas fa-eye"></i> View</button></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="table-controls" style="margin-top: 10px;">
            <div>Showing <?= count($loans) ?> to <?= count($loans) ?> of <?= count($loans) ?> entries</div>
            <div class="pagination">
                <button disabled>Previous</button>
                <button disabled>Next</button>
            </div>
        </div>
    </div>
</div>
<?php
// End of file
?>
