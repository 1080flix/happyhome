<?php
$category_name = $category['category_name'] ?? '';
$description = $category['description'] ?? '';
?>

<form method="POST" action="<?= isset($category['id']) ? 'category-update.php' : 'category-save.php' ?>">
  <?php if (isset($category['id'])): ?>
    <input type="hidden" name="id" value="<?= $category['id'] ?>">
  <?php endif; ?>

  <div class="form-group">
    <label>ชื่อหมวดหมู่สินค้า:</label>
    <input type="text" name="category_name" class="form-control" value="<?= htmlspecialchars($category_name) ?>" required>
  </div>

  <div class="form-group">
    <label>คำอธิบาย:</label>
    <textarea name="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
  </div>

  <button type="submit" class="btn btn-primary">
    <i class="fas fa-save"></i> บันทึกข้อมูล
  </button>
</form>
