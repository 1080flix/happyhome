<?php
include('../../config/db.php');

$id = $_GET['id'] ?? 0;
if (!$id || !is_numeric($id)) {
  die('ไม่พบรหัสสินค้า');
}

// ดึง path รูปเดิม
$result = $conn->query("SELECT image FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if ($product && !empty($product['image'])) {
  $image_path = "../../" . $product['image'];

  // ลบไฟล์จาก server
  if (file_exists($image_path)) {
    unlink($image_path);
  }

  // เคลียร์ค่าจาก DB
  $stmt = $conn->prepare("UPDATE products SET image = NULL WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

header("Location: product-edit.php?id=$id");
exit;
?>
