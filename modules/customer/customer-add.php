<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">เพิ่มลูกค้าใหม่</h1>
      <a href="customer-list.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> ย้อนกลับ
      </a>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <?php include('customer-form.php'); ?>
    </div>
  </section>
</div>

<?php include($base_path . 'includes/footer.php'); ?>
