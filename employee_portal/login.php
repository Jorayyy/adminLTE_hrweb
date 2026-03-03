<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Portal | Login</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <style>
    body { background: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; }
    .login-box { width: 400px; }
    .card { border-top: 5px solid #007bff; border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .btn-primary { background-color: #007bff; border: none; font-weight: bold; }
    .logo-container { text-align: center; margin-bottom: 20px; }
    .logo-img { height: 80px; }
    .login-card-body { padding: 40px; }
    .alert { font-size: 14px; margin-bottom: 20px; }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="logo-container">
    <img src="https://hrweb.ph/assets/images/logo.png" alt="HRWEB.PH" class="logo-img">
  </div>
  <div class="card card-outline card-primary shadow-lg border-0">
    <div class="card-header text-center bg-primary text-white py-3">
      <h3 class="mb-0 text-bold" style="font-size: 18px;">EMPLOYEE LOGIN</h3>
    </div>
    <div class="card-body login-card-body">
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible">
          <i class="icon fas fa-ban mr-2"></i>
          <?php 
            if ($_GET['error'] == 'empty') echo "Please enter both credentials.";
            else if ($_GET['error'] == 'invalid') echo "Invalid ID or password.";
          ?>
        </div>
      <?php endif; ?>
      
      <p class="login-box-msg text-muted mb-4">Sign in using your Employee ID</p>

      <form action="auth.php" method="post">
        <div class="form-group mb-3">
          <label class="small text-bold text-dark mb-1">Employee ID (Username)</label>
          <div class="input-group">
            <input type="text" name="username" class="form-control shadow-none" placeholder="Enter ID" required>
            <div class="input-group-append">
              <div class="input-group-text bg-light border-left-0"><span class="fas fa-user text-primary"></span></div>
            </div>
          </div>
        </div>
        <div class="form-group mb-4">
          <label class="small text-bold text-dark mb-1">Password (Employee ID)</label>
          <div class="input-group">
            <input type="password" name="password" class="form-control shadow-none" placeholder="Enter ID again" required>
            <div class="input-group-append">
              <div class="input-group-text bg-light border-left-0"><span class="fas fa-lock text-primary"></span></div>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block py-2">
          <i class="fas fa-sign-in-alt mr-2"></i> SIGN IN
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
