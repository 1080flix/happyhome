<?php include('config/check_login.php'); ?>
<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="content-wrapper">
  <!-- ส่วนหัวหน้า -->
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">แดชบอร์ด</h1>
    </div>
  </div>

  <!-- ส่วนเนื้อหาหลัก -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">

        <!-- การ์ด: ใบกำกับภาษี -->
        <div class="col-lg-4 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>4</h3>
              <p>ใบกำกับภาษี</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-invoice"></i>
            </div>
            <a href="modules/invoice/invoice-list.php" class="small-box-footer">
              ดูเพิ่มเติม <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- การ์ด: POS ขายหน้าร้าน -->
        <div class="col-lg-4 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>1</h3>
              <p>ขายหน้าร้าน (POS)</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="modules/pos/pos-list.php" class="small-box-footer">
              ดูเพิ่มเติม <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- การ์ด: ลูกค้าทั้งหมด -->
        <div class="col-lg-4 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>2</h3>
              <p>ลูกค้าทั้งหมด</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="modules/customer/customer-list.php" class="small-box-footer">
              ดูเพิ่มเติม <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

<?php include('includes/footer.php'); ?>
