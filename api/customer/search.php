<?php
include('../../config/db.php');

$keyword = $_GET['keyword'] ?? '';
$keyword = "%$keyword%";

$stmt = $conn->prepare("
  SELECT id, customer_name, customer_type, phone, address,
         contact_name, contact_phone, tax_id, branch, email
  FROM customers
  WHERE customer_name LIKE ? OR phone LIKE ?
  ORDER BY customer_name ASC
  LIMIT 50
");
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();

$customers = [];
while ($row = $result->fetch_assoc()) {
  $customers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($customers);
