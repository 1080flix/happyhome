<?php
include('../../config/db.php');

$search = $_GET['search'] ?? '';
$category_id = $_GET['category_id'] ?? '';

$where = "WHERE 1";
if ($search !== '') {
  $safe = $conn->real_escape_string($search);
  $where .= " AND (p.product_code LIKE '%$safe%' OR p.product_name LIKE '%$safe%')";
}
if ($category_id !== '') {
  $where .= " AND p.category_id = " . intval($category_id);
}

$sql = "SELECT p.product_code, p.product_name, p.unit, p.sale_price, p.stock_qty, c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        $where
        ORDER BY p.product_name ASC
        LIMIT 100";

$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
