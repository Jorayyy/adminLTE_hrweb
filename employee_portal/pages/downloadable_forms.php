<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
$page_title = "Downloadable Forms";
include "layout_template.php";
?>
<div class="card-custom">
    <div class="card-header-custom" style="border-top: 3px solid #c58de8;">
        <i class="far fa-eye mr-2"></i> <?= $page_title ?>
    </div>
    <div style="padding: 40px; text-align: center; color: #777;">
        <i class="fas fa-tools mb-3" style="font-size: 64px; color: #c58de8;"></i>
        <h3>Under Maintenance</h3>
        <p>This module (<?= $page_title ?>) is currently under maintenance. Please check back later.</p>
    </div>
</div>
