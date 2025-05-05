<?php
include('../../config/db.php');

header('Content-Type: application/json');
$id = $_GET['id'] ?? null;

if (!$id) {
  echo json_encode(['success' => false, 'message' => 'ไม่พบ ID']);
  exit;
}

$stmt = $conn->prepare("SELECT * FROM sales WHERE id = ? AND status = 'draft'");
$stmt->bind_param("i", $id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();

if (!$sale) {
  echo json_encode(['success' => false, 'message' => 'ไม่พบร่างนี้']);
  exit;
}

$items = [];
$detail = $conn->prepare("SELECT d.*, p.name, p.code, p.unit FROM sales_detail d
                          LEFT JOIN products p ON d.product_id = p.id
                          WHERE d.sale_id = ?");
$detail->bind_param("i", $id);
$detail->execute();
$res = $detail->get_result();
while ($row = $res->fetch_assoc()) {
  $items[] = [
    'id' => $row['product_id'],
    'code' => $row['code'],
    'name' => $row['name'],
    'qty' => (int)$row['qty'],
    'price' => (float)$row['price'],
    'unit' => $row['unit']
  ];
}

echo json_encode([
  'success' => true,
  'sale' => [
    'id' => $sale['id'],
    'sale_code' => $sale['sale_code'],
    'discount' => (float)$sale['discount'],
    'note' => $sale['note']
  ],
  'items' => $items
]);
