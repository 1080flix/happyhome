<?php
// ตั้งค่า path พื้นฐาน หากยังไม่ได้กำหนด (ใช้สำหรับไฟล์ใน modules/)
if (!isset($base_path)) {
  $base_path = './'; // ใช้ใน dashboard.php, login.php
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>HappyHOME POS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $base_path ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="<?= $base_path ?>assets/plugins/bootstrap/css/bootstrap.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= $base_path ?>assets/dist/css/adminlte.min.css">
  <!-- Custom -->
  <link rel="stylesheet" href="<?= $base_path ?>assets/css/custom.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- ด้านซ้าย -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= $base_path ?>dashboard.php" class="nav-link">หน้าหลัก</a>
    </li>
  </ul>

  <!-- ด้านขวา -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <span class="nav-link text-muted">
        ผู้ใช้: <?= $_SESSION['username'] ?? 'ไม่ทราบชื่อ' ?>
      </span>
    </li>
    <li class="nav-item">
      <a class="nav-link text-danger" href="<?= $base_path ?>logout.php" title="ออกจากระบบ">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </li>
  </ul>
</nav>
