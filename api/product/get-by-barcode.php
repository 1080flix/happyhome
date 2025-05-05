<?php
include('../../config/db.php');

$barcode = $_GET['barcode'] ?? '';
$barcode = trim($barcode);

if ($barcode === '') {
  http_response_code(400);
  echo json_encode(['error' => 'barcode is required']);
  exit;
}

$stmt = $conn->prepare("
  SELECT p.*, c.category_name 
  FROM products p 
  LEFT JOIN categories c ON p.category_id = c.id 
  WHERE p.barcode = ?
  LIMIT 1
");
$stmt->bind_param("s", $barcode);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  header('Content-Type: application/json');
  echo json_encode($row);
} else {
  echo json_encode(['error' => 'not found']);
}
