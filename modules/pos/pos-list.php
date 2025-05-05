<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');
include($base_path . 'config/db.php');
include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');

$search_code = $_GET['search_code'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// เงื่อนไขค้นหา
$conditions = "WHERE 1 ";
$params = [];
$types = '';

// ค้นหาเลขบิล
if (!empty($search_code)) {
  $conditions .= "AND s.sale_code LIKE ? ";
  $params[] = '%' . $search_code . '%';
  $types .= 's';
}

// ค้นหาช่วงวันที่
if (!empty($start_date) && !empty($end_date)) {
  $conditions .= "AND DATE(s.sale_date) BETWEEN ? AND ? ";
  $params[] = $start_date;
  $params[] = $end_date;
  $types .= 'ss';
} elseif (!empty($start_date)) {
  $conditions .= "AND DATE(s.sale_date) >= ? ";
  $params[] = $start_date;
  $types .= 's';
} elseif (!empty($end_date)) {
  $conditions .= "AND DATE(s.sale_date) <= ? ";
  $params[] = $end_date;
  $types .= 's';
}

// ดึงจำนวนทั้งหมด
$count_sql = "SELECT COUNT(*) as total FROM sales s LEFT JOIN customers c ON s.customer_id = c.id $conditions";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
  $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_assoc();
$total_rows = $count_result['total'];
$total_pages = ceil($total_rows / $limit);

// ดึงรายการที่ต้องแสดง
$sql = "SELECT s.*, c.customer_name FROM sales s 
        LEFT JOIN customers c ON s.customer_id = c.id 
        $conditions
        ORDER BY s.sale_date DESC 
        LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="content-wrapper p-3">
  <h4><i class="fas fa-list"></i> รายการขายทั้งหมด</h4>

  <!-- ค้นหา -->
  <form class="form-inline mb-3" method="GET">
    <div class="form-group mr-2">
      <input type="text" name="search_code" class="form-control" placeholder="เลขที่บิล" value="<?= htmlspecialchars($search_code) ?>">
    </div>
    <div class="form-group mr-2">
      <label class="mr-1">จาก</label>
      <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
    </div>
    <div class="form-group mr-2">
      <label class="mr-1">ถึง</label>
      <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> ค้นหา</button>
    <a href="pos-list.php" class="btn btn-secondary ml-2">รีเซ็ต</a>
  </form>

  <!-- ตารางรายการ -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="thead-light">
        <tr>
          <th>#</th>
          <th>เลขที่เอกสาร</th>
          <th>วันที่</th>
          <th>ลูกค้า</th>
          <th>ยอดรวม</th>
          <th>วิธีชำระเงิน</th>
          <th>สถานะ</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php $i = $offset + 1; ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['sale_code']) ?></td>
              <td><?= htmlspecialchars($row['sale_date']) ?></td>
              <td><?= htmlspecialchars($row['customer_name'] ?? 'Walk-in') ?></td>
              <td class="text-right"><?= number_format($row['total_price'], 2) ?></td>
              <td><?= ucfirst($row['payment_method']) ?></td>
              <td><?= ucfirst($row['status']) ?></td>
              <td>
                <a href="pos-print.php?sale_id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-info">
                  <i class="fas fa-print"></i> พิมพ์
                </a>
                <a href="tax-invoice-add.php?sale_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                  <i class="fas fa-file-invoice"></i> ใบกำกับภาษี
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?>&search_code=<?= $search_code ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">ก่อนหน้า</a>
          </li>
        <?php endif; ?>
        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
          <li class="page-item <?= ($p == $page ? 'active' : '') ?>">
            <a class="page-link" href="?page=<?= $p ?>&search_code=<?= $search_code ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>"><?= $p ?></a>
          </li>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1 ?>&search_code=<?= $search_code ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">ถัดไป</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<?php include($base_path . 'includes/footer.php'); ?>
