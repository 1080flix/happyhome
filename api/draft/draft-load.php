<?php
include('../../config/db.php');

// ตรวจสอบว่ามี parameter draft_id
if (!isset($_GET['draft_id']) || !is_numeric($_GET['draft_id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบ draft_id']);
    exit;
}

$draft_id = intval($_GET['draft_id']);

try {
    // ดึงข้อมูลหลักของพักบิล
    $query = "SELECT d.*, c.customer_name, c.customer_type, c.tax_id, c.phone, c.email,
              c.address, c.branch, c.contact_name, c.contact_phone
              FROM sales_draft d
              LEFT JOIN customers c ON d.customer_id = c.id
              WHERE d.id = $draft_id";
    
    $result = mysqli_query($conn, $query);
    $draft = mysqli_fetch_assoc($result);
    
    if (!$draft) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลพักบิล']);
        exit;
    }
    
    // ดึงข้อมูลรายการสินค้า
    $query = "SELECT * FROM sales_draft_detail WHERE draft_id = $draft_id";
    $result = mysqli_query($conn, $query);
    
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    
    // แปลงข้อมูลรายการสินค้าให้อยู่ในรูปแบบที่ต้องการ
    $products = [];
    foreach ($items as $item) {
        $products[] = [
            'product_code' => $item['product_code'],
            'product_name' => $item['product_name'],
            'unit' => $item['unit'],
            'sale_price' => floatval($item['sale_price']),
            'qty' => intval($item['qty'])
        ];
    }
    
    // สร้างข้อมูลลูกค้า
    $customer = [
        'id' => $draft['customer_id'],
        'customer_name' => $draft['customer_name'],
        'customer_type' => $draft['customer_type'],
        'tax_id' => $draft['tax_id'],
        'phone' => $draft['phone'],
        'email' => $draft['email'],
        'address' => $draft['address'],
        'branch' => $draft['branch'],
        'contact_name' => $draft['contact_name'],
        'contact_phone' => $draft['contact_phone']
    ];
    
    echo json_encode([
        'success' => true,
        'draft' => $draft,
        'products' => $products,
        'customer' => $customer
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>