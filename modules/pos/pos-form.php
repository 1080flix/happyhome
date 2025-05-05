<?php
require_once '../../config/datetime.php';
$base_path = '../../';
include($base_path . 'config/check_login.php');
?>

<form method="post" id="posForm" onsubmit="return handleFormSubmit(event)">
<div class="row mb-3">
  <!-- ซ้าย: ข้อมูลเอกสาร + ลูกค้า -->
  <div class="col-md-6">
    <!-- ข้อมูลเอกสาร -->
    <div class="card card-outline card-primary mb-3">
      <div class="card-header bg-light">
        <h5 class="mb-0"><i class="far fa-file-alt"></i> ข้อมูลเอกสาร</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="sale_code" class="form-control" value="AUTO" readonly placeholder="เลขที่เอกสาร">
            </div>
          </div>
            <div class="col-md-4">
              <div class="form-group">
                <input type="text" class="form-control" value="<?= getThaiDate() ?>" readonly placeholder="วันที่">
                <input type="hidden" name="form_date" value="<?= date('Y-m-d') ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input type="text" class="form-control" value="<?= getThaiTime() ?>" readonly placeholder="เวลา">
                <input type="hidden" name="form_time" value="<?= date('H:i') ?>">
              </div>
            </div>
        </div>
      </div>
    </div>

    <!-- ข้อมูลลูกค้า -->
    <div class="card card-outline card-warning">
      <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="fas fa-user"></i> ข้อมูลลูกค้า</h5>
          <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#searchCustomerModal">
            <i class="fas fa-search"></i> ค้นหาลูกค้า
          </button>
        </div>
      </div>
      <div class="card-body py-2">
        <div class="form-group mb-2">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
            </div>
            <input type="hidden" name="customer_id" id="customer_id" value="1">
            <input type="text" class="form-control" id="customer_name" value="Walk-in" readonly>
          </div>
        </div>

        <div class="row customer-details">
          <div class="col-md-4">
            <small id="customer_address">ที่อยู่: -</small>
          </div>
          <div class="col-md-4">
            <small id="customer_contact_name">ผู้ติดต่อ: -</small>
          </div>
          <div class="col-md-4">
            <small id="customer_contact_phone">เบอร์ผู้ติดต่อ: -</small>
          </div>
        </div>

        <div class="row customer-details">
          <div class="col-md-4">
            <small id="customer_type">ประเภทลูกค้า: -</small>
          </div>
          <div class="col-md-4">
            <small id="customer_tax_id">เลขประจำตัวผู้เสียภาษี: -</small>
          </div>
          <div class="col-md-4">
            <small id="customer_branch">สาขา: -</small>
          </div>
        </div>

        <div class="row customer-details">
          <div class="col-md-4">
            <small id="customer_phone">เบอร์โทรศัพท์: -</small>
          </div>
          <div class="col-md-4">
            <small id="customer_email">อีเมล: -</small>
          </div>
          <div class="col-md-4">
            <!-- เผื่ออนาคต -->
          </div>
        </div>
      </div>
    </div>
  </div>
    
  <!-- ส่วนขวา: ข้อมูลการเงิน -->
  <div class="col-md-6">
    <div class="card card-outline card-danger">
      <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> ข้อมูลการเงิน</h5>
      </div>
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-md-4">
            <div class="d-flex align-items-center">
              <i class="fas fa-percent mr-1"></i>
              <label class="mb-0">ส่วนลด:</label>
            </div>
          </div>
          <div class="col-md-4">
            <input type="number" class="form-control" id="discount_value" name="discount_value" value="0" min="0">
          </div>
          <div class="col-md-4">
            <select class="form-control" id="discount_type" name="discount_type">
              <option value="baht">บาท</option>
              <option value="percent">เปอร์เซ็นต์ (%)</option>
            </select>
          </div>
        </div>
        
        <div class="row mb-2">
          <div class="col-md-4">
            <div class="d-flex align-items-center">
              <i class="fas fa-receipt mr-1"></i>
              <label class="mb-0">VAT:</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="input-group">
              <select class="form-control" id="vat_type" name="vat_type">
                <option value="none">ไม่รวม VAT</option>
                <option value="inclusive">รวม VAT แล้ว</option>
                <option value="exclusive">ยังไม่รวม VAT</option>
              </select>
              <div class="input-group-append">
                <span class="input-group-text">(<span id="vat_display">0.00</span> บาท)</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- ช่องทางการชำระเงิน -->
        <div class="card bg-light mb-3 mt-3">
          <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-credit-card"></i> ช่องทางการชำระเงิน</h5>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="payment_method"><i class="fas fa-money-bill-wave mr-1"></i> เลือกช่องทางการชำระเงิน</label>
                  <select id="payment_method" name="payment_method" class="form-control" onchange="togglePaymentFields()">
                    <option value="cash">เงินสด</option>
                    <option value="transfer">โอนบัญชีธนาคาร</option>
                    <option value="promptpay">PromptPay</option>
                    <option value="credit">บัตรเครดิต</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- ช่องเฉพาะของแต่ละวิธีการชำระเงิน -->
            <div class="payment-options mt-3">
              <!-- ถ้าเป็นเงินสด -->
              <div class="row payment-cash">
                <div class="col-md-6">
                  <div class="form-group">
                    <label><i class="fas fa-hand-holding-usd mr-1"></i> รับเงินมา (บาท)</label>
                    <input type="number" class="form-control" name="received_amount_cash" id="received_amount_cash" oninput="updateMainReceivedAmount(this.value)">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label><i class="fas fa-exchange-alt mr-1"></i> เงินทอน (บาท)</label>
                    <input type="text" class="form-control bg-light" id="change_amount_cash" readonly value="0.00">
                  </div>
                </div>
              </div>

              <!-- ถ้าเป็น PromptPay -->
              <div class="payment-qr" style="display:none;">
                <div class="form-group">
                  <label><i class="fas fa-qrcode mr-1"></i> สแกน QR Code เพื่อชำระเงิน</label>
                  <div class="text-center p-3 bg-white rounded border">
                    <img id="promptpay_qr" src="../../assets/img/promptpay-qr-placeholder.png" alt="PromptPay QR" class="img-fluid" style="max-width: 200px;">
                    <div class="mt-2 text-muted small">กรุณาสแกนเพื่อชำระเงินและแจ้งสลิปการชำระเงิน</div>
                  </div>
                </div>
              </div>

              <!-- ถ้าเป็นโอน -->
              <div class="payment-transfer" style="display:none;">
                <div class="form-group">
                  <label><i class="fas fa-university mr-1"></i> เลือกบัญชีธนาคารที่โอนเข้า</label>
                  <select name="transfer_account" class="form-control">
                    <option value="kbank">กสิกรไทย - 123-456789-0</option>
                    <option value="scb">ไทยพาณิชย์ - 111-222222-2</option>
                  </select>
                </div>
                <div class="form-group mt-2">
                  <label><i class="fas fa-receipt mr-1"></i> หลักฐานการโอนเงิน</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="transfer_slip" accept="image/*">
                    <label class="custom-file-label" for="transfer_slip">เลือกไฟล์...</label>
                  </div>
                </div>
              </div>
              
              <!-- ถ้าเป็นบัตรเครดิต -->
              <div class="payment-credit" style="display:none;">
                <div class="form-group">
                  <label><i class="far fa-credit-card mr-1"></i> หมายเลขบัตรเครดิต</label>
                  <input type="text" class="form-control" id="credit_number" placeholder="XXXX-XXXX-XXXX-XXXX">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><i class="far fa-calendar-alt mr-1"></i> วันหมดอายุ</label>
                      <input type="text" class="form-control" id="credit_expiry" placeholder="MM/YY">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><i class="fas fa-lock mr-1"></i> CVV</label>
                      <input type="text" class="form-control" id="credit_cvv" placeholder="XXX">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- ช่องรับเงินและเงินทอนหลัก (สำหรับการทำงานเดิม) -->        
        <div class="text-right mt-2">
          <h4 class="text-success mb-0"><i class="fas fa-calculator"></i> ยอดรวมทั้งสิ้น: <span id="total_price">0.00</span> บาท</h4>
        </div>
      </div>
    </div>
  </div>
