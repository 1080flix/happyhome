<?php
include('../../config/db.php');

// ตรวจสอบว่าเป็น POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// สร้างรหัส draft อัตโนมัติ
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM sales_draft");
$row = mysqli_fetch_assoc($result);
$count = $row['count'] + 1;
$draft_code = 'DRAFT-' . str_pad($count, 3, '0', STR_PAD_LEFT);

// รับข้อมูลจาก POST
$customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 1;
$discount_value = isset($_POST['discount_value']) ? floatval($_POST['discount_value']) : 0;
$discount_type = isset($_POST['discount_type']) ? $_POST['discount_type'] : 'baht';
$vat_type = isset($_POST['vat_type']) ? $_POST['vat_type'] : 'none';
$vat_amount = isset($_POST['vat_amount']) ? floatval($_POST['vat_amount']) : 0;
$total_price = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash';
$note = isset($_POST['note']) ? $_POST['note'] : '';

// รับข้อมูลสินค้า
$products_json = isset($_POST['products']) ? $_POST['products'] : '[]';
$products = json_decode($products_json, true);

if (empty($products) || !is_array($products)) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบรายการสินค้า', 'data' => $products_json]);
    exit;
}

// เริ่ม transaction
mysqli_begin_transaction($conn);

try {
    // บันทึกข้อมูลหลัก
    $query = "INSERT INTO sales_draft (draft_code, customer_id, draft_date, discount_value, 
                       discount_type, vat_type, vat_amount, total_price, payment_method, note) 
                       VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sidssdsss", 
        $draft_code, 
        $customer_id, 
        $discount_value,
        $discount_type,
        $vat_type, 
        $vat_amount,
        $total_price,
        $payment_method,
        $note
    );
    
    mysqli_stmt_execute($stmt);
    $draft_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    
    // บันทึกรายการสินค้า
    foreach ($products as $product) {
        // ค้นหา product_id จาก product_code
        $product_code = mysqli_real_escape_string($conn, $product['product_code']);
        $result = mysqli_query($conn, "SELECT id FROM products WHERE product_code = '$product_code'");
        $product_id = 0;
        if ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['id'];
        }
        
        $product_name = mysqli_real_escape_string($conn, $product['product_name']);
        $unit = mysqli_real_escape_string($conn, $product['unit'] ?? '');
        $qty = intval($product['qty'] ?? 1);
        $sale_price = floatval($product['price'] ?? 0);
        
        $query = "INSERT INTO sales_draft_detail (draft_id, product_id, product_code, product_name, 
                                 unit, qty, sale_price) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iisssid", 
            $draft_id,
            $product_id,
            $product_code,
            $product_name,
            $unit,
            $qty,
            $sale_price
        );
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    echo json_encode([
        'success' => true, 
        'message' => 'บันทึกพักบิลเรียบร้อยแล้ว', 
        'draft_id' => $draft_id,
        'draft_code' => $draft_code
    ]);
    
} catch (Exception $e) {
    // กรณีเกิดข้อผิดพลาด rollback การทำงาน
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>