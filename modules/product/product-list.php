<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'config/db.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');

// รับตัวแปรค้นหา
$search = $_GET['search'] ?? '';

// WHERE เงื่อนไข
$where = '';
if (!empty($search)) {
  $escaped = mysqli_real_escape_string($conn, $search);
  $where = "WHERE p.product_code LIKE '%$escaped%' OR p.product_name LIKE '%$escaped%' OR c.category_name LIKE '%$escaped%'";
}

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $per_page;

// จำนวนรายการทั้งหมด
$count_sql = "SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id = c.id $where";
$total_rows = mysqli_fetch_row(mysqli_query($conn, $count_sql))[0];
$total_pages = ceil($total_rows / $per_page);

// รายการ
$sql = "SELECT p.*, c.category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        $where 
        ORDER BY p.id DESC 
        LIMIT $start, $per_page";
$result = mysqli_query($conn, $sql);
?>

<div class="content-wrapper">
  <div class="container-fluid pt-3">
    <h4 class="mb-3"><i class="fas fa-box"></i> รายการสินค้า</h4>

    <!-- ค้นหา -->
    <form method="GET" class="form-inline mb-3">
      <input type="text" name="search" class="form-control mr-2" placeholder="ค้นหาชื่อสินค้า / รหัส" value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search"></i> ค้นหา</button>
      <a href="product-add.php" class="btn btn-success"><i class="fas fa-plus"></i> เพิ่มสินค้าใหม่</a>
    </form>

    <!-- ตารางสินค้า -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-sm">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>รหัสสินค้า</th>
            <th>ชื่อสินค้า</th>
            <th>หมวดหมู่</th>
            <th>ราคาขาย</th>
            <th>คงเหลือ</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = $start + 1; ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['product_code']) ?></td>
              <td><?= htmlspecialchars($row['product_name']) ?></td>
              <td><?= htmlspecialchars($row['category_name']) ?></td>
              <td class="text-right"><?= number_format($row['sale_price'], 2) ?></td>
              <td class="text-center"><?= $row['stock_qty'] ?></td>
              <td>
                <a href="product-edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                <a href="product-delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบ?')"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <nav>
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">« ก่อนหน้า</a></li>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
          <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $p ?>"><?= $p ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">ถัดไป »</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</div>

<?php include($base_path . 'includes/footer.php'); ?>
