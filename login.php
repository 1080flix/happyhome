<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ | HappyHOME POS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <style>
    body {
      background-color: #f4f6f9;
    }
    .login-box {
      width: 400px;
      margin: 8% auto;
    }
    .login-logo img {
      max-width: 80px;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>Happy</b>HOME</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">กรุณาเข้าสู่ระบบ</p>

        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_GET['error']) ?>
          </div>
        <?php endif; ?>

        <form action="check_login.php" method="POST">
          <div class="mb-3">
            <label>ชื่อผู้ใช้</label>
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
          </div>
          <div class="mb-3">
            <label>รหัสผ่าน</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="row">
            <div class="col-8"></div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

<!-- Scripts -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
