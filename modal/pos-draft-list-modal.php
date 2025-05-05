<!-- Modal แสดงรายการพักบิล -->
<div class="modal fade" id="pos-draft-list-modal" tabindex="-1" role="dialog" aria-labelledby="draftListModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="draftListModalLabel"><i class="fas fa-list"></i> รายการบิลที่พักไว้</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div id="draft-loading" class="text-center py-3">
            <i class="fas fa-spinner fa-spin"></i> กำลังโหลดข้อมูล...
          </div>
          <table class="table table-bordered table-hover" id="draftTable" style="display: none;">
            <thead class="thead-light">
              <tr>
                <th style="width: 100px;">รหัสพักบิล</th>
                <th>ลูกค้า</th>
                <th>วันที่</th>
                <th style="width: 100px;">รายการ</th>
                <th style="width: 100px;">ยอดรวม</th>
                <th style="width: 100px;">ดำเนินการ</th>
              </tr>
            </thead>
            <tbody id="draft-items">
              <!-- ข้อมูลจะถูกใส่ตรงนี้ด้วย JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
        <button type="button" class="btn btn-primary" onclick="loadDraftList()">
          <i class="fas fa-sync-alt"></i> โหลดข้อมูลใหม่
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// ฟังก์ชันโหลดรายการพักบิล
function loadDraftList() {
  // แสดง loading และซ่อนตาราง
  document.getElementById('draft-loading').style.display = 'block';
  document.getElementById('draftTable').style.display = 'none';
  document.getElementById('draft-items').innerHTML = '';
  
  // สร้าง AJAX request
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '../../api/draft/draft-list.php', true);
  
  xhr.onload = function() {
    // ซ่อน loading
    document.getElementById('draft-loading').style.display = 'none';
    
    if (xhr.status === 200) {
      try {
        var response = JSON.parse(xhr.responseText);
        
        if (response.success) {
          // มีข้อมูล
          if (response.drafts && response.drafts.length > 0) {
            var html = '';
            
            // วนลูปสร้างแถวตาราง
            for (var i = 0; i < response.drafts.length; i++) {
              var draft = response.drafts[i];
              html += '<tr>' +
                      '<td>' + draft.draft_code + '</td>' +
                      '<td>' + (draft.customer_name || '-') + '</td>' +
                      '<td>' + draft.formatted_date + '</td>' +
                      '<td class="text-center">' + draft.item_count + ' รายการ</td>' +
                      '<td class="text-right">' + draft.formatted_price + '</td>' +
                      '<td class="text-center">' +
                      '<button type="button" class="btn btn-sm btn-primary" ' +
                      'onclick="loadDraft(' + draft.id + ')" data-dismiss="modal">' +
                      '<i class="fas fa-check"></i> เรียกคืน' +
                      '</button>' +
                      '</td>' +
                      '</tr>';
            }
            
            document.getElementById('draft-items').innerHTML = html;
            document.getElementById('draftTable').style.display = 'table';
          } else {
            // ไม่มีข้อมูล
            document.getElementById('draft-items').innerHTML = '<tr><td colspan="6" class="text-center">ไม่พบรายการพักบิล</td></tr>';
            document.getElementById('draftTable').style.display = 'table';
          }
        } else {
          // มีข้อผิดพลาด
          document.getElementById('draft-items').innerHTML = '<tr><td colspan="6" class="text-danger">เกิดข้อผิดพลาด: ' + response.message + '</td></tr>';
          document.getElementById('draftTable').style.display = 'table';
        }
      } catch (e) {
        document.getElementById('draft-items').innerHTML = '<tr><td colspan="6" class="text-danger">เกิดข้อผิดพลาดในการประมวลผลข้อมูล</td></tr>';
        document.getElementById('draftTable').style.display = 'table';
      }
    } else {
      document.getElementById('draft-items').innerHTML = '<tr><td colspan="6" class="text-danger">เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>';
      document.getElementById('draftTable').style.display = 'table';
    }
  };
  
  xhr.onerror = function() {
    // ซ่อน loading
    document.getElementById('draft-loading').style.display = 'none';
    
    // แสดงข้อผิดพลาด
    document.getElementById('draft-items').innerHTML = '<tr><td colspan="6" class="text-danger">เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์</td></tr>';
    document.getElementById('draftTable').style.display = 'table';
  };
  
  xhr.send();
}
</script>