<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'config/db.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');

$id = $_GET['id'] ?? null;
if (!$id) {
  echo "<script>alert('ไม่พบรหัสบิล'); history.back();</script>";
  exit;
}

// โหลดข้อมูลการขาย
$sale = $conn->query("SELECT * FROM sales WHERE id = $id")->fetch_assoc();
$customer = $conn->query("SELECT * FROM customers WHERE id = {$sale['customer_id']}")->fetch_assoc();
$sale_items = $conn->query("SELECT * FROM sale_items WHERE sale_id = $id");
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0">แก้ไขบิลขาย: <?= htmlspecialchars($sale['sale_code']) ?></h1>
      <a href="pos-list.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> ย้อนกลับ
      </a>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <form action="pos-edit-save.php" method="POST" id="edit-sale-form">
        <input type="hidden" name="sale_id" value="<?= $sale['id'] ?>">

        <!-- ✅ ใช้ฟอร์ม POS แนวนอนแบบเดิม -->
        <?php include('pos-form.php'); ?>
      </form>
    </div>
  </section>
</div>

<?php include($base_path . 'includes/footer.php'); ?>

<!-- ✅ เติมค่าเริ่มต้นใน JS (ลูกค้า + สินค้า) -->
<script>
const cartItems = <?= json_encode(iterator_to_array($sale_items)) ?>;
const saleInfo = <?= json_encode($sale) ?>;
const customerInfo = <?= json_encode($customer) ?>;

// TODO: สั่งให้ JS เติมข้อมูลทั้งหมดลง form หลังโหลดเสร็จ เช่น:
// fillCustomer(customerInfo);
// fillCart(cartItems);
// fillSaleInfo(saleInfo);
</script>
