<?php
if (!isset($base_path)) {
  $base_path = './';
}
include($base_path . 'config/db.php');

// โหลด logo จาก settings
$setting_sql = "SELECT logo FROM settings WHERE id = 1";
$setting_result = mysqli_query($conn, $setting_sql);
$setting_data = mysqli_fetch_assoc($setting_result);
$logo = !empty($setting_data['logo']) ? $setting_data['logo'] : 'logo.png';
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- โลโก้ -->
  <a href="<?= $base_path ?>dashboard.php" class="brand-link">
  <img src="<?= $base_path ?>upload/settings/<?= $logo ?>" alt="โลโก้" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">HappyHOME POS</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- เมนูหลัก -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?= $base_path ?>dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>แดชบอร์ด</p>
          </a>
        </li>

        <!-- สินค้า -->
        <li class="nav-item">
          <a href="<?= $base_path ?>modules/product/product-list.php" class="nav-link">
            <i class="nav-icon fas fa-box"></i>
            <p>จัดการสินค้า</p>
          </a>
        </li>

        <!-- หมวดหมู่สินค้า -->
        <li class="nav-item">
          <a href="<?= $base_path ?>modules/category/category-list.php" class="nav-link">
            <i class="nav-icon fas fa-tags"></i>
            <p>หมวดหมู่สินค้า</p>
          </a>
        </li>

        <!-- ลูกค้า -->
        <li class="nav-item">
          <a href="<?= $base_path ?>modules/customer/customer-list.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>ลูกค้า</p>
          </a>
        </li>

        <!-- ขายหน้าร้าน (POS) -->
        <li class="nav-item">
          <a href="<?= $base_path ?>modules/pos/pos.php" class="nav-link">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>ขายหน้าร้าน (POS)</p>
          </a>
        </li>

          <!-- รายการขายทั้งหมด (POS) -->
          <li class="nav-item">
          <a href="<?= $base_path ?>modules/pos/pos-list.php" class="nav-link">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>รายการขายทั้งหมด (POS)</p>
          </a>
        </li>

        <!-- ใบกำกับภาษี -->
        <li class="nav-item">
          <a href="<?= $base_path ?>modules/invoice/invoice-list.php" class="nav-link">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>ใบกำกับภาษี</p>
          </a>
        </li>

        <!-- ใบส่งของ -->
        <li class="nav-item">
          <a href="<?= $base_path ?>modules/delivery/delivery-list.php" class="nav-link">
            <i class="nav-icon fas fa-truck"></i>
            <p>ใบส่งของ</p>
          </a>
        </li>

        <!-- ใบวางบิล -->
        <li class="nav-item">
          <a href="<?= $base_path ?>modules/billing/billing-list.php" class="nav-link">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>ใบวางบิล</p>
          </a>
        </li>

        <!-- ตั้งค่าระบบ -->
        <li class="nav-item">
          <a href="<?= $base_path ?>settings/settings.php" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>ตั้งค่าระบบ</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
