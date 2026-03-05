<?php
session_start();
if (!isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
$page_title = "Dashboard";
include "layout_template.php";
?>
        <div class="page-title">
            <span>DASHBOARD</span>
            <div class="bread-crumb"><i class="fas fa-home"></i> Home > Dashboard</div>
        </div>

        <div class="card-row">
            <!-- Announcements Section -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <a href="#" class="tab-link active" style="border-top-color: #d9534f;"><i class="fas fa-bookmark text-danger"></i> Announcements</a>
                </div>
                <div style="padding: 40px 20px; color: #999; font-size: 13px; text-align: center; background: #fff; border: 1px solid #ddd; border-top: none; min-height: 350px;">
                    No Announcements
                </div>
            </div>

            <!-- Birthday Celebrants Section -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <a href="#" class="tab-link active"><i class="fas fa-users text-primary" style="color: #d9534f !important;"></i> Birthday Celebrants of the week</a>
                    <a href="#" class="tab-link" style="border: none; background: transparent;"><i class="fas fa-bookmark text-danger" style="opacity: 0.5;"></i> Employee Licences</a>
                </div>
                <div style="padding: 15px; background: #fff; border: 1px solid #ddd; border-top: none; min-height: 350px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 15px; background: #fff; border: 1px solid #eee; margin-bottom: 10px;">
                        <span style="font-size: 13px; font-weight: bold;">Birthday Celebrants</span>
                        <i class="fas fa-birthday-cake text-muted"></i>
                    </div>
                    
                    <div class="celebrant-item">
                        <div class="celebrant-img"><i class="fas fa-user"></i></div>
                        <div class="celebrant-info">
                            <h6>JAYVI DOMINGUITO</h6>
                            <p>AGENT</p>
                            <p>March 08</p>
                        </div>
                        <button class="btn-send">Send Message</button>
                    </div>

                    <div class="celebrant-item">
                        <div class="celebrant-img"><i class="fas fa-user"></i></div>
                        <div class="celebrant-info">
                            <h6>JUN ARIES ARDIENTE</h6>
                            <p>AGENT</p>
                            <p>March 11</p>
                        </div>
                        <button class="btn-send">Send Message</button>
                    </div>

                    <div class="celebrant-item">
                        <div class="celebrant-img">
                            <img src="../../assets/img/user1-128x128.jpg" style="width: 100%; border-radius: 50%;" onerror="this.parentElement.innerHTML='<i class=\"fas fa-user\"></i>'">
                        </div>
                        <div class="celebrant-info">
                            <h6>RM JYRA SERATA</h6>
                            <p>AGENT</p>
                            <p>March 07</p>
                        </div>
                        <button class="btn-send">Send Message</button>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding: 0 5px;">
                        <span style="font-size: 11px; color: #777;">Showing 1 to 3 of 4 entries</span>
                        <div style="display: flex;">
                            <button style="border: 1px solid #ddd; background: #fff; padding: 4px 10px; font-size: 11px; cursor: pointer; color: #555; border-radius: 3px 0 0 3px;">Previous</button>
                            <button style="border: 1px solid #ddd; background: #fff; padding: 4px 10px; font-size: 11px; cursor: pointer; color: #555; border-radius: 0 3px 3px 0; border-left: none;">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty bottom cards for layout -->
        <div class="card-row">
            <div class="card-custom" style="min-height: 200px;"></div>
            <div class="card-custom" style="min-height: 200px;"></div>
        </div>
<?php /* The rest of the HTML is handled by the template - closing body/html tag not needed here */ ?>
