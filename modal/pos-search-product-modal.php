<?php
include(__DIR__ . '/../config/db.php');

// ดึงหมวดหมู่
$categories = $conn->query("SELECT id, category_name FROM categories ORDER BY category_name ASC");
?>

<div class="modal fade" id="searchProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">ค้นหาสินค้า</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

        <div class="form-row mb-3">
          <div class="col-md-6">
            <input type="text" id="productSearchInput" class="form-control" placeholder="ค้นหาชื่อ / รหัสสินค้า">
          </div>
          <div class="col-md-4">
            <select id="categoryFilter" class="form-control">
              <option value="">ทุกหมวดหมู่</option>
              <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead class="thead-light">
              <tr>
                <th>รหัส</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่</th>
                <th>ราคาขาย</th>
                <th>คงเหลือ</th>
                <th>หน่วย</th>
                <th>เลือก</th>
              </tr>
            </thead>
            <tbody id="productTableBody">
              <tr><td colspan="7" class="text-center text-muted">กำลังโหลด...</td></tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
function fetchProducts() {
  const search = document.getElementById('productSearchInput').value;
  const categoryId = document.getElementById('categoryFilter').value;

  fetch(`../../api/product/search.php?search=${encodeURIComponent(search)}&category_id=${categoryId}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('productTableBody');
      tbody.innerHTML = '';

      if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">ไม่พบสินค้า</td></tr>';
        return;
      }

      data.forEach(p => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${p.product_code}</td>
          <td>${p.product_name}</td>
          <td>${p.category_name}</td>
          <td>${parseFloat(p.sale_price).toFixed(2)}</td>
          <td>${p.stock_qty}</td>
          <td>${p.unit}</td>
          <td><button class="btn btn-sm btn-success" onclick='selectProduct(${JSON.stringify(p)})'>เลือก</button></td>
        `;
        tbody.appendChild(tr);
      });
    });
}

// โหลดอัตโนมัติ
document.addEventListener('DOMContentLoaded', fetchProducts);
document.getElementById('productSearchInput').addEventListener('input', fetchProducts);
document.getElementById('categoryFilter').addEventListener('change', fetchProducts);

// เพิ่มสินค้าเข้า POS
function selectProduct(product) {
  if (typeof window.selectProductForPOS === 'function') {
    window.selectProductForPOS(product);
  } else {
    const tbody = document.querySelector('#pos-items');
    if (!tbody) return;

    const existing = tbody.querySelector(`tr[data-code="${product.product_code}"]`);
    if (existing) {
      const qtyInput = existing.querySelector('.item-qty');
      qtyInput.value = parseInt(qtyInput.value) + 1;
      updateRowTotal(existing);
    } else {
      const tr = document.createElement('tr');
      tr.setAttribute('data-code', product.product_code);
      tr.innerHTML = `
        <td></td>
        <td>${product.product_code}<input type="hidden" name="items[][code]" value="${product.product_code}"></td>
        <td>${product.product_name}</td>
        <td><input type="number" name="items[][qty]" value="1" min="1" class="form-control form-control-sm item-qty" onchange="updateRowTotal(this.closest('tr'))"></td>
        <td>${product.unit}</td>
        <td>${parseFloat(product.sale_price).toFixed(2)}<input type="hidden" name="items[][price]" value="${product.sale_price}"></td>
        <td class="item-total">${parseFloat(product.sale_price).toFixed(2)}</td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); updateRowNumbers()">ลบ</button></td>
      `;
      tbody.appendChild(tr);
      updateRowNumbers();
    }
  }

  $('#searchProductModal').modal('hide');
}
function updateRowTotal(row) {
  const qty = parseFloat(row.querySelector('.item-qty').value);
  const price = parseFloat(row.querySelector('input[name="items[][price]"]').value);
  row.querySelector('.item-total').textContent = (qty * price).toFixed(2);
}

function updateRowNumbers() {
  document.querySelectorAll('#pos-items tr').forEach((tr, i) => {
    tr.querySelector('td').textContent = i + 1;
  });
}
</script>
