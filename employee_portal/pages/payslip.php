<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
require_once "../../config/db.php";
$emp_id = $_SESSION['emp_id'] ?? '';
$emp_id_number = $_SESSION['emp_id_number'] ?? '';

$page_title = "My Payslip";
include "layout_template.php";

// Fetch available payroll periods for the selector
$current_date = date('Y-m-d');
$periods_stmt = $conn->prepare("
    SELECT p.*, g.group_name 
    FROM payroll_periods p 
    JOIN payroll_period_groups g ON p.group_id = g.id 
    JOIN employee_group_assignments ega ON g.id = ega.group_id
    WHERE ega.employee_id = ? AND p.date_from <= ?
    ORDER BY p.date_from DESC
");
$periods_stmt->execute([$emp_id_number, $current_date]);
$periods = $periods_stmt->fetchAll();

$selected_period_id = $_GET['period_id'] ?? '';
$payslip_data = null;

if (!empty($selected_period_id)) {
    // Fetch period details
    $p_stmt = $conn->prepare("SELECT p.*, g.group_name FROM payroll_periods p JOIN payroll_period_groups g ON p.group_id = g.id WHERE p.id = ?");
    $p_stmt->execute([$selected_period_id]);
    $period_info = $p_stmt->fetch();

    if ($period_info) {
        // MOCK DATA for the payslip (as database tables for calculations are not yet available)
        // Values based on the provided screenshot for 'Regular Payroll'
        $payslip_data = [
            'period_desc' => $period_info['group_name'] . ' (' . date('M d, Y', strtotime($period_info['date_from'])) . ' - ' . date('M d, Y', strtotime($period_info['date_to'])) . ')',
            'pay_date' => date('M d, Y', strtotime($period_info['pay_date'])),
            'earnings' => [
                ['label' => 'SALARY / LEAVE PAY', 'amount' => 5000.00],
                ['label' => 'ALLOWANCE', 'amount' => 1200.00],
                ['label' => 'OVERTIME', 'amount' => 450.75],
                ['label' => 'NIGHT DIFF', 'amount' => 120.50],
                ['label' => 'HOLIDAY PAY', 'amount' => 0.00],
            ],
            'deductions' => [
                ['label' => 'SSS CONTRIBUTION', 'amount' => 450.00],
                ['label' => 'PHILHEALTH', 'amount' => 200.00],
                ['label' => 'PAG-IBIG', 'amount' => 100.00],
                ['label' => 'WITHHOLDING TAX', 'amount' => 345.20],
                ['label' => 'LATE / UNDERSPELL', 'amount' => 85.00],
                ['label' => 'SSS LOAN', 'amount' => 0.00],
                ['label' => 'PAG-IBIG LOAN', 'amount' => 0.00],
            ],
            'ytd' => [
                'gross' => 150000.00,
                'tax' => 12450.50,
                'sss' => 5400.00,
                'ph' => 2400.00,
                'pi' => 1200.00
            ],
            'loans' => [
                ['type' => 'SSS LOAN', 'balance' => 0.00],
                ['type' => 'PAG-IBIG LOAN', 'balance' => 0.00],
                ['type' => 'COMPANY LOAN', 'balance' => 2500.00]
            ]
        ];
    }
}

// Fetch employee details
$emp_stmt = $conn->prepare("SELECT e.*, ex.company, ex.position, ex.location, ex.section 
                           FROM employees e 
                           LEFT JOIN employees_extended ex ON e.id_number = ex.employee_id 
                           WHERE e.id_number = ?");
$emp_stmt->execute([$emp_id_number]);
$employee = $emp_stmt->fetch();
?>

<style>
    .payslip-container { background: #fff; border: 1px solid #ddd; margin-top: 10px; padding: 0; }
    .payslip-header { background: #f8f9fa; padding: 10px 20px; border-bottom: 2px solid #ddd; }
    .filter-section { padding: 20px; border-bottom: 1px solid #eee; background: #fff; }
    .payslip-content { padding: 20px; background: #fff; }
    
    .payslip-table { width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 13px; }
    .payslip-table th { background: #003d5b; color: white; padding: 8px; border: 1px solid #000; text-align: left; }
    .payslip-table td { border: 1px solid #000; padding: 6px 10px; vertical-align: top; }
    
    .section-title { font-weight: bold; background: #eee; padding: 5px 10px; border: 1px solid #000; margin-top: -1px; }
    .total-row { font-weight: bold; background: #f8f9fa; border-top: 2px solid #000; }
    .net-pay-box { border: 2px solid #000; padding: 10px; text-align: center; margin-top: 20px; font-weight: bold; font-size: 18px; background: #fff9c4; }
    
    .amount-col { text-align: right; width: 150px; }
    .label-col { text-align: left; }
    
    .ytd-box { margin-top: 20px; font-size: 12px; }
    .ytd-table { width: 100%; border-collapse: collapse; }
    .ytd-table td { border: 1px solid #ddd; padding: 4px 8px; }
    .ytd-header { background: #003d5b; color: white; font-weight: bold; padding: 4px 8px; }

    @media print {
        .filter-section, .tab-bar, .top-nav { display: none; }
        .payslip-container { border: none; }
    }
</style>

<div class="payslip-container">
    <div class="payslip-header">
        <h5 class="mb-0"><i class="fas fa-file-invoice-dollar mr-2 text-primary"></i> <?= $page_title ?></h5>
    </div>
    
    <div class="filter-section">
        <form action="payslip.php" method="GET" class="form-inline">
            <label class="mr-3 font-weight-bold">Select Payroll Period:</label>
            <select name="period_id" class="form-control" style="min-width: 400px;" onchange="this.form.submit()">
                <option value="">-- Choose Pay Date --</option>
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($selected_period_id == $p['id']) ? 'selected' : '' ?>>
                        <?= date('M d, Y', strtotime($p['pay_date'])) ?> | <?= $p['group_name'] ?> (<?= date('M d, Y', strtotime($p['date_from'])) ?> - <?= date('M d, Y', strtotime($p['date_to'])) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($payslip_data): ?>
                <button type="button" class="btn btn-default ml-3" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            <?php endif; ?>
        </form>
    </div>

    <div class="payslip-content">
        <?php if ($payslip_data): ?>
            <!-- Payslip Content -->
            <div id="printable-payslip">
                <div class="text-center mb-4">
                    <h4 class="mb-0 font-weight-bold"><?= $employee['company'] ?? 'COMPANY NAME' ?></h4>
                    <p class="mb-0"><?= $employee['location'] ?? '' ?></p>
                    <h5 class="mt-3 font-weight-bold" style="text-decoration: underline;">PAY ADVICE</h5>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <table style="width: 100%; font-size: 13px;">
                            <tr><td width="100">Employee ID:</td><td class="font-weight-bold"><?= $employee['id_number'] ?></td></tr>
                            <tr><td>Name:</td><td class="font-weight-bold"><?= $employee['lastname'] . ', ' . $employee['firstname'] ?></td></tr>
                            <tr><td>Department:</td><td><?= $employee['department'] ?></td></tr>
                        </table>
                    </div>
                    <div class="col-6 text-right">
                        <table style="width: 100%; font-size: 13px;">
                            <tr><td class="text-right">Pay Date:</td><td class="font-weight-bold text-right"><?= $payslip_data['pay_date'] ?></td></tr>
                            <tr><td class="text-right">Period:</td><td class="text-right"><?= $payslip_data['period_desc'] ?></td></tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 pr-0">
                        <table class="payslip-table">
                            <thead><tr><th colspan="2">EARNINGS</th></tr></thead>
                            <tbody>
                                <?php 
                                $total_earnings = 0;
                                foreach ($payslip_data['earnings'] as $item): 
                                    $total_earnings += $item['amount'];
                                ?>
                                    <tr>
                                        <td class="label-col"><?= $item['label'] ?></td>
                                        <td class="amount-col font-weight-bold"><?= number_format($item['amount'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <!-- Empty rows to balance height -->
                                <?php for($i=0; $i<3; $i++): ?><tr><td>&nbsp;</td><td>&nbsp;</td></tr><?php endfor; ?>
                                <tr class="total-row">
                                    <td>TOTAL GROSS</td>
                                    <td class="amount-col text-primary"><?= number_format($total_earnings, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6 pl-0">
                        <table class="payslip-table" style="border-left: none;">
                            <thead><tr><th colspan="2">DEDUCTIONS</th></tr></thead>
                            <tbody>
                                <?php 
                                $total_deductions = 0;
                                foreach ($payslip_data['deductions'] as $item): 
                                    $total_deductions += $item['amount'];
                                ?>
                                    <tr>
                                        <td class="label-col"><?= $item['label'] ?></td>
                                        <td class="amount-col text-danger"><?= number_format($item['amount'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="total-row">
                                    <td>TOTAL DEDUCTIONS</td>
                                    <td class="amount-col text-danger"><?= number_format($total_deductions, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-8">
                        <div class="ytd-box">
                            <div class="ytd-header text-center">YEAR-TO-DATE (YTD) SUMMARY</div>
                            <table class="ytd-table">
                                <tr>
                                    <td>Gross Pay</td><td class="font-weight-bold"><?= number_format($payslip_data['ytd']['gross'], 2) ?></td>
                                    <td>SSS</td><td class="font-weight-bold"><?= number_format($payslip_data['ytd']['sss'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td>W/Tax</td><td class="font-weight-bold"><?= number_format($payslip_data['ytd']['tax'], 2) ?></td>
                                    <td>PhilHealth</td><td class="font-weight-bold"><?= number_format($payslip_data['ytd']['ph'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Pag-IBIG</td><td class="font-weight-bold"><?= number_format($payslip_data['ytd']['pi'], 2) ?></td>
                                    <td>&nbsp;</td><td>&nbsp;</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="net-pay-box">
                            <div style="font-size: 12px; font-weight: normal; color: #666;">NET TAKE HOME PAY</div>
                            ₱ <?= number_format($total_earnings - $total_deductions, 2) ?>
                        </div>
                    </div>
                </div>

                <div class="mt-4" style="font-size: 11px; color: #777;">
                    <p><i>Note: This is a system-generated pay advice. No signature is required. For discrepancies, please contact the HR/Accounting department within 24 hours.</i></p>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5" style="border: 1px dashed #ccc; background: #fafafa;">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <h5>Please select a payroll period to view your payslip.</h5>
                <p class="text-muted">Currently showing payroll periods for Employee: <strong><?= $emp_id_number ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</div>