</div>
  
  
<!-- ส่วนตารางสินค้า -->
<div class="card mb-3">
  <div class="card-header bg-light">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> ตารางสินค้า</h5>
      <div class="input-group" style="width: 50%;">
        <input type="text" id="barcode_input" class="form-control" placeholder="ยิงบาร์โค้ดที่นี่">
        <div class="input-group-append">
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#searchProductModal">
            <i class="fas fa-search"></i> ค้นหาสินค้า
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-hover mb-0" id="productTable">
        <thead class="thead-light">
          <tr>
            <th style="width: 40px;">#</th>
            <th style="width: 120px;">รหัสสินค้า</th>
            <th>ชื่อสินค้า</th>
            <th style="width: 100px;">จำนวน</th>
            <th style="width: 80px;">หน่วย</th>
            <th style="width: 100px;">ราคาขาย</th>
            <th style="width: 100px;">ราคารวม</th>
            <th style="width: 70px;">จัดการ</th>
          </tr>
        </thead>
        <tbody id="pos-items"></tbody>
      </table>
    </div>
  </div>
</div>
  
<!-- หมายเหตุและปุ่มดำเนินการ -->
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label><i class="fas fa-comment-alt"></i> หมายเหตุ</label>
      <textarea name="note" class="form-control" rows="4" id="note"></textarea>
    </div>
  </div>
  <div class="col-md-6 text-right">
  <div class="btn-group mt-4">
    <button type="button" class="btn btn-warning" onclick="clearFullPOSForm()">
      <i class="fas fa-trash"></i> เคลียร์รายการ
    </button>
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#pos-draft-list-modal">
      <i class="fas fa-folder-open"></i> โหลดบิลที่พักไว้
    </button>
    <button type="button" class="btn btn-info" onclick="saveDraft()">
      <i class="fas fa-save"></i> พักบิลไว้ก่อน
    </button>
    <button type="submit" class="btn btn-success">
      <i class="fas fa-check-circle"></i> บันทึกการขาย
    </button>
  </div>
</div>
</div>
  
<!-- hidden fields -->
<input type="hidden" name="subtotal" id="hidden_subtotal" value="0">
<input type="hidden" name="discount" id="hidden_discount" value="0">
<input type="hidden" name="vat_amount" id="hidden_vat_amount" value="0">
<input type="hidden" name="total_amount" id="hidden_total_amount" value="0">
<input type="hidden" name="products" id="hidden_products" value="[]">
<input type="hidden" name="change_amount" id="hidden_change_amount" value="0.00">
<input type="hidden" name="transfer_account" id="hidden_transfer_account" value="">
<input type="hidden" name="credit_note" id="hidden_credit_note" value="">
</form>

<script>

// กำหนดตัวแปรสำหรับเก็บรายการสินค้า
var productList = [];

// อัพเดทจำนวนสินค้า
function updateQty(index, value) {
  let qty = parseInt(value) || 1;
  if (qty < 1) qty = 1;
  
  if (index >= 0 && index < productList.length) {
    productList[index].qty = qty;
    // คำนวณราคารวมของรายการนี้
    let lineTotal = qty * productList[index].sale_price;
    
    // อัพเดทเฉพาะช่องราคารวมของรายการนี้
    let row = document.querySelector(`#pos-items tr:nth-child(${index + 1})`);
    if (row) {
      let totalCell = row.querySelector('.item-total');
      if (totalCell) {
        totalCell.textContent = lineTotal.toFixed(2);
      }
    }
    
    // คำนวณราคาใหม่
    calculatePOSWithoutRender();
  }
}

// คำนวณราคาทั้งหมด
function calculatePOSWithoutRender() {
  try {
    // คำนวณจากรายการสินค้า
    var subtotal = 0;
    for (var i = 0; i < productList.length; i++) {
      subtotal += productList[i].qty * productList[i].sale_price;
    }
    
    // คำนวณส่วนลด
    var discountValueElement = document.getElementById('discount_value');
    var discountTypeElement = document.getElementById('discount_type');
    
    var discountValue = parseFloat(discountValueElement.value || 0);
    var discountType = discountTypeElement.value;
    
    var discount = 0;
    if (discountType === 'percent') {
      discount = subtotal * (discountValue / 100);
    } else {
      discount = discountValue;
    }
    
    // ป้องกันส่วนลดมากกว่ายอดรวม
    discount = Math.min(discount, subtotal);
    
    // คำนวณหลังหักส่วนลด
    var afterDiscount = subtotal - discount;
    
    // คำนวณ VAT
    var vatTypeElement = document.getElementById('vat_type');
    var vatType = vatTypeElement.value;
    var vatRate = 0.07;
    var vat = 0;
    
    if (vatType === 'inclusive') {
      vat = (afterDiscount * vatRate) / (1 + vatRate);
    } else if (vatType === 'exclusive') {
      vat = afterDiscount * vatRate;
    }
    
    // คำนวณยอดรวม
    var total = afterDiscount;
    if (vatType === 'exclusive') {
      total += vat;
    }
    
    // ป้องกันค่าติดลบ
    total = Math.max(0, total);
    
    console.log("การคำนวณ:", {
      subtotal: subtotal.toFixed(2),
      discount: discount.toFixed(2),
      afterDiscount: afterDiscount.toFixed(2),
      vatType: vatType,
      vat: vat.toFixed(2),
      total: total.toFixed(2)
    });
    
    // อัพเดทการแสดงผล VAT
    var vatDisplayElement = document.getElementById('vat_display');
    if (vatDisplayElement) {
      vatDisplayElement.textContent = vat.toFixed(2);
    }
    
    // อัพเดทการแสดงผลยอดรวม
    var totalPriceElement = document.getElementById('total_price');
    if (totalPriceElement) {
      totalPriceElement.textContent = total.toFixed(2);
      console.log("อัพเดทยอดรวมเป็น: " + total.toFixed(2));
    } else {
      console.error("ไม่พบอิลิเมนต์ total_price");
    }

    // อัพเดท hidden fields สำหรับส่งข้อมูล
    document.getElementById('hidden_subtotal').value = subtotal.toFixed(2);
    document.getElementById('hidden_discount').value = discount.toFixed(2);
    document.getElementById('hidden_vat_amount').value = vat.toFixed(2);
    document.getElementById('hidden_total_amount').value = total.toFixed(2);
    document.getElementById('hidden_products').value = JSON.stringify(productList);
    
    // คำนวณเงินทอน
    calculateChange();
    
  } catch (error) {
    console.error("เกิดข้อผิดพลาดในการคำนวณ:", error);
  }
}

