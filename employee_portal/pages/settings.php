<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
$page_title = "Settings";
include "layout_template.php";
?>
<div style="display: flex; gap: 20px;">
    <!-- Left Side: Menu -->
    <div class="card-custom" style="width: 300px; min-height: 400px; margin-bottom: 0;">
        <div class="card-header-custom" style="background: #fff; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; padding: 15px;">
            <span style="font-size: 16px; color: #555;">Employee Settings</span>
            <i class="fas fa-cog text-muted"></i>
        </div>
        <div style="padding: 0;">
            <style>
                .settings-menu-item {
                    display: flex;
                    align-items: center;
                    padding: 12px 20px;
                    border-bottom: 1px solid #f5f5f5;
                    color: #777;
                    text-decoration: none;
                    font-size: 14px;
                    transition: background 0.2s;
                }
                .settings-menu-item:hover {
                    background: #fcfcfc;
                    color: #333;
                }
                .settings-menu-item i {
                    margin-right: 15px;
                    font-size: 12px;
                }
            </style>
            <a href="change_password.php" class="settings-menu-item">
                <i class="far fa-circle"></i> Change Password
            </a>
            <a href="account_settings.php" class="settings-menu-item">
                <i class="far fa-circle"></i> Account Settings
            </a>
            <a href="change_bundy_code.php" class="settings-menu-item">
                <i class="far fa-circle"></i> Change Web Bundy Code
            </a>
        </div>
    </div>

    <!-- Right Side: Content Area (Initially empty like screenshot) -->
    <div style="flex: 1; border-top: 2px solid #2ecc71;">
        <!-- Specific settings content would load here -->
    </div>
</div>
