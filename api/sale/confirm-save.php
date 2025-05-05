<?php
require_once '../../config/datetime.php';
// ตั้งค่า error reporting เพื่อเห็นข้อผิดพลาดทั้งหมด (ใช้ในตอนพัฒนา)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// สร้างโฟลเดอร์ logs ถ้ายังไม่มี
if (!file_exists('../../logs')) {
    mkdir('../../logs', 0755, true);
}

// บันทึกข้อมูลที่ได้รับมาจาก POST เพื่อตรวจสอบ
file_put_contents('../../logs/pos_data.log', date('Y-m-d H:i:s') . " - POST data: " . json_encode($_POST) . "\n", FILE_APPEND);

// เชื่อมต่อฐานข้อมูล
require_once '../../config/db.php';

// ตรวจสอบว่ามีตัวแปร $pdo หรือไม่
if (!isset($pdo) || $pdo === null) {
  // ถ้าไม่มี ให้เชื่อมต่อฐานข้อมูลเอง
  $db_host = 'localhost';
  $db_name = 'happyhome';
  $db_user = 'root';
  $db_pass = '';
  
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    file_put_contents('../../logs/pos_error.log', date('Y-m-d H:i:s') . " - DB Error: " . $e->getMessage() . "\n", FILE_APPEND);
    die(json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]));
  }
}

header('Content-Type: application/json');

