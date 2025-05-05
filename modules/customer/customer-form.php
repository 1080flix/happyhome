<?php
$is_edit = isset($customer);
$action = $is_edit ? '../../api/customer/update.php' : '../../api/customer/save.php';
?>

<form action="<?= $action ?>" method="POST">
  <div class="card">
    <div class="card-body row">

      <div class="form-group col-md-6">
        <label>ชื่อลูกค้า</label>
        <input type="text" name="customer_name" class="form-control" value="<?= $customer['customer_name'] ?? '' ?>" required>
      </div>

      <div class="form-group col-md-6">
        <label>ประเภทลูกค้า</label>
        <select name="customer_type" class="form-control" required>
          <option value="">-- เลือก --</option>
          <option value="บุคคล" <?= ($customer['customer_type'] ?? '') === 'บุคคล' ? 'selected' : '' ?>>บุคคล</option>
          <option value="บริษัท" <?= ($customer['customer_type'] ?? '') === 'บริษัท' ? 'selected' : '' ?>>บริษัท</option>
        </select>
      </div>

      <div class="form-group col-md-4">
        <label>เลขประจำตัวผู้เสียภาษี</label>
        <input type="text" name="tax_id" class="form-control" value="<?= $customer['tax_id'] ?? '' ?>">
      </div>

      <div class="form-group col-md-4">
        <label>เบอร์โทร</label>
        <input type="text" name="phone" class="form-control" value="<?= $customer['phone'] ?? '' ?>">
      </div>

      <div class="form-group col-md-4">
        <label>อีเมล</label>
        <input type="email" name="email" class="form-control" value="<?= $customer['email'] ?? '' ?>">
      </div>

      <div class="form-group col-md-12">
        <label>ที่อยู่</label>
        <textarea name="address" class="form-control" rows="2"><?= $customer['address'] ?? '' ?></textarea>
      </div>

      <div class="form-group col-md-6">
        <label>สาขา</label>
        <input type="text" name="branch" class="form-control" value="<?= $customer['branch'] ?? '' ?>">
      </div>

      <div class="form-group col-md-6">
        <label>ชื่อผู้ติดต่อ</label>
        <input type="text" name="contact_name" class="form-control" value="<?= $customer['contact_name'] ?? '' ?>">
      </div>

      <div class="form-group col-md-6">
        <label>เบอร์ผู้ติดต่อ</label>
        <input type="text" name="contact_phone" class="form-control" value="<?= $customer['contact_phone'] ?? '' ?>">
      </div>

      <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?= $customer['id'] ?>">
      <?php endif; ?>

    </div>

    <div class="card-footer text-right">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> บันทึกข้อมูล
      </button>
      <a href="customer-list.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
  </div>
</form>
