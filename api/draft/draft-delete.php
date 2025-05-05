<?php
include('../../config/db.php');

// ตรวจสอบว่ามี parameter draft_id
if (!isset($_GET['draft_id']) || !is_numeric($_GET['draft_id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบ draft_id']);
    exit;
}

$draft_id = intval($_GET['draft_id']);

try {
    // เริ่ม transaction
    mysqli_begin_transaction($conn);
    
    // เก็บข้อมูลก่อนลบเพื่อส่งกลับ
    $query = "SELECT draft_code FROM sales_draft WHERE id = $draft_id";
    $result = mysqli_query($conn, $query);
    $draft = mysqli_fetch_assoc($result);
    
    // ลบข้อมูลรายการสินค้า (ไม่จำเป็นต้องลบหากใช้ CASCADE)
    $query = "DELETE FROM sales_draft_detail WHERE draft_id = $draft_id";
    mysqli_query($conn, $query);
    
    // ลบข้อมูลหลัก
    $query = "DELETE FROM sales_draft WHERE id = $draft_id";
    mysqli_query($conn, $query);
    
    // Commit transaction
    mysqli_commit($conn);
    
    echo json_encode([
        'success' => true, 
        'message' => 'ลบพักบิลเรียบร้อยแล้ว',
        'draft_code' => $draft ? $draft['draft_code'] : ''
    ]);
    
} catch (Exception $e) {
    // กรณีเกิดข้อผิดพลาด rollback การทำงาน
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>