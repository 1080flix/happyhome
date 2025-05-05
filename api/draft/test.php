<?php
include('../../config/db.php');
header('Content-Type: application/json');

// ทดสอบการเชื่อมต่อกับฐานข้อมูล
if (mysqli_connect_errno()) {
    echo json_encode([
        'success' => false, 
        'message' => 'เชื่อมต่อฐานข้อมูลล้มเหลว: ' . mysqli_connect_error()
    ]);
    exit;
}

// ดูว่ามีการสร้างตาราง sales_draft หรือไม่
$result = mysqli_query($conn, "SHOW TABLES LIKE 'sales_draft'");
if (mysqli_num_rows($result) > 0) {
    $has_table = true;
} else {
    $has_table = false;
}

// ตรวจสอบว่ามีข้อมูลในตาราง sales_draft หรือไม่
$count = 0;
if ($has_table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM sales_draft");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
    }
}

echo json_encode([
    'success' => true,
    'has_table' => $has_table,
    'record_count' => $count,
    'version' => 'test-1.0',
    'php_version' => PHP_VERSION,
    'current_time' => date('Y-m-d H:i:s')
]);
?>