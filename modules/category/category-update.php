<?php
include('../../config/db.php');

$id = $_POST['id'] ?? null;
$category_name = $_POST['category_name'] ?? '';
$description = $_POST['description'] ?? '';

if ($id && $category_name) {
  $stmt = $conn->prepare("UPDATE categories SET category_name = ?, description = ? WHERE id = ?");
  $stmt->bind_param("ssi", $category_name, $description, $id);
  $stmt->execute();
}

header("Location: category-list.php");
exit();