// แยกฟังก์ชันคำนวณเงินทอน
function calculateChange() {
  try {
    var paymentMethod = document.getElementById('payment_method').value;
    var totalAmount = parseFloat(document.getElementById('total_price').textContent) || 0;
    
    if (paymentMethod === 'cash') {
      // อ่านค่าจากช่องรับเงินสด
      var receivedCashElement = document.getElementById('received_amount_cash');
      var cashChangeElement = document.getElementById('change_amount_cash');
      
      if (receivedCashElement && cashChangeElement) {
        var received = parseFloat(receivedCashElement.value || 0);
        var change = received - totalAmount;
        
        // แสดงเงินทอน
        cashChangeElement.value = change > 0 ? change.toFixed(2) : '0.00';
        
        // อัพเดท hidden field สำหรับเงินทอน
        document.getElementById('hidden_change_amount').value = 
          change > 0 ? change.toFixed(2) : '0.00';
        
        console.log("คำนวณเงินทอน: " + received + " - " + totalAmount + " = " + (change > 0 ? change.toFixed(2) : '0.00'));
      }
    } else {
      // กรณีไม่ใช่เงินสด อัตโนมัติรับเงินเท่ากับยอดรวม
      var cashChangeElement = document.getElementById('change_amount_cash');
      if (cashChangeElement) {
        cashChangeElement.value = '0.00';
      }
      
      document.getElementById('hidden_change_amount').value = '0.00';
      
      // เซ็ตค่ารับเงินเท่ากับยอดรวมสำหรับวิธีการชำระเงินอื่น
      var mainReceivedAmount = document.getElementById('received_amount');
      if (mainReceivedAmount) {
        mainReceivedAmount.value = totalAmount.toFixed(2);
      }
    }
  } catch (error) {
    console.error("เกิดข้อผิดพลาดในการคำนวณเงินทอน:", error);
  }
}

// ลบสินค้าออกจากตาราง
function removeProduct(index) {
  if (index >= 0 && index < productList.length) {
    productList.splice(index, 1);
    renderProductTable(); // จำเป็นต้อง render ใหม่เพราะลำดับแถวเปลี่ยน
  }
}

// เพิ่มสินค้าจากการยิงบาร์โค้ดหรือการค้นหา
function addProductToTable(p) {
  // เพิ่มการตรวจสอบข้อมูล
  if (!p || !p.product_name || isNaN(parseFloat(p.sale_price))) {
    console.error("ข้อมูลสินค้าไม่สมบูรณ์:", p);
    return false;
  }
  
  // ค้นหาสินค้าที่มีอยู่แล้ว
  var existingIndex = -1;
  for (var i = 0; i < productList.length; i++) {
    if (productList[i].product_code === p.product_code) {
      existingIndex = i;
      break;
    }
  }

  if (existingIndex >= 0) {
    // เพิ่มจำนวนหากพบสินค้าซ้ำ
    productList[existingIndex].qty += 1;
    
    // อัพเดทเฉพาะรายการที่เปลี่ยน
    let row = document.querySelector(`#pos-items tr:nth-child(${existingIndex + 1})`);
    if (row) {
      let qtyInput = row.querySelector('.item-qty');
      let totalCell = row.querySelector('.item-total');
      if (qtyInput && totalCell) {
        qtyInput.value = productList[existingIndex].qty;
        let lineTotal = productList[existingIndex].qty * productList[existingIndex].sale_price;
        totalCell.textContent = lineTotal.toFixed(2);
      }
    }
    
    // คำนวณราคาใหม่
    calculatePOSWithoutRender();
  } else {
    // เพิ่มสินค้าใหม่
    var newProduct = {
      product_code: p.product_code,
      product_name: p.product_name,
      unit: p.unit || '-',
      sale_price: parseFloat(p.sale_price),
      qty: 1
    };
    productList.push(newProduct);
    
    // อัพเดทตารางเฉพาะการเพิ่มรายการใหม่
    appendProductRow(newProduct, productList.length - 1);
    
    // คำนวณราคาใหม่
    calculatePOSWithoutRender();
  }

  return true;
}

// เพิ่มแถวสินค้าใหม่โดยไม่ต้อง render ทั้งตาราง
function appendProductRow(product, index) {
  var tbody = document.getElementById('pos-items');
  if (!tbody) return;
  
  var row = document.createElement('tr');
  var lineTotal = product.qty * product.sale_price;
  
  row.innerHTML = `
    <td>${index + 1}</td>
    <td>${product.product_code}</td>
    <td>${product.product_name}</td>
    <td>
      <input type="number" class="form-control item-qty" 
             value="${product.qty}" min="1" onchange="updateQty(${index}, this.value)">
    </td>
    <td>${product.unit}</td>
    <td>
      <input type="hidden" name="product[${index}][price]" 
             class="item-price" value="${product.sale_price}">
      ${product.sale_price.toFixed(2)}
    </td>
    <td class="item-total">${lineTotal.toFixed(2)}</td>
    <td>
      <button type="button" class="btn btn-sm btn-danger" 
              onclick="removeProduct(${index})">
        &times;
      </button>
    </td>
  `;
  
  tbody.appendChild(row);
  
  // เพิ่ม event listener ให้กับ input จำนวนสินค้า
  var qtyInput = row.querySelector('.item-qty');
  if (qtyInput) {
    qtyInput.addEventListener('input', function(e) {
      updateQty(index, this.value);
    });
  }
}

