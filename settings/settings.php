<?php
$base_path = '../';
include($base_path . 'config/check_login.php');
include($base_path . 'config/db.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');

// โหลดข้อมูลจาก DB
$sql = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($conn, $sql);
$settings = mysqli_fetch_assoc($result);
?>

<!-- เริ่ม content-wrapper -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h4 class="mb-3 mt-2"><i class="fas fa-cogs"></i> ตั้งค่าระบบ</h4>
    </div>
  </div>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <form action="settings-save.php" method="POST" enctype="multipart/form-data">
        <div class="card mb-3">
          <div class="card-header bg-light">
            <strong><i class="fas fa-building"></i> ข้อมูลบริษัท</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>ชื่อบริษัท:</label>
                <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($settings['company_name']) ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label>เลขประจำตัวผู้เสียภาษี:</label>
                <input type="text" name="tax_id" class="form-control" value="<?= htmlspecialchars($settings['tax_id']) ?>">
              </div>
              <div class="col-md-12 mb-3">
                <label>ที่อยู่:</label>
                <textarea name="address" class="form-control"><?= htmlspecialchars($settings['address']) ?></textarea>
              </div>
              <div class="col-md-4 mb-3">
                <label>เบอร์โทร:</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($settings['phone']) ?>">
              </div>
              <div class="col-md-4 mb-3">
                <label>โทรสาร:</label>
                <input type="text" name="fax" class="form-control" value="<?= htmlspecialchars($settings['fax']) ?>">
              </div>
              <div class="col-md-4 mb-3">
                <label>อีเมล:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($settings['email']) ?>">
              </div>
              <div class="col-md-12">
                <label>โลโก้บริษัท:</label><br>
                <?php if (!empty($settings['logo'])): ?>
                  <img src="<?= $base_path ?>upload/settings/<?= $settings['logo'] ?>" alt="โลโก้" class="img-thumbnail mb-2" style="max-height:120px;">
                <?php endif; ?>
                <input type="file" name="logo" class="form-control-file mt-2">
                <small class="form-text text-muted">ไฟล์ควรเป็น .jpg / .png และไม่เกิน 1MB</small>
              </div>
            </div>
          </div>
        </div>

        <div class="text-right mb-4">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> บันทึกการตั้งค่า
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
<!-- จบ content-wrapper -->

<?php include($base_path . 'includes/footer.php'); ?>
