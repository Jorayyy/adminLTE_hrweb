<?php
session_start();
if (isset($_SESSION['emp_logged_in']) && $_SESSION['emp_logged_in'] === true) {
    header("Location: " . (strpos($_SERVER['PHP_SELF'], 'pages/') !== false ? '../' : '') . "employee_portal/pages/dashboard.php");
    exit;
}
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: " . (strpos($_SERVER['PHP_SELF'], 'pages/') !== false ? '../' : '') . "index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>HRWEB.PH | Login</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'>
    <?php 
        $is_in_pages = strpos($_SERVER['PHP_SELF'], 'pages/') !== false;
        $base = $is_in_pages ? '../assets/' : 'assets/';
        echo "<link rel='stylesheet' href='{$base}css/adminlte.min.css'>";
    ?>
    <style>
        body { background: url('https://img.freepik.com/free-photo/abstract-luxury-gradient-blue-background-smooth-dark-blue-with-black-vignette-studio-banner_1258-52393.jpg') no-repeat center center fixed; background-size: cover; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; font-family: 'Source Sans Pro', sans-serif; overflow: hidden; }
        .login-container { display: flex; gap: 20px; width: 90%; max-width: 900px; padding: 20px; }
        .card-custom { background: #fff; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .card-header-bundy { background: #f8d7da; color: #721c24; padding: 12px 15px; font-weight: bold; border-bottom: 1px solid #f5c6cb; }
        .card-header-portal { background: #448aff; color: #fff; padding: 12px 15px; font-weight: bold; }
        .card-body { padding: 30px; position: relative; flex: 1; }
        .mini-clock { position: absolute; top: 15px; right: 20px; width: 80px; height: 80px; border: 4px solid #ff4d4d; border-radius: 50%; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .hand { position: absolute; bottom: 50%; left: 50%; transform-origin: bottom center; background: #333; transform: translateX(-50%); }
        .hour { width: 4px; height: 20px; } .min { width: 3px; height: 28px; } .sec { width: 1px; height: 32px; background: red; }
        .form-group { margin-bottom: 20px; } .form-group label { font-weight: bold; display: block; margin-bottom: 8px; color: #333; font-size: 14px; }
        .input-custom { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 15px; }
        .login-input { background: #f0f4ff; } 
        .btn-punch { background: #e74c3c; color: #fff; border: none; width: 100%; padding: 12px; font-weight: bold; border-radius: 4px; border-bottom: 4px solid #c0392b; margin-top: 15px; cursor: pointer; font-size: 16px; }
        .btn-portal { background: #448aff; color: #fff; border: none; width: 100%; padding: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; margin-top: 15px; font-size: 16px; }
        .bundy-table { width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #eee; }
        .bundy-row { display: flex; justify-content: space-between; padding: 10px 15px; font-weight: bold; border-bottom: 1px solid #f9f9f9; font-size: 14px; }
        .row-blue { background: #448aff; color: white; } .row-red { background: #f8d7da; color: #721c24; }
        .notif-bar { position: fixed; top: 20px; right: 20px; min-width: 300px; padding: 15px; border-radius: 5px; color: #fff; font-weight: bold; z-index: 9999; display: none; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        .notif-error { background: #e74c3c; } .notif-success { background: #27ae60; }
        .logo-box { text-align: center; margin-bottom: 25px; } .logo-box img { max-width: 220px; }
    </style>
</head>
<body>
    <div id='notif' class='notif-bar'></div>
    <div class='login-container'>
        <div class='card-custom'>
            <div class='card-header-bundy'>Web Bundy</div>
            <div class='card-body'>
                <div class='mini-clock'>
                    <div class='hand hour' id='h'></div>
                    <div class='hand min' id='m'></div>
                    <div class='hand sec' id='s'></div>
                </div>
                <div style='margin-top: 50px;'>
                    <div class='form-group'>
                        <label>Employee ID <i class='fas fa-id-card' style='color:#a94442'></i></label>
                        <input type='text' id='eid' class='input-custom' placeholder='Enter Employee ID'>
                    </div>
                    <div class='form-group'>
                        <label>Security Code <i class='fas fa-key' style='color:#a94442'></i></label>
                        <input type='password' id='code' class='input-custom' placeholder='Enter Code'>
                    </div>
                </div>
                <div class='bundy-table'>
                    <div class='bundy-row row-blue'>IN <input type='radio' name='p' value='IN' checked></div>
                    <div class='bundy-row row-red'>LUNCH BREAK <input type='radio' name='p' value='LUNCH BREAK'></div>
                    <div class='bundy-row row-blue'>OUT <input type='radio' name='p' value='OUT'></div>
                </div>
                <button class='btn-punch' onclick='punch()'>PUNCH</button>
            </div>
        </div>
        <div class='card-custom'>
            <div class='card-header-portal'>EMPLOYEE LOGIN</div>
            <div class='card-body'>
                <div class='logo-box'><img src='https://hrweb.ph/assets/images/logo.png'></div>
                <form action='employee_portal/auth.php' method='POST' id='lform'>
                    <div class='form-group'>
                        <label>Username <i class='fas fa-user-circle'></i></label>
                        <input type='text' name='username' class='input-custom login-input' placeholder='Username' required autocomplete='username'>
                    </div>
                    <div class='form-group'>
                        <label>Password <i class='fas fa-lock'></i></label>
                        <input type='password' name='password' class='input-custom login-input' placeholder='Password' required autocomplete='current-password'>
                    </div>
                    <button type='submit' class='btn-portal'><i class='fas fa-sign-in-alt'></i> Portal Login</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'invalid') showNotif('Invalid credentials.', 'error');
        if (urlParams.get('error') === 'empty') showNotif('Please fill all fields.', 'error');
        if (urlParams.get('logout') === 'success') showNotif('Successfully logged out.', 'success');
        function showNotif(m, t) { const n = document.getElementById('notif'); n.innerText = m; n.className = 'notif-bar notif-' + t; n.style.display = 'block'; setTimeout(() => n.style.display='none', 5000); }
        function punch() {
            const eid = document.getElementById('eid').value; const code = document.getElementById('code').value; 
            const type = document.querySelector('input[name="p"]:checked').value;
            if(!eid || !code) return showNotif('Please fill Employee ID and Code', 'error');
            const fd = new FormData(); fd.append('employee_id', eid); fd.append('security_code', code); fd.append('punch_type', type);
            fetch('process_punch.php', { method: 'POST', body: fd }).then(r => r.json()).then(d => { showNotif(d.message, d.success ? 'success' : 'error'); });
        }
        function tick() {
            const d = new Date(); const s = d.getSeconds(); const m = d.getMinutes(); const h = d.getHours();
            document.getElementById('s').style.transform = 'translateX(-50%) rotate(' + (s*6) + 'deg)';
            document.getElementById('m').style.transform = 'translateX(-50%) rotate(' + (m*6) + 'deg)';
            document.getElementById('h').style.transform = 'translateX(-50%) rotate(' + (h*30 + m*0.5) + 'deg)';
        }
        setInterval(tick, 1000); tick();
        if (window.location.pathname.includes('/pages/')) {
            document.getElementById('lform').action = '../employee_portal/auth.php';
        }
    </script>
</body>
</html>