try {
  // รับค่าจาก Form
  $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 1;
  $sale_code = 'S' . date('YmdHis');
  $note = isset($_POST['note']) ? $_POST['note'] : '';
  $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash';
  $discount_value = isset($_POST['discount_value']) ? floatval($_POST['discount_value']) : 0;
  $discount_type = isset($_POST['discount_type']) ? $_POST['discount_type'] : 'baht';
  $vat_type = isset($_POST['vat_type']) ? $_POST['vat_type'] : 'none';
  $received_amount = isset($_POST['received_amount']) ? floatval($_POST['received_amount']) : 0;
  $change_amount = isset($_POST['change_amount']) ? floatval($_POST['change_amount']) : 0;
  $transfer_account = isset($_POST['transfer_account']) ? $_POST['transfer_account'] : null;
  $credit_note = isset($_POST['credit_note']) ? $_POST['credit_note'] : null;
  $sale_date = getSQLDateTime();
  
  // รับรายการสินค้าจากฟอร์ม
  $products_json = isset($_POST['products']) ? $_POST['products'] : '[]';
  file_put_contents('../../logs/pos_products.log', date('Y-m-d H:i:s') . " - Products: " . $products_json . "\n", FILE_APPEND);
  
  $products = json_decode($products_json, true);
  
  // ตรวจสอบการ decode JSON
  if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception("ข้อมูลสินค้าไม่อยู่ในรูปแบบ JSON ที่ถูกต้อง: " . json_last_error_msg());
  }
  
  if (!is_array($products)) {
    throw new Exception("รูปแบบข้อมูลสินค้าไม่ถูกต้อง (ไม่ใช่ array)");
  }
  
  if (count($products) === 0) {
    throw new Exception("ไม่พบรายการสินค้า");
  }
  
  // เริ่มต้น transaction
  $pdo->beginTransaction();

  // คำนวณยอดรวมจากสินค้าที่ส่งมา
  $subtotal = 0;
  foreach ($products as $item) {
    if (!isset($item['qty']) || !isset($item['price'])) {
      throw new Exception("ข้อมูลสินค้าไม่ครบถ้วน");
    }
    $qty = intval($item['qty']);
    $price = floatval($item['price']);
    $subtotal += ($qty * $price);
  }

  // ส่วนลด
  $discount = ($discount_type === 'percent') ? $subtotal * ($discount_value / 100) : $discount_value;

  // คำนวณ VAT
  $vat_rate = 0.07;
  $vat = 0;
  $total_price = 0;

  if ($vat_type === 'inclusive') {
    $beforeVat = $subtotal - $discount;
    $vat = ($beforeVat * $vat_rate) / (1 + $vat_rate);
    $total_price = $beforeVat;
  } elseif ($vat_type === 'exclusive') {
    $beforeVat = $subtotal - $discount;
    $vat = $beforeVat * $vat_rate;
    $total_price = $beforeVat + $vat;
  } else {
    $total_price = $subtotal - $discount;
  }

  // ตรวจสอบฟิลด์ในตาราง sales
  $checkTableStmt = $pdo->prepare("SHOW COLUMNS FROM sales");
  $checkTableStmt->execute();
  $tableColumns = $checkTableStmt->fetchAll(PDO::FETCH_COLUMN);
  
  // เตรียม query ตามฟิลด์ที่มีจริงในตาราง
  $sql = "INSERT INTO sales (sale_code, customer_id, sale_date";
  $params = [$sale_code, $customer_id, $sale_date];
  $placeholders = "?, ?, ?";
  
  // ส่วนของข้อมูลทางการเงิน
  if (in_array('discount_value', $tableColumns)) {
    $sql .= ", discount_value";
    $placeholders .= ", ?";
    $params[] = $discount_value;
  }
  
  if (in_array('discount_type', $tableColumns)) {
    $sql .= ", discount_type";
    $placeholders .= ", ?";
    $params[] = $discount_type;
  }
  
  if (in_array('vat_type', $tableColumns)) {
    $sql .= ", vat_type";
    $placeholders .= ", ?";
    $params[] = $vat_type;
  }
  
  if (in_array('vat_amount', $tableColumns)) {
    $sql .= ", vat_amount";
    $placeholders .= ", ?";
    $params[] = $vat;
  }
  
  if (in_array('total_price', $tableColumns)) {
    $sql .= ", total_price";
    $placeholders .= ", ?";
    $params[] = $total_price;
  }
  
  // ข้อมูลการชำระเงิน
  if (in_array('payment_method', $tableColumns)) {
    $sql .= ", payment_method";
    $placeholders .= ", ?";
    $params[] = $payment_method;
  }
  
  if (in_array('transfer_account', $tableColumns)) {
    $sql .= ", transfer_account";
    $placeholders .= ", ?";
    $params[] = $transfer_account;
  }
  
  if (in_array('credit_note', $tableColumns)) {
    $sql .= ", credit_note";
    $placeholders .= ", ?";
    $params[] = $credit_note;
  }
  
  if (in_array('received_amount', $tableColumns)) {
    $sql .= ", received_amount";
    $placeholders .= ", ?";
    $params[] = $received_amount;
  }
  
  if (in_array('change_amount', $tableColumns)) {
    $sql .= ", change_amount";
    $placeholders .= ", ?";
    $params[] = $change_amount;
  }
  
  if (in_array('note', $tableColumns)) {
    $sql .= ", note";
    $placeholders .= ", ?";
    $params[] = $note;
  }
  
  if (in_array('created_at', $tableColumns)) {
    $sql .= ", created_at";
    $placeholders .= ", ?";
    $params[] = $sale_date;
  }
  
  // ตรวจสอบฟิลด์ status
  if (in_array('status', $tableColumns)) {
    $sql .= ", status";
    $placeholders .= ", ?";
    $params[] = 'completed';
  }
  
  // สร้าง query สมบูรณ์
  $sql .= ") VALUES (" . $placeholders . ")";
  
  // เพิ่มข้อมูลในตาราง sales
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  $sale_id = $pdo->lastInsertId();
  
  // ตรวจสอบฟิลด์ในตาราง sale_detail
  $checkDetailTableStmt = $pdo->prepare("SHOW COLUMNS FROM sale_detail");
  $checkDetailTableStmt->execute();
  $detailColumns = $checkDetailTableStmt->fetchAll(PDO::FETCH_COLUMN);

  // เพิ่มสินค้ารายการย่อย
  foreach ($products as $item) {
    // ตรวจสอบข้อมูลสินค้า
    if (empty($item['product_code'])) {
      throw new Exception("ไม่พบรหัสสินค้าในรายการ");
    }
    
    $product_code = $item['product_code'];
    $product_name = isset($item['product_name']) ? $item['product_name'] : '';
    $unit = isset($item['unit']) ? $item['unit'] : '';
    $qty = isset($item['qty']) ? intval($item['qty']) : 0;
    $price = isset($item['price']) ? floatval($item['price']) : 0;
    
    if ($qty <= 0) {
      throw new Exception("จำนวนสินค้าไม่ถูกต้อง: " . $product_code);
    }
    
    // ค้นหา product_id จาก product_code
    $stmt = $pdo->prepare("SELECT id, product_type FROM products WHERE product_code = ?");
    $stmt->execute([$product_code]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
      throw new Exception("ไม่พบสินค้ารหัส: " . $product_code);
    }
    
    $product_id = $product['id'];
    $product_type = $product['product_type'];
    
    // สร้าง SQL สำหรับ sale_detail ตามฟิลด์ที่มีจริงในตาราง
    $detailSQL = "INSERT INTO sale_detail (sale_id, product_id";
    $detailParams = [$sale_id, $product_id];
    $detailPlaceholders = "?, ?";
    
    if (in_array('product_code', $detailColumns)) {
      $detailSQL .= ", product_code";
      $detailPlaceholders .= ", ?";
      $detailParams[] = $product_code;
    }
    
    if (in_array('product_name', $detailColumns)) {
      $detailSQL .= ", product_name";
      $detailPlaceholders .= ", ?";
      $detailParams[] = $product_name;
    }
    
    if (in_array('unit', $detailColumns)) {
      $detailSQL .= ", unit";
      $detailPlaceholders .= ", ?";
      $detailParams[] = $unit;
    }
    
    if (in_array('qty', $detailColumns)) {
      $detailSQL .= ", qty";
      $detailPlaceholders .= ", ?";
      $detailParams[] = $qty;
    }
    
    if (in_array('sale_price', $detailColumns)) {
      $detailSQL .= ", sale_price";
      $detailPlaceholders .= ", ?";
      $detailParams[] = $price;
    }
    
    if (in_array('created_at', $detailColumns)) {
      $detailSQL .= ", created_at";
      $detailPlaceholders .= ", ?";
      $detailParams[] = $sale_date;
    }
    
    // สร้าง query สมบูรณ์
    $detailSQL .= ") VALUES (" . $detailPlaceholders . ")";
    
    // เพิ่มลง sale_detail
    $detailStmt = $pdo->prepare($detailSQL);
    $detailStmt->execute($detailParams);

    // อัปเดต stock เฉพาะสินค้าประเภท stock
    if ($product_type === 'stock') {
      $updateStmt = $pdo->prepare("UPDATE products SET stock_qty = stock_qty - ? WHERE id = ?");
      $updateStmt->execute([$qty, $product_id]);
    }
  }

  $pdo->commit();

  echo json_encode([
    'success' => true, 
    'sale_id' => $sale_id, 
    'sale_code' => $sale_code,
    'message' => 'บันทึกการขายเรียบร้อย'
  ]);
  
} catch (Exception $e) {
  if (isset($pdo) && $pdo->inTransaction()) {
    $pdo->rollBack();
  }
  
  // บันทึกข้อผิดพลาด
  file_put_contents('../../logs/pos_error.log', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
  
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}