// สร้างตารางสินค้าใหม่ทั้งหมด
function renderProductTable() {
  var tbody = document.getElementById('pos-items');
  if (!tbody) return;
  
  // ล้างตารางก่อน
  tbody.innerHTML = '';

  // วนลูปสร้างแถวสินค้า
  for (var i = 0; i < productList.length; i++) {
    appendProductRow(productList[i], i);
  }
  
  // อัพเดท hidden field สำหรับส่งข้อมูลรายการสินค้า
  document.getElementById('hidden_products').value = JSON.stringify(productList);
  
  // เรียกคำนวณราคาทุกครั้งหลังอัพเดทตาราง
  calculatePOSWithoutRender();
}

// คำนวณราคาทั้งหมด (เวอร์ชันเดิม แต่ยังคงไว้เพื่อความเข้ากันได้)
function calculatePOS() {
  calculatePOSWithoutRender();
}

// ฟังก์ชันอัพเดทค่า received_amount หลักเมื่อมีการเปลี่ยนแปลงในฟิลด์เงินสด
function updateMainReceivedAmount(value) {
  try {
    // แปลงค่าเป็นตัวเลข
    var receivedValue = parseFloat(value) || 0;
    
    // อัพเดทช่องรับเงินหลัก (ถ้ามี)
    var mainReceivedAmount = document.getElementById('received_amount');
    if (mainReceivedAmount) {
      mainReceivedAmount.value = receivedValue;
    }
    
    // คำนวณเงินทอน
    calculateChange();
    
    console.log("อัพเดทเงินรับและเงินทอนเรียบร้อย");
  } catch (error) {
    console.error("เกิดข้อผิดพลาดในการอัพเดทเงินทอน:", error);
  }
}

// ล้างฟอร์มทั้งหมด
function clearFullPOSForm() {
  // ล้างรายการสินค้า
  productList = [];
  
  // รีเซ็ตข้อมูลลูกค้า
  var customerIdElement = document.getElementById('customer_id');
  var customerNameElement = document.getElementById('customer_name');
  
  if (customerIdElement) customerIdElement.value = '1';
  if (customerNameElement) customerNameElement.value = 'Walk-in';
  
  // รีเซ็ตรายละเอียดลูกค้า
  var customerDetails = document.querySelectorAll('.customer-details small');
  for (var i = 0; i < customerDetails.length; i++) {
    var el = customerDetails[i];
    var id = el.id;
    var label = '';
    if (id === 'customer_address') label = 'ที่อยู่: -';
    if (id === 'customer_contact_name') label = 'ผู้ติดต่อ: -';
    if (id === 'customer_contact_phone') label = 'เบอร์ผู้ติดต่อ: -';
    if (id === 'customer_type') label = 'ประเภทลูกค้า: -';
    if (id === 'customer_tax_id') label = 'เลขประจำตัวผู้เสียภาษี: -';
    if (id === 'customer_branch') label = 'สาขา: -';
    if (id === 'customer_phone') label = 'เบอร์โทรศัพท์: -';
    if (id === 'customer_email') label = 'อีเมล: -';
    
    el.textContent = label;
  }
  
  // ล้างตารางสินค้า
  var posItems = document.getElementById('pos-items');
  if (posItems) posItems.innerHTML = '';
  
  // รีเซ็ตฟอร์ม
  var noteElement = document.getElementById('note');
  var discountValueElement = document.getElementById('discount_value');
  var receivedAmountElement = document.getElementById('received_amount');
  var changeAmountElement = document.getElementById('change_amount');
  var totalPriceElement = document.getElementById('total_price');
  var vatDisplayElement = document.getElementById('vat_display');
  
  if (noteElement) noteElement.value = '';
  if (discountValueElement) discountValueElement.value = '0';
  if (receivedAmountElement) receivedAmountElement.value = '';
  if (changeAmountElement) changeAmountElement.value = '0.00';
  if (totalPriceElement) totalPriceElement.textContent = '0.00';
  if (vatDisplayElement) vatDisplayElement.textContent = '0.00';
  
  // รีเซ็ต hidden fields
  if (document.getElementById('hidden_subtotal')) {
    document.getElementById('hidden_subtotal').value = '0.00';
  }
  
  if (document.getElementById('hidden_discount')) {
    document.getElementById('hidden_discount').value = '0.00';
  }
  
  if (document.getElementById('hidden_vat_amount')) {
    document.getElementById('hidden_vat_amount').value = '0.00';
  }
  
  if (document.getElementById('hidden_total_amount')) {
    document.getElementById('hidden_total_amount').value = '0.00';
  }
  
  if (document.getElementById('hidden_products')) {
    document.getElementById('hidden_products').value = '[]';
  }
  
  if (document.getElementById('hidden_change_amount')) {
    document.getElementById('hidden_change_amount').value = '0.00';
  }
  
  if (document.getElementById('hidden_transfer_account')) {
    document.getElementById('hidden_transfer_account').value = '';
  }
  
  if (document.getElementById('hidden_credit_note')) {
    document.getElementById('hidden_credit_note').value = '';
  }
  
  // รีเซ็ตฟิลด์ในส่วนช่องทางการชำระเงิน
  if (document.getElementById('received_amount_cash')) {
    document.getElementById('received_amount_cash').value = '';
  }
  
  if (document.getElementById('change_amount_cash')) {
    document.getElementById('change_amount_cash').value = '0.00';
  }
  
  // กลับไปที่การชำระเงินสด
  if (document.getElementById('payment_method')) {
    document.getElementById('payment_method').value = 'cash';
    togglePaymentFields();
  }
}

