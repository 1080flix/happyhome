<?php
include('../../config/db.php');

// รับค่าจากฟอร์ม
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

// ✅ ตรวจสอบว่ารหัสสินค้าซ้ำหรือไม่
$check = $conn->prepare("SELECT id FROM products WHERE product_code = ?");
$check->bind_param("s", $product_code);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    header("Location: ../../modules/product/product-add.php?error=duplicate_code&code=" . urlencode($product_code));
    exit();
}
$check->close();

// ✅ ตรวจสอบ barcode ซ้ำ (ถ้ามีกรอก)
$barcode = $barcode !== '' ? $barcode : null;
if ($barcode !== null) {
    $check = $conn->prepare("SELECT id FROM products WHERE barcode = ?");
    $check->bind_param("s", $barcode);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: ../../modules/product/product-add.php?error=duplicate_barcode&barcode=" . urlencode($barcode));
        exit();
    }

    $check->close();
}

// ✅ อัปโหลดรูป
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "../../upload/product-images/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = "upload/product-images/" . $image_name;
    }
}

// ✅ INSERT
$stmt = $conn->prepare("INSERT INTO products 
(product_code, product_name, barcode, cost_price, sale_price, unit, stock_qty, category_id, product_type, image, description)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sssddssisss",
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
    $description
);

if ($stmt->execute()) {
    header("Location: ../../modules/product/product-list.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
