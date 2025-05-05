<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');

$error = $_GET['error'] ?? '';
$code = $_GET['code'] ?? '';
$barcode_error = $_GET['barcode'] ?? '';
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">เพิ่มสินค้าใหม่</h1>
      <a href="product-list.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> ย้อนกลับ
      </a>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <?php if ($error === 'duplicate_code'): ?>
        <div class="alert alert-danger">
          รหัสสินค้านี้ "<strong><?= htmlspecialchars($code) ?></strong>" มีอยู่ในระบบแล้ว กรุณาเปลี่ยนรหัสสินค้า
        </div>
      <?php elseif ($error === 'duplicate_barcode'): ?>
        <div class="alert alert-danger">
          บาร์โค้ด "<strong><?= htmlspecialchars($barcode_error) ?></strong>" มีอยู่ในระบบแล้ว กรุณาเปลี่ยนบาร์โค้ด
        </div>
      <?php endif; ?>

      <?php include('product-form.php'); ?>

    </div>
  </section>
</div>

<?php include($base_path . 'includes/footer.php'); ?>
