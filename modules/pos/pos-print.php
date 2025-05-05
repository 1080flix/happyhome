<?php
include('../../config/db.php');
require_once '../../config/datetime.php';

// ตั้งค่าการแสดงข้อผิดพลาด
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ดึงการตั้งค่าบริษัท
$setting_q = $conn->query("SELECT * FROM settings LIMIT 1");
$setting = $setting_q->fetch_assoc();

// รับ sale_id
$sale_id = $_GET['sale_id'] ?? 0;
if (!$sale_id) {
  die("กรุณาระบุหมายเลขการขาย");
}

// เตรียมข้อมูลการขาย
$stmt = $conn->prepare(
  "SELECT s.*, 
    c.customer_name, 
    c.tax_id AS customer_tax_id, 
    c.address AS customer_address,
    c.phone AS customer_phone,
    c.email AS customer_email,
    COALESCE(s.received_amount, 0) AS received_amount,
    COALESCE(s.change_amount, 0) AS change_amount
  FROM sales s
  LEFT JOIN customers c ON s.customer_id = c.id
  WHERE s.id = ?"
);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$sale) {
  die("ไม่พบข้อมูลการขาย");
}

// เตรียมรายการสินค้า
$stmt2 = $conn->prepare("
  SELECT sd.*, 
    p.product_name, 
    p.unit, 
    sd.sale_price AS product_price
  FROM sale_detail sd
  LEFT JOIN products p ON sd.product_id = p.id
  WHERE sd.sale_id = ?
");
$stmt2->bind_param("i", $sale_id);
$stmt2->execute();
$items = $stmt2->get_result();

// แปลงค่าการชำระเงิน
$payment_method_map = [
  'cash' => 'เงินสด',
  'transfer' => 'โอนบัญชี',
  'promptpay' => 'พร้อมเพย์',
  'credit' => 'บัตรเครดิต'
];

// คำนวณยอดรวม
$subtotal = 0;
$itemsArray = [];
while ($row = $items->fetch_assoc()) {
  $price = floatval($row['product_price']);
  $qty = floatval($row['qty']);
  $line_total = $price * $qty;
  $subtotal += $line_total;
  
  $itemsArray[] = [
    'name' => $row['product_name'] ?? 'ไม่ระบุ',
    'qty' => $qty,
    'unit' => $row['unit'] ?? '-',
    'price' => $price,
    'total' => $line_total
  ];
}

// คำนวณส่วนลด VAT และยอดสุทธิ
$discount = $sale['discount_type'] === 'percent' 
  ? $subtotal * ($sale['discount_value'] / 100) 
  : floatval($sale['discount_value']);
$vat = floatval($sale['vat_amount']);
$total = $subtotal - $discount + $vat;

// เตรียมข้อมูลลูกค้า
$customerName = !empty($sale['customer_name']) ? $sale['customer_name'] : 'Walk-in';

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จรับเงิน <?= htmlspecialchars($sale['sale_code']) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
        
        :root {
            --primary-color: #1a73e8;
            --text-color: #333;
            --border-color: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: var(--text-color);
            padding: 20px;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
        }

        .header-logo {
            max-width: 150px;
            margin-right: 20px;
        }

        .header-info {
            flex-grow: 1;
        }

        .header-info h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .receipt-details {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .customer-info, .sale-info {
            width: 48%;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .product-table th, .product-table td {
            border: 1px solid var(--border-color);
            padding: 10px;
            text-align: right;
        }

        .product-table th {
            background-color: #f1f1f1;
        }

        .product-table td:first-child,
        .product-table th:first-child {
            text-align: left;
        }

        .summary {
            margin-left: auto;
            width: 50%;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            font-size: 0.8rem;
        }

        @media print {
            body { background: white; }
            .receipt-container { 
                box-shadow: none; 
                margin: 0;
            }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
        <?php if (!empty($setting['logo'])): ?>
    <img src="../../upload/settings/<?= htmlspecialchars($setting['logo']) ?>" alt="Logo" class="header-logo">
<?php endif; ?>
            <div class="header-info">
                <h1><?= htmlspecialchars($setting['company_name'] ?? 'บริษัท') ?></h1>
                <p><?= htmlspecialchars($setting['address'] ?? 'ที่อยู่') ?></p>
                <p>โทร: <?= htmlspecialchars($setting['phone'] ?? '-') ?> 
                   | เลขประจำตัวผู้เสียภาษี: <?= htmlspecialchars($setting['tax_id'] ?? '-') ?></p>
            </div>
        </div>

        <!-- Receipt Details -->
        <div class="receipt-details">
            <div class="sale-info">
                <h3>ข้อมูลการขาย</h3>
                <p><strong>เลขที่ใบเสร็จ:</strong> <?= htmlspecialchars($sale['sale_code']) ?></p>
                <p><strong>วันที่:</strong> <?= getThaiDate($sale['sale_date']) ?></p>
                <p><strong>เวลา:</strong> <?= getThaiTime($sale['sale_date']) ?></p>
            </div>
            <div class="customer-info">
                <h3>ข้อมูลลูกค้า</h3>
                <p><strong>ชื่อ:</strong> <?= htmlspecialchars($customerName) ?></p>
                <?php if (!empty($sale['customer_address'])): ?>
                    <p><strong>ที่อยู่:</strong> <?= htmlspecialchars($sale['customer_address']) ?></p>
                <?php endif; ?>
                <?php if (!empty($sale['customer_tax_id'])): ?>
                    <p><strong>เลขประจำตัวผู้เสียภาษี:</strong> <?= htmlspecialchars($sale['customer_tax_id']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Table -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>รายการสินค้า</th>
                    <th>จำนวน</th>
                    <th>หน่วย</th>
                    <th>ราคาต่อหน่วย</th>
                    <th>รวม</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itemsArray as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td><?= htmlspecialchars($item['unit']) ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                    <td><?= number_format($item['total'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>รวมเป็นเงิน</span>
                <span><?= number_format($subtotal, 2) ?> บาท</span>
            </div>
            <?php if ($discount > 0): ?>
            <div class="summary-row">
                <span>ส่วนลด <?= $sale['discount_type'] === 'percent' ? '(' . $sale['discount_value'] . '%)' : '' ?></span>
                <span>-<?= number_format($discount, 2) ?> บาท</span>
            </div>
            <?php endif; ?>
            <?php if ($vat > 0): ?>
            <div class="summary-row">
                <span>VAT 7%</span>
                <span><?= number_format($vat, 2) ?> บาท</span>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <span>ยอดสุทธิ</span>
                <span><?= number_format($total, 2) ?> บาท</span>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="receipt-details">
            <div class="sale-info">
                <h3>ช่องทางการชำระเงิน</h3>
                <p><?= htmlspecialchars($payment_method_map[$sale['payment_method']] ?? $sale['payment_method']) ?></p>
                <?php if (!empty($sale['transfer_account'])): ?>
                    <p>บัญชี: <?= htmlspecialchars($sale['transfer_account']) ?></p>
                <?php endif; ?>
            </div>
            <div class="customer-info">
                <?php if ($sale['received_amount'] > 0 || $sale['change_amount'] > 0): ?>
                    <h3>รายละเอียดการชำระเงิน</h3>
                    <p><strong>รับเงิน:</strong> <?= number_format($sale['received_amount'], 2) ?> บาท</p>
                    <p><strong>เงินทอน:</strong> <?= number_format($sale['change_amount'], 2) ?> บาท</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <?= !empty($setting['receipt_footer']) 
                ? htmlspecialchars($setting['receipt_footer']) 
                : 'สินค้าซื้อแล้วไม่รับคืน กรุณาเก็บใบเสร็จไว้เป็นหลักฐาน' ?>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                if (!window.location.href.includes('debug=true')) {
                    window.print();
                }
            }, 500);
        };
    </script>
</body>
</html>

<?php 
$conn->close();
?>