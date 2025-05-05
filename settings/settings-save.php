<?php
$base_path = '../';
include($base_path . 'config/check_login.php');
include($base_path . 'config/db.php');

$company_name = $_POST['company_name'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';
$fax = $_POST['fax'] ?? '';
$phone = $_POST['phone'] ?? '';
$tax_id = $_POST['tax_id'] ?? '';

$logo = ''; // จะใช้เฉพาะถ้ามีการอัปโหลดใหม่

// เช็คว่ามีไฟล์อัปโหลดหรือไม่
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
  $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
  $new_name = 'logo_' . time() . '.' . $ext;
  $target_dir = '../upload/settings/';
  $target_file = $target_dir . $new_name;

  if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
    $logo = $new_name;

    // ดึงโลโก้เก่าเพื่อลบ (ยกเว้น logo.png)
    $oldQuery = mysqli_query($conn, "SELECT logo FROM settings WHERE id = 1");
    $old = mysqli_fetch_assoc($oldQuery);
    if ($old && $old['logo'] !== 'logo.png') {
      @unlink($target_dir . $old['logo']);
    }

    // อัปเดตโลโก้ใหม่
    $stmt_logo = $conn->prepare("UPDATE settings SET logo = ? WHERE id = 1");
    $stmt_logo->bind_param("s", $logo);
    $stmt_logo->execute();
  }
}

// อัปเดตข้อมูลบริษัท
$stmt = $conn->prepare("UPDATE settings SET company_name=?, address=?, email=?, fax=?, phone=?, tax_id=? WHERE id = 1");
$stmt->bind_param("ssssss", $company_name, $address, $email, $fax, $phone, $tax_id);
$stmt->execute();

header("Location: settings.php");
exit();
