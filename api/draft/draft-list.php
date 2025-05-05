<?php
include('../../config/db.php');

try {
    // ดึงข้อมูลรายการพักบิลล่าสุด 5 รายการ
    $query = "
        SELECT d.id, d.draft_code, d.draft_date, d.total_price, c.customer_name,
               (SELECT COUNT(*) FROM sales_draft_detail WHERE draft_id = d.id) as item_count
        FROM sales_draft d
        LEFT JOIN customers c ON d.customer_id = c.id
        ORDER BY d.draft_date DESC
        LIMIT 5
    ";
    
    $result = mysqli_query($conn, $query);
    
    $drafts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // แปลงวันที่ให้อยู่ในรูปแบบที่ต้องการ
        $row['formatted_date'] = date('d/m/Y H:i', strtotime($row['draft_date']));
        $row['formatted_price'] = number_format($row['total_price'], 2);
        $drafts[] = $row;
    }
    
    echo json_encode(['success' => true, 'drafts' => $drafts]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>