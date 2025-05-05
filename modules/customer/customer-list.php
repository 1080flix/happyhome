<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'config/db.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');

// ตัวแปรค้นหา
$search = $_GET['search'] ?? '';

// เงื่อนไข WHERE
$where = '';
if (!empty($search)) {
  $escaped = mysqli_real_escape_string($conn, $search);
  $where = "WHERE customer_name LIKE '%$escaped%' OR phone LIKE '%$escaped%'";
}

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $per_page;

// ดึงจำนวนทั้งหมด
$count_sql = "SELECT COUNT(*) FROM customers $where";
$count_result = mysqli_query($conn, $count_sql);
$total_rows = mysqli_fetch_row($count_result)[0];
$total_pages = ceil($total_rows / $per_page);

// ดึงรายการตามหน้า
$sql = "SELECT * FROM customers $where ORDER BY id DESC LIMIT $start, $per_page";
$result = mysqli_query($conn, $sql);
?>

<div class="content-wrapper">
  <div class="container-fluid pt-3">
    <h4 class="mb-3"><i class="fas fa-users"></i> รายชื่อลูกค้าทั้งหมด</h4>

    <!-- ฟอร์มค้นหา -->
    <form method="GET" class="form-inline mb-3">
      <input type="text" name="search" class="form-control mr-2" placeholder="ค้นหาชื่อหรือเบอร์" value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search"></i> ค้นหา</button>
      <a href="customer-add.php" class="btn btn-success"><i class="fas fa-plus"></i> เพิ่มลูกค้าใหม่</a>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-hover table-sm">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>ชื่อลูกค้า</th>
            <th>เบอร์โทร</th>
            <th>อีเมล</th>
            <th>ประเภท</th>
            <th>เลขประจำตัวผู้เสียภาษี</th>
            <th>สาขา</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $i = $start + 1;
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $i++ . '</td>';
            echo '<td>' . htmlspecialchars($row['customer_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['customer_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['tax_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['branch']) . '</td>';
            echo '<td>
              <a href="customer-edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
              <a href="customer-delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'ยืนยันการลบลูกค้านี้?\')"><i class="fas fa-trash"></i></a>
              <a href="customer-history.php?id=' . $row['id'] . '" class="btn btn-sm btn-info"><i class="fas fa-history"></i></a>
            </td>';
            echo '</tr>';
          }
          ?>
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
