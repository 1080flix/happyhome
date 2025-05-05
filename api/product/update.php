<?php
include('../../config/db.php');

$id             = $_POST['id'];
$product_code   = $_POST['product_code'];
$product_name   = $_POST['product_name'];
$barcode        = trim($_POST['barcode']);
$cost_price     = $_POST['cost_price'];
$sale_price     = $_POST['sale_price'];
$unit           = $_POST['unit'];
$stock_qty      = $_POST['stock_qty'];
$product_type   = $_POST['product_type'];
$description    = $_POST['description'];
$category_id    = isset($_POST['category_id']) && is_numeric($_POST['category_id']) ? (int)$_POST['category_id'] : 1;

// ✅ ถ้า barcode เป็นค่าว่าง ให้เป็น NULL
$barcode = $barcode !== '' ? $barcode : null;

// ✅ ตรวจสอบ barcode ซ้ำ (เว้นสินค้าตัวเอง)
if ($barcode !== null) {
  $check = $conn->prepare("SELECT id FROM products WHERE barcode = ? AND id != ?");
  $check->bind_param("si", $barcode, $id);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    header("Location: ../../modules/product/product-edit.php?id=$id&error=duplicate_barcode&barcode=" . urlencode($barcode));
    exit();
  }

  $check->close();
}

// ✅ ดึงข้อมูลรูปเดิม
$result = $conn->query("SELECT image FROM products WHERE id = $id");
$row = $result->fetch_assoc();
$old_image_path = $row['image'] ?? '';
$image_path = $old_image_path;

// ✅ อัปโหลดรูปใหม่
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "../../upload/product-images/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        if (!empty($old_image_path) && file_exists("../../" . $old_image_path)) {
            unlink("../../" . $old_image_path);
        }
        $image_path = "upload/product-images/" . $image_name;
    }
}

// ✅ UPDATE
$stmt = $conn->prepare("UPDATE products SET 
  product_code = ?, 
  product_name = ?, 
  barcode = ?, 
  cost_price = ?, 
  sale_price = ?, 
  unit = ?, 
  stock_qty = ?, 
  category_id = ?, 
  product_type = ?, 
  image = ?, 
  description = ? 
  WHERE id = ?");

$stmt->bind_param(
    "sssddssisssi",
    $product_code,
    $product_name,
    $barcode,
    $cost_price,
    $sale_price,
    $unit,
    $stock_qty,
    $category_id,
    $product_type,
    $image_path,
    $description,
    $id
);

// ✅ รันคำสั่ง
if ($stmt->execute()) {
    header("Location: ../../modules/product/product-list.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
