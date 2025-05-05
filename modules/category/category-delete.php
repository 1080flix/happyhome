<?php
include('../../config/db.php');

$id = $_GET['id'] ?? null;
if ($id) {
  $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: category-list.php");
exit();