// ฟังก์ชันสำหรับแสดง/ซ่อนฟิลด์ตามรูปแบบการชำระเงิน
function togglePaymentFields() {
  try {
    // รับค่าวิธีการชำระเงินที่เลือก
    var paymentMethod = document.getElementById('payment_method').value;
    
    // ซ่อนทุกส่วนก่อน
    var cashFields = document.querySelectorAll('.payment-cash');
    var transferFields = document.querySelectorAll('.payment-transfer');
    var qrFields = document.querySelectorAll('.payment-qr');
    var creditFields = document.querySelectorAll('.payment-credit');
    
    // ใช้ for loop ที่ปลอดภัยกว่า
    for (var i = 0; i < cashFields.length; i++) cashFields[i].style.display = 'none';
    for (var i = 0; i < transferFields.length; i++) transferFields[i].style.display = 'none';
    for (var i = 0; i < qrFields.length; i++) qrFields[i].style.display = 'none';
    for (var i = 0; i < creditFields.length; i++) creditFields[i].style.display = 'none';
    
    // ค้นหาช่องรับเงิน/เงินทอนหลัก วิธีที่ปลอดภัยกว่า
    var mainPaymentFields = document.getElementById('main-payment-fields');
    
    // แสดงส่วนที่เหมาะสมตามวิธีการชำระเงิน
    if (paymentMethod === 'cash') {
      // แสดงช่องเงินสด
      for (var i = 0; i < cashFields.length; i++) cashFields[i].style.display = '';
      
      // แสดงช่องรับเงิน/เงินทอนหลัก
      if (mainPaymentFields) {
        mainPaymentFields.style.display = '';
      }
      
      // ซิงค์ค่าระหว่างช่องรับเงินหลักและเงินสด
      var mainReceived = document.getElementById('received_amount');
      var cashReceived = document.getElementById('received_amount_cash');
      if (mainReceived && cashReceived) {
        // ถ้าช่องเงินสดยังว่าง ให้ใช้ค่าจากช่องหลัก
        if (!cashReceived.value && mainReceived.value) {
          cashReceived.value = mainReceived.value;
        }
      }
    } 
    else if (paymentMethod === 'transfer') {
      // แสดงช่องการโอน
      for (var i = 0; i < transferFields.length; i++) transferFields[i].style.display = '';
      
      // ซ่อนช่องรับเงิน/เงินทอนหลัก
      if (mainPaymentFields) {
        mainPaymentFields.style.display = 'none';
      }
      
      // เซ็ตค่ารับเงินเท่ากับยอดรวมอัตโนมัติ
      var totalPrice = document.getElementById('total_price');
      var receivedAmount = document.getElementById('received_amount');
      if (totalPrice && receivedAmount) {
        receivedAmount.value = parseFloat(totalPrice.textContent) || 0;
      }
      
      // บันทึกบัญชีที่โอนเข้า
      var transferAccount = document.querySelector('select[name="transfer_account"]');
      if (transferAccount) {
        document.getElementById('hidden_transfer_account').value = transferAccount.value;
      }
    } 
    else if (paymentMethod === 'promptpay') {
      // แสดงช่อง PromptPay
      for (var i = 0; i < qrFields.length; i++) qrFields[i].style.display = '';
      
      // ซ่อนช่องรับเงิน/เงินทอนหลัก
      if (mainPaymentFields) {
        mainPaymentFields.style.display = 'none';
      }
      
      // เซ็ตค่ารับเงินเท่ากับยอดรวมอัตโนมัติ
      var totalPrice = document.getElementById('total_price');
      var receivedAmount = document.getElementById('received_amount');
      if (totalPrice && receivedAmount) {
        receivedAmount.value = parseFloat(totalPrice.textContent) || 0;
      }
    } 
    else if (paymentMethod === 'credit') {
      // แสดงช่องบัตรเครดิต
      for (var i = 0; i < creditFields.length; i++) creditFields[i].style.display = '';
      
      // ซ่อนช่องรับเงิน/เงินทอนหลัก
      if (mainPaymentFields) {
        mainPaymentFields.style.display = 'none';
      }
      
      // เซ็ตค่ารับเงินเท่ากับยอดรวมอัตโนมัติ
      var totalPrice = document.getElementById('total_price');
      var receivedAmount = document.getElementById('received_amount');
      if (totalPrice && receivedAmount) {
        receivedAmount.value = parseFloat(totalPrice.textContent) || 0;
      }
    }
    
    // อัพเดทการคำนวณ
    calculatePOSWithoutRender();
  } catch (error) {
    console.error("เกิดข้อผิดพลาดในการสลับช่องทางชำระเงิน:", error);
  }
}

// เตรียมข้อมูลสินค้าสำหรับส่งไป API
function prepareProductsForSubmit() {
  try {
    console.log("เริ่มการเตรียมข้อมูลสินค้า");
    console.log("รายการสินค้า:", productList);
    
    // ตรวจสอบว่ามีสินค้าหรือไม่
    if (!productList || productList.length === 0) {
      alert("ไม่พบรายการสินค้า กรุณาเพิ่มสินค้าอย่างน้อย 1 รายการ");
      return false;
    }
    
    // แปลง productList เป็นรูปแบบที่ API ต้องการ
    var formattedProducts = [];
    for (var i = 0; i < productList.length; i++) {
      var item = productList[i];
      if (!item.product_code) {
        console.error("ไม่พบรหัสสินค้าที่รายการที่ " + (i+1));
        continue; // ข้ามรายการที่ไม่มีรหัสสินค้า
      }
      
      formattedProducts.push({
        id: item.product_code,
        product_code: item.product_code,
        product_name: item.product_name || '',
        unit: item.unit || '',
        qty: parseInt(item.qty) || 1,
        price: parseFloat(item.sale_price) || 0
      });
    }
    
    if (formattedProducts.length === 0) {
      alert("ไม่สามารถเตรียมข้อมูลสินค้าได้ กรุณาตรวจสอบรายการสินค้า");
      return false;
    }
    
    console.log("แปลงข้อมูลสินค้าเรียบร้อย:", formattedProducts);
    
    // แปลงเป็น JSON และเก็บในฟิลด์ hidden
    var productsJSON = JSON.stringify(formattedProducts);
    
    // ตรวจสอบว่า hidden field มีอยู่จริง
    var hiddenProductsField = document.getElementById('hidden_products');
    if (!hiddenProductsField) {
      console.error("ไม่พบฟิลด์ hidden_products");
      // สร้างฟิลด์ใหม่ถ้าไม่มี
      hiddenProductsField = document.createElement('input');
      hiddenProductsField.type = 'hidden';
      hiddenProductsField.id = 'hidden_products';
      hiddenProductsField.name = 'products';
      document.getElementById('posForm').appendChild(hiddenProductsField);
    }
    hiddenProductsField.value = productsJSON;
    
    console.log("บันทึกข้อมูลสินค้าเรียบร้อย:", productsJSON);
    
    // คำนวณเงินทอน
    var totalAmount = parseFloat(document.getElementById('total_price')?.textContent) || 0;
    var receivedAmountElem = document.getElementById('received_amount');
    var receivedAmount = receivedAmountElem ? parseFloat(receivedAmountElem.value) || 0 : 0;
    var changeAmount = Math.max(0, receivedAmount - totalAmount);
    
    // ตรวจสอบฟิลด์ hidden_change_amount
    var hiddenChangeField = document.getElementById('hidden_change_amount');
    if (!hiddenChangeField) {
      console.error("ไม่พบฟิลด์ hidden_change_amount");
      // สร้างฟิลด์ใหม่ถ้าไม่มี
      hiddenChangeField = document.createElement('input');
      hiddenChangeField.type = 'hidden';
      hiddenChangeField.id = 'hidden_change_amount';
      hiddenChangeField.name = 'change_amount';
      document.getElementById('posForm').appendChild(hiddenChangeField);
    }
    hiddenChangeField.value = changeAmount.toFixed(2);
    
    // บันทึกข้อมูลช่องทางการชำระเงิน
    var paymentMethod = document.getElementById('payment_method')?.value || 'cash';
    
    if (paymentMethod === 'transfer') {
      var transferAccount = document.querySelector('select[name="transfer_account"]');
      var hiddenTransferField = document.getElementById('hidden_transfer_account');
      
      if (!hiddenTransferField) {
        hiddenTransferField = document.createElement('input');
        hiddenTransferField.type = 'hidden';
        hiddenTransferField.id = 'hidden_transfer_account';
        hiddenTransferField.name = 'transfer_account';
        document.getElementById('posForm').appendChild(hiddenTransferField);
      }
      
      if (transferAccount) {
        hiddenTransferField.value = transferAccount.value || '';
      }
    } else if (paymentMethod === 'credit') {
      var creditNote = "";
      var creditNumber = document.getElementById('credit_number');
      var creditExpiry = document.getElementById('credit_expiry');
      var hiddenCreditNoteField = document.getElementById('hidden_credit_note');
      
      if (!hiddenCreditNoteField) {
        hiddenCreditNoteField = document.createElement('input');
        hiddenCreditNoteField.type = 'hidden';
        hiddenCreditNoteField.id = 'hidden_credit_note';
        hiddenCreditNoteField.name = 'credit_note';
        document.getElementById('posForm').appendChild(hiddenCreditNoteField);
      }
      
      if (creditNumber && creditExpiry) {
        creditNote = "บัตรเลขที่: " + (creditNumber.value || '') + " วันหมดอายุ: " + (creditExpiry.value || '');
        hiddenCreditNoteField.value = creditNote;
      }
    }
    
    console.log("เตรียมข้อมูลเสร็จสมบูรณ์");
    return true;
  } catch (error) {
    console.error("เกิดข้อผิดพลาดในการเตรียมข้อมูล:", error);
    alert("เกิดข้อผิดพลาดในการเตรียมข้อมูลสินค้า กรุณาลองใหม่อีกครั้ง");
    return false;
  }
}

