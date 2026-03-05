<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
$page_title = "Account Settings";
include "layout_template.php";
?>
<div style="display: flex; gap: 20px;">
    <!-- Left Menu -->
    <div class="card-custom" style="width: 300px; min-height: 400px; margin-bottom: 0;">
        <div class="card-header-custom" style="background: #fff; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; padding: 15px;">
            <span style="font-size: 16px; color: #555;">Employee Settings</span>
            <i class="fas fa-cog text-muted"></i>
        </div>
        <div style="padding: 0;">
            <style>
                .settings-menu-item { display: flex; align-items: center; padding: 12px 20px; border-bottom: 1px solid #f5f5f5; color: #777; text-decoration: none; font-size: 14px; transition: background 0.2s; }
                .settings-menu-item:hover { background: #fcfcfc; color: #333; }
                .settings-menu-item.active { background: #f0f0f0; color: #333; border-left: 3px solid #2ecc71; }
                .settings-menu-item i { margin-right: 15px; font-size: 12px; }
            </style>
            <a href="change_password.php" class="settings-menu-item"><i class="far fa-circle"></i> Change Password</a>
            <a href="account_settings.php" class="settings-menu-item active"><i class="far fa-circle"></i> Account Settings</a>
            <a href="change_bundy_code.php" class="settings-menu-item"><i class="far fa-circle"></i> Change Web Bundy Code</a>
        </div>
    </div>

    <!-- Content Area -->
    <div style="flex: 1; border-top: 2px solid #2ecc71;">
        <div class="card-custom" style="min-height: 400px;">
            <div class="card-header-custom">Account Settings</div>
            <div style="padding: 20px;">
                <form>
                    <div class="form-group">
                        <label>Preferred Name / Display Name</label>
                        <input type="text" class="form-control" name="display_name" value="<?= $_SESSION['emp_fullname'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Email Notification Preferences</label>
                        <select class="form-control" name="email_pref">
                            <option value="all">Receive All Notifications</option>
                            <option value="none">Disable All Notifications</option>
                            <option value="important">Only Important Notifications</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="background: #3c8dbc; border: none;">Save Account Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
