<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="content-wrapper">
  <!-- หัวข้อ -->
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">ขายหน้าร้าน (POS)</h1>
      <a href="pos-list.php" class="btn btn-secondary">
        <i class="fas fa-list"></i> รายการขายทั้งหมด
      </a>
    </div>
  </section>

  <!-- เนื้อหา -->
  <section class="content">
    <div class="container-fluid">
      <?php include('pos-form.php'); ?>
    </div>
  </section>
</div>

<!-- เพิ่มต่อท้ายไฟล์ pos.php ก่อนปิด body tag -->
<script>
// โหลดข้อมูลพักบิลอัตโนมัติเมื่อเปิดโมดัล
document.addEventListener('DOMContentLoaded', function() {
  // เปลี่ยนวิธีการเรียกใช้โมดัล
  var openDraftButton = document.querySelector('button[data-target="#pos-draft-list-modal"]');
  if (openDraftButton) {
    openDraftButton.addEventListener('click', function() {
      // เรียกฟังก์ชันโหลดข้อมูลหลังจากเปิดโมดัล 100ms
      setTimeout(function() {
        if (typeof loadDraftList === 'function') {
          loadDraftList();
        }
      }, 100);
    });
  }
});
</script>

<!-- include modal ค้นหาลูกค้าและสินค้า -->
<?php include($base_path . 'modal/pos-search-customer-modal.php'); ?>
<?php include($base_path . 'modal/pos-search-product-modal.php'); ?>
<?php include($base_path . 'modal/pos-draft-list-modal.php'); ?>
<?php include($base_path . 'includes/footer.php'); ?>
