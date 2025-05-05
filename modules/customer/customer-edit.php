<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
include($base_path . 'config/db.php');

// ดึงข้อมูลลูกค้า
$id = $_GET['id'] ?? 0;
if (!is_numeric($id)) {
  echo "<div class='alert alert-danger'>ไม่พบข้อมูล</div>"; exit;
}

$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
  echo "<div class='alert alert-danger'>ไม่พบข้อมูล</div>"; exit;
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">แก้ไขลูกค้า: <?= htmlspecialchars($customer['customer_name']) ?></h1>
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
