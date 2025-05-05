<?php
include('../../config/db.php');

$id = $_GET['id'] ?? null;
if (!$id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing product ID']);
  exit();
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode([
    'id' => $row['id'],
    'product_code' => $row['product_code'],
    'barcode' => $row['barcode'],
    'product_name' => $row['product_name'],
    'category_name' => $row['category_name'],
    'price' => $row['price'],
    'cost_price' => $row['cost_price'],
    'stock_qty' => $row['stock_qty'],
    'unit' => $row['unit'],
    'product_type' => $row['product_type'],
    'description' => $row['description'],
    'image_path' => $row['image_path']
  ]);
} else {
  http_response_code(404);
  echo json_encode(['error' => 'Product not found']);
}
