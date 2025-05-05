<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
include($base_path . 'config/db.php');

// รับ ID
$id = $_GET['id'] ?? 0;
if (!is_numeric($id)) {
  echo "<div class='alert alert-danger'>ไม่พบรหัสสินค้าที่ต้องการแก้ไข</div>";
  exit;
}

// ดึงข้อมูลสินค้า
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
  echo "<div class='alert alert-danger'>ไม่พบข้อมูลสินค้า</div>";
  exit;
}

$category_id = $product['category_id'] ?? null;
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">แก้ไขสินค้า: <?= htmlspecialchars($product['product_name']) ?></h1>
      <a href="product-list.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> ย้อนกลับ
      </a>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <?php if (isset($_GET['error']) && $_GET['error'] === 'duplicate_barcode'): ?>
        <div class="alert alert-danger">
          บาร์โค้ด <strong><?= htmlspecialchars($_GET['barcode']) ?></strong> ถูกใช้ไปแล้ว กรุณาใช้บาร์โค้ดอื่น
        </div>
      <?php endif; ?>

      <?php include('product-form.php'); ?>
    </div>
  </section>
</div>

<?php include($base_path . 'includes/footer.php'); ?>
