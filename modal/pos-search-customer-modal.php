<!-- Modal ค้นหาลูกค้า -->
<div class="modal fade" id="searchCustomerModal" tabindex="-1" role="dialog" aria-labelledby="searchCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ค้นหาลูกค้า</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="ปิด">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- ฟอร์มค้นหา -->
        <div class="form-group">
          <input type="text" id="searchCustomerInput" class="form-control" placeholder="ค้นหาชื่อลูกค้า / เบอร์โทร">
        </div>

        <!-- ตารางลูกค้า -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="thead-dark">
              <tr>
                <th>ชื่อ</th>
                <th>ประเภท</th>
                <th>เบอร์โทร</th>
                <th>เลือก</th>
              </tr>
            </thead>
            <tbody id="customerTableBody">
              <!-- ข้อมูลจะถูกโหลดด้วย JS -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function fetchCustomers(keyword = '') {
  fetch('../../api/customer/search.php?keyword=' + encodeURIComponent(keyword))
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('customerTableBody');
      tbody.innerHTML = '';

      data.forEach(c => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${c.customer_name}</td>
          <td>${c.customer_type}</td>
          <td>${c.phone || '-'}</td>
          <td><button class="btn btn-sm btn-primary" onclick='selectCustomer(${JSON.stringify(c)})'>เลือก</button></td>
        `;
        tbody.appendChild(tr);
      });
    });
}

// เรียกเมื่อมีการค้นหา
document.getElementById('searchCustomerInput').addEventListener('input', (e) => {
  fetchCustomers(e.target.value);
});

// โหลดข้อมูลทันทีเมื่อ modal เปิด
$('#searchCustomerModal').on('shown.bs.modal', function () {
  fetchCustomers();
});

// เปลี่ยนฟังก์ชันเลือกลูกค้า
function selectCustomer(customer) {
  if (typeof window.selectCustomerForPOS === 'function') {
    window.selectCustomerForPOS(customer);
  } else {
    $('#customer_id').val(customer.id);
    $('#customer_name').val(customer.customer_name);
    $('#customer_address').text('ที่อยู่: ' + (customer.address || '-'));
    $('#customer_contact_name').text('ผู้ติดต่อ: ' + (customer.contact_name || '-'));
    $('#customer_contact_phone').text('เบอร์ผู้ติดต่อ: ' + (customer.contact_phone || '-'));
    $('#customer_type').text('ประเภทลูกค้า: ' + (customer.customer_type || '-'));
    $('#customer_tax_id').text('เลขประจำตัวผู้เสียภาษี: ' + (customer.tax_id || '-'));
    $('#customer_branch').text('สาขา: ' + (customer.branch || '-'));
    $('#customer_phone').text('เบอร์โทรศัพท์: ' + (customer.phone || '-'));
    $('#customer_email').text('อีเมล: ' + (customer.email || '-'));
  }

  $('#searchCustomerModal').modal('hide');
}
</script>
