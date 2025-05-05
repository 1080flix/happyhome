<?php
include('../../config/db.php');
$is_edit = isset($product);
$action = $is_edit ? '../../api/product/update.php' : '../../api/product/save.php';
$cats = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
?>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-4">
          <label>รหัสสินค้า</label>
          <input type="text" name="product_code" class="form-control" value="<?= $product['product_code'] ?? '' ?>" required>
        </div>

        <div class="form-group col-md-4">
          <label>ชื่อสินค้า</label>
          <input type="text" name="product_name" class="form-control" value="<?= $product['product_name'] ?? '' ?>" required>
        </div>

        <div class="form-group col-md-4">
          <label>บาร์โค้ด</label>
          <input type="text" name="barcode" class="form-control" value="<?= $product['barcode'] ?? '' ?>">
        </div>

        <div class="form-group col-md-4">
          <label>ราคาทุน</label>
          <input type="number" step="0.01" name="cost_price" class="form-control" value="<?= $product['cost_price'] ?? '' ?>">
        </div>

        <div class="form-group col-md-4">
          <label>ราคาขาย</label>
          <input type="number" step="0.01" name="sale_price" class="form-control" value="<?= $product['sale_price'] ?? '' ?>">
        </div>

        <div class="form-group col-md-4">
          <label>หน่วยสินค้า</label>
          <input type="text" name="unit" class="form-control" value="<?= $product['unit'] ?? '' ?>">
        </div>

        <div class="form-group col-md-4">
          <label>จำนวนคงเหลือ</label>
          <input type="number" name="stock_qty" class="form-control" value="<?= $product['stock_qty'] ?? '' ?>">
        </div>

        <div class="form-group col-md-4">
          <label>หมวดหมู่</label>
          <select name="category_id" class="form-control">
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php while ($cat = $cats->fetch_assoc()): ?>
              <option value="<?= $cat['id'] ?>"
                <?= (isset($product['category_id']) ? ($product['category_id'] == $cat['id'] ? 'selected' : '') : ($cat['id'] == 1 ? 'selected' : '')) ?>>
                <?= htmlspecialchars($cat['category_name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group col-md-4">
          <label>ประเภทสินค้า</label>
          <select name="product_type" class="form-control">
            <option value="stock" <?= isset($product['product_type']) && $product['product_type'] === 'stock' ? 'selected' : '' ?>>สินค้านับสต๊อก</option>
            <option value="non-stock" <?= isset($product['product_type']) && $product['product_type'] === 'non-stock' ? 'selected' : '' ?>>สินค้าไม่นับสต๊อก</option>
            <option value="service" <?= isset($product['product_type']) && $product['product_type'] === 'service' ? 'selected' : '' ?>>สินค้าบริการ</option>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label>อัปโหลดรูปภาพสินค้า</label>
          <input type="file" name="image" class="form-control-file" id="imageInput" accept="image/*">

          <?php if ($is_edit && !empty($product['image'])): ?>
            <div class="mt-2">
              <img src="../../<?= $product['image'] ?>" class="img-thumbnail" style="max-width: 200px;">
              <br>
              <a href="product-delete-image.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger mt-2"
                 onclick="return confirm('ลบรูปภาพนี้หรือไม่?')">ลบรูป</a>
            </div>
          <?php else: ?>
            <div id="previewWrapper" class="mt-2" style="display:none;">
              <img id="imagePreview" src="#" class="img-thumbnail mb-2" style="max-width: 200px;">
              <br>
              <button type="button" class="btn btn-sm btn-danger" id="removeImageBtn">ลบรูป</button>
            </div>
          <?php endif; ?>
        </div>

        <div class="form-group col-md-12">
          <label>รายละเอียดสินค้า</label>
          <textarea name="description" rows="4" class="form-control"><?= $product['description'] ?? '' ?></textarea>
        </div>

        <?php if ($is_edit): ?>
          <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php endif; ?>
      </div>
    </div>

    <div class="card-footer text-right">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> บันทึกข้อมูล
      </button>
      <a href="product-list.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
  </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById('imageInput');
  const previewWrapper = document.getElementById('previewWrapper');
  const preview = document.getElementById('imagePreview');
  const removeBtn = document.getElementById('removeImageBtn');

  if (input) {
    input.addEventListener('change', function (event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          previewWrapper.style.display = 'block';
        }
        reader.readAsDataURL(file);
      } else {
        previewWrapper.style.display = 'none';
        preview.src = '#';
      }
    });
  }

  if (removeBtn) {
    removeBtn.addEventListener('click', function () {
      input.value = '';
      preview.src = '#';
      previewWrapper.style.display = 'none';
    });
  }
});
</script>
