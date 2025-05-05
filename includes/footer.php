<?php
// ตั้งค่า path พื้นฐาน ถ้ายังไม่ได้กำหนด
if (!isset($base_path)) {
  $base_path = './';
}
?>

<!-- Main Footer -->
<footer class="main-footer text-sm">
  <strong>© <?= date('Y') ?> HappyHOME</strong> - ระบบ POS ร้านวัสดุก่อสร้าง
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 1.0
  </div>
</footer>
</div> <!-- ปิด .wrapper -->

<!-- JS Scripts -->
<script src="<?= $base_path ?>assets/plugins/jquery/jquery.min.js"></script>
<script src="<?= $base_path ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base_path ?>assets/dist/js/adminlte.min.js"></script>
<script src="<?= $base_path ?>assets/js/custom.js"></script>
</body>
</html>