// ฟังก์ชันจัดการการส่งฟอร์ม
// เพิ่มตัวแปรเป็นตัวกลางที่อยู่นอกฟังก์ชัน
let isFormSubmitting = false;

function handleFormSubmit(event) {
  // ป้องกันการส่งฟอร์มซ้ำ
  if (isFormSubmitting) {
    event.preventDefault();
    return false;
  }

  event.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ
  
  // ตั้งค่าฟล็กว่ากำลังส่งฟอร์ม
  isFormSubmitting = true;
  
  // เรียกใช้ฟังก์ชันเตรียมข้อมูลสินค้าที่มีอยู่แล้ว
  if (!prepareProductsForSubmit()) {
    isFormSubmitting = false;
    return false;
  }
  
  // ส่งข้อมูลไปยัง API ด้วย AJAX
  var form = document.getElementById('posForm');
  var formData = new FormData(form);
  
  // ส่ง AJAX request
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '../../api/sale/confirm-save.php', true);
  xhr.onload = function() {
    // รีเซ็ตฟล็กเมื่อส่งเสร็จ
    isFormSubmitting = false;

    if (xhr.status === 200) {
      try {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          // เปิดหน้าพิมพ์ในโหมดใหม่
          var printWindow = window.open('pos-print.php?sale_id=' + response.sale_id, '_blank');
          
          // เคลียร์ฟอร์มเพื่อขายครั้งต่อไป
          clearFullPOSForm();
        } else {
          alert('เกิดข้อผิดพลาด: ' + (response.message || 'ไม่สามารถบันทึกการขายได้'));
        }
      } catch (e) {
        console.error('Error parsing JSON:', e);
        alert('เกิดข้อผิดพลาดในการประมวลผลข้อมูล กรุณาลองใหม่อีกครั้ง');
      }
    } else {
      alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง');
    }
  };
  xhr.onerror = function() {
    // รีเซ็ตฟล็กหากเกิดข้อผิดพลาด
    isFormSubmitting = false;
    alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง');
  };
  xhr.send(formData);
  
  return false;
}

