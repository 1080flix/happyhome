<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
include($base_path . 'config/db.php');

// รับค่า ID จาก URL
$id = $_GET['id'] ?? null;

if (!$id) {
  header("Location: category-list.php");
  exit;
}

// ดึงข้อมูลหมวดหมู่จาก DB
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
  header("Location: category-list.php");
  exit;
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">แก้ไขหมวดหมู่สินค้า</h1>
      <a href="category-list.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> ย้อนกลับ
      </a>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <?php include('category-form.php'); ?>
    </div>
  </section>
</div>

<?php include($base_path . 'includes/footer.php'); ?>
