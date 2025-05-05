<?php
include('../../config/db.php');

$id = $_GET['id'] ?? 0;
if (!is_numeric($id) || $id == 1) {
  header("Location: customer-list.php");
  exit;
}

$stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: customer-list.php");