// เมื่อโหลดหน้าเสร็จ ตั้งค่า event listeners และดำเนินการเริ่มต้น
document.addEventListener('DOMContentLoaded', function() {
  try {
    // เชื่อมต่อกับ modal เลือกสินค้า
    window.selectProductForPOS = function(product) {
      addProductToTable({
        id: product.product_code,
        product_code: product.product_code,
        product_name: product.product_name,
        unit: product.unit,
        sale_price: product.sale_price,
        qty: 1
      });
    };

    // เชื่อมต่อกับ modal เลือกลูกค้า
    window.selectCustomerForPOS = function(customer) {
      document.getElementById('customer_id').value = customer.id;
      document.getElementById('customer_name').value = customer.customer_name;
      document.getElementById('customer_address').textContent = 'ที่อยู่: ' + (customer.address || '-');
      document.getElementById('customer_contact_name').textContent = 'ผู้ติดต่อ: ' + (customer.contact_name || '-');
      document.getElementById('customer_contact_phone').textContent = 'เบอร์ผู้ติดต่อ: ' + (customer.contact_phone || '-');
      document.getElementById('customer_type').textContent = 'ประเภทลูกค้า: ' + (customer.customer_type || '-');
      document.getElementById('customer_tax_id').textContent = 'เลขประจำตัวผู้เสียภาษี: ' + (customer.tax_id || '-');
      document.getElementById('customer_branch').textContent = 'สาขา: ' + (customer.branch || '-');
      document.getElementById('customer_phone').textContent = 'เบอร์โทรศัพท์: ' + (customer.phone || '-');
      document.getElementById('customer_email').textContent = 'อีเมล: ' + (customer.email || '-');
    };
    
    // ผูกอีเวนต์กับอิลิเมนต์ที่มีผลต่อการคำนวณ
    var calcElements = ['discount_value', 'discount_type', 'vat_type', 'payment_method'];
    
    for (var i = 0; i < calcElements.length; i++) {
      var element = document.getElementById(calcElements[i]);
      if (element) {
        // ใช้ทั้ง input และ change event
        element.addEventListener('input', calculatePOSWithoutRender);
        element.addEventListener('change', calculatePOSWithoutRender);
      }
    }
    
    // ผูกอีเวนต์กับช่องรับเงินสด
    var cashReceivedElement = document.getElementById('received_amount_cash');
    if (cashReceivedElement) {
      cashReceivedElement.addEventListener('input', function() {
        updateMainReceivedAmount(this.value);
      });
    }
    
    // จัดการบาร์โค้ด
    var barcodeTimer;
    var lastBarcode = '';
    var barcodeInput = document.getElementById('barcode_input');
    
    if (barcodeInput) {
      barcodeInput.addEventListener('input', function() {
        var barcode = this.value.trim();
        clearTimeout(barcodeTimer);
        if (barcode === '') return;
        
        barcodeTimer = setTimeout(function() {
          if (barcode !== lastBarcode) {
            lastBarcode = barcode;
            
            fetch('../../api/product/get-by-barcode.php?barcode=' + encodeURIComponent(barcode))
              .then(function(res) {
                if (!res.ok) {
                  throw new Error('เกิดข้อผิดพลาดในการค้นหาสินค้า');
                }
                return res.json();
              })
              .then(function(p) {
                if (p && p.product_code) {
                  addProductToTable(p);
                } else {
                  console.warn("ไม่พบสินค้าจากบาร์โค้ดนี้");
                  alert('ไม่พบสินค้าจากบาร์โค้ดนี้');
                }
                lastBarcode = '';
                barcodeInput.value = '';
              })
              .catch(function(err) {
                console.error('เกิดข้อผิดพลาดในการค้นหาสินค้า:', err);
                alert('เกิดข้อผิดพลาดในการค้นหาสินค้า');
                lastBarcode = '';
                barcodeInput.value = '';
              });
          }
        }, 200);
      });
    }

    // ผูกอีเวนต์กับฟอร์ม
    var posForm = document.getElementById('posForm');
    if (posForm) {
      posForm.addEventListener('submit', function(e) {
        // เรียกฟังก์ชันจัดการการส่งฟอร์ม
        return handleFormSubmit(e);
      });
    }
    
    // เพิ่มอีเวนต์สำหรับช่องทางการชำระเงิน
    var paymentMethodElement = document.getElementById('payment_method');
    if (paymentMethodElement) {
      paymentMethodElement.addEventListener('change', togglePaymentFields);
      
      // เรียกครั้งแรกเพื่อตั้งค่าเริ่มต้น
      togglePaymentFields();
    }
    
    // จัดการกับการอัพโหลดไฟล์
    var transferSlip = document.getElementById('transfer_slip');
    if (transferSlip) {
      transferSlip.addEventListener('change', function() {
        var fileName = '';
        if (this.files && this.files.length > 0) {
          fileName = this.files[0].name;
        } else {
          fileName = 'เลือกไฟล์...';
        }
        
        var fileLabel = this.nextElementSibling;
        if (fileLabel && fileLabel.classList.contains('custom-file-label')) {
          fileLabel.textContent = fileName;
        }
      });
    }
    
    // เรียกคำนวณราคาครั้งแรก
    setTimeout(function() {
      console.log("เริ่มคำนวณราคาเริ่มต้น");
      calculatePOSWithoutRender();
    }, 300);
    
    console.log("ตั้งค่า event listeners เรียบร้อยแล้ว");
  } catch (error) {
    console.error("เกิดข้อผิดพลาดในการเริ่มต้น:", error);
  }
});

// ฟังก์ชันบันทึกพักบิล
function saveDraft() {
  // ตรวจสอบว่ามีสินค้าหรือไม่
  if (productList.length === 0) {
    alert('กรุณาเพิ่มสินค้าอย่างน้อย 1 รายการ');
    return;
  }
  
  // เรียกใช้ฟังก์ชันเตรียมข้อมูลสินค้า
  if (!prepareProductsForSubmit()) {
    return;
  }
  
  // ส่งข้อมูลไปยัง API ด้วย AJAX
  var form = document.getElementById('posForm');
  var formData = new FormData(form);
  
  // ส่ง AJAX request
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '../../api/draft/draft-save.php', true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      try {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          alert('พักบิลเรียบร้อยแล้ว รหัส: ' + response.draft_code);
        } else {
          alert('เกิดข้อผิดพลาด: ' + (response.message || 'ไม่สามารถบันทึกพักบิลได้'));
        }
      } catch (e) {
        console.error('Error parsing JSON:', e);
        alert('เกิดข้อผิดพลาดในการประมวลผลข้อมูล กรุณาลองใหม่อีกครั้ง');
      }
    } else {
      alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง');
    }
  };
  xhr.onerror = function() {
    alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง');
  };
  xhr.send(formData);
}

// ฟังก์ชันโหลดบิลที่พัก
function loadDraft(draft_id) {
  if (!draft_id) return;
  
  // ส่ง AJAX request เพื่อโหลดข้อมูลพักบิล
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '../../api/draft/draft-load.php?draft_id=' + draft_id, true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      try {
        var data = JSON.parse(xhr.responseText);
        if (data.success) {
          // เคลียร์ข้อมูลเดิมก่อน
          clearFullPOSForm();
          
          // โหลดข้อมูลลูกค้า
          if (data.customer) {
            document.getElementById('customer_id').value = data.customer.id;
            document.getElementById('customer_name').value = data.customer.customer_name;
            
            // อัพเดทข้อมูลลูกค้าอื่นๆ
            document.getElementById('customer_address').textContent = 'ที่อยู่: ' + (data.customer.address || '-');
            document.getElementById('customer_contact_name').textContent = 'ผู้ติดต่อ: ' + (data.customer.contact_name || '-');
            document.getElementById('customer_contact_phone').textContent = 'เบอร์ผู้ติดต่อ: ' + (data.customer.contact_phone || '-');
            document.getElementById('customer_type').textContent = 'ประเภทลูกค้า: ' + (data.customer.customer_type || '-');
            document.getElementById('customer_tax_id').textContent = 'เลขประจำตัวผู้เสียภาษี: ' + (data.customer.tax_id || '-');
            document.getElementById('customer_branch').textContent = 'สาขา: ' + (data.customer.branch || '-');
            document.getElementById('customer_phone').textContent = 'เบอร์โทรศัพท์: ' + (data.customer.phone || '-');
            document.getElementById('customer_email').textContent = 'อีเมล: ' + (data.customer.email || '-');
          }
          
          // โหลดค่าส่วนลดและ VAT
          if (data.draft.discount_value !== undefined) {
            document.getElementById('discount_value').value = data.draft.discount_value;
          }
          
          if (data.draft.discount_type) {
            document.getElementById('discount_type').value = data.draft.discount_type;
          }
          
          if (data.draft.vat_type) {
            document.getElementById('vat_type').value = data.draft.vat_type;
          }
          
          // โหลดวิธีการชำระเงิน
          if (data.draft.payment_method) {
            document.getElementById('payment_method').value = data.draft.payment_method;
            togglePaymentFields();
          }
          
          // โหลดหมายเหตุ
          if (data.draft.note) {
            document.getElementById('note').value = data.draft.note;
          }
          
          // โหลดรายการสินค้า
          if (data.products && data.products.length > 0) {
            // วนลูปเพิ่มรายการสินค้า
            data.products.forEach(function(product) {
              addProductToTable({
                product_code: product.product_code,
                product_name: product.product_name,
                unit: product.unit,
                sale_price: product.sale_price,
                qty: product.qty
              });
            });
          }
          
          // แจ้งเตือนการโหลดสำเร็จ
          alert('โหลดข้อมูลพักบิลเรียบร้อยแล้ว');
        } else {
          alert('ไม่สามารถโหลดข้อมูลพักบิล: ' + data.message);
        }
      } catch (e) {
        console.error('Error loading draft:', e);
        alert('เกิดข้อผิดพลาดในการโหลดข้อมูลพักบิล');
      }
    } else {
      alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
    }
  };
  xhr.onerror = function() {
    alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
  };
  xhr.send();
}

