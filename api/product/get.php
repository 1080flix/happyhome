<?php
include('../../config/db.php');

header('Content-Type: application/json');

$sql = "SELECT p.*, c.name AS category
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.id DESC";

$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
  $products[] = [
    'id' => $row['id'],
    'code' => $row['code'],
    'name' => $row['name'],
    'price' => (float)$row['price'],
    'unit' => $row['unit'],
    'stock' => (int)$row['stock'],
    'category' => $row['category']
  ];
}

echo json_encode($products);
