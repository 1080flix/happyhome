<?php
include('../../config/db.php');

$category_name = $_POST['category_name'] ?? '';
$description = $_POST['description'] ?? '';

if ($category_name) {
  $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
  $stmt->bind_param("ss", $category_name, $description);
  $stmt->execute();
}

header("Location: category-list.php");
exit();