// ฟังก์ชันโหลดบิลที่พัก
function loadDraft(draft_id) {
  if (!draft_id) {
    console.error("ไม่พบ draft_id");
    return;
  }
  
  console.log("เริ่มโหลดบิล ID:", draft_id);
  
  // ส่ง AJAX request เพื่อโหลดข้อมูลพักบิล
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '../../api/draft/draft-load.php?draft_id=' + draft_id, true);
  
  xhr.onload = function() {
    if (xhr.status === 200) {
      try {
        var data = JSON.parse(xhr.responseText);
        console.log("ข้อมูลที่ได้รับ:", data);
        
        if (data.success) {
          // เคลียร์ข้อมูลเดิมก่อน
          clearFullPOSForm();
          
          // โหลดข้อมูลลูกค้า
          if (data.customer) {
            document.getElementById('customer_id').value = data.customer.id;
            document.getElementById('customer_name').value = data.customer.customer_name;
            
            // อัพเดทข้อมูลลูกค้าอื่นๆ
            document.getElementById('customer_address').textContent = 'ที่อยู่: ' + (data.customer.address || '-');
            document.getElementById('customer_contact_name').textContent = 'ผู้ติดต่อ: ' + (data.customer.contact_name || '-');
            document.getElementById('customer_contact_phone').textContent = 'เบอร์ผู้ติดต่อ: ' + (data.customer.contact_phone || '-');
            document.getElementById('customer_type').textContent = 'ประเภทลูกค้า: ' + (data.customer.customer_type || '-');
            document.getElementById('customer_tax_id').textContent = 'เลขประจำตัวผู้เสียภาษี: ' + (data.customer.tax_id || '-');
            document.getElementById('customer_branch').textContent = 'สาขา: ' + (data.customer.branch || '-');
            document.getElementById('customer_phone').textContent = 'เบอร์โทรศัพท์: ' + (data.customer.phone || '-');
            document.getElementById('customer_email').textContent = 'อีเมล: ' + (data.customer.email || '-');
          }
          
          // โหลดค่าส่วนลดและ VAT
          if (data.draft.discount_value !== undefined) {
            document.getElementById('discount_value').value = data.draft.discount_value;
          }
          
          if (data.draft.discount_type) {
            document.getElementById('discount_type').value = data.draft.discount_type;
          }
          
          if (data.draft.vat_type) {
            document.getElementById('vat_type').value = data.draft.vat_type;
          }
          
          // โหลดวิธีการชำระเงิน
          if (data.draft.payment_method) {
            document.getElementById('payment_method').value = data.draft.payment_method;
            togglePaymentFields(); // เรียกฟังก์ชันเปลี่ยนการแสดงผลช่องชำระเงิน
          }
          
          // โหลดหมายเหตุ
          if (data.draft.note) {
            document.getElementById('note').value = data.draft.note;
          }
          
          // โหลดรายการสินค้า
          if (data.products && data.products.length > 0) {
            // วนลูปเพิ่มรายการสินค้า
            for (var i = 0; i < data.products.length; i++) {
              var product = data.products[i];
              addProductToTable({
                product_code: product.product_code,
                product_name: product.product_name,
                unit: product.unit,
                sale_price: product.sale_price,
                qty: product.qty
              });
            }
            
            // คำนวณราคาใหม่
            calculatePOSWithoutRender();
          }
          
          // แจ้งเตือนการโหลดสำเร็จ
          alert('โหลดข้อมูลพักบิลเรียบร้อยแล้ว');
          console.log("โหลดข้อมูลสำเร็จ");
          
          // ลบพักบิลหลังจากโหลดสำเร็จ
          deleteDraft(draft_id);
          
        } else {
          alert('ไม่สามารถโหลดข้อมูลพักบิล: ' + data.message);
          console.error("โหลดข้อมูลไม่สำเร็จ:", data.message);
        }
      } catch (e) {
        console.error('Error loading draft:', e);
        alert('เกิดข้อผิดพลาดในการโหลดข้อมูลพักบิล');
      }
    } else {
      alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
      console.error("เชื่อมต่อล้มเหลว, status:", xhr.status);
    }
  };
  
  xhr.onerror = function() {
    alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
    console.error("เกิดข้อผิดพลาดในการเชื่อมต่อ");
  };
  
  xhr.send();
  console.log("ส่งคำขอโหลดพักบิลแล้ว");
}

// ฟังก์ชันลบพักบิล
function deleteDraft(draft_id) {
  if (!draft_id) return;
  
  console.log("เริ่มลบพักบิล ID:", draft_id);
  
  // ส่ง AJAX request เพื่อลบพักบิล
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '../../api/draft/draft-delete.php?draft_id=' + draft_id, true);
  
  xhr.onload = function() {
    if (xhr.status === 200) {
      try {
        var data = JSON.parse(xhr.responseText);
        console.log("ลบพักบิล:", data);
        
        // ไม่ต้องแจ้งผู้ใช้เพราะเป็นการลบอัตโนมัติหลังโหลด
        
        // รีเฟรชรายการพักบิลในโมดัล (ถ้าเปิดอยู่)
        if (document.getElementById('pos-draft-list-modal').classList.contains('show')) {
          loadDraftList();
        }
      } catch (e) {
        console.error('Error deleting draft:', e);
        // ไม่ต้อง alert เพราะเป็นการลบเบื้องหลัง
      }
    } else {
      console.error("Delete request failed:", xhr.status);
    }
  };
  
  xhr.onerror = function() {
    console.error("Network error while deleting draft");
  };
  
  xhr.send();
}

</script>