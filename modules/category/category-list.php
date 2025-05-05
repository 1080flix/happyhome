<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'config/db.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');

// รับค่าค้นหา
$search = $_GET['search'] ?? '';

// WHERE เงื่อนไข
$where = '';
if (!empty($search)) {
  $escaped = mysqli_real_escape_string($conn, $search);
  $where = "WHERE category_name LIKE '%$escaped%'";
}

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $per_page;

// จำนวนรายการทั้งหมด
$count_sql = "SELECT COUNT(*) FROM categories $where";
$total_rows = mysqli_fetch_row(mysqli_query($conn, $count_sql))[0];
$total_pages = ceil($total_rows / $per_page);

// รายการ
$sql = "SELECT * FROM categories $where ORDER BY id DESC LIMIT $start, $per_page";
$result = mysqli_query($conn, $sql);
?>

<div class="content-wrapper">
  <div class="container-fluid pt-3">
    <h4 class="mb-3"><i class="fas fa-tags"></i> รายการหมวดหมู่สินค้า</h4>

    <!-- ค้นหา -->
    <form method="GET" class="form-inline mb-3">
      <input type="text" name="search" class="form-control mr-2" placeholder="ค้นหาหมวดหมู่" value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search"></i> ค้นหา</button>
      <a href="category-add.php" class="btn btn-success"><i class="fas fa-plus"></i> เพิ่มหมวดหมู่</a>
    </form>

    <!-- ตาราง -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-sm">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>ชื่อหมวดหมู่</th>
            <th>คำอธิบาย</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = $start + 1; ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['category_name']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td>
                <a href="category-edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                <a href="category-delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบ?')"><i class="fas fa-trash-alt"></i></a>
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
