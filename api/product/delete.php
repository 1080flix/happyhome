<?php
include('../../config/db.php');

$id = $_GET['id'] ?? 0;
if (!is_numeric($id) || $id <= 0) {
    die("ID ไม่ถูกต้อง");
}

// ดึงชื่อไฟล์ภาพ
$result = $conn->query("SELECT image FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if ($product && !empty($product['image'])) {
    $image_path = "../../" . $product['image'];
    if (file_exists($image_path)) {
        unlink($image_path); // ลบรูปจริง
    }
}

// ลบข้อมูลสินค้า
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// กลับไปหน้า list
header("Location: ../../modules/product/product-list.php");
exit;
?>